<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyCategory;

class SupplyCategoriesController extends Controller
{
    public function index()
    {
        $supply_categories = SupplyCategory::orderBy('name', 'asc')->get();
        return view('supply_categories.index', compact('supply_categories'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
        ]);

        $supply_category = $this->store_callback($validated_data['name']);

        if($supply_category) {
            request()->session()->flash('alertmessage', [
                'message' => 'La categoría de materia prima ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        SupplyCategory::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Categoría de materia prima agregada',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supply_category = SupplyCategory::find(request('id'));
        return response()->json($supply_category);
    }

    public function update(Request $request, SupplyCategory $supply_category)
    {
        $validated_data = $request->validate([
            'name' => 'required',
        ]);

        $existing_supply_category = $this->update_callback($supply_category->id, $validated_data['name']);

        if($existing_supply_category) {
            request()->session()->flash('alertmessage', [
                'message' => 'La categoría de materia prima ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supply_category->name = $validated_data['name'];
        $supply_category->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Categoría de materia prima actualizada',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search supply category by name
        $supply_category = SupplyCategory::where('name', $name)->first();
        return $supply_category;
    }

    private function update_callback($id, $name)
    {
        // Search supply category by name different from id
        $supply_category = SupplyCategory::where('id', '<>', $id)->where('name', $name)->first();
        return $supply_category;
    }
}
