<?php

namespace App\Http\Controllers;

use App\Models\StockLevel1;

class StockLevel1Controller extends Controller
{
    public function index()
    {
        $stock_level_1 = StockLevel1::orderBy('created_at', 'desc')->get();
        return view('stock_level_1.index', compact('stock_level_1'));
    }

    public function detail(StockLevel1 $stock_level_1)
    {
        $details = $stock_level_1->stock_level_1_details->sortByDesc('created_at');
        return view('stock_level_1.detail', compact('stock_level_1', 'details'));
    }
}
