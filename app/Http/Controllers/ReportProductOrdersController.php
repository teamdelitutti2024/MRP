<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportProductOrdersController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        return view('report_product_orders.index', compact('from_date', 'to_date'));
    }

    public function download(Request $request)
    {
        dd('TO DO');
    }

    public function export(Request $request)
    {
        dd('TO DO');
    }
}
