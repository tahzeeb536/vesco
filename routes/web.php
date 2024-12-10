<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PrintDocsController;
use App\Http\Controllers\ExportController;
use App\Models\VendorProductPrice;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware('api')->get('/api/product-variants', function (Request $request) {
    $search = $request->query('search', '');
    $vendorId = $request->query('vendor_id', '');
    return ProductVariant::with(['vendor_prices' => function($query) use($vendorId) {
        $query->where('vendor_id', $vendorId);
    }])
    ->where('name', 'like', "%$search%")
    ->limit(25)
    ->get(['id', 'name', 'vendor_price']);
});

Route::get('/stock-search-report', [ReportsController::class, 'generateReport'])->name('stock.report');
Route::get('/stock-report/pdf', [ReportsController::class, 'downloadPdf'])->name('stock.report.pdf');


Route::get('/purchase-order/print-po/{record}', [PrintDocsController::class, 'printPO'])->name('print_po');
Route::get('/purchase-order/print-po-no-price/{record}', [PrintDocsController::class, 'printPONoPrice'])->name('print_po_no_price');


Route::get('/export-out-of-stock', [ExportController::class, 'exportOutOfStock'])->name('export.out_of_stock');
Route::get('/export-stock-detail', [ExportController::class, 'exportStockDetail'])->name('export.stock_detail');