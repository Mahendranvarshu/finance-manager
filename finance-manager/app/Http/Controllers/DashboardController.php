<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Collector;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Statistics
        $totalParties = Party::count();
        $activeParties = Party::where('status', 'active')->count();
        $totalCollectors = Collector::where('status', 'active')->count();
        
        // Today's collections
        $todayCollections = Collection::whereDate('date', $today)->sum('amount_collected');
        
        // Week's collections
        $weekCollections = Collection::whereBetween('date', [$startOfWeek, Carbon::now()])->sum('amount_collected');
        
        // Month's collections
        $monthCollections = Collection::whereBetween('date', [$startOfMonth, Carbon::now()])->sum('amount_collected');
        
        // Total loan amount (all parties)
        $totalLoanAmount = Party::where('status', 'active')->sum('loan_amount');
        
        // Total collected amount (all time)
        $totalCollected = Collection::sum('amount_collected');
        
        // Calculate remaining loan amount for active parties
        $activePartiesList = Party::where('status', 'active')->with('collections')->get();
        $totalActiveLoan = $activePartiesList->sum(function ($party) {
            return $party->loan_amount + ($party->interest_amount ?? 0);
        });
        $totalActiveCollected = $activePartiesList->sum(function ($party) {
            return $party->collections->sum('amount_collected');
        });
        $remainingLoanAmount = max(0, $totalActiveLoan - $totalActiveCollected);

        // Recent collections
        $recentCollections = Collection::with(['party', 'collector'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Active parties with progress
        $partiesWithProgress = Party::with(['collector', 'collections'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($party) {
                $collected = $party->collections->sum('amount_collected');
                $totalAmount = $party->loan_amount + $party->interest_amount;
                $remaining = $totalAmount - $collected;
                $progress = $totalAmount > 0 ? ($collected / $totalAmount) * 100 : 0;
                
                return [
                    'party' => $party,
                    'collected' => $collected,
                    'remaining' => $remaining,
                    'progress' => round($progress, 2),
                ];
            });

        return view('dashboard', compact(
            'totalParties',
            'activeParties',
            'totalCollectors',
            'todayCollections',
            'weekCollections',
            'monthCollections',
            'totalLoanAmount',
            'totalCollected',
            'remainingLoanAmount',
            'recentCollections',
            'partiesWithProgress'
        ));
    }
}

