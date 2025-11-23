<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate daily report data
     */
    public function getDailyReport(Carbon $date = null)
    {
        $date = $date ?? Carbon::today();

        $collections = Collection::with(['party', 'collector'])
            ->whereDate('date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCollected = $collections->sum('amount_collected');
        $collectionCount = $collections->count();

        // Group by collector
        $byCollector = $collections->groupBy('collector_id')->map(function ($items, $collectorId) {
            $collector = $items->first()->collector;
            return [
                'collector' => $collector,
                'collections' => $items,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        });

        return [
            'date' => $date,
            'collections' => $collections,
            'totalCollected' => $totalCollected,
            'collectionCount' => $collectionCount,
            'byCollector' => $byCollector,
        ];
    }

    /**
     * Generate weekly report data
     */
    public function getWeeklyReport(Carbon $date = null)
    {
        $date = $date ?? Carbon::now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();

        $collections = Collection::with(['party', 'collector'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCollected = $collections->sum('amount_collected');
        $collectionCount = $collections->count();

        // Group by date
        $byDate = $collections->groupBy(function ($collection) {
            return $collection->date->format('Y-m-d');
        })->map(function ($items, $date) {
            return [
                'date' => $date,
                'collections' => $items,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        });

        // Group by collector
        $byCollector = $collections->groupBy('collector_id')->map(function ($items, $collectorId) {
            $collector = $items->first()->collector;
            return [
                'collector' => $collector,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        });

        return [
            'start' => $start,
            'end' => $end,
            'collections' => $collections,
            'totalCollected' => $totalCollected,
            'collectionCount' => $collectionCount,
            'byDate' => $byDate,
            'byCollector' => $byCollector,
        ];
    }

    /**
     * Generate monthly report data
     */
    public function getMonthlyReport(Carbon $date = null)
    {
        $date = $date ?? Carbon::now();
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $collections = Collection::with(['party', 'collector'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCollected = $collections->sum('amount_collected');
        $collectionCount = $collections->count();

        // Group by date
        $byDate = $collections->groupBy(function ($collection) {
            return $collection->date->format('Y-m-d');
        })->map(function ($items, $date) {
            return [
                'date' => $date,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        });

        // Group by collector
        $byCollector = $collections->groupBy('collector_id')->map(function ($items, $collectorId) {
            $collector = $items->first()->collector;
            return [
                'collector' => $collector,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        });

        // Top parties
        $topParties = $collections->groupBy('party_id')->map(function ($items, $partyId) {
            $party = $items->first()->party;
            return [
                'party' => $party,
                'total' => $items->sum('amount_collected'),
                'count' => $items->count(),
            ];
        })->sortByDesc('total')->take(10);

        return [
            'start' => $start,
            'end' => $end,
            'collections' => $collections,
            'totalCollected' => $totalCollected,
            'collectionCount' => $collectionCount,
            'byDate' => $byDate,
            'byCollector' => $byCollector,
            'topParties' => $topParties,
        ];
    }
}

