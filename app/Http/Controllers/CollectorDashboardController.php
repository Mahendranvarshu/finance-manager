<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollectorDashboardController extends Controller
{
    public function index()
    {
        $collector = auth('collector')->user();
        $today = Carbon::today();

        // Today's collections
        $todayCollections = Collection::where('collector_id', $collector->id)
            ->whereDate('date', $today)
            ->with('party')
            ->orderBy('created_at', 'desc')
            ->get();

        $todayTotal = $todayCollections->sum('amount_collected');

        // This week's collections
        $weekStart = Carbon::now()->startOfWeek();
        $weekCollections = Collection::where('collector_id', $collector->id)
            ->whereBetween('date', [$weekStart, Carbon::now()])
            ->sum('amount_collected');

        // This month's collections
        $monthStart = Carbon::now()->startOfMonth();
        $monthCollections = Collection::where('collector_id', $collector->id)
            ->whereBetween('date', [$monthStart, Carbon::now()])
            ->sum('amount_collected');

        // Active parties assigned to this collector
        $activeParties = Party::where('collector_id', $collector->id)
            ->where('status', 'active')
            ->with('collections')
            ->get()
            ->map(function ($party) {
                $collected = $party->collections->sum('amount_collected');
                $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
                $remaining = $totalAmount - $collected;
                $progress = $totalAmount > 0 ? ($collected / $totalAmount) * 100 : 0;
                
                return [
                    'party' => $party,
                    'collected' => $collected,
                    'remaining' => $remaining,
                    'progress' => round($progress, 2),
                ];
            });

        return view('collector.dashboard', compact(
            'collector',
            'todayCollections',
            'todayTotal',
            'weekCollections',
            'monthCollections',
            'activeParties'
        ));
    }
}

