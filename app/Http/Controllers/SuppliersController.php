<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\SupplierCategory;
use App\Models\CommercialTerm;
use App\Models\MeasurementUnit;
use Barryvdh\DomPDF\Facade\Pdf;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        // Get supplies categories for each supplier
        foreach($suppliers as $supplier) {
            $supplies_categories = DB::table('suppliers')
                ->join('supplier_supplies', 'suppliers.id', '=', 'supplier_supplies.supplier_id')
                ->join('supplies', 'supplier_supplies.supply_id', '=', 'supplies.id')
                ->join('supply_categories', 'supplies.supply_category_id', '=', 'supply_categories.id')
                ->select('supply_categories.name')
                ->where('suppliers.id', $supplier->id)
                ->distinct()
                ->get();

            $supplier->supplies_categories = $supplies_categories;
        }

        return view('suppliers.index', compact('suppliers'));
    }

    public function download()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        $suppliers_data = array();

        foreach($suppliers as $supplier) {
            $suppliers_data['suppliers'][] = array(
                'supplier_key' => $supplier->supplier_key,
                'name' => $supplier->name,
                'address' => $supplier->address,
                'delivery_time' => $supplier->delivery_time,
                'preferred_payment_method' => $supplier->preferred_payment_method,
                'category' => $supplier->category->name,
                'require_invoice' => $supplier->require_invoice,
                'created' => date('d-m-Y H:i:s', strtotime($supplier->created)),
            );
        }

        return Pdf::loadView('suppliers.pdf', $suppliers_data)->download('listado_proveedores_' . date('Y-m-d') . '.pdf');
    }

    public function add()
    {
        $supplier_categories = SupplierCategory::orderBy('name', 'asc')->get();
        $commercial_terms = CommercialTerm::orderBy('name', 'asc')->get();
        return view('suppliers.add', compact('supplier_categories', 'commercial_terms'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'delivery_time' => 'sometimes|integer|nullable',
            'address' => 'required',
            'notes' => '',
            'payment_information' => '',
            'preferred_payment_method' => 'required',
            'supplier_category_id' => 'required',
            'commercial_term_id' => 'required',
            'require_invoice' => 'required',
        ]);

        $supplier = $this->store_callback($validated_data['name']);

        if($supplier) {
            request()->session()->flash('alertmessage', [
                'message' => 'El proveedor ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        $supplier = Supplier::create($validated_data);

        // Build new key to be updated
        $supplier_key = 'PR-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT);

        // Update supplier key
        $supplier->supplier_key = $supplier_key;
        $supplier->saveQuietly();

        request()->session()->flash('alertmessage', [
            'message' => 'Proveedor agregado',
            'type' => 'success',
        ]);

        return redirect('/suppliers');
    }

    public function get_row()
    {
        $supplier = Supplier::find(request('id'));
        return response()->json($supplier);
    }

    public function edit(Supplier $supplier)
    {
        // Save previous url in session
        session(['prev_url' => URL::previous()]);
        $supplier_categories = SupplierCategory::orderBy('name', 'asc')->get();
        $commercial_terms = CommercialTerm::orderBy('name', 'asc')->get();

        return view('suppliers.edit', compact('supplier', 'supplier_categories', 'commercial_terms'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'delivery_time' => 'sometimes|integer|nullable',
            'address' => 'required',
            'notes' => '',
            'payment_information' => '',
            'preferred_payment_method' => 'required',
            'supplier_category_id' => 'required',
            'commercial_term_id' => 'required',
            'require_invoice' => 'required',
        ]);

        $existing_supplier = $this->update_callback($supplier->id, $validated_data['name']);

        if($existing_supplier) {
            request()->session()->flash('alertmessage', [
                'message' => 'El proveedor ya se encuentra registrado',
                'type' => 'error',
            ]);

            return back();
        }

        $supplier->name = $validated_data['name'];
        $supplier->delivery_time = $validated_data['delivery_time'];
        $supplier->address = $validated_data['address'];
        $supplier->notes = $validated_data['notes'];
        $supplier->payment_information = $validated_data['payment_information'];
        $supplier->preferred_payment_method = $validated_data['preferred_payment_method'];
        $supplier->supplier_category_id = $validated_data['supplier_category_id'];
        $supplier->commercial_term_id = $validated_data['commercial_term_id'];
        $supplier->require_invoice = $validated_data['require_invoice'];
        $supplier->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Proveedor actualizado',
            'type' => 'success',
        ]);

        // Get prev url session variable
        $prev_url = session('prev_url');

        // Delete prev url session variable
        session()->forget('prev_url');

        return redirect ($prev_url);        
    }

    private function store_callback($name)
    {
        // Search supplier by name
        $supplier = Supplier::where('name', $name)->first();
        return $supplier;
    }

    private function update_callback($id, $name)
    {
        // Search supplier by name different from id
        $supplier = Supplier::where('id', '<>', $id)->where('name', $name)->first();
        return $supplier;
    }

    public function detail(Supplier $supplier)
    {
        $not_associated_supplies = Supply::whereDoesntHave('suppliers', function (Builder $query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->orderBy('name', 'asc')->get();

        $measurement_units = MeasurementUnit::orderBy('measure', 'asc')->get();

        return view('suppliers.detail', compact('supplier', 'not_associated_supplies', 'measurement_units'));
    }

    public function get_supplies()
    {
        $supplier = Supplier::find(request('id'));

        $supplier_supplies = array();

        foreach($supplier->supplies as $element) {
            if($element->is_active) {
                if(!empty($element['association']['measurement_unit_id'])) {
                    $element['association']['measure'] = MeasurementUnit::find($element['association']['measurement_unit_id'])->measure;
                    $supplier_supplies[] = $element;
                }
            }
        }

        return response()->json($supplier_supplies);
    }
}
