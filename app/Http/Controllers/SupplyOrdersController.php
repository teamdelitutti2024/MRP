<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderDetail;
use App\Models\Supplier;
use App\Models\CommercialTerm;
use App\Models\Supply;
use App\Models\MeasurementUnit;
use App\Models\SupplierSupply;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplyOrdersController extends Controller
{
    public function index()
    {
        $supply_orders = SupplyOrder::orderBy('created_at', 'desc')->get();
        return view('supply_orders.index', compact('supply_orders'));
    }

    public function add()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $commercial_terms = CommercialTerm::orderBy('name', 'asc')->get();

        return view('supply_orders.add', compact('suppliers', 'commercial_terms'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'supplier_id' => 'required',
            'commercial_term_id' => 'required',
            'require_invoice' => 'required',
            'preferred_payment_method' => 'required',
            'type' => 'required',
            'total' => 'required',
            'details' => '',
        ]);

        // Get related supplier to build the delivery date
        $supplier = Supplier::find($validated_data['supplier_id']);

        // Create supply order with status in progress
        $supply_order = SupplyOrder::create([
            'type' => $validated_data['type'],
            'total' => $validated_data['total'],
            'status' => '1',
            'commercial_term_default' => $validated_data['commercial_term_id'] == $supplier->commercial_term_id ? true : false,
            'require_invoice' => $validated_data['require_invoice'],
            'preferred_payment_method' => $validated_data['preferred_payment_method'],
            'supplier_id' => $validated_data['supplier_id'],
            'responsible_id' => auth()->user()->id,
            'commercial_term_id' => $validated_data['commercial_term_id'] == $supplier->commercial_term_id ? null : $validated_data['commercial_term_id'],
        ]);

        $supply_order_details = array();    

        // Create supply order details
        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
                $supply_order_details[] = array(
                    'supply' => Supply::find($validated_data['details']['supply_id'][$i])->name,
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'supply_id' => $validated_data['details']['supply_id'][$i],
                    'measurement_unit_id' => $validated_data['details']['measurement_unit_id'][$i],
                    'supply_order_id' => $supply_order->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        SupplyOrderDetail::insert($supply_order_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Pedido de materia prima agregado',
            'type' => 'success',
        ]);

        return redirect('/supply_orders');
    }

    public function edit(SupplyOrder $supply_order)
    {
        $commercial_terms = CommercialTerm::orderBy('name', 'asc')->get();
        $supplies = array();

        foreach($supply_order->supplier->supplies as $element) {
            if($element->is_active) {
                if(!empty($element['association']['measurement_unit_id'])) {
                    $element['association']['measure'] = MeasurementUnit::find($element->association->measurement_unit_id)->measure;
                    $supplies[] = $element;
                }
            }
        }

        return view('supply_orders.edit', compact('supply_order', 'commercial_terms', 'supplies'));
    }

    public function update(Request $request, SupplyOrder $supply_order)
    {
        $validated_data = $request->validate([
            'commercial_term_id' => 'required',
            'require_invoice' => 'required',
            'preferred_payment_method' => 'required',
            'type' => 'required',
            'total' => 'required',
            'details' => '',
        ]);

        // Update supply order
        $supply_order->commercial_term_default = $validated_data['commercial_term_id'] == $supply_order->supplier->commercial_term_id ? true : false;
        $supply_order->require_invoice = $validated_data['require_invoice'];
        $supply_order->preferred_payment_method = $validated_data['preferred_payment_method'];
        $supply_order->commercial_term_id = $validated_data['commercial_term_id'] == $supply_order->supplier->commercial_term_id ? null : $validated_data['commercial_term_id'];
        $supply_order->type = $validated_data['type'];
        $supply_order->total = $validated_data['total'];
        $supply_order->responsible_id = auth()->user()->id;
        $supply_order->save();

        // Delete old supply order details
        SupplyOrderDetail::where('supply_order_id', $supply_order->id)->delete();

        $supply_order_details = array();

        // Create new supply order details if exist
        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
                $supply_order_details[] = array(
                    'supply' => Supply::find($validated_data['details']['supply_id'][$i])->name,
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'supply_id' => $validated_data['details']['supply_id'][$i],
                    'measurement_unit_id' => $validated_data['details']['measurement_unit_id'][$i],
                    'supply_order_id' => $supply_order->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        SupplyOrderDetail::insert($supply_order_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Pedido de materia prima actualizado',
            'type' => 'success',
        ]);

        return redirect('/supply_orders');
    }

    public function download(SupplyOrder $supply_order)
    {
        $supply_order_data = array();

        $supply_order_data['supply_order'] = array(
            'id' => $supply_order->id,
            'type' => $supply_order->type,
            'total' => $supply_order->total,
            'delivery_date' => date('d-m-Y', strtotime($supply_order->delivery_date)),
            'status' => $supply_order->status,
            'require_invoice' => $supply_order->require_invoice,
            'preferred_payment_method' => $supply_order->preferred_payment_method,
            'created_at' => date('d-m-Y H:i:s', strtotime($supply_order->created_at)),
            'supplier' => $supply_order->supplier,
            'responsible' => $supply_order->responsible->name,
            'commercial_term' => $supply_order->commercial_term_default ? $supply_order->supplier->commercial_term : $supply_order->commercial_term,
        );

        foreach($supply_order->supply_order_details as $element) {
            $supply_order_data['supplies'][] = array(
                'supply_key' => $element->sup->supply_key,
                'supply' => $element->supply,
                'measurement_unit' => $element->measurement_unit->measure,
                'cost' => $element->cost,
                'quantity' => $element->quantity,
                'total' => $element->cost * $element->quantity,
            );
        }

        return Pdf::loadView('supply_orders.pdf', $supply_order_data)->download('pedido_materia_prima_' . $supply_order->id . '.pdf');
    }

    public function request(Request $request, SupplyOrder $supply_order)
    {
        $data = $request->all();
        $total = 0;

        // Freeze price and cost and update average cost for every supply requested
        foreach($supply_order->supply_order_details as $element) {
            $supplier_supply = SupplierSupply::where('supplier_id', $supply_order->supplier_id)->where('supply_id', $element->supply_id)->first();

            $element->price = $supplier_supply->price;
            $element->cost = $supplier_supply->cost;
            $element->save();

            $total += $element->cost * $element->quantity;

            // Get supply
            $supply = Supply::find($element->supply_id);

            // Set and save new average cost for this supply
            $supply->average_cost = $supplier_supply->cost / $supply->standard_pack;
            $supply->save();
        }

        // Update delivery date, status and total from supply order
        $supply_order->delivery_date = $supply_order->supplier->delivery_time ? date('Y-m-d', strtotime($data['request_date'] . ' + ' . $supply_order->supplier->delivery_time . ' days')) : null;
        $supply_order->status = '3';
        $supply_order->total = $total;
        $supply_order->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Pedido de materia prima solicitado',
            'type' => 'success',
        ]);

        return back();
    }

    public function cancel(SupplyOrder $supply_order)
    {
        $supply_order->status = '2';
        $supply_order->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Pedido de materia prima cancelado',
            'type' => 'success',
        ]);

        return back();
    }

    public function detail(SupplyOrder $supply_order)
    {
        return view('supply_orders.detail', compact('supply_order'));
    }

    public function get_receptions(SupplyOrder $supply_order)
    {
        return view('supply_orders.receptions', compact('supply_order'));
    }
}
