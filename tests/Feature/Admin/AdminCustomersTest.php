<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests away from the customers page', function () {
    $this->get(route('admin.customers.index'))->assertRedirect(route('admin.login'));
});

it('shows registered customers with their onboarding status', function () {
    $admin = User::factory()->admin()->create();

    User::factory()->create([
        'name' => 'Pelanggan Pending',
        'email' => 'pending@example.com',
        'profile_completed_at' => null,
    ]);

    User::factory()->create([
        'name' => 'Pelanggan Sukses',
        'email' => 'success@example.com',
        'account_type' => 'personal',
        'profile_completed_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.customers.index'))
        ->assertOk()
        ->assertSee('Pelanggan Pending')
        ->assertSee('Pending')
        ->assertSee('Pelanggan Sukses')
        ->assertSee('Sukses');
});

it('excludes admin accounts from the customer list', function () {
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create();

    $response = $this->actingAs($admin)->get(route('admin.customers.index'))->assertOk();

    $ids = $response->viewData('customers')->pluck('id');

    expect($ids)->toContain($customer->id)->not->toContain($admin->id);
});

it('filters customers by status', function () {
    $admin = User::factory()->admin()->create();

    User::factory()->create(['name' => 'Si Pending', 'profile_completed_at' => null]);
    User::factory()->create(['name' => 'Si Sukses', 'profile_completed_at' => now()]);

    $this->actingAs($admin)
        ->get(route('admin.customers.index', ['status' => 'pending']))
        ->assertOk()
        ->assertSee('Si Pending')
        ->assertDontSee('Si Sukses');

    $this->actingAs($admin)
        ->get(route('admin.customers.index', ['status' => 'success']))
        ->assertOk()
        ->assertSee('Si Sukses')
        ->assertDontSee('Si Pending');
});

it('searches customers by name or email', function () {
    $admin = User::factory()->admin()->create();

    User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
    User::factory()->create(['name' => 'Siti Aminah', 'email' => 'siti@example.com']);

    $this->actingAs($admin)
        ->get(route('admin.customers.index', ['q' => 'Budi']))
        ->assertOk()
        ->assertSee('Budi Santoso')
        ->assertDontSee('Siti Aminah');

    $this->actingAs($admin)
        ->get(route('admin.customers.index', ['q' => 'siti@example.com']))
        ->assertOk()
        ->assertSee('Siti Aminah')
        ->assertDontSee('Budi Santoso');
});
