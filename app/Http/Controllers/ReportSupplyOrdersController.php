<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SupplyOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportSupplyOrdersController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = DB::table('supply_order_details AS sod')
            ->join('supplies AS s', 'sod.supply_id', '=', 's.id') 
            ->join('supply_orders AS so', 'sod.supply_order_id', '=', 'so.id')
            ->join('measurement_units AS m', 'sod.measurement_unit_id', '=', 'm.id')
            ->join('suppliers AS su', 'so.supplier_id', '=', 'su.id')
            ->select('sod.supply_order_id', 'sod.supply', 'sod.quantity', 'sod.cost', 'sod.received_quantity', 'sod.supply_id', 'sod.measurement_unit_id', 's.supply_key', 'so.status', 'so.created_at', 'm.measure', 'su.supplier_key')
            ->where('s.supply_key', $supply_key)
            ->whereDate('so.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $to_date);
        }

        $supply_orders = $query->orderBy('sod.created_at', 'asc')->get();
 
        return view('report_supply_orders.index', compact('supply_orders', 'from_date', 'to_date', 'supply_key'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');
        
        $query = DB::table('supply_order_details AS sod')
            ->join('supplies AS s', 'sod.supply_id', '=', 's.id') 
            ->join('supply_orders AS so', 'sod.supply_order_id', '=', 'so.id')
            ->join('measurement_units AS m', 'sod.measurement_unit_id', '=', 'm.id')
            ->join('suppliers AS su', 'so.supplier_id', '=', 'su.id')
            ->select('sod.supply_order_id', 'sod.supply', 'sod.quantity', 'sod.cost', 'sod.received_quantity', 'sod.supply_id', 'sod.measurement_unit_id', 's.supply_key', 'so.status', 'so.created_at', 'm.measure', 'su.supplier_key')
            ->where('s.supply_key', $supply_key)
            ->whereDate('so.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $to_date);
        }

        $supply_orders = $query->orderBy('sod.created_at', 'asc')->get();

        return Pdf::loadView('report_supply_orders.pdf', ['supply_orders' => $supply_orders])->setPaper('a4','landscape')->download('Compras_Por_Materia_Prima_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        return Excel::download(new SupplyOrdersExport($from_date, $to_date, $supply_key), 'Compras_Por_Materia_Prima' . date('d-m-Y') . '.xlsx');
    }
}
