<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\SaleInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function salesReport()
    {
        $selectedMonth = request('month', Carbon::now()->format('Y-m'));

        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->copy()->startOfMonth();
        $endOfMonth = $carbonMonth->copy()->endOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;

        $monthsDropdown = [];
        for ($i = 0; $i <= 12; $i++) {
            $monthObj = Carbon::now()->subMonths($i);
            $monthsDropdown[$monthObj->format('Y-m')] = $monthObj->format('F Y');
        }

        $salesData = SaleInvoice::where('sale_invoices.is_draft', false)
            ->whereBetween('sale_invoices.created_at', [$startOfMonth, $endOfMonth])
            ->join('sale_invoice_lines', 'sale_invoices.id', '=', 'sale_invoice_lines.sale_invoice_id')
            ->select(
                DB::raw('DAY(sale_invoices.created_at) as day'),
                DB::raw('COUNT(DISTINCT sale_invoices.id) as invoices_count'),
                DB::raw('SUM(sale_invoice_lines.quantity) as items_count'),
                DB::raw('SUM(sale_invoice_lines.total) as total_sales')
            )
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $reportRows = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $hasData = isset($salesData[$d]);
            $reportRows[] = [
                'day' => $d,
                'invoices_count' => $hasData ? $salesData[$d]->invoices_count : 0,
                'items_count' => $hasData ? $salesData[$d]->items_count : 0,
                'total_sales' => $hasData ? $salesData[$d]->total_sales : 0,
            ];
        }

        return view('sale-reports.sales', compact('reportRows', 'monthsDropdown', 'selectedMonth'));
    }

    public function exportReport(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->copy()->startOfMonth();
        $endOfMonth = $carbonMonth->copy()->endOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;

        $salesData = SaleInvoice::where('sale_invoices.is_draft', false)
            ->whereBetween('sale_invoices.created_at', [$startOfMonth, $endOfMonth])
            ->join('sale_invoice_lines', 'sale_invoices.id', '=', 'sale_invoice_lines.sale_invoice_id')
            ->select(
                DB::raw('DAY(sale_invoices.created_at) as day'),
                DB::raw('COUNT(DISTINCT sale_invoices.id) as invoices_count'),
                DB::raw('SUM(sale_invoice_lines.quantity) as items_count'),
                DB::raw('SUM(sale_invoice_lines.total) as total_sales')
            )
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $reportRows = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $hasData = isset($salesData[$d]);
            $reportRows[] = [
                'day' => 'Day ' . $d,
                'invoices_count' => $hasData ? $salesData[$d]->invoices_count : "0",
                'items_count' => $hasData ? $salesData[$d]->items_count : "0",
                'total_sales' => $hasData ? $salesData[$d]->total_sales : "0",
            ];
        }

        $fileName = 'sales_report_' . $selectedMonth . '.xls';

        return Excel::download(new SalesReportExport($reportRows), $fileName, \Maatwebsite\Excel\Excel::XLS);
    }
}
