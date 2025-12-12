<?php

namespace App\Http\Controllers;

use App\Events\CollectorCreated;
use App\Http\Requests\CollectorRequest;
use App\Models\Collector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Collector::withCount(['parties', 'collections']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by name or area
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $collectors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('collectors.index', compact('collectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('collectors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectorRequest $request)
    {
        $data = $request->validated();
        
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $collector = Collector::create($data);
        event(new CollectorCreated($collector));
        return redirect()->route('collectors.index')
            ->with('success', 'Collector created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collector $collector)
    {
        $collector->load(['parties', 'collections' => function ($query) {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        }]);

        $totalCollections = $collector->collections->sum('amount_collected');
        $recentCollections = $collector->collections()->with('party')->orderBy('date', 'desc')->limit(10)->get();

        return view('collectors.show', compact('collector', 'totalCollections', 'recentCollections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collector $collector)
    {
        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectorRequest $request, Collector $collector)
    {
        $data = $request->validated();
        
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from update if not provided
            unset($data['password']);
        }

        $collector->update($data);

        return redirect()->route('collectors.index')
            ->with('success', 'Collector updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collector $collector)
    {
        // Check if collector has parties or collections
        if ($collector->parties()->count() > 0 || $collector->collections()->count() > 0) {
            return redirect()->route('collectors.index')
                ->with('error', 'Cannot delete collector with existing parties or collections.');
        }

        $collector->delete();

        return redirect()->route('collectors.index')
            ->with('success', 'Collector deleted successfully.');
    }
}
