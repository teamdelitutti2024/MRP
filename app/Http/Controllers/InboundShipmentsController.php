<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InboundShipment;
use App\Models\InboundShipmentDetail;
use App\Models\DepartureShipment;
use App\Models\DepartureShipmentDetail;
use App\Models\Branch;

class InboundShipmentsController extends Controller
{
    public function index()
    {
        $inbound_shipments = InboundShipment::orderBy('created_at', 'desc')->get();
        return view('inbound_shipments.index', compact('inbound_shipments'));
    }

    public function add($id = null)
    {
        $departure_shipment = DepartureShipment::find($id);

        $branches_ids = array();

        foreach($departure_shipment->inbound_shipments as $inbound_shipment) {
            $branches_ids[] = $inbound_shipment->branch_id;
        }

        $available_branches = Branch::whereNotIn('id', $branches_ids)->orderBy('name', 'asc')->get();

        return view('inbound_shipments.add', compact('departure_shipment', 'available_branches'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'departure_shipment_id' => 'required',
            'branch_id' => 'required',
            'received_date' => 'required',
            'quantities' => '',
            'departure_shipment_details' => '',
            'status' => '',
            'justifications' => '',
        ]);

        $inbound_shipment = InboundShipment::create([
            'departure_shipment_id' => $validated_data['departure_shipment_id'],
            'branch_id' => $validated_data['branch_id'],
            'received_date' => $validated_data['received_date'],
            'responsible_id' => auth()->user()->id,
        ]);

        $inbound_shipment_details = array();

        for($i = 0; $i < count($validated_data['quantities']); $i++) {
            $departure_shipment_detail = DepartureShipmentDetail::find($validated_data['departure_shipment_details'][$i]);

            $inbound_shipment_details[] = array(
                'product_name' => $departure_shipment_detail->product_name,
                'quantity' => $validated_data['quantities'][$i],
                'status' => $validated_data['status'][$i],
                'justification' => $validated_data['justifications'][$i],
                'product_id' => $departure_shipment_detail->product_id,
                'inbound_shipment_id' => $inbound_shipment->id,
                'product_size_id' => $departure_shipment_detail->product_size_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $departure_shipment_detail->received_quantity = $departure_shipment_detail->received_quantity + $validated_data['quantities'][$i];
            $departure_shipment_detail->save();
        }

        InboundShipmentDetail::insert($inbound_shipment_details);

        $departure_shipment = DepartureShipment::find($inbound_shipment->departure_shipment_id);

        $status = true;

        foreach($departure_shipment->departure_shipment_details as $element) {
            if($element->quantity > $element->received_quantity) {
                $status = false;
                break;
            }
        }

        if($status) {
            $departure_shipment->status = 4;
        }
        else {
            $departure_shipment->status = 3;
        }

        $departure_shipment->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Embarque de entrada agregado',
            'type' => 'success',
        ]);

        return redirect('/inbound_shipments');
    }

    public function detail(InboundShipment $inbound_shipment)
    {
        return view('inbound_shipments.detail', compact('inbound_shipment'));
    }
}
