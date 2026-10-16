<?php

namespace App\Http\Controllers;

use App\Enums\BillingPeriod;
use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $plans = Package::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get()
            ->map(fn (Package $package) => $this->toPlan($package))
            ->values()
            ->all();

        return view('landingpages.index', compact('plans'));
    }

    /**
     * Map a package into the tuple consumed by the landing plans component.
     *
     * @return array{0: string, 1: string, 2: string, 3: int, 4: string, 5: string, 6: array<int, array{0: string, 1: bool}>, 7: string, 8: bool, 9: string, 10: string}
     */
    protected function toPlan(Package $package): array
    {
        return [
            $package->name,
            $package->badge ?: $package->type->label(),
            (string) $package->description,
            $package->price,
            $package->billing_period === BillingPeriod::Yearly ? 'Ditagih per tahun' : 'Ditagih per bulan',
            $package->type === PackageType::Business ? 'COCOK UNTUK TIM & MULTI-CHANNEL' : 'COCOK UNTUK INDIVIDU',
            $this->features($package),
            'Pilih '.$package->name,
            $package->is_featured,
            $package->billing_period === BillingPeriod::Yearly ? 'tahun' : 'bulan',
            $package->slug,
        ];
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     */
    protected function features(Package $package): array
    {
        $custom = $package->features;

        if (! empty($custom)) {
            return collect($custom)
                ->map(function (string $feature): array {
                    $feature = trim($feature);
                    $enabled = ! str_starts_with($feature, '-');

                    return [ltrim($feature, '- '), $enabled];
                })
                ->values()
                ->all();
        }

        if ($package->type === PackageType::Business) {
            $features = [
                ['Upload <b>tanpa batas</b>', true],
                [$package->seat_limit ? 'Hingga '.$package->seat_limit.' pengguna tim' : 'Pengguna tim tanpa batas', true],
            ];
        } else {
            $features = [
                [$package->upload_limit ? $package->upload_limit.' kali upload' : 'Upload tanpa batas', true],
                ['1 pengguna', true],
            ];
        }

        $features[] = ['Pencocokan SKU standar', true];

        return $features;
    }
}
