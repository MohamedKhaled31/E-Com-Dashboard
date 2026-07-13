<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\PurchaseInvoiceLineController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\SaleInvoiceLineController;
use App\Http\Controllers\SuppliersController;
use Illuminate\Support\Facades\Route;




Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register/store', [AuthController::class, 'register_store'])->name('register.store');
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login/store', [AuthController::class, 'login_store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('categories', CategoriesController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('suppliers', SuppliersController::class);

    Route::post('purchase-invoices/{purchase_invoice}/save', [PurchaseInvoiceController::class, 'save'])->name('purchase-invoices.save');
    Route::resource('purchase-invoices', PurchaseInvoiceController::class);
    Route::resource('purchase-invoices/{purchase_invoice}/lines', PurchaseInvoiceLineController::class)->names('purchase-invoices.lines');

    Route::post('sale-invoices/{sale_invoice}/save', [SaleInvoiceController::class, 'save'])->name('sale-invoices.save');
    Route::resource('sale-invoices', SaleInvoiceController::class);
    Route::resource('sale-invoices/{sale_invoice}/lines', SaleInvoiceLineController::class)->names('sale-invoices.lines');

    Route::get('reports/sales', [ReportsController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/sales/export', [ReportsController::class, 'exportReport'])->name('reports.sales.export');

    Route::get('reports/purchases', [PurchaseReportController::class, 'purchaseReport'])->name('reports.purchases');
    Route::get('reports/purchases/export', [PurchaseReportController::class, 'exportPurchaseReport'])->name('reports.purchases.export');
});
