<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use App\Models\StockEntry;

class ReportsController extends Controller
{
    public function generateReport(Request $request)
    {
        $data = $request->all();

        $query = StockEntry::query()
            ->selectRaw('product_variant_id, SUM(quantity) as total_quantity')
            ->with([
                'productVariant.product.category', 
                'productVariant.size', 
                'productVariant.color', 
                'shelf.rack.room.store'
            ])
            ->groupBy('product_variant_id');


            if (!empty($data['product_name'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['product_name'] . '%');
                });
            }

            if (!empty($data['vendor_name'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('name_for_vendor', 'like', '%' . $data['vendor_name'] . '%');
                });
            }
        
            if (!empty($data['article_number'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('article_number', 'like', '%' . $data['article_number'] . '%');
                });
            }

            if (!empty($data['size'])) {
                $query->whereHas('productVariant.size', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['size'] . '%');
                });
            }
        
            if (!empty($data['color'])) {
                $query->whereHas('productVariant.color', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['color'] . '%');
                });
            }

            if (!empty($data['category'])) {
                $query->whereHas('productVariant.product.category', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['category'] . '%');
                });
            }

            if (!empty($data['store'])) {
                $query->whereHas('shelf.rack.room.store', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['store'] . '%');
                });
            }
        
            if (!empty($data['room'])) {
                $query->whereHas('shelf.rack.room', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['room'] . '%');
                });
            }
        
            if (!empty($data['rack'])) {
                $query->whereHas('shelf.rack', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['rack'] . '%');
                });
            }

            if (!empty($data['shelf'])) {
                $query->whereHas('shelf', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['shelf'] . '%');
                });
            }
        
        

        // Retrieve the matching stock entries with grouped quantities
        $records = $query->get();

        return view('reports.stock-report', compact('records'));

    }


    public function downloadPdf(Request $request)
    {
        $data = $request->all();

        $query = StockEntry::query()
            ->selectRaw('product_variant_id, SUM(quantity) as total_quantity')
            ->with([
                'productVariant.product.category', 
                'productVariant.size', 
                'productVariant.color', 
                'shelf.rack.room.store'
            ])
            ->groupBy('product_variant_id');


            if (!empty($data['product_name'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['product_name'] . '%');
                });
            }

            if (!empty($data['vendor_name'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('name_for_vendor', 'like', '%' . $data['vendor_name'] . '%');
                });
            }
        
            if (!empty($data['article_number'])) {
                $query->whereHas('productVariant.product', function (Builder $q) use ($data) {
                    $q->where('article_number', 'like', '%' . $data['article_number'] . '%');
                });
            }

            if (!empty($data['size'])) {
                $query->whereHas('productVariant.size', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['size'] . '%');
                });
            }
        
            if (!empty($data['color'])) {
                $query->whereHas('productVariant.color', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['color'] . '%');
                });
            }

            if (!empty($data['category'])) {
                $query->whereHas('productVariant.product.category', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['category'] . '%');
                });
            }

            if (!empty($data['store'])) {
                $query->whereHas('shelf.rack.room.store', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['store'] . '%');
                });
            }
        
            if (!empty($data['room'])) {
                $query->whereHas('shelf.rack.room', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['room'] . '%');
                });
            }
        
            if (!empty($data['rack'])) {
                $query->whereHas('shelf.rack', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['rack'] . '%');
                });
            }

            if (!empty($data['shelf'])) {
                $query->whereHas('shelf', function (Builder $q) use ($data) {
                    $q->where('name', 'like', '%' . $data['shelf'] . '%');
                });
            }
        
        

        // Retrieve the matching stock entries with grouped quantities
        $records = $query->get();

        // Generate the PDF with A4 settings
        $pdf = Pdf::loadView('reports.stock-report-pdf', compact('records'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('stock-report.pdf');
    }
}
