<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows only active packages ordered by sort order', function () {
    Package::factory()->personal()->create([
        'name' => 'Paket Kedua',
        'sort_order' => 2,
        'price' => 200000,
    ]);

    Package::factory()->personal()->create([
        'name' => 'Paket Pertama',
        'sort_order' => 1,
        'price' => 100000,
    ]);

    Package::factory()->inactive()->create([
        'name' => 'Paket Nonaktif',
        'sort_order' => 0,
    ]);

    $response = $this->get('/')->assertOk();

    $response->assertSee('Paket Pertama')
        ->assertSee('Paket Kedua')
        ->assertDontSee('Paket Nonaktif');

    $response->assertSeeInOrder(['Paket Pertama', 'Paket Kedua']);
});

it('renders the price and derived features of a package', function () {
    Package::factory()->personal()->create([
        'name' => 'Paket Hemat',
        'price' => 149000,
        'upload_limit' => 8,
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('149.000')
        ->assertSee('8 kali upload');
});

it('uses custom features from the package when provided', function () {
    Package::factory()->business()->create([
        'name' => 'Paket Custom',
        'features' => ['Upload tanpa batas', '- API akses'],
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Upload tanpa batas')
        ->assertSee('API akses');
});

it('shows an empty state when there are no active packages', function () {
    Package::factory()->inactive()->create();

    $this->get('/')
        ->assertOk()
        ->assertSee('Belum ada paket tersedia');
});
