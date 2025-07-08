<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyLocation;
use App\Models\Supply;
use App\Models\Stock;

class SupplyLocationsController extends Controller
{
    public function index()
    {
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();
        return view('supply_locations.index', compact('supply_locations'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'location' => 'required',
        ]);

        $supply_location = $this->store_callback($validated_data['location']);

        if($supply_location) {
            request()->session()->flash('alertmessage', [
                'message' => 'La ubicación ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supply_location = SupplyLocation::create($validated_data);

        $supplies = Supply::all();

        $stock_data = array();

        foreach($supplies as $supply) {
            $stock_data[] = array(
                'supply_id' => $supply->id,
                'supply_location_id' => $supply_location->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        Stock::insert($stock_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Ubicación agregada',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supply_location = SupplyLocation::find(request('id'));
        return response()->json($supply_location);
    }

    public function update(Request $request, SupplyLocation $supply_location)
    {
        $validated_data = $request->validate([
            'location' => 'required',
        ]);

        $existing_supply_location = $this->update_callback($supply_location->id, $validated_data['location']);

        if($existing_supply_location) {
            request()->session()->flash('alertmessage', [
                'message' => 'La ubicación ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supply_location->location = $validated_data['location'];
        $supply_location->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Ubicación actualizada',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($location)
    {
        // Search supply location by location
        $supply_location = SupplyLocation::where('location', $location)->first();
        return $supply_location;
    }

    private function update_callback($id, $location)
    {
        // Search supply location by location different from id
        $supply_location = SupplyLocation::where('id', '<>', $id)->where('location', $location)->first();
        return $supply_location;
    }
}
