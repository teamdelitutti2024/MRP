<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeclinedProduct;
use App\Models\ProductSize;
use App\Models\StockLevel1;
use App\Models\StockLevel1Detail;

class DeclinedProductsController extends Controller
{
    public function index()
    {
        $declined_products = DeclinedProduct::orderBy('created_at', 'desc')->get();
        return view('declined_products.index', compact('declined_products'));
    }

    public function add()
    {
        $product_sizes = ProductSize::orderBy('name', 'asc')->get();
        return view('declined_products.add', compact('product_sizes'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'details' => '',
        ]);

        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
                $product_size = ProductSize::find($validated_data['details']['product_size_id'][$i]);

                // Get related stock level 1
                $stock_level_1 = StockLevel1::where('product_size_id', $product_size->id)->first();

                // If stock level 1 exist then update quantity
                if($stock_level_1) {
                    // Save last quantity
                    $last_quantity = $stock_level_1->quantity;

                    $stock_level_1->quantity = $stock_level_1->quantity - $validated_data['details']['quantity'][$i];
                    $stock_level_1->save();
                }
                else {
                    // Set last quantity to 0
                    $last_quantity = 0;

                    // Insert stock level 1 row
                    $stock_level_1 = StockLevel1::create([
                        'quantity' => $validated_data['details']['quantity'][$i] * -1,
                        'product_id' => $product_size->product_id,
                        'product_size_id' => $product_size->id,
                    ]);
                }

                // Insert stock level 1 detail row
                StockLevel1Detail::create([
                    'quantity' => $validated_data['details']['quantity'][$i] * -1,
                    'last_quantity' => $last_quantity,
                    'reason' => 'MERMA',
                    'stock_level_1_id' => $stock_level_1->id,
                    'responsible_id' => auth()->user()->id,
                ]);

                DeclinedProduct::create([
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'product_date' => $validated_data['details']['product_date'][$i],
                    'product_name' => $product_size->product->name,
                    'product_size_name' => $product_size->name,
                    'price' => $product_size->price,
                    'comments' => $validated_data['details']['comment'][$i],
                    'product_id' => $product_size->product_id,
                    'product_size_id' => $product_size->id,
                    'responsible_id' => auth()->user()->id,
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de productos agregada',
            'type' => 'success',
        ]);

        return redirect('/declined_products');
    }

    public function detail(DeclinedProduct $declined_product)
    {
        return view('declined_products.detail', compact('declined_product'));
    }

    public function revert(DeclinedProduct $declined_product)
    {
        // Get related stock level 1
        $stock_level_1 = StockLevel1::where('product_size_id', $declined_product->product_size_id)->first();

        // Save last quantity
        $last_quantity = $stock_level_1->quantity;

        $stock_level_1->quantity = $stock_level_1->quantity + $declined_product->quantity;
        $stock_level_1->save();

        // Insert stock level 1 detail row
        StockLevel1Detail::create([
            'quantity' => $declined_product->quantity,
            'last_quantity' => $last_quantity,
            'reason' => 'MERMA REVERTIDA',
            'stock_level_1_id' => $stock_level_1->id,
            'responsible_id' => auth()->user()->id,
        ]);

        $declined_product->status = false;
        $declined_product->reversed_responsible_id = auth()->user()->id;
        $declined_product->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de producto revertida',
            'type' => 'success',
        ]);

        return redirect('/declined_products');
    }
}
