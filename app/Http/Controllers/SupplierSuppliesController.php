<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierSupply;
use App\Models\Supply;
use App\Models\MeasurementUnit;

class SupplierSuppliesController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'supply_id' => 'required',
            'supplier_id' => 'required',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
        ]);

        $supply = Supply::find($validated_data['supply_id']);
        $supply->suppliers()->attach($validated_data['supplier_id'], ['measurement_unit_id' => $supply->measurement_unit_id, 'price' => $validated_data['price'], 'cost' => $validated_data['cost']]);

        request()->session()->flash('alertmessage', [
            'message' => 'Elemento asociado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supplier_supply = SupplierSupply::find(request('id'));
        return response()->json($supplier_supply);
    }

    public function update(Request $request, SupplierSupply $supplier_supply)
    {
        $validated_data = $request->validate([
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
        ]);

        $supply = Supply::find($supplier_supply->supply_id);
        $supply->suppliers()->updateExistingPivot($supplier_supply->supplier_id, ['measurement_unit_id' => $supply->measurement_unit_id, 'price' => $validated_data['price'], 'cost' => $validated_data['cost']]);

        request()->session()->flash('alertmessage', [
            'message' => 'Elemento asociado actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_supplier_supplies()
    {
        $supplier_supply = SupplierSupply::where('supply_id', request('supply_id'))->where('supplier_id', request('supplier_id'))->first();
        $supplier_supply['measure'] = MeasurementUnit::find($supplier_supply->measurement_unit_id)->measure;

        return response()->json($supplier_supply);
    }
}
