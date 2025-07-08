<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSize;
use App\Models\BakeBreadSize;
use App\Models\PreparedProduct;
use App\SuppliesHelper;
use App\Exports\ProjectionExport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectionController extends Controller
{
    public function index()
    {
        $product_sizes = ProductSize::orderBy('name', 'asc')->get();
        return view('projection.index', compact('product_sizes'));
    }

    public function bake_breads()
    {
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        return view('projection.bake_breads', compact('bake_bread_sizes'));
    }

    public function prepared()
    {
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        return view('projection.prepared', compact('prepared_products'));
    }

    public function calculate(Request $request, $type = null)
    {
        $data = $request->all();

        $products = $projection = array();

        for($i = 0; $i < count($data['details']['id']); $i++) {
            $products[] = array(
                'id' => $data['details']['id'][$i],
                'quantity' => $data['details']['quantity'][$i],
            );
        }

        switch($type) {
            case 2:
                $projection = SuppliesHelper::calculate_bake_breads_supplies_for_projection($products);
                break;

            case 3:
                $projection = SuppliesHelper::calculate_prepared_products_supplies($products);
                break;
            
            default:
                $projection = SuppliesHelper::calculate_products_supplies($products);
                break;
        }

        return response()->json($projection);
    }

    public function download(Request $request, $type = null)
    {
        $data = $request->all();

        $products = array();

        for($i = 0; $i < count($data['details']['id']); $i++) { 
            $products[] = array(
                'id' => $data['details']['id'][$i],
                'quantity' => $data['details']['quantity'][$i],
            );
        }

        return Excel::download(new ProjectionExport($products, $type), 'proyección_' . date('Y-m-d') . '.xlsx');
    }
}
