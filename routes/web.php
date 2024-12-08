<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PrintDocsController;
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
    return ProductVariant::where('name', 'like', "%$search%")
        ->limit(25)
        ->get(['id', 'name', 'vendor_price']);
});

Route::middleware('api')->get('/api/variant-vendor-price', function (Request $request) {
    $variant_id = $request->query('variant_id');
    if(!empty($variant_id)) {
        $variant = ProductVariant::where('id', $variant_id)->first();
        if($variant) {
            $vendor_id = $request->query('vendor_id') ?? '';
            if($vendor_id) {
                $vendor_price = VendorProductPrice::where('vendor_id', $vendor_id)
                    ->where('product_variant_id', $variant->id)
                    ->first();
                if($vendor_price) {
                    return $vendor_price->price;
                }
                else {
                    return $variant->vendor_price;
                }
            }
            else {
                return $variant->vendor_price;
            }
        }
    }
    return 0;
});


Route::get('/stock-search-report', [ReportsController::class, 'generateReport'])->name('stock.report');
Route::get('/stock-report/pdf', [ReportsController::class, 'downloadPdf'])->name('stock.report.pdf');


Route::get('/purchase-order/print-po/{record}', [PrintDocsController::class, 'printPO'])->name('print_po');
Route::get('/purchase-order/print-po-no-price/{record}', [PrintDocsController::class, 'printPONoPrice'])->name('print_po_no_price');
