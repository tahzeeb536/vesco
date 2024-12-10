<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\OutOfStockExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportOutOfStock()
    {
        return Excel::download(new OutOfStockExport, 'out-of-stock.xlsx');
    }
}
