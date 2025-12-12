<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use App\Models\Collector;
use App\Models\Party;
use App\Services\LoanCalculator;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Collection::with(['party', 'collector']);

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter by party
        if ($request->has('party_id') && $request->party_id !== '') {
            $query->where('party_id', $request->party_id);
        }

        // Filter by collector
        if ($request->has('collector_id') && $request->collector_id !== '') {
            $query->where('collector_id', $request->collector_id);
        }

        $collections = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $parties = Party::where('status', 'active')->get();
        $collectors = Collector::where('status', 'active')->get();

        if (Gate::allows('isAdmin')) {
            // If allowed, show dashboard
           
            return view('collections.index', compact('collections', 'parties', 'collectors'));
        }
    
        // If not allowed, throw 403 Access Denied
        abort(403, "You are not authorized to access this page.");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parties = Party::where('status', 'active')->get();
        $collectors = Collector::where('status', 'active')->get();
        
        $selectedParty = null;
        if ($request->has('party_id')) {
            $selectedParty = Party::with('collections')->find($request->party_id);
        }

        return view('collections.create', compact('parties', 'collectors', 'selectedParty'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectionRequest $request)
    {
        $data = $request->validated();

        // Get party to calculate remaining amount and day number
        $party = Party::findOrFail($data['party_id']);
        
        // Calculate day number if not provided
        if (empty($data['day_number'])) {
            $partyStartDate = Carbon::parse($party->starting_date);
            $collectionDate = Carbon::parse($data['date']);
            $data['day_number'] = LoanCalculator::calculateDayNumber($collectionDate, $partyStartDate);
        }

        // Calculate remaining amount if not provided
        if (empty($data['remaining_amount'])) {
            $collectedAmount = $party->collections->sum('amount_collected') + $data['amount_collected'];
            $remaining = LoanCalculator::calculateRemainingAmount(
                $party->loan_amount,
                $party->interest_amount ?? 0,
                $collectedAmount
            );
            $data['remaining_amount'] = $remaining;
        }

        Collection::create($data);

        // Update party status if loan is completed
        $party->refresh();
        $totalCollected = $party->collections->sum('amount_collected');
        $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
        
        if ($totalCollected >= $totalAmount && $party->status === 'active') {
            $party->update(['status' => 'completed']);
        }

        return redirect()->route('collections.index')
            ->with('success', 'Collection recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $collection->load(['party', 'collector']);
        return view('collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        $collection->load(['party', 'collector']);
        $parties = Party::where('status', 'active')->get();
        $collectors = Collector::where('status', 'active')->get();
        
        return view('collections.edit', compact('collection', 'parties', 'collectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectionRequest $request, Collection $collection)
    {
        $data = $request->validated();

        // Recalculate remaining amount if amount changed
        $party = Party::findOrFail($data['party_id']);
        $oldAmount = $collection->amount_collected;
        $newAmount = $data['amount_collected'];
        $difference = $newAmount - $oldAmount;

        if ($difference != 0) {
            $collectedAmount = $party->collections()
                ->where('id', '!=', $collection->id)
                ->sum('amount_collected') + $newAmount;
            
            $remaining = LoanCalculator::calculateRemainingAmount(
                $party->loan_amount,
                $party->interest_amount ?? 0,
                $collectedAmount
            );
            $data['remaining_amount'] = $remaining;
        }

        $collection->update($data);

        // Update party status if loan is completed
        $party->refresh();
        $totalCollected = $party->collections->sum('amount_collected');
        $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
        
        if ($totalCollected >= $totalAmount && $party->status === 'active') {
            $party->update(['status' => 'completed']);
        } elseif ($totalCollected < $totalAmount && $party->status === 'completed') {
            $party->update(['status' => 'active']);
        }

        return redirect()->route('collections.index')
            ->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $party = $collection->party;
        $collection->delete();

        // Update party status if needed
        $party->refresh();
        $totalCollected = $party->collections->sum('amount_collected');
        $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
        
        if ($totalCollected < $totalAmount && $party->status === 'completed') {
            $party->update(['status' => 'active']);
        }

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted successfully.');
    }
}
