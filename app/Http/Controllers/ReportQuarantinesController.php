<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\QuarantinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportQuarantinesController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = DB::table('quarantines AS q')
            ->join('supplies AS s', 'q.supply', '=', 's.name')
            ->join('measurement_units AS m', 'q.measurement_unit_id', '=', 'm.id')
            ->join('supply_locations AS sl', 'q.supply_location_id', '=', 'sl.id')
            ->select('q.supply', 'q.transaction_amount', 'q.quantity', 'q.category', 'q.reason', 'q.created_at', 'q.measurement_unit_id', 'q.supply_location_id', 'm.measure', 'sl.location', 's.supply_key', 's.average_cost')
            ->whereDate('q.created_at', '>=', $from_date);
        
        if(!empty($to_date)) {
            $query->whereDate('q.created_at', '<=', $to_date);
        }

        if(!empty($supply_key)) {
            $query->where('s.supply_key', $supply_key);
        }

        $quarantines = $query->orderBy('q.created_at', 'asc')->get();
       
        return view('report_quarantines.index', compact('quarantines', 'supply_key', 'from_date', 'to_date'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');    
        
        $query = DB::table('quarantines AS q')
            ->join('supplies AS s', 'q.supply', '=', 's.name')
            ->join('measurement_units AS m', 'q.measurement_unit_id', '=', 'm.id')
            ->join('supply_locations AS sl', 'q.supply_location_id', '=', 'sl.id')
            ->select('q.supply', 'q.transaction_amount', 'q.quantity', 'q.category', 'q.reason', 'q.created_at', 'q.measurement_unit_id', 'q.supply_location_id', 'm.measure', 'sl.location', 's.supply_key', 's.average_cost')
            ->whereDate('q.created_at', '>=', $from_date);
        
        if(!empty($to_date)) {
            $query->whereDate('q.created_at', '<=', $to_date);
        }

        if(!empty($supply_key)) {
            $query->where('s.supply_key', $supply_key);
        }

        $quarantines = $query->orderBy('q.created_at', 'asc')->get();

        return Pdf::loadView('report_quarantines.pdf', ['quarantines' => $quarantines])->download('Reporte_Cuarentenas_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $supply_key = $request->get('supply_key');    

        return Excel::download(new QuarantinesExport($from_date, $to_date, $supply_key), 'Reporte_Cuarentenas_' . date('d-m-Y') . '.xlsx');
    }
}
