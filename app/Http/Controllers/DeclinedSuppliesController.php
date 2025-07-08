<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeclinedSupply;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\Stock;
use App\ChangesLogHelper;

class DeclinedSuppliesController extends Controller
{
    public function index()
    {
        $declined_supplies = DeclinedSupply::orderBy('created_at', 'desc')->get();
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('declined_supplies.index', compact('declined_supplies', 'supplies', 'supply_locations'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'supply_id' => 'required',
            'lost_quantity' => 'required|numeric',
            'supply_location_id' => 'required',
            'category' => 'required',
            'reason' => 'required',
        ]);

        $quantity = $validated_data['lost_quantity'];

        // Get related quantity
        $supply = Supply::find($validated_data['supply_id']);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $validated_data['supply_id'])->where('supply_location_id', $validated_data['supply_location_id'])->first();

        if($quantity > 0) {
            // Check if input quantity is greater than available quantity in stock
            $comp_result = bccomp($supply_stock->quantity, $quantity, 7);

            if($comp_result == 0 || $comp_result == 1) {
                $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

                DeclinedSupply::create([
                    'supply' => $supply->name,
                    'lost_quantity' => $quantity,
                    'transaction_amount' => $cost * $quantity,
                    'category' => $validated_data['category'],
                    'reason' => $validated_data['reason'],
                    'status' => 'active',
                    'supply_id' => $supply->id,
                    'measurement_unit_id' => $supply->measurement_unit_id,
                    'enabled_responsible_id' => auth()->user()->id,
                    'supply_location_id' => $validated_data['supply_location_id'],
                ]);

                // New quantity for stock
                $new_quantity = $supply_stock->quantity - $quantity;

                // Add log
                $data = array(
                    'element_name' => $supply->name . ' - ' . $supply_stock->supply_location->location,
                    'element_key' => $supply->supply_key,
                    'previous_quantity' => $supply_stock->quantity,
                    'new_quantity' => $new_quantity,
                    'model' => 'Stock',
                    'event' => 'ACTUALIZACIÓN DESDE MERMAS',
                    'responsible_id' => auth()->user()->id,
                );

                ChangesLogHelper::add($data);

                // Decrease stock quantity
                $supply_stock->quantity = $new_quantity;
                $supply_stock->save();

                request()->session()->flash('alertmessage', [
                    'message' => 'Merma de materia prima agregada',
                    'type' => 'success',
                ]);
        
                return back();
            }

            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de merma no puede ser mayor a la cantidad disponible en stock para esta materia prima y ubicación',
                'type' => 'error',
            ]);
    
            return back();
        }
        else {
            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de merma no puede ser menor que 0',
                'type' => 'error',
            ]);
    
            return back();
        }
    }

    public function get_row()
    {
        $declined_supply = DeclinedSupply::find(request('id'));
        return response()->json($declined_supply);
    }

    public function update(Request $request, DeclinedSupply $declined_supply)
    {
        $validated_data = $request->validate([
            'lost_quantity' => 'required|numeric',
            'category' => 'required',
            'reason' => 'required',
        ]);

        $quantity = $validated_data['lost_quantity'];

        // Get related quantity
        $supply = Supply::find($declined_supply->supply_id);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $declined_supply->supply_id)->where('supply_location_id', $declined_supply->supply_location_id)->first();

        // Calculate available quantity without declined supply
        $available_quantity = $supply_stock->quantity + $declined_supply->lost_quantity;

        if($quantity > 0) {
            // Check if input quantity is greater than available quantity
            $comp_result = bccomp($available_quantity, $quantity, 7);

            if($comp_result == 0 || $comp_result == 1) {
                $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

                $declined_supply->lost_quantity = $quantity;
                $declined_supply->transaction_amount = $cost * $quantity;
                $declined_supply->category = $validated_data['category'];
                $declined_supply->reason = $validated_data['reason'];
                $declined_supply->save();

                // New quantity for stock
                $new_quantity = $available_quantity - $quantity;

                // Add log
                $data = array(
                    'element_name' => $supply->name . ' - ' . $supply_stock->supply_location->location,
                    'element_key' => $supply->supply_key,
                    'previous_quantity' => $available_quantity,
                    'new_quantity' => $new_quantity,
                    'model' => 'Stock',
                    'event' => 'ACTUALIZACIÓN DESDE MERMAS',
                    'responsible_id' => auth()->user()->id,
                );

                ChangesLogHelper::add($data);

                // Update stock quantity
                $supply_stock->quantity = $new_quantity;
                $supply_stock->save();

                request()->session()->flash('alertmessage', [
                    'message' => 'Merma de materia prima actualizada',
                    'type' => 'success',
                ]);
        
                return back();
            }

            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de merma no puede ser mayor a la cantidad disponible en stock para esta materia prima y ubicación',
                'type' => 'error',
            ]);
    
            return back();
        }
        else {
            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de merma no puede ser menor que 0',
                'type' => 'error',
            ]);
    
            return back();
        }
    }

    public function detail(DeclinedSupply $declined_supply)
    {
        return view('declined_supplies.detail', compact('declined_supply'));
    }

    public function change_status($id = null, $status = null)
    {
        $declined_supply = DeclinedSupply::find($id);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $declined_supply->supply_id)->where('supply_location_id', $declined_supply->supply_location_id)->first();

        $message = '';
        
        if($status == 'active') {
            $message = 'activada';
            $declined_supply->enabled_responsible_id = auth()->user()->id;

            // Decrease stock quantity
            $supply_stock->quantity = $supply_stock->quantity - $declined_supply->lost_quantity;
        }
        else {
            $message = 'desactivada';
            $declined_supply->disabled_date = date('Y-m-d H:i:s');
            $declined_supply->disabled_responsible_id = auth()->user()->id;

            // Increase stock quantity
            $supply_stock->quantity = $supply_stock->quantity + $declined_supply->lost_quantity;
        }

        $supply_stock->save();

        $declined_supply->status = $status;
        $declined_supply->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Merma ' . $message,
            'type' => 'success',
        ]);

        return back();
    }

    public function revert(DeclinedSupply $declined_supply)
    {
        // Get related stock
        $supply_stock = Stock::where('supply_id', $declined_supply->supply_id)->where('supply_location_id', $declined_supply->supply_location_id)->first();

        // Increase stock quantity
        $supply_stock->quantity = $supply_stock->quantity + $declined_supply->lost_quantity;
        $supply_stock->save();

        // Update declined supply status
        $declined_supply->status = 'reversed';
        $declined_supply->reversed_responsible_id = auth()->user()->id;
        $declined_supply->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Merma revertida',
            'type' => 'success',
        ]);

        return back();
    }
}
