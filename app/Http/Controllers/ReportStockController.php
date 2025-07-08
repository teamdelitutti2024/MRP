<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupplyLocation;
use App\Models\SupplyCategory;
use App\Exports\StockExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportStockController extends Controller
{
    public function index(Request $request)
    {
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();
        $supply_categories = SupplyCategory::orderBy('name', 'asc')->get();

        $category = $request->get('category');
        $location = $request->get('location_id');
        $supply_key = $request->get('supply_key');

        $query = DB::table('stock AS s')
            ->join('supplies AS su', 's.supply_id', '=', 'su.id')
            ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
            ->join('supply_locations AS sl', 's.supply_location_id', '=', 'sl.id')
            ->join('measurement_units AS m', 'su.measurement_unit_id', '=', 'm.id')
            ->select('s.quantity', 's.supply_id', 's.supply_location_id', 'su.supply_key', 'su.name', 'su.measurement_unit_id', 'su.average_cost', 'sc.name as cat_name', 'sl.location', 'm.measure');

        if(!empty($supply_key)) {
            $query->where('su.supply_key', $supply_key);
        }

        if(!empty($category) && $category != 'All') {
            $query->where('su.supply_category_id', $category);
        }

        if(!empty($location)) {
            $query->where('s.supply_location_id', $location);
        }

        $stock = $query->orderBy('cat_name', 'asc')->get();

        return view('report_stock.index', compact('stock', 'supply_locations', 'supply_categories', 'category', 'location', 'supply_key'));
    }

    public function download(Request $request)
    {
        $category = $request->get('category');
        $location = $request->get('location_id');
        $supply_key = $request->get('supply_key');

        $query = DB::table('stock AS s')
            ->join('supplies AS su', 's.supply_id', '=', 'su.id')
            ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
            ->join('supply_locations AS sl', 's.supply_location_id', '=', 'sl.id')
            ->join('measurement_units AS m', 'su.measurement_unit_id', '=', 'm.id')
            ->select('s.quantity', 's.supply_id', 's.supply_location_id', 'su.supply_key', 'su.name', 'su.measurement_unit_id', 'su.average_cost', 'sc.name as cat_name', 'sl.location', 'm.measure');

        if(!empty($supply_key)) {
            $query->where('su.supply_key', $supply_key);
        }

        if($category != 'All') {
            $query->where('su.supply_category_id', $category);
        }

        if(!empty($location)) {
            $query->where('s.supply_location_id', $location);
        }

        $stock = $query->orderBy('cat_name', 'asc')->get();

        return Pdf::loadView('report_stock.pdf', ['stock' => $stock])->download('Cantidades_Inventario_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $category = $request->get('category');
        $location = $request->get('location_id');
        $supply_key = $request->get('supply_key');

        return Excel::download(new StockExport($category, $location, $supply_key), 'Cantidades_Inventario_' . date('d-m-Y') . '.xlsx');
    }
}
