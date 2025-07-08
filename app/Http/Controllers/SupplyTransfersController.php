<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyTransfer;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\Stock;
use App\ChangesLogHelper;

class SupplyTransfersController extends Controller
{
    public function index()
    {
        $supply_transfers = SupplyTransfer::orderBy('created_at', 'desc')->get();
        return view('supply_transfers.index', compact('supply_transfers'));
    }

    public function add() {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('supply_transfers.add', compact('supplies', 'supply_locations'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'supply_transfers' => '',
        ]);

        $transfers_errors = array();
        $transfers_success = array();

        // Create new ingredients if exist
        if(isset($validated_data['supply_transfers'])) {
            for($i = 0; $i < count($validated_data['supply_transfers']['supply_id']); $i++) {
                $transferred_quantity = $validated_data['supply_transfers']['transferred_quantity'][$i];

                // Check if source location and destination location are the same
                if($validated_data['supply_transfers']['source_location_id'][$i] == $validated_data['supply_transfers']['destination_location_id'][$i]) {
                    $transfers_errors[] = 'La ubicación de origen y la ubicación de destino no pueden ser iguales en el elemento ' . ($i + 1) . ' de la lista';
                    continue;
                }

                if($transferred_quantity > 0) {
                    // Get source stock
                    $source_stock_element = Stock::where('supply_id', $validated_data['supply_transfers']['supply_id'][$i])->where('supply_location_id', $validated_data['supply_transfers']['source_location_id'][$i])->first();

                    // Check if input quantity is greater than stock quantity for this supply id and supply location
                    if($transferred_quantity > $source_stock_element->quantity) {
                        $transfers_errors[] = 'La cantidad a transferir no puede ser mayor a la cantidad disponible en stock para esta materia prima y ubicación en el elemento ' . ($i + 1) . ' de la lista';
                        continue;
                    }

                    // New quantity for source stock
                    $source_new_quantity = $source_stock_element->quantity - $transferred_quantity;

                    // Add log
                    $data = array(
                        'element_name' => $source_stock_element->supply->name . ' - ' . $source_stock_element->supply_location->location,
                        'element_key' => $source_stock_element->supply->supply_key,
                        'previous_quantity' => $source_stock_element->quantity,
                        'new_quantity' => $source_new_quantity,
                        'model' => 'Stock',
                        'event' => 'ACTUALIZACIÓN DESDE TRANSFERENCIAS DE MATERIA PRIMA',
                        'responsible_id' => auth()->user()->id,
                    );

                    ChangesLogHelper::add($data);

                    // Decrease the stock quantity for this supply and source supply location
                    $source_stock_element->quantity = $source_new_quantity;
                    $source_stock_element->save();

                    $supply = Supply::find($validated_data['supply_transfers']['supply_id'][$i]);

                    $cost = $supply->average_cost == 0 ? $supply->initial_cost : $supply->average_cost;

                    SupplyTransfer::create([
                        'supply' => $supply->name,
                        'transferred_quantity' => $validated_data['supply_transfers']['transferred_quantity'][$i],
                        'transaction_amount' => $cost * $transferred_quantity,
                        'comment' => $validated_data['supply_transfers']['comment'][$i],
                        'supply_id' =>$supply->id,
                        'measurement_unit_id' => $supply->measurement_unit_id,
                        'source_location_id' => $validated_data['supply_transfers']['source_location_id'][$i],
                        'destination_location_id' => $validated_data['supply_transfers']['destination_location_id'][$i],
                        'responsible_id' => auth()->user()->id,
                    ]);

                    // Get destination stock
                    $destination_stock_element = Stock::where('supply_id', $validated_data['supply_transfers']['supply_id'][$i])->where('supply_location_id', $validated_data['supply_transfers']['destination_location_id'][$i])->first();

                    // New quantity for destination stock
                    $destination_source_quantity = $destination_stock_element->quantity + $transferred_quantity;

                    // Add log
                    $data = array(
                        'element_name' => $destination_stock_element->supply->name . ' - ' . $destination_stock_element->supply_location->location,
                        'element_key' => $destination_stock_element->supply->supply_key,
                        'previous_quantity' => $destination_stock_element->quantity,
                        'new_quantity' => $destination_source_quantity,
                        'model' => 'Stock',
                        'event' => 'ACTUALIZACIÓN DESDE TRANSFERENCIAS DE MATERIA PRIMA',
                        'responsible_id' => auth()->user()->id,
                    );

                    ChangesLogHelper::add($data);

                    // Increase the stock quantity for this supply and destination supply location
                    $destination_stock_element->quantity = $destination_source_quantity;
                    $destination_stock_element->save();

                    $transfers_success[] = 'Transferencia de materia prima agregada para el elemento ' . ($i + 1) . ' de la lista';
                }
                else {
                    $transfers_errors[] = 'La cantidad a transferir no puede ser menor que 0 en el elemento ' . ($i + 1) . ' de la lista';
                }
            }
        }

        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('supply_transfers.add', compact('supplies', 'supply_locations', 'transfers_errors', 'transfers_success'));
    }

    public function detail(SupplyTransfer $supply_transfer)
    {
        return view('supply_transfers.detail', compact('supply_transfer'));
    }
}
