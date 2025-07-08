<?php

namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Models\CommercialTerm;

class CommercialTermsController extends Controller
{
    public function index()
    {
        $commercial_terms = CommercialTerm::orderBy('name', 'asc')->get();
        return view('commercial_terms.index', compact('commercial_terms'));
    }
    
    public function add()
    {
        return view('commercial_terms.add');
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'deposit' => 'required|numeric',
            'payments_detail' => '',
        ]);

        if(isset($validated_data['payments_detail'])) {
            $payments_detail_tmp = array();
            for($i = 0; $i < count($validated_data['payments_detail']['percentage']); $i++) {
                $payment_detail_tmp = array(
                    'percentage' => number_format($validated_data['payments_detail']['percentage'][$i], 2),
                    'days' => $validated_data['payments_detail']['days'][$i],
                );
                $payments_detail_tmp[] = $payment_detail_tmp;
            }

            $validated_data['payments_detail'] = json_encode($payments_detail_tmp);
        }

        CommercialTerm::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Condición comercial agregada',
            'type' => 'success',
        ]);

        return redirect('/commercial_terms');
    }

    public function edit(CommercialTerm $commercial_term)
    {
        return view('commercial_terms.edit', compact('commercial_term'));
    }

    public function update(Request $request, CommercialTerm $commercial_term)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'deposit' => 'required|numeric',
            'payments_detail' => '',
        ]);

        if(isset($validated_data['payments_detail'])) {
            $payments_detail_tmp = array();
            for($i = 0; $i < count($validated_data['payments_detail']['percentage']); $i++) {
                $payment_detail_tmp = array(
                    'percentage' => number_format($validated_data['payments_detail']['percentage'][$i], 2),
                    'days' => $validated_data['payments_detail']['days'][$i],
                );
                $payments_detail_tmp[] = $payment_detail_tmp;
            }

            $validated_data['payments_detail'] = json_encode($payments_detail_tmp);
        }
        else {
            $validated_data['payments_detail'] = null;
        }

        $commercial_term->name = $validated_data['name'];
        $commercial_term->type = $validated_data['type'];
        $commercial_term->deposit = $validated_data['deposit'];
        $commercial_term->payments_detail = $validated_data['payments_detail'];
        $commercial_term->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Condición comercial actualizada',
            'type' => 'success',
        ]);

        return redirect('/commercial_terms');
    }

    public function detail(CommercialTerm $commercial_term)
    {
        return view('commercial_terms.detail', compact('commercial_term'));
    }
}
