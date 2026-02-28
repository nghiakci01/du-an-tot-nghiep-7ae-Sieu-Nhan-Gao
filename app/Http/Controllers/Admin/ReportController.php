<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Export Orders to Excel
     */
    public function exportOrdersExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        return Excel::download(
            new OrdersExport($startDate, $endDate), 
            'orders-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx'
        );
    }

    /**
     * Export Revenue Report to PDF
     */
    public function exportRevenuePDF(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        $stats = $this->reportService->getOverviewStats($startDate, $endDate);
        $revenueChart = $this->reportService->getRevenueChartData($startDate, $endDate);
        $topProducts = $this->reportService->getTopProducts($startDate, $endDate, 10);

        $pdf = Pdf::loadView('admin.reports.revenue-pdf', [
            'stats' => $stats,
            'revenueChart' => $revenueChart,
            'topProducts' => $topProducts,
            'startDate' => $startDate->format('d/m/Y'),
            'endDate' => $endDate->format('d/m/Y'),
            'generatedAt' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download('revenue-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf');
    }
}
