<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Party;
use App\Services\LoanCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollectorCollectionController extends Controller
{
    public function create(Request $request)
    {
        $collector = auth('collector')->user();
        $dlNo = $request->get('dl_no');
        $party = null;

        if ($dlNo) {
            $party = Party::where('dl_no', $dlNo)
                ->where('collector_id', $collector->id)
                ->where('status', 'active')
                ->with('collections')
                ->first();

            if ($party) {
                $collected = $party->collections->sum('amount_collected');
                $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
                $remaining = $totalAmount - $collected;
                $party->collected_amount = $collected;
                $party->remaining_amount = $remaining;
                $party->daily_amount_display = $party->daily_amount ?? 0;
            }
        }

        return view('collector.collection.create', compact('party', 'dlNo'));
    }

    public function store(Request $request)
    {
        $collector = auth('collector')->user();

        $request->validate([
            'dl_no' => 'required|string',
            'amount_collected' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $party = Party::where('dl_no', $request->dl_no)
            ->where('collector_id', $collector->id)
            ->where('status', 'active')
            ->first();

        if (!$party) {
            return back()->withErrors(['dl_no' => 'Party not found or not assigned to you.'])->withInput();
        }

        // Calculate day number
        $partyStartDate = Carbon::parse($party->starting_date);
        $collectionDate = Carbon::parse($request->date);
        $dayNumber = LoanCalculator::calculateDayNumber($collectionDate, $partyStartDate);

        // Calculate remaining amount
        $collectedAmount = $party->collections->sum('amount_collected') + $request->amount_collected;
        $remaining = LoanCalculator::calculateRemainingAmount(
            $party->loan_amount,
            $party->interest_amount ?? 0,
            $collectedAmount
        );

        Collection::create([
            'party_id' => $party->id,
            'collector_id' => $collector->id,
            'date' => $request->date,
            'amount_collected' => $request->amount_collected,
            'remaining_amount' => $remaining,
            'day_number' => $dayNumber,
            'remarks' => $request->remarks,
        ]);

        // Update party status if loan is completed
        $party->refresh();
        $totalCollected = $party->collections->sum('amount_collected');
        $totalAmount = $party->loan_amount + ($party->interest_amount ?? 0);
        
        if ($totalCollected >= $totalAmount && $party->status === 'active') {
            $party->update(['status' => 'completed']);
        }

        return redirect()->route('collector.collection.create')
            ->with('success', 'Collection recorded successfully!')
            ->with('dl_no', $request->dl_no);
    }

    public function index(Request $request)
    {
        $collector = auth('collector')->user();
        $query = Collection::where('collector_id', $collector->id)
            ->with('party')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by date
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('date', $request->date);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $collections = $query->paginate(20);

        return view('collector.collection.index', compact('collections'));
    }

    public function report()
    {
        $collector = auth('collector')->user();
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Today's collections
        $todayCollections = Collection::where('collector_id', $collector->id)
            ->whereDate('date', $today)
            ->with('party')
            ->get();
        $todayTotal = $todayCollections->sum('amount_collected');

        // Week's collections
        $weekCollections = Collection::where('collector_id', $collector->id)
            ->whereBetween('date', [$startOfWeek, Carbon::now()])
            ->get();
        $weekTotal = $weekCollections->sum('amount_collected');

        // Month's collections
        $monthCollections = Collection::where('collector_id', $collector->id)
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->get();
        $monthTotal = $monthCollections->sum('amount_collected');

        // Group by date for daily breakdown
        $dailyBreakdown = Collection::where('collector_id', $collector->id)
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->get()
            ->groupBy(function ($collection) {
                return $collection->date->format('Y-m-d');
            })
            ->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'count' => $items->count(),
                    'total' => $items->sum('amount_collected'),
                ];
            })
            ->sortKeysDesc();

        return view('collector.collection.report', compact(
            'todayTotal',
            'weekTotal',
            'monthTotal',
            'dailyBreakdown'
        ));
    }

    public function exportExcel(Request $request)
    {
        $collector = auth('collector')->user();
        $query = Collection::where('collector_id', $collector->id)
            ->with('party')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by date
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('date', $request->date);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $collections = $query->get();

        $filename = 'collections_' . $collector->name . '_' . date('Y-m-d') . '.xlsx';
        
        // Generate Excel XML format (Excel 2003+ compatible)
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= '<Worksheet ss:Name="Collections">' . "\n";
        $xml .= '<Table>' . "\n";

        // Headers
        $xml .= '<Row>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Date</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Time</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Party Name</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">DL Number</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Amount Collected</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Remaining Amount</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Day Number</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String">Remarks</Data></Cell>' . "\n";
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($collections as $collection) {
            $xml .= '<Row>' . "\n";
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($collection->date->format('Y-m-d')) . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($collection->created_at->format('H:i:s')) . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($collection->party->name) . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($collection->party->dl_no ?? '-') . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="Number">' . $collection->amount_collected . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="Number">' . ($collection->remaining_amount ?? 0) . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="Number">' . ($collection->day_number ?? '') . '</Data></Cell>' . "\n";
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($collection->remarks ?? '') . '</Data></Cell>' . "\n";
            $xml .= '</Row>' . "\n";
        }

        // Total row
        $total = $collections->sum('amount_collected');
        $xml .= '<Row>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"><B>Total</B></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="Number">' . $total . '</Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '<Cell><Data ss:Type="String"></Data></Cell>' . "\n";
        $xml .= '</Row>' . "\n";

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return response($xml, 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    public function exportCsv(Request $request)
    {
        $collector = auth('collector')->user();
        $query = Collection::where('collector_id', $collector->id)
            ->with('party')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by date
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('date', $request->date);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $collections = $query->get();

        $filename = 'collections_' . str_replace(' ', '_', $collector->name) . '_' . date('Y-m-d') . '.csv';
        
        // Build CSV content
        $csvData = [];
        
        // Add UTF-8 BOM for Excel compatibility
        $csvData[] = chr(0xEF) . chr(0xBB) . chr(0xBF);
        
        // CSV Headers
        $headers = [
            'Date',
            'Time',
            'Party Name',
            'DL Number',
            'Store Name',
            'Phone',
            'Amount Collected',
            'Remaining Amount',
            'Day Number',
            'Remarks'
        ];
        $csvData[] = implode(',', array_map(function($field) {
            return '"' . str_replace('"', '""', $field) . '"';
        }, $headers));

        // CSV Data rows
        foreach ($collections as $collection) {
            $row = [
                $collection->date->format('Y-m-d'),
                $collection->created_at->format('H:i:s'),
                $collection->party->name,
                $collection->party->dl_no ?? '',
                $collection->party->store_name ?? '',
                $collection->party->phone ?? '',
                number_format($collection->amount_collected, 2),
                number_format($collection->remaining_amount ?? 0, 2),
                $collection->day_number ?? '',
                $collection->remarks ?? ''
            ];
            $csvData[] = implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row));
        }

        // Total row
        $total = $collections->sum('amount_collected');
        $csvData[] = ''; // Empty row
        $totalRow = ['', '', '', '', '', '', 'Total', number_format($total, 2), '', ''];
        $csvData[] = implode(',', array_map(function($field) {
            return '"' . str_replace('"', '""', $field) . '"';
        }, $totalRow));

        $csvContent = implode("\n", $csvData);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'public');
    }
}

