<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarantine;
use App\Models\DeclinedSupply;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\Stock;
use App\ChangesLogHelper;

class QuarantinesController extends Controller
{
    public function index()
    {
        $quarantines = Quarantine::orderBy('created_at', 'desc')->get();
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('quarantines.index', compact('quarantines', 'supplies', 'supply_locations'));
    }

    public function store(Request $request, $id = null)
    {
        $validated_data = $request->validate([
            'supply_id' => 'required',
            'quantity' => 'required|numeric',
            'supply_location_id' => 'required',
            'category' => 'required',
            'reason' => 'required',
        ]);

        $quantity = $validated_data['quantity'];

        // Get related quantity
        $supply = Supply::find($validated_data['supply_id']);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $validated_data['supply_id'])->where('supply_location_id', $validated_data['supply_location_id'])->first();

        if($quantity > 0) {
            // Check if input quantity is greater than available quantity in stock
            $comp_result = bccomp($supply_stock->quantity, $quantity, 7);

            if($comp_result == 0 || $comp_result == 1) {
                $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

                Quarantine::create([
                    'supply' => $supply->name,
                    'quantity' => $quantity,
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
                    'event' => 'ACTUALIZACIÓN DESDE CUARENTENAS',
                    'responsible_id' => auth()->user()->id,
                );

                ChangesLogHelper::add($data);

                // Decrease stock quantity
                $supply_stock->quantity = $new_quantity;
                $supply_stock->save();

                request()->session()->flash('alertmessage', [
                    'message' => 'Cuarentena agregada',
                    'type' => 'success',
                ]);
        
                return back();
            }

            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de cuarentena no puede ser mayor a la cantidad disponible en stock para esta materia prima y ubicación',
                'type' => 'error',
            ]);
    
            return back();
        }
        else {
            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de cuarentena no puede ser menor que 0',
                'type' => 'error',
            ]);
    
            return back();
        }
    }

    public function get_row()
    {
        $quarantine = Quarantine::find(request('id'));
        return response()->json($quarantine);
    }

    public function update(Request $request, Quarantine $quarantine)
    {
        $validated_data = $request->validate([
            'quantity' => 'required|numeric',
            'category' => 'required',
            'reason' => 'required',
        ]);

        $quantity = $validated_data['quantity'];

        // Get related quantity
        $supply = Supply::find($quarantine->supply_id);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $quarantine->supply_id)->where('supply_location_id', $quarantine->supply_location_id)->first();

        // Calculate available quantity without quarantine
        $available_quantity = $supply_stock->quantity + $quarantine->quantity;

        if($quantity > 0) {
            // Check if input quantity is greater than remaining quantity in supply reception detail
            $comp_result = bccomp($available_quantity, $quantity, 7);

            if($comp_result == 0 || $comp_result == 1) {
                $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

                $quarantine->quantity = $quantity;
                $quarantine->transaction_amount = $cost * $quantity;
                $quarantine->category = $validated_data['category'];
                $quarantine->reason = $validated_data['reason'];
                $quarantine->save();

                // New quantity for stock
                $new_quantity = $available_quantity - $quantity;

                // Add log
                $data = array(
                    'element_name' => $supply->name . ' - ' . $supply_stock->supply_location->location,
                    'element_key' => $supply->supply_key,
                    'previous_quantity' => $available_quantity,
                    'new_quantity' => $new_quantity,
                    'model' => 'Stock',
                    'event' => 'ACTUALIZACIÓN DESDE CUARENTENAS',
                    'responsible_id' => auth()->user()->id,
                );

                ChangesLogHelper::add($data);

                // Update stock quantity
                $supply_stock->quantity = $new_quantity;
                $supply_stock->save();

                request()->session()->flash('alertmessage', [
                    'message' => 'Cuarentena actualizada',
                    'type' => 'success',
                ]);
        
                return back();
            }

            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de cuarentena no puede ser mayor a la cantidad disponible en stock para esta materia prima y ubicación',
                'type' => 'error',
            ]);
    
            return back();
        }
        else {
            request()->session()->flash('alertmessage', [
                'message' => 'La cantidad de cuarentena no puede ser menor que 0',
                'type' => 'error',
            ]);
    
            return back();
        }
    }

    public function detail(Quarantine $quarantine)
    {
        return view('quarantines.detail', compact('quarantine'));
    }

    public function change_status($id = null, $status = null)
    {
        $quarantine = Quarantine::find($id);

        // Get related stock
        $supply_stock = Stock::where('supply_id', $quarantine->supply_id)->where('supply_location_id', $quarantine->supply_location_id)->first();

        $message = '';
        
        if($status == 'active') {
            $message = 'activada';
            $quarantine->enabled_responsible_id = auth()->user()->id;

            // Decrease stock quantity
            $supply_stock->quantity = $supply_stock->quantity - $quarantine->quantity;
        }
        else {
            $message = 'desactivada';
            $quarantine->disabled_date = date('Y-m-d H:i:s');
            $quarantine->disabled_responsible_id = auth()->user()->id;

            // Increase stock quantity
            $supply_stock->quantity = $supply_stock->quantity + $quarantine->quantity;
        }

        $supply_stock->save();

        $quarantine->status = $status;
        $quarantine->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Cuarentena ' . $message,
            'type' => 'success',
        ]);

        return back();
    }

    public function change_to_declined(Request $request, Quarantine $quarantine)
    {
        $validated_data = $request->validate([
            'category' => 'required',
            'reason' => 'required',
        ]);

        $quarantine->change_to_decreased_date = date('Y-m-d H:i:s');
        $quarantine->change_to_decreased_responsible_id = auth()->user()->id;
        $quarantine->status = 'to_declined';
        $quarantine->save();

        $quantity = $quarantine->quantity;

        // Get related supply
        $supply = Supply::find($quarantine->supply_id);

        $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

        // Process to register a declined supply for this quarantine
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
            'quarantine_id' => $quarantine->id,
            'supply_location_id' => $quarantine->supply_location_id,
        ]);

        request()->session()->flash('alertmessage', [
            'message' => 'Cuarentena cambiada a merma',
            'type' => 'success',
        ]);

        return back();
    }
}
