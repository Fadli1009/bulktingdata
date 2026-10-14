<?php

use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

it('redirects guests hitting /admin to admin login page', function () {
    $this->get('/admin')->assertRedirect(route('admin.login'));
});

it('only allows seeded admin emails to request admin OTP', function () {
    User::factory()->create(['email' => 'user@example.com', 'is_admin' => false]);

    $this->postJson('/admin/auth/otp/send', ['email' => 'user@example.com'])
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');

    $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);

    $this->postJson('/admin/auth/otp/send', ['email' => 'admin@example.com'])
        ->assertStatus(200);

    $this->assertDatabaseHas('otp_tokens', ['email' => 'admin@example.com']);
});

it('logs admin in directly to dashboard on valid OTP', function () {
    $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);

    OtpToken::create([
        'email' => 'admin@example.com',
        'token' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
    ]);

    $this->post('/admin/auth/otp/verify', [
        'email' => 'admin@example.com',
        'code' => '123456',
    ])->assertRedirect('/admin');

    $this->assertAuthenticatedAs($admin);
});

it('blocks non-admin authenticated users from the admin area', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get('/admin')->assertRedirect(route('admin.login'));
    $this->assertGuest();
});

it('lets an admin access the dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertOk();
});
