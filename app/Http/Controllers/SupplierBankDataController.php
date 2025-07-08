<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierBankData;

class SupplierBankDataController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'bank' => 'required',
            'account_holder' => 'required',
            'account_number' => 'required',
            'clabe' => 'required',
            'supplier_id' => 'required',
        ]);

        SupplierBankData::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Información bancaria agregada',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supplier_bank_data = SupplierBankData::find(request('id'));
        return response()->json($supplier_bank_data);
    }

    public function update(Request $request, SupplierBankData $supplier_bank_data)
    {
        $validated_data = $request->validate([
            'bank' => 'required',
            'account_holder' => 'required',
            'account_number' => 'required',
            'clabe' => 'required',
        ]);

        $supplier_bank_data->bank = $validated_data['bank'];
        $supplier_bank_data->account_holder = $validated_data['account_holder'];
        $supplier_bank_data->account_number = $validated_data['account_number'];
        $supplier_bank_data->clabe = $validated_data['clabe'];
        $supplier_bank_data->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Información bancaria actualizada',
            'type' => 'success',
        ]);

        return back();
    }
}
