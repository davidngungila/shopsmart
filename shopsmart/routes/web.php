<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserRoleController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Products/Inventory
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('stock-movements', StockMovementController::class);
Route::get('/inventory/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');

// Sales
Route::resource('sales', SaleController::class);
Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
Route::post('/pos/complete', [POSController::class, 'complete'])->name('pos.complete');
Route::get('/sales/invoices', [SaleController::class, 'invoices'])->name('sales.invoices');
Route::get('/sales/returns', [SaleController::class, 'returns'])->name('sales.returns');

// Quotations
Route::resource('quotations', QuotationController::class);
Route::post('/quotations/{quotation}/update-status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
Route::post('/quotations/{quotation}/convert-to-sale', [QuotationController::class, 'convertToSale'])->name('quotations.convert-to-sale');
Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendEmail'])->name('quotations.send-email');
Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])->name('quotations.pdf');
Route::get('/quotations/reports/overview', [QuotationReportController::class, 'overview'])->name('quotations.reports.overview');
Route::get('/quotations/reports', [QuotationReportController::class, 'overview'])->name('quotations.reports');

// Purchases
Route::resource('purchases', PurchaseController::class);
Route::resource('suppliers', SupplierController::class);
Route::get('/purchases/orders', [PurchaseController::class, 'orders'])->name('purchases.orders');

// Customers
Route::resource('customers', CustomerController::class);
Route::get('/customers/loyalty', [CustomerController::class, 'loyalty'])->name('customers.loyalty');

// Employees
Route::resource('employees', EmployeeController::class);
Route::get('/employees/roles', [EmployeeController::class, 'roles'])->name('employees.roles');
Route::get('/employees/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');

// Financial
Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
Route::get('/financial/income', [FinancialController::class, 'income'])->name('financial.income');
Route::resource('expenses', ExpenseController::class);
Route::resource('transactions', TransactionController::class);
Route::get('/financial/profit-loss', [FinancialController::class, 'profitLoss'])->name('financial.profit-loss');

// Reports
Route::get('/reports', function () {
    return view('reports.index');
})->name('reports.index');
Route::get('/reports/sales', function () {
    return view('reports.sales');
})->name('reports.sales');
Route::get('/reports/inventory', function () {
    return view('reports.inventory');
})->name('reports.inventory');
Route::get('/reports/financial', function () {
    return view('reports.financial');
})->name('reports.financial');
Route::get('/reports/customers', function () {
    return view('reports.customers');
})->name('reports.customers');

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::get('/general', [SettingsController::class, 'general'])->name('general');
    Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
    Route::get('/users', [SettingsController::class, 'users'])->name('users');
    Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
    Route::get('/system', [SettingsController::class, 'system'])->name('system');
    Route::post('/system', [SettingsController::class, 'updateSystem'])->name('system.update');
    Route::get('/financial', [SettingsController::class, 'financial'])->name('financial');
    Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial.update');
    Route::get('/inventory', [SettingsController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [SettingsController::class, 'updateInventory'])->name('inventory.update');
    Route::get('/quotations', [SettingsController::class, 'quotations'])->name('quotations');
    Route::post('/quotations', [SettingsController::class, 'updateQuotations'])->name('quotations.update');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
    Route::resource('user-roles', UserRoleController::class);
});
