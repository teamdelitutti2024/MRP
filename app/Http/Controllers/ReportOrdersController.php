<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrder;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportOrdersController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        if(empty($from_date)) {
            $from_date = date('Y-m-d');
        }

        $query = SupplyOrder::whereDate('created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('created_at', '<=', $to_date);
        }

        $supply_orders = $query->orderBy('created_at', 'asc')->get();

        return view('report_orders.index', compact('supply_orders', 'from_date', 'to_date'));
    }

    public function download(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $query = SupplyOrder::whereDate('created_at', '>=', $from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('created_at', '<=', $to_date);
        }

        $supply_orders = $query->orderBy('created_at', 'asc')->get();

        return Pdf::loadView('report_orders.pdf', ['supply_orders' => $supply_orders])->download('Reporte_Ordenes_Compra_' . date('d-m-Y') . '.pdf');
    }
    
    public function export(Request $request)
    {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        return Excel::download(new OrdersExport($from_date, $to_date), 'Reporte_Ordenes_Compra_' . date('d-m-Y') . '.xlsx');
    }
}
