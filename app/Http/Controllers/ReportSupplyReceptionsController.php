<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use App\Exports\SupplyReceptionsExport;
use App\SuppliesHelper;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportSupplyReceptionsController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = SupplyOrder::whereIn('status', [3, 4, 5])->where('delivery_date', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->where('delivery_date', '<=', $to_date);
        }

        $supply_orders = $query->get();

        $supplies = SuppliesHelper::get_supplies_from_supply_orders($supply_orders);
        
        return view('report_receptions.index', compact('from_date', 'to_date', 'supplies'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $query = SupplyOrder::whereIn('status', [3, 4, 5])->where('delivery_date', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->where('delivery_date', '<=', $to_date);
        }

        $supply_orders = $query->get();

        $supplies = SuppliesHelper::get_supplies_from_supply_orders($supply_orders);
        
        return Pdf::loadView('report_receptions.pdf', ['supplies' => $supplies])->download('Reporte_Recepciones_' . date('d-m-Y') . '.pdf');
    }

    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        return Excel::download(new SupplyReceptionsExport($from_date, $to_date), 'Reporte_Recepciones_' . date('d-m-Y') . '.xlsx');
    }
}
