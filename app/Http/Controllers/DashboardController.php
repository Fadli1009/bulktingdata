<?php

namespace App\Http\Controllers;

use App\Enums\UploadStatus;
use App\Models\Upload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        $owner = $user->subscriptionOwner();

        $subscription = $owner->activeSubscription()->with('package')->first();

        $uploadStats = Upload::query()
            ->ownedBy($owner->id)
            ->selectRaw('count(*) as total')
            ->selectRaw('count(*) filter (where status = ?) as done', [UploadStatus::Done->value])
            ->selectRaw('count(*) filter (where status = ?) as failed', [UploadStatus::Failed->value])
            ->selectRaw('count(*) filter (where status = ?) as processing', [UploadStatus::Processing->value])
            ->selectRaw('coalesce(sum(rows_processed), 0) as rows')
            ->first();

        $quotaUsed = Upload::query()
            ->ownedBy($owner->id)
            ->when($subscription?->starts_at, fn ($query, $startsAt) => $query->where('created_at', '>=', $startsAt))
            ->count();

        $recentUploads = Upload::query()
            ->ownedBy($owner->id)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentOrders = $owner->orders()
            ->with('package')
            ->latest('ordered_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'user' => $user,
            'owner' => $owner,
            'subscription' => $subscription,
            'package' => $subscription?->package,
            'uploadStats' => $uploadStats,
            'quotaUsed' => $quotaUsed,
            'recentUploads' => $recentUploads,
            'recentOrders' => $recentOrders,
            'isTeamOwner' => $user->id === $owner->id,
            'teamMembersCount' => $user->isBusiness() ? $owner->teamMembers()->count() : 0,
        ]);
    }
}
