<?php

use App\Models\OtpToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

it('can request OTP and store token', function () {
    $this->postJson('/auth/otp/send', ['email' => 'test@example.com'])->assertStatus(200);
    $this->assertDatabaseHas('otp_tokens', ['email' => 'test@example.com']);
});

it('enforces daily limit of 3 OTP per email', function () {
    for ($i = 0; $i < 3; $i++) {
        $this->postJson('/auth/otp/send', ['email' => 'test@example.com']);
        $this->travel(61)->seconds();
    }
    $this->postJson('/auth/otp/send', ['email' => 'test@example.com'])->assertStatus(422);
});

it('verifies correct OTP and logs in', function () {
    OtpToken::create([
        'email' => 'test@example.com',
        'token' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->post('/auth/otp/verify', [
        'email' => 'test@example.com',
        'code' => '123456',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    $this->assertAuthenticated();
});

it('fails on wrong OTP', function () {
    OtpToken::create([
        'email' => 'test@example.com',
        'token' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
    ]);

    $this->post('/auth/otp/verify', [
        'email' => 'test@example.com',
        'code' => '999999',
    ])->assertSessionHasErrors('code');
});
