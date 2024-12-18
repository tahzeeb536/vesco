<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PackagingList;
use App\Models\LetterHead;
use Illuminate\Support\Facades\Crypt;

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

    public function print_letter_head_with_logo($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_with_logo', compact('letterHead'));
    }

    public function print_letter_head_without_logo($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_without_logo', compact('letterHead'));
    }

    public function print_letter_head_without_stamp($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_without_stamp', compact('letterHead'));
    }
    
    public function print_packaging_list_with_logo($record) {
        $packagingList = PackagingList::findOrFail($record);
        return view('pdf.print_packaging_list_with_logo', compact('packagingList'));
    }

    public function print_packaging_list_with_stamp($record) {
        $packagingList = PackagingList::findOrFail($record);
        return view('pdf.print_packaging_list_with_stamp', compact('packagingList'));
    }

    public function share_packaging_list($record) {
        $decodedId = Crypt::decrypt($record);
        $packagingList = PackagingList::findOrFail($decodedId);
        return view('pdf.share_packaging_list', compact('packagingList'));
    }
}
