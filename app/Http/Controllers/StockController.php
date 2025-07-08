<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supply;
use App\Models\Stock;
use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class StockController extends Controller
{
    public function general()
    {
        $general_stock = array();
        $supply_key = '';

        $query = Stock::where(DB::raw('1'), 1);

        if(isset($_GET['supply_key'])) {
            $supply_key = $_GET['supply_key'];

            $supply = Supply::where('supply_key', $supply_key)->first();

            if(!$supply) {
                return view('stock.general', compact('general_stock', 'supply_key'));
            }

            $query = $query->where('supply_id', $supply->id);
        }

        $general_stock = $query->orderBy('supply_location_id')->orderBy('supply_id')->paginate(15);

        return view('stock.general', compact('general_stock', 'supply_key'));
    }

    public function upload_stock()
    {
        return view('stock.upload');
    }

    public function update_stock(Request $request)
    {
        $request->validate([
            'stock' => 'required|file|mimes:xlsx,xls',
        ]);

        // Get headings
        $headings = (new HeadingRowImport)->toArray($request->file('stock'));

        // Validate headings
        if(!in_array('supply_key', $headings[0][0]) || !in_array('location', $headings[0][0]) || !in_array('quantity', $headings[0][0])) {
            request()->session()->flash('alertmessage', [
                'message' => 'El archivo no tiene el formato correcto',
                'type' => 'error',
            ]);
    
            return back();
        }

        $updated_stock = new StockImport();

        Excel::import($updated_stock, $request->file('stock'));

        $success_count = $updated_stock->get_success_count();
        $errors_result = $updated_stock->get_errors();

        return view('stock.upload', compact('success_count', 'errors_result'));
    }
}
