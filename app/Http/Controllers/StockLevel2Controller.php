<?php

namespace App\Http\Controllers;

use App\Models\StockLevel2;

class StockLevel2Controller extends Controller
{
    public function index()
    {
        $stock_level_2 = StockLevel2::orderBy('created_at', 'desc')->get();
        return view('stock_level_2.index', compact('stock_level_2'));
    }

    public function detail(StockLevel2 $stock_level_2)
    {
        $details = $stock_level_2->stock_level_2_details->sortByDesc('created_at');
        return view('stock_level_2.detail', compact('stock_level_2', 'details'));
    }
}
