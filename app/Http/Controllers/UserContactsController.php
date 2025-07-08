<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserContact;

class UserContactsController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'relationship' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'user_id' => 'required',
        ]);

        UserContact::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Contacto agregado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $user_contact = UserContact::find(request('id'));
        return response()->json($user_contact);
    }

    public function update(Request $request, UserContact $user_contact)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'relationship' => 'required',
            'email' => 'required',
            'mobile' => 'required',
        ]);

        $user_contact->name = $validated_data['name'];
        $user_contact->relationship = $validated_data['relationship'];
        $user_contact->email = $validated_data['email'];
        $user_contact->mobile = $validated_data['mobile'];
        $user_contact->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Contacto actualizado',
            'type' => 'success',
        ]);

        return back();
    }
}
