<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierContact;

class SupplierContactsController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'email' => '',
            'phone' => '',
            'mobile' => 'required',
            'notes' => '',
            'preferred' => 'required',
            'supplier_id' => 'required',
        ]);

        SupplierContact::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Contacto agregado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $supplier_contact = SupplierContact::find(request('id'));
        return response()->json($supplier_contact);
    }

    public function update(Request $request, SupplierContact $supplier_contact)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'email' => '',
            'phone' => '',
            'mobile' => 'required',
            'notes' => '',
            'preferred' => 'required',
        ]);

        $supplier_contact->name = $validated_data['name'];
        $supplier_contact->department = $validated_data['department'];
        $supplier_contact->email = $validated_data['email'];
        $supplier_contact->phone = $validated_data['phone'];
        $supplier_contact->mobile = $validated_data['mobile'];
        $supplier_contact->notes = $validated_data['notes'];
        $supplier_contact->preferred = $validated_data['preferred'];
        $supplier_contact->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Contacto actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function detail(SupplierContact $supplier_contact)
    {
        return view('supplier_contacts.detail', compact('supplier_contact'));
    }
}
