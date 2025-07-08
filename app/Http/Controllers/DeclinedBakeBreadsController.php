<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeclinedBakeBread;
use App\Models\BakeBreadSize;
use App\Models\StockLevel2;
use App\Models\StockLevel2Detail;

class DeclinedBakeBreadsController extends Controller
{
    public function index()
    {
        $declined_bake_breads = DeclinedBakeBread::orderBy('created_at', 'desc')->get();
        return view('declined_bake_breads.index', compact('declined_bake_breads'));
    }

    public function add()
    {
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        return view('declined_bake_breads.add', compact('bake_bread_sizes'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'details' => '',
        ]);

        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
                $bake_bread_size = BakeBreadSize::find($validated_data['details']['bake_bread_size_id'][$i]);

                // Get related stock level 2
                $stock_level_2 = StockLevel2::where('bake_bread_size_id', $bake_bread_size->id)->first();

                // If stock level 2 exist then update quantity
                if($stock_level_2) {
                    // Save last quantity
                    $last_quantity = $stock_level_2->quantity;

                    $stock_level_2->quantity = $stock_level_2->quantity - $validated_data['details']['quantity'][$i];
                    $stock_level_2->save();
                }
                else {
                    // Set last quantity to 0
                    $last_quantity = 0;

                    // Insert stock level 2 row
                    $stock_level_2 = StockLevel2::create([
                        'quantity' => $validated_data['details']['quantity'][$i] * -1,
                        'bake_bread_size_id' => $bake_bread_size->id,
                    ]);
                }

                // Insert stock level 2 detail row
                StockLevel2Detail::create([
                    'quantity' => $validated_data['details']['quantity'][$i] * -1,
                    'last_quantity' => $last_quantity,
                    'reason' => 'MERMA',
                    'stock_level_2_id' => $stock_level_2->id,
                    'responsible_id' => auth()->user()->id,
                ]);

                DeclinedBakeBread::create([
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'bake_bread_date' => $validated_data['details']['bake_bread_date'][$i],
                    'bake_bread_size_name' => $bake_bread_size->name,
                    'comments' => $validated_data['details']['comment'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'responsible_id' => auth()->user()->id,
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de bases agregada',
            'type' => 'success',
        ]);

        return redirect('/declined_bake_breads');
    }

    public function detail(DeclinedBakeBread $declined_bake_bread)
    {
        return view('declined_bake_breads.detail', compact('declined_bake_bread'));
    }

    public function revert(DeclinedBakeBread $declined_bake_bread)
    {
        // Get related stock level 2
        $stock_level_2 = StockLevel2::where('bake_bread_size_id', $declined_bake_bread->bake_bread_size_id)->first();

        // Save last quantity
        $last_quantity = $stock_level_2->quantity;

        $stock_level_2->quantity = $stock_level_2->quantity + $declined_bake_bread->quantity;
        $stock_level_2->save();

        // Insert stock level 2 detail row
        StockLevel2Detail::create([
            'quantity' => $declined_bake_bread->quantity,
            'last_quantity' => $last_quantity,
            'reason' => 'MERMA REVERTIDA',
            'stock_level_2_id' => $stock_level_2->id,
            'responsible_id' => auth()->user()->id,
        ]);

        $declined_bake_bread->status = false;
        $declined_bake_bread->reversed_responsible_id = auth()->user()->id;
        $declined_bake_bread->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de base revertida',
            'type' => 'success',
        ]);

        return redirect('/declined_bake_breads');
    }
}
