<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierTaxData;
use App\Models\Supplier;

class SupplierTaxDataController extends Controller
{
    public function add(Supplier $supplier)
    {
        return view('supplier_tax_data.add', compact('supplier'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'business_reason' => 'required',
            'RFC' => 'required',
            'street' => 'required',
            'outside_number' => 'required',
            'inside_number' => '',
            'colony' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'supplier_id' => 'required',
        ]);

        SupplierTaxData::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Datos fiscales agregados',
            'type' => 'success',
        ]);

        return redirect('/suppliers/detail/' . $validated_data['supplier_id']);
    }

    public function edit(SupplierTaxData $supplier_tax_data)
    {
        return view('supplier_tax_data.edit', compact('supplier_tax_data'));
    }

    public function update(Request $request, SupplierTaxData $supplier_tax_data)
    {
        $validated_data = $request->validate([
            'business_reason' => 'required',
            'RFC' => 'required',
            'street' => 'required',
            'outside_number' => 'required',
            'inside_number' => '',
            'colony' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $supplier_tax_data->business_reason = $validated_data['business_reason'];
        $supplier_tax_data->RFC = $validated_data['RFC'];
        $supplier_tax_data->street = $validated_data['street'];
        $supplier_tax_data->outside_number = $validated_data['outside_number'];
        $supplier_tax_data->inside_number = $validated_data['inside_number'];
        $supplier_tax_data->colony = $validated_data['colony'];
        $supplier_tax_data->zip_code = $validated_data['zip_code'];
        $supplier_tax_data->city = $validated_data['city'];
        $supplier_tax_data->state = $validated_data['state'];
        $supplier_tax_data->country = $validated_data['country'];
        $supplier_tax_data->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Datos fiscales actualizados',
            'type' => 'success',
        ]);

        return redirect('/suppliers/detail/' . $supplier_tax_data->supplier_id);
    }

    public function detail(SupplierTaxData $supplier_tax_data)
    {
        return view('supplier_tax_data.detail', compact('supplier_tax_data'));
    }
}
