<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Exports\SupplierOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportSupplierOrdersController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('supplier_key', 'asc')->get();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier_key = $request->get('supplier_key');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = DB::table('supply_orders AS so')
            ->join('suppliers AS s', 'so.supplier_id', '=', 's.id') 
            ->select('so.id', 'so.total', 'so.status', 'so.created_at', 'so.supplier_id', 's.name', 's.supplier_key')
            ->where('s.supplier_key', $supplier_key)
            ->whereDate('so.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $to_date);
        }

        $supplier_orders = $query->orderBy('so.created_at', 'asc')->get();
 
        return view('report_supplier_orders.index', compact('suppliers', 'supplier_orders', 'from_date', 'to_date', 'supplier_key'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier_key = $request->get('supplier_key');

        $query = DB::table('supply_orders AS so')
            ->join('suppliers AS s', 'so.supplier_id', '=', 's.id') 
            ->select('so.id', 'so.total', 'so.status', 'so.created_at', 'so.supplier_id', 's.name', 's.supplier_key')
            ->where('s.supplier_key', $supplier_key)
            ->whereDate('so.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $to_date);
        }

        $supplier_orders = $query->orderBy('so.created_at', 'asc')->get();
 
        return Pdf::loadView('report_supplier_orders.pdf', ['supplier_orders' => $supplier_orders])->download('Compras_Por_Proveedor_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supplier_key = $request->get('supplier_key');

        return Excel::download(new SupplierOrdersExport($from_date, $to_date, $supplier_key), 'Compras_Por_Proveedor_' . date('d-m-Y') . '.xlsx');
    }
}
