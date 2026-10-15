<?php

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests away from the packages page', function () {
    $this->get(route('admin.packages.index'))->assertRedirect(route('admin.login'));
});

it('lets an admin view the package list', function () {
    $admin = User::factory()->admin()->create();
    Package::factory()->personal()->create(['name' => 'Paket Personal Uji']);

    $this->actingAs($admin)
        ->get(route('admin.packages.index'))
        ->assertOk()
        ->assertSee('Paket Personal Uji');
});

it('filters packages by type', function () {
    $admin = User::factory()->admin()->create();

    Package::factory()->personal()->create(['name' => 'Paket Personal A']);
    Package::factory()->business()->create(['name' => 'Paket Bisnis B']);

    $this->actingAs($admin)
        ->get(route('admin.packages.index', ['type' => 'personal']))
        ->assertOk()
        ->assertSee('Paket Personal A')
        ->assertDontSee('Paket Bisnis B');

    $this->actingAs($admin)
        ->get(route('admin.packages.index', ['type' => 'bisnis']))
        ->assertOk()
        ->assertSee('Paket Bisnis B')
        ->assertDontSee('Paket Personal A');
});

it('creates a personal package with an upload limit', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.packages.store'), [
        'name' => 'Personal Basic',
        'description' => 'Paket individu',
        'type' => 'personal',
        'price' => 149000,
        'billing_period' => 'monthly',
        'upload_limit' => 8,
        'seat_limit' => 5,
        'status' => 'active',
        'sort_order' => 3,
        'is_featured' => true,
        'features' => "8 kali upload / bulan\n1 pengguna",
    ])->assertRedirect();

    $package = Package::where('name', 'Personal Basic')->firstOrFail();

    expect($package->type->value)->toBe('personal')
        ->and($package->upload_limit)->toBe(8)
        ->and($package->seat_limit)->toBeNull()
        ->and($package->slug)->toBe('personal-basic')
        ->and($package->is_featured)->toBeTrue()
        ->and($package->features)->toBe(['8 kali upload / bulan', '1 pengguna']);
});

it('creates a business package with unlimited uploads and seats', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.packages.store'), [
        'name' => 'Bisnis Pro',
        'type' => 'bisnis',
        'price' => 599000,
        'billing_period' => 'yearly',
        'upload_limit' => 10,
        'seat_limit' => 5,
        'status' => 'active',
    ])->assertRedirect();

    $package = Package::where('name', 'Bisnis Pro')->firstOrFail();

    expect($package->type->value)->toBe('bisnis')
        ->and($package->upload_limit)->toBeNull()
        ->and($package->seat_limit)->toBe(5)
        ->and($package->billing_period->value)->toBe('yearly');
});

it('rejects an invalid package type and price', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.packages.store'), [
        'name' => 'Paket Salah',
        'type' => 'enterprise',
        'price' => -5,
        'billing_period' => 'weekly',
        'status' => 'active',
    ])->assertSessionHasErrors(['type', 'price', 'billing_period']);
});

it('updates an existing package', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->personal()->create(['price' => 100000]);

    $this->actingAs($admin)->put(route('admin.packages.update', $package), [
        'name' => $package->name,
        'type' => 'personal',
        'price' => 250000,
        'billing_period' => 'monthly',
        'upload_limit' => 12,
        'status' => 'inactive',
    ])->assertRedirect();

    expect($package->fresh())
        ->price->toBe(250000)
        ->upload_limit->toBe(12)
        ->status->toBe('inactive');
});

it('toggles a package status', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->create(['status' => 'active']);

    $this->actingAs($admin)
        ->patch(route('admin.packages.toggle', $package))
        ->assertRedirect();

    expect($package->fresh()->status)->toBe('inactive');
});

it('deletes a package', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.packages.destroy', $package))
        ->assertRedirect();

    $this->assertDatabaseMissing('packages', ['id' => $package->id]);
});

it('blocks non-admin users from managing packages', function () {
    $customer = User::factory()->create(['is_admin' => false]);

    $this->actingAs($customer)
        ->get(route('admin.packages.index'))
        ->assertRedirect(route('admin.login'));
});
