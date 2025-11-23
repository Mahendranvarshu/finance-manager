<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // ----------------------------
    // DAILY REPORT
    // ----------------------------
    public function daily(Request $request)
    {
        $date = $request->has('date') && $request->date 
            ? Carbon::parse($request->date) 
            : Carbon::today();

        $reportData = $this->reportService->getDailyReport($date);

        return view('reports.daily', $reportData);
    }

    // ----------------------------
    // WEEKLY REPORT
    // ----------------------------
    public function weekly(Request $request)
    {
        $date = $request->has('date') && $request->date 
            ? Carbon::parse($request->date) 
            : Carbon::now();

        $reportData = $this->reportService->getWeeklyReport($date);

        return view('reports.weekly', $reportData);
    }

    // ----------------------------
    // MONTHLY REPORT
    // ----------------------------
    public function monthly(Request $request)
    {
        $date = $request->has('date') && $request->date 
            ? Carbon::parse($request->date) 
            : Carbon::now();

        $reportData = $this->reportService->getMonthlyReport($date);

        return view('reports.monthly', $reportData);
    }
}
