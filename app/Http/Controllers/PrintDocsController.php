<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PrintDocsController extends Controller
{
    public function printPO($record) {
        $purchaseOrder = PurchaseOrder::with('vendor', 'items')->findOrFail($record);
        return view('pdf.print-po', compact('purchaseOrder'));
    }

    public function printPONoPrice($record) {
        $purchaseOrder = PurchaseOrder::with('vendor', 'items')->findOrFail($record);
        return view('pdf.print-po-no-price', compact('purchaseOrder'));
    }
}
