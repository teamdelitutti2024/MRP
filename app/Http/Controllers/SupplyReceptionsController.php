<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyReception;
use App\Models\SupplyReceptionDetail;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderDetail;
use App\Models\SupplyLocation;
use App\Models\Stock;
use App\ChangesLogHelper;

class SupplyReceptionsController extends Controller
{
    public function index()
    {
        $supply_receptions = SupplyReception::orderBy('created_at', 'desc')->get();
        return view('supply_receptions.index', compact('supply_receptions'));
    }

    public function add($id = null)
    {
        $supply_order = SupplyOrder::find($id);
        return view('supply_receptions.add', compact('supply_order'));
    }

    public function store(Request $request)
    { 
        $validated_data = $request->validate([
            'total' => 'required',
            'supply_order_id' => 'required',
            'code' => 'required',
            'details' => '',
        ]);

        // Create supply reception
        $supply_reception = SupplyReception::create([
            'code' => $validated_data['code'],
            'total' => $validated_data['total'],
            'supply_order_id' => $validated_data['supply_order_id'],
            'responsible_id' => auth()->user()->id,
        ]);

        // Create supply receptions details
        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) { 
                $supply_reception_detail = SupplyReceptionDetail::create([
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'supply_reception_id' => $supply_reception->id,
                    'supply_order_detail_id' => $validated_data['details']['supply_order_detail_id'][$i],
                ]);

                // Get related stock
                $stock_element = Stock::where('supply_id', $validated_data['details']['supply_id'][$i])->where('supply_location_id', 1)->first();

                // New quantity for stock
                $new_quantity = $stock_element->quantity + ($validated_data['details']['quantity'][$i] * $stock_element->supply->standard_pack);

                // Add log
                $data = array(
                    'element_name' => $stock_element->supply->name . ' - ' . $stock_element->supply_location->location,
                    'element_key' => $stock_element->supply->supply_key,
                    'previous_quantity' => $stock_element->quantity,
                    'new_quantity' => $new_quantity,
                    'model' => 'Stock',
                    'event' => 'ACTUALIZACIÓN DESDE RECEPCIONES DE MATERIA PRIMA',
                    'responsible_id' => auth()->user()->id,
                );

                ChangesLogHelper::add($data);

                // Increase the stock quantity for this supply and supply location
                $stock_element->quantity = $new_quantity;
                $stock_element->save();

                // Increase the received quantity for the supply
                $supply_reception_detail->supply_order_detail->received_quantity = $supply_reception_detail->supply_order_detail->received_quantity + $supply_reception_detail->quantity;
                $supply_reception_detail->supply_order_detail->save();
            }
        }

        // Get the supply order details for the supply order
        $supply_order_details = SupplyOrderDetail::where('supply_order_id', $supply_reception->supply_order_id)->get();

        // Check if supply order is completed now
        $completed = true;

        foreach($supply_order_details as $element) {
            if($element->quantity > $element->received_quantity) {
                $completed = false;
                break;
            }
        }

        // If completed, change supply order status
        if($completed) {
            // Partially recibed
            $supply_reception->supply_order->status = 5;
        }
        else {
            // Recibed
            $supply_reception->supply_order->status = 4;
        }

        $supply_reception->supply_order->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Recepción de materia prima agregada',
            'type' => 'success',
        ]);

        return redirect('/supply_orders/receptions/' . $supply_reception->supply_order_id);
    }

    public function detail(SupplyReception $supply_reception)
    {
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        return view('supply_receptions.detail', compact('supply_reception', 'supply_locations'));
    }
}
