<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartyRequest;
use App\Models\Collector;
use App\Models\Party;
use App\Services\LoanCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Party::with(['collector', 'collections']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by collector
        if ($request->has('collector_id') && $request->collector_id !== '') {
            $query->where('collector_id', $request->collector_id);
        }

        // Search by name or DL number
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dl_no', 'like', "%{$search}%")
                  ->orWhere('store_name', 'like', "%{$search}%");
            });
        }

        $parties = $query->orderBy('created_at', 'desc')->paginate(15);
        $collectors = Collector::where('status', 'active')->get();

        // Calculate collected and remaining amounts for each party
        $parties->getCollection()->transform(function ($party) {
            $collected = $party->collections->sum('amount_collected');
            $totalAmount = $party->loan_amount + $party->interest_amount;
            $remaining = $totalAmount - $collected;
            $progress = $totalAmount > 0 ? ($collected / $totalAmount) * 100 : 0;
            
            $party->collected_amount = $collected;
            $party->remaining_amount = $remaining;
            $party->progress_percentage = round($progress, 2);
            
            return $party;
        });

        return view('parties.index', compact('parties', 'collectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $collectors = Collector::where('status', 'active')->get();
        return view('parties.create', compact('collectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartyRequest $request)
    {
        $data = $request->validated();

        // Calculate daily amount if not provided
        if (empty($data['daily_amount'])) {
            $data['daily_amount'] = LoanCalculator::calculateDailyAmount(
                $data['loan_amount'],
                $data['interest_amount'] ?? 0,
                $data['total_days']
            );
        }

        // Calculate ending date if not provided
        if (empty($data['ending_date']) && !empty($data['starting_date'])) {
            $startDate = Carbon::parse($data['starting_date']);
            $data['ending_date'] = $startDate->copy()->addDays($data['total_days'] - 1)->format('Y-m-d');
        }

        Party::create($data);

        return redirect()->route('parties.index')
            ->with('success', 'Party created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Party $party)
    {
        $party->load(['collector', 'collections' => function ($query) {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        }]);

        $collected = $party->collections->sum('amount_collected');
        $totalAmount = $party->loan_amount + $party->interest_amount;
        $remaining = $totalAmount - $collected;
        $progress = $totalAmount > 0 ? ($collected / $totalAmount) * 100 : 0;

        return view('parties.show', compact('party', 'collected', 'remaining', 'progress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $party)
    {
        $collectors = Collector::where('status', 'active')->get();
        return view('parties.edit', compact('party', 'collectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartyRequest $request, Party $party)
    {
        $data = $request->validated();

        // Recalculate daily amount if loan amount, interest, or total days changed
        if ($data['loan_amount'] != $party->loan_amount 
            || ($data['interest_amount'] ?? 0) != $party->interest_amount
            || $data['total_days'] != $party->total_days) {
            
            $data['daily_amount'] = LoanCalculator::calculateDailyAmount(
                $data['loan_amount'],
                $data['interest_amount'] ?? 0,
                $data['total_days']
            );
        }

        // Recalculate ending date if starting date or total days changed
        if (($data['starting_date'] != $party->starting_date || $data['total_days'] != $party->total_days)
            && empty($data['ending_date'])) {
            $startDate = Carbon::parse($data['starting_date']);
            $data['ending_date'] = $startDate->copy()->addDays($data['total_days'] - 1)->format('Y-m-d');
        }

        $party->update($data);

        return redirect()->route('parties.index')
            ->with('success', 'Party updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        // Check if party has collections
        if ($party->collections()->count() > 0) {
            return redirect()->route('parties.index')
                ->with('error', 'Cannot delete party with existing collections. Please delete collections first.');
        }

        $party->delete();

        return redirect()->route('parties.index')
            ->with('success', 'Party deleted successfully.');
    }
}
