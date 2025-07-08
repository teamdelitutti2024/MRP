<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportProductsController extends Controller
{
    public function index()
    {
        $products = DB::table('product_sizes')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->select('product_sizes.*', 'products.name as product_name', 'products.status')
            ->get();

        return view('report_products.index', compact('products'));
    }

    public function download(Request $request)
    {
        $products = DB::table('product_sizes')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->select('product_sizes.*', 'products.name as product_name', 'products.status')
            ->get();

        return Pdf::loadView('report_products.pdf', ['products' => $products])->setPaper('a4', 'landscape')->download('Reporte_Productos_' . date('d-m-Y') . '.pdf');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'Reporte_Productos_' . date('d-m-Y') . '.xlsx');
    }
}
