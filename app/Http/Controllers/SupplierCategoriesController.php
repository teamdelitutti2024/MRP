<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierCategory;

class SupplierCategoriesController extends Controller
{
    public function index()
    {
        $supplier_categories = SupplierCategory::orderBy('name', 'asc')->get();
        return view('supplier_categories.index', compact('supplier_categories'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
        ]);

        $supplier_category = $this->store_callback($validated_data['name']);

        if($supplier_category) {
            request()->session()->flash('alertmessage', [
                'message' => 'La categoría de proveedor ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        SupplierCategory::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Categoría de proveedor agregada',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supplier_category = SupplierCategory::find(request('id'));
        return response()->json($supplier_category);
    }

    public function update(Request $request, SupplierCategory $supplier_category)
    {
        $validated_data = $request->validate([
            'name' => 'required',
        ]);

        $existing_supplier_category = $this->update_callback($supplier_category->id, $validated_data['name']);

        if($existing_supplier_category) {
            request()->session()->flash('alertmessage', [
                'message' => 'La categoría de proveedor ya se encuentra registrada',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supplier_category->name = $validated_data['name'];
        $supplier_category->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Categoría de proveedor actualizada',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search supplier category by name
        $supplier_category = SupplierCategory::where('name', $name)->first();
        return $supplier_category;
    }

    private function update_callback($id, $name)
    {
        // Search supplier category by name different from id
        $supplier_category = SupplierCategory::where('id', '<>', $id)->where('name', $name)->first();
        return $supplier_category;
    }
}
