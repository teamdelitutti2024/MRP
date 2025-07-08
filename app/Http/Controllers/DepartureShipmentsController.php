<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartureShipment;
use App\Models\DepartureShipmentDetail;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockLevel1;
use App\Models\StockLevel1Detail;

class DepartureShipmentsController extends Controller
{
    public function index()
    {
        $departure_shipments = DepartureShipment::orderBy('created_at', 'desc')->get();
        return view('departure_shipments.index', compact('departure_shipments'));
    }

    public function add($id = null)
    {
        $order = Order::find($id);
        $products = Product::where('status', true)->orderBy('name', 'asc')->get();

        return view('departure_shipments.add', compact('order', 'products'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'total' => 'required',
            'order_id' => 'required',
            'shipment_date' => 'required',
            'details' => '',
        ]);

        $order = Order::find($validated_data['order_id']);

        $departure_shipment = DepartureShipment::create([
            'shipment_date' => $validated_data['shipment_date'],
            'total' => $validated_data['total'],
            'status' => 1,
            'order_id' => $order->id,
            'responsible_id' => auth()->user()->id,
            'branch_id' => $order->branch_id,
        ]);

        $departure_shipment_details = array();

        for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
            $product_size = ProductSize::find($validated_data['details']['product_size_id'][$i]);

            $departure_shipment_details[] = array(
                'product_name' => $product_size->product->name,
                'product_size_name' => $product_size->name,
                'quantity' => $validated_data['details']['quantity'][$i],
                'requested_quantity' => $validated_data['details']['requested_quantity'][$i],
                'price' => $product_size->price,
                'product_id' => $validated_data['details']['product_id'][$i],
                'departure_shipment_id' => $departure_shipment->id,
                'product_size_id' => $product_size->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DepartureShipmentDetail::insert($departure_shipment_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Embarque de salida agregado',
            'type' => 'success',
        ]);

        return redirect('/departure_shipments');
    }

    public function edit(DepartureShipment $departure_shipment)
    {
        $products = Product::where('status', true)->orderBy('name', 'asc')->get();
        return view('departure_shipments.edit', compact('departure_shipment', 'products'));
    }

    public function update(Request $request, DepartureShipment $departure_shipment)
    {
        $validated_data = $request->validate([
            'total' => 'required',
            'shipment_date' => 'required',
            'details' => '',
        ]);

        $departure_shipment->shipment_date = $validated_data['shipment_date'];
        $departure_shipment->total = $validated_data['total'];
        $departure_shipment->responsible_id = auth()->user()->id;
        $departure_shipment->save();

        // Delete old departure shipment details
        DepartureShipmentDetail::where('departure_shipment_id', $departure_shipment->id)->delete();

        $departure_shipment_details = array();

        for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
            $product_size = ProductSize::find($validated_data['details']['product_size_id'][$i]);

            $departure_shipment_details[] = array(
                'product_name' => $product_size->product->name,
                'product_size_name' => $product_size->name,
                'quantity' => $validated_data['details']['quantity'][$i],
                'requested_quantity' => $validated_data['details']['requested_quantity'][$i],
                'price' => $product_size->price,
                'product_id' => $validated_data['details']['product_id'][$i],
                'departure_shipment_id' => $departure_shipment->id,
                'product_size_id' => $product_size->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DepartureShipmentDetail::insert($departure_shipment_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Embarque de salida actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function finish(DepartureShipment $departure_shipment)
    {
        // Change status of departure shipment
        $departure_shipment->status = 2;
        $departure_shipment->responsible_id = auth()->user()->id;
        $departure_shipment->save();

        foreach($departure_shipment->departure_shipment_details as $element) {
            // Get related stock level 1
            $stock_level_1 = StockLevel1::where('product_size_id', $element->product_size_id)->first();

            // If stock level 1 exist then update quantity
            if($stock_level_1) {
                // Save last quantity
                $last_quantity = $stock_level_1->quantity;

                $stock_level_1->quantity = $stock_level_1->quantity - $element->quantity;
                $stock_level_1->save();
            }
            else {
                // Set last quantity to 0
                $last_quantity = 0;

                // Insert stock level 1 row
                $stock_level_1 = StockLevel1::create([
                    'quantity' => $element->quantity * -1,
                    'product_id' => $element->product_id,
                    'product_size_id' => $element->product_size_id,
                ]);
            }

            // Insert stock level 1 detail row
            StockLevel1Detail::create([
                'quantity' => $element->quantity * -1,
                'last_quantity' => $last_quantity,
                'reason' => 'TOMADO PARA EMBARQUE',
                'stock_level_1_id' => $stock_level_1->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Embarque de salida finalizado',
            'type' => 'success',
        ]);

        return redirect('/departure_shipments');
    }

    public function detail(DepartureShipment $departure_shipment)
    {
        return view('departure_shipments.detail', compact('departure_shipment'));
    }

    public function get_inbound_shipments(DepartureShipment $departure_shipment)
    {
        return view('departure_shipments.inbound_shipments', compact('departure_shipment'));
    }
}
