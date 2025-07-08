<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\MeasurementUnit;
use App\Models\SupplyLocation;
use App\Models\Stock;
use App\Models\SupplyCategory;

class SuppliesController extends Controller
{
    public function index()
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $measurement_units = MeasurementUnit::orderBy('measure', 'asc')->get();
        $supply_categories = SupplyCategory::orderBy('name', 'asc')->get();

        return view('supplies.index', compact('supplies', 'measurement_units', 'supply_categories'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'supply_key' => 'required',
            'initial_cost' => 'required|numeric',
            'min_stock' => 'required|numeric',
            'max_stock' => 'required|numeric',
            'reorder_stock' => 'required|numeric',
            'safety_stock' => 'required|numeric',
            'measurement_unit_id' => 'required',
            'supply_category_id' => 'required',
            'standard_pack' => 'required',
            'requires_iva' => 'required|boolean',
            'requires_ieps' => 'required|boolean',
        ]);

        $supply = $this->store_callback($validated_data['name']);

        if($supply) {
            request()->session()->flash('alertmessage', [
                'message' => 'La materia prima ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supply = Supply::create($validated_data);

        // Build new key to be updated
        $supply_key = 'MP-' . $validated_data['supply_key'] . '-' . str_pad($supply->id, 3, '0', STR_PAD_LEFT);

        // Update supply key
        $supply->supply_key = $supply_key;
        $supply->saveQuietly();        

        $supply_locations = SupplyLocation::all();

        $stock_data = array();

        foreach($supply_locations as $supply_location) {
            $stock_data[] = array(
                'supply_id' => $supply->id,
                'supply_location_id' => $supply_location->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        Stock::insert($stock_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Materia prima agregada',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
    	$supply = Supply::find(request('id'));

        if($supply->measurement_unit_id) {
            $supply['measure'] = $supply->measurement_unit->measure;
        }

        return response()->json($supply);
    }

    public function update(Request $request, Supply $supply)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'supply_key' => 'required',
            'initial_cost' => 'required|numeric',
            'min_stock' => 'required|numeric',
            'max_stock' => 'required|numeric',
            'reorder_stock' => 'required|numeric',
            'safety_stock' => 'required|numeric',
            'measurement_unit_id' => 'required',
            'supply_category_id' => 'required',
            'standard_pack' => 'required',
            'requires_iva' => 'required|boolean',
            'requires_ieps' => 'required|boolean',
        ]);

        $existing_supply = $this->update_callback($supply->id, $validated_data['name']);

        if($existing_supply) {
            request()->session()->flash('alertmessage', [
                'message' => 'La materia prima ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supply->name = $validated_data['name'];
        $supply->supply_key = 'MP-' . $validated_data['supply_key'] . '-' . str_pad($supply->id, 3, '0', STR_PAD_LEFT);
        $supply->standard_pack = $validated_data['standard_pack'];
        $supply->initial_cost = $validated_data['initial_cost'];
        $supply->min_stock = $validated_data['min_stock'];
        $supply->max_stock = $validated_data['max_stock'];
        $supply->reorder_stock = $validated_data['reorder_stock'];
        $supply->safety_stock = $validated_data['safety_stock'];
        $supply->measurement_unit_id = $validated_data['measurement_unit_id'];
        $supply->supply_category_id = $validated_data['supply_category_id'];
        $supply->requires_iva = $validated_data['requires_iva'];
        $supply->requires_ieps = $validated_data['requires_ieps'];
        $supply->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Materia prima actualizada',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search supply by name
        $supply = Supply::where('name', $name)->first();
        return $supply;
    }

    private function update_callback($id, $name)
    {
        // Search supply by name different from id
        $supply = Supply::where('id', '<>', $id)->where('name', $name)->first();
        return $supply;
    }

    public function detail(Supply $supply)
    {
        $not_associated_suppliers = Supplier::whereDoesntHave('supplies', function (Builder $query) use ($supply) {
            $query->where('supply_id', $supply->id);
        })->orderBy('name', 'asc')->get();

        return view('supplies.detail', compact('supply', 'not_associated_suppliers'));
    }

    public function change_status($id = null, $status = null)
    {
        $supply = Supply::find($id);

        $message = '';
        
        if($status == 'active') {
            $supply->is_active = true;
            $message = 'activada';
        }
        else {
            $supply->is_active = false;
            $message = 'desactivada';
        }

        $supply->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Materia prima ' . $message,
            'type' => 'success',
        ]);

        return back();
    }
}
