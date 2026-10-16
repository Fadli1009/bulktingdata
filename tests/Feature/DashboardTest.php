<?php

use App\Models\Package;
use App\Models\Subscription;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function completedCustomer(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'account_type' => 'personal',
        'profile_completed_at' => now(),
    ], $attributes));
}

it('redirects guests to the login page', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

it('redirects customers who have not finished onboarding', function () {
    $user = User::factory()->create(['account_type' => null]);

    $this->actingAs($user)->get('/dashboard')->assertRedirect(route('account.type'));
});

it('renders the dashboard for a completed customer', function () {
    $user = completedCustomer(['name' => 'Budi Santoso']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Budi Santoso')
        ->assertSee('Belum ada langganan aktif');
});

it('shows the active subscription plan and usage', function () {
    $user = completedCustomer();

    $package = Package::factory()->personal()->create(['name' => 'Paket Pro', 'upload_limit' => 10]);

    Subscription::factory()->create([
        'user_id' => $user->id,
        'package_id' => $package->id,
    ]);

    Upload::factory()->create([
        'user_id' => $user->id,
        'owner_id' => $user->id,
        'original_name' => 'katalog-tokopedia.xlsx',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Paket Pro')
        ->assertSee('katalog-tokopedia.xlsx')
        ->assertSee('Aktif');
});

it('shows unlimited quota for a business plan without an upload limit', function () {
    $owner = completedCustomer(['account_type' => 'bisnis']);

    $package = Package::factory()->business()->create(['name' => 'Paket Bisnis', 'upload_limit' => null]);

    Subscription::factory()->create([
        'user_id' => $owner->id,
        'package_id' => $package->id,
    ]);

    $this->actingAs($owner)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Paket Bisnis')
        ->assertSee('Tanpa batas');
});

it('redirects administrators to the admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin');
});
