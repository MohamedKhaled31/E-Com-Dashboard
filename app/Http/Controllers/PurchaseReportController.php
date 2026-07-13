<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseReportExport;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReportController extends Controller
{
    public function purchaseReport(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));

        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->copy()->startOfMonth();
        $endOfMonth = $carbonMonth->copy()->endOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;

        $monthsDropdown = [];
        for ($i = 0; $i <= 12; $i++) {
            $monthObj = Carbon::now()->subMonths($i);
            $monthsDropdown[$monthObj->format('Y-m')] = $monthObj->format('F Y');
        }

        $purchaseData = PurchaseInvoice::where('purchase_invoices.is_draft', false)
            ->whereBetween('purchase_invoices.created_at', [$startOfMonth, $endOfMonth])
            ->join('purchase_invoice_lines', 'purchase_invoices.id', '=', 'purchase_invoice_lines.purchase_invoice_id')
            ->select(
                DB::raw('DAY(purchase_invoices.created_at) as day'),
                DB::raw('COUNT(DISTINCT purchase_invoices.id) as invoices_count'),
                DB::raw('SUM(purchase_invoice_lines.quantity) as items_count'),
                DB::raw('SUM(purchase_invoice_lines.total) as total_purchases')
            )
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $reportRows = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $hasData = isset($purchaseData[$d]);
            $reportRows[] = [
                'day' => $d,
                'invoices_count' => $hasData ? $purchaseData[$d]->invoices_count : 0,
                'items_count' => $hasData ? $purchaseData[$d]->items_count : 0,
                'total_purchases' => $hasData ? $purchaseData[$d]->total_purchases : 0,
            ];
        }

        return view('sale-reports.purchases', compact('reportRows', 'monthsDropdown', 'selectedMonth'));
    }

    public function exportPurchaseReport(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->copy()->startOfMonth();
        $endOfMonth = $carbonMonth->copy()->endOfMonth();
        $daysInMonth = $carbonMonth->daysInMonth;

        $purchaseData = PurchaseInvoice::where('purchase_invoices.is_draft', false)
            ->whereBetween('purchase_invoices.created_at', [$startOfMonth, $endOfMonth])
            ->join('purchase_invoice_lines', 'purchase_invoices.id', '=', 'purchase_invoice_lines.purchase_invoice_id')
            ->select(
                DB::raw('DAY(purchase_invoices.created_at) as day'),
                DB::raw('COUNT(DISTINCT purchase_invoices.id) as invoices_count'),
                DB::raw('SUM(purchase_invoice_lines.quantity) as items_count'),
                DB::raw('SUM(purchase_invoice_lines.total) as total_purchases')
            )
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $reportRows = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $hasData = isset($purchaseData[$d]);
            $reportRows[] = [
                'day' => 'Day ' . $d,
                'invoices_count' => $hasData ? $purchaseData[$d]->invoices_count : "0",
                'items_count' => $hasData ? $purchaseData[$d]->items_count : "0",
                'total_purchases' => $hasData ? $purchaseData[$d]->total_purchases : "0",
            ];
        }

        $fileName = 'purchase_report_' . $selectedMonth . '.xls';

        return Excel::download(new PurchaseReportExport($reportRows), $fileName, \Maatwebsite\Excel\Excel::XLS);
    }
}
