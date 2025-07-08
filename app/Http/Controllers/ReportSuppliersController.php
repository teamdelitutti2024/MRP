<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Exports\SuppliersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportSuppliersController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('suppliers AS s')
            ->leftJoin('supplier_tax_data AS st', 's.id', '=', 'st.supplier_id')
            ->leftJoin('supplier_contacts AS sc', 's.id' ,'=', 'sc.supplier_id')
            ->select('s.id', 's.name', 's.supplier_key', 's.address', 's.preferred_payment_method', 'st.rfc', 'st.supplier_id', 'sc.name as contact', 'sc.phone', 'sc.email')
            ->orderBy('name', 'asc')
            ->get();

        // Get supplies categories for each supplier
        foreach($suppliers as $supplier) {
            $supplies_categories = DB::table('suppliers AS s')
                ->join('supplier_supplies AS ss', 's.id', '=', 'ss.supplier_id')
                ->join('supplies AS su', 'ss.supply_id', '=', 'su.id')
                ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
                ->select('sc.name')
                ->where('s.id', $supplier->id)
                ->distinct()
                ->get()
                ->toArray();

            $supplier->categories = $supplies_categories;
        }

        return view('report_suppliers.index', compact('suppliers'));
    }

    public function download()
    {
        $suppliers = DB::table('suppliers AS s')
            ->leftJoin('supplier_tax_data AS st','s.id','=','st.supplier_id')
            ->leftJoin('supplier_contacts AS sc','s.id','=','sc.supplier_id')
            ->select('s.id', 's.name', 's.supplier_key', 's.address', 's.preferred_payment_method', 'st.rfc', 'st.supplier_id', 'sc.name as contact', 'sc.phone', 'sc.email')
            ->orderBy('name', 'asc')
            ->get();

        // Get supplies categories for each supplier
        foreach($suppliers as $supplier) {
            $supplies_categories = DB::table('suppliers AS s')
                ->join('supplier_supplies AS ss', 's.id', '=', 'ss.supplier_id')
                ->join('supplies AS su', 'ss.supply_id', '=', 'su.id')
                ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
                ->select('sc.name')
                ->where('s.id', $supplier->id)
                ->distinct()
                ->get()
                ->toArray();

            $supplier->categories = $supplies_categories;
        }

        return Pdf::loadView('report_suppliers.pdf', ['suppliers' => $suppliers])->setPaper('a4','landscape')->download('Reporte_Proveedores_' . date('d-m-Y') . '.pdf');
    }

    public function export() 
    {
        return Excel::download(new SuppliersExport, 'Reporte_Proveedores_' . date('d-m-Y') . '.xlsx');
    }
}
