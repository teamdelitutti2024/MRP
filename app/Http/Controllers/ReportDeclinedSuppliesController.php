<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\DeclinedSuppliesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportDeclinedSuppliesController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = DB::table('declined_supplies AS ds')
            ->join('measurement_units AS m', 'ds.measurement_unit_id', '=', 'm.id') 
            ->join('supply_locations AS sl', 'ds.supply_location_id', '=', 'sl.id')
            ->join('supplies AS s','ds.supply_id', '=', 's.id')
            ->select('ds.supply', 'ds.lost_quantity', 'ds.category', 'ds.reason', 'ds.transaction_amount', 'ds.created_at', 'ds.measurement_unit_id', 'ds.supply_location_id', 'm.measure', 'sl.location', 's.supply_key', 's.average_cost')
            ->whereDate('ds.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query->whereDate('ds.created_at', '<=', $to_date);
        }

        if(!empty($supply_key)) {
            $query->where('s.supply_key', $supply_key);
        }

        $declined_supplies = $query->orderBy('ds.created_at', 'asc')->get();

        return view('report_declined_supplies.index', compact('declined_supplies', 'from_date', 'to_date', 'supply_key'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        $query = DB::table('declined_supplies AS ds')
            ->join('measurement_units AS m', 'ds.measurement_unit_id', '=', 'm.id') 
            ->join('supply_locations AS sl', 'ds.supply_location_id', '=', 'sl.id')
            ->join('supplies AS s','ds.supply_id', '=', 's.id')
            ->select('ds.supply', 'ds.lost_quantity', 'ds.category', 'ds.reason', 'ds.transaction_amount', 'ds.created_at', 'ds.measurement_unit_id', 'ds.supply_location_id', 'm.measure', 'sl.location', 's.supply_key', 's.average_cost')
            ->whereDate('ds.created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query->whereDate('ds.created_at', '<=', $to_date);
        }

        if(!empty($supply_key)) {
            $query->where('s.supply_key', $supply_key);
        }

        $declined_supplies = $query->orderBy('ds.created_at', 'asc')->get();
        
        return Pdf::loadView('report_declined_supplies.pdf', ['declined' => $declined_supplies])->download('Reporte_Mermas_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        return Excel::download(new DeclinedSuppliesExport($from_date, $to_date, $supply_key), 'Reporte_Mermas_' . date('d-m-Y') . '.xlsx');
    }
}
