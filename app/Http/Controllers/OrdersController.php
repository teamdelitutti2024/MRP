<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Ingredient;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductSize;
use App\Exports\ProjectionExport;
use App\SuppliesHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created', 'desc')->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function download(Order $order)
    {
        $order_data = array();

        $order_data['order'] = array(
            'id' => $order->id,
            'total' => $order->total,
            'delivery_date' => date('d-m-Y H:i:s', strtotime($order->delivery_date)),
            'branch' => $order->branch->name,
            'status' => $order->status,
            'payment' => $order->payment,
            'payment_method' => $order->payment_method,
            'payment_date' => date('d-m-Y H:i:s', strtotime($order->payment_date)),
            'created_at' => date('d-m-Y H:i:s', strtotime($order->created)),
        );

        foreach($order->order_products as $element) {
            $order_data['products'][] = array(
                'product_key' => $element->product_size->product_size_key,
                'product' => $element->product->name,
                'size' => $element->product_size->name,
                'price' => $element->price,
                'quantity' => $element->quantity,
                'total_price' => $element->total_price,
            );
        }

        return PDF::loadView('orders.pdf', $order_data)->download('pedido_' . $order->id . '.pdf');
    }

    public function detail(Order $order)
    {
        return view('orders.detail', compact('order'));
    }

    public function projection(Order $order)
    {
        $products = array();

        foreach($order->order_products as $product) {
            $products[] = array(
                'id' => $product->product_size_id,
                'quantity' => $product->quantity,
            );
        }

        $supplies = SuppliesHelper::calculate_products_supplies($products);

        return view('orders.projection', compact('order', 'supplies'));
    }

    public function download_projection(Order $order)
    {
        $products = array();

        foreach($order->order_products as $product) {
            $products[] = array(
                'id' => $product->product_size_id,
                'quantity' => $product->quantity,
            );
        }

        return Excel::download(new ProjectionExport($products, 1), 'proyección_' . date('Y-m-d') . '.xlsx');
    }

    public function fill_ingredients($order_id = null)
    {
        $order = Order::find($order_id);

        foreach ($order->order_products as $element) {
            // Insert ingredients
            Ingredient::create([
                'quantity' => rand(1, 10),
                'supply_id' => 1,
                'product_size_id' => $element->product_size_id,
                'measurement_unit_id' => rand(1, 4),
            ]);

            Ingredient::create([
                'quantity' => rand(1, 10),
                'supply_id' => 2,
                'product_size_id' => $element->product_size_id,
                'measurement_unit_id' => rand(1, 4),
            ]);

            Ingredient::create([
                'quantity' => rand(1, 10),
                'supply_id' => 50,
                'product_size_id' => $element->product_size_id,
                'measurement_unit_id' => rand(1, 4),
            ]);

            Ingredient::create([
                'quantity' => rand(1, 10),
                'supply_id' => 51,
                'product_size_id' => $element->product_size_id,
                'measurement_unit_id' => rand(1, 4),
            ]);
        }
    }

    public function add()
    {
        $branches = Branch::orderBy('name', 'asc')->get();
        $products = Product::where('status', true)->orderBy('name', 'asc')->get();
        return view('orders.add', compact('branches', 'products'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'total' => 'required',
            'branch_id' => 'required',
            'delivery_date' => 'required',
            'details' => '',
        ]);

        // Create order
        $order = Order::create([
            'total' => $validated_data['total'],
            'delivery_date' => $validated_data['delivery_date'],
            'status' => 1,
            'branch_id' => $validated_data['branch_id'],
        ]);

        //  Create order products records
        for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
            $product_size = ProductSize::find($validated_data['details']['product_size_id'][$i]);

            $order_products[] = array(
                'quantity' => $validated_data['details']['quantity'][$i],
                'price' => $product_size->price,
                'total_price' => $validated_data['details']['quantity'][$i] * $product_size->price,
                'product_size_id' => $validated_data['details']['product_size_id'][$i],
                'order_id' => $order->id,
                'product_id' => $validated_data['details']['product_id'][$i],
                'quantity_sold' => 0,
                'code' => '',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            );
        }

        OrderProduct::insert($order_products);

        request()->session()->flash('alertmessage', [
            'message' => 'Pedido agregado',
            'type' => 'success',
        ]);

        return redirect('/orders');
    }
}
