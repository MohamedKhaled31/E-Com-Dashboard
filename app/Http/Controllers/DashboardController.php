<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\SaleInvoice;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalPurchaseThisMonth = PurchaseInvoice::where('is_draft', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $totalSalesThisMonth = SaleInvoice::where('is_draft', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $totalStockValue = Product::sum(DB::raw('quantity * price'));

        $salesTarget = 100000;
        $targetPercentage = $salesTarget > 0 ? ($totalSalesThisMonth / $salesTarget) * 100 : 0;
        $progressPercentage = min($targetPercentage, 100);

        $daysInMonth = Carbon::now()->daysInMonth;
        $dailySalesData = SaleInvoice::where('is_draft', false)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('DAY(created_at) as day'), DB::raw('SUM(total) as total'))
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $chartDaysLabels = [];
        $chartDaysValues = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $chartDaysLabels[] = "Day " . $d;
            $chartDaysValues[] = $dailySalesData[$d] ?? 0;
        }

        $categorySales = Category::join('products', 'categories.id', '=', 'products.category_id')
            ->join('sale_invoice_lines', 'products.id', '=', 'sale_invoice_lines.product_id')
            ->join('sale_invoices', 'sale_invoice_lines.sale_invoice_id', '=', 'sale_invoices.id')
            ->where('sale_invoices.is_draft', false)
            ->whereBetween('sale_invoices.created_at', [$startOfMonth, $endOfMonth])
            ->select('categories.name', DB::raw('SUM(sale_invoice_lines.total) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $categoryLabels = $categorySales->pluck('name')->toArray();
        $categoryTotals = $categorySales->pluck('total')->toArray();

        return view('dashboard', compact(
            'totalPurchaseThisMonth', 'totalSalesThisMonth', 'totalStockValue',
            'salesTarget', 'targetPercentage', 'progressPercentage',
            'chartDaysLabels', 'chartDaysValues', 'categoryLabels', 'categoryTotals'
        ));
    }
}
