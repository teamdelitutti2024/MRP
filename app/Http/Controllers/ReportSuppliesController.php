<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SuppliesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportSuppliesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = DB::table('supplies')
            ->join('supply_categories', 'supplies.supply_category_id', '=', 'supply_categories.id')
            ->join('measurement_units', 'supplies.measurement_unit_id', '=', 'measurement_units.id')
            ->select('supplies.*', 'supply_categories.name as category', 'measurement_units.measure as measure');

        if(!empty($status) && $status != 'All') {
            $status == 1 ? $query->where('supplies.is_active', true) : $query->where('supplies.is_active', false);
        }

        $supplies = $query->orderBy('supplies.name', 'asc')->get();

        return view('report_supplies.index', compact('supplies', 'status'));
    }

    public function download(Request $request)
    {
        $status = $request->get('status');

        $query = DB::table('supplies')
            ->join('supply_categories', 'supplies.supply_category_id', '=', 'supply_categories.id')
            ->join('measurement_units', 'supplies.measurement_unit_id', '=', 'measurement_units.id')
            ->select('supplies.*', 'supply_categories.name as category', 'measurement_units.measure as measure');
        
        if(!empty($status) && $status != 'All') {
            $status == 1 ? $query->where('supplies.is_active', true) : $query->where('supplies.is_active', false);
        }

        $supplies = $query->orderBy('supplies.name', 'asc')->get();

        return Pdf::loadView('report_supplies.pdf', ['supplies' => $supplies])->setPaper('a4', 'landscape')->download('Reporte_Materias_Primas_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $status = $request->get('status');

        return Excel::download(new SuppliesExport($status), 'Reporte_Materias_Primas_' . date('d-m-Y') . '.xlsx');
    }
}
