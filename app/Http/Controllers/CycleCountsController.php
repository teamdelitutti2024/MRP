<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CycleCount;
use App\Models\CycleCountDetail;
use App\Models\Stock;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Exports\CycleCountExport;
use App\Exports\CycleCountPartialExport;
use App\ChangesLogHelper;
use Maatwebsite\Excel\Facades\Excel;

class CycleCountsController extends Controller
{
    public function index()
    {
        $cycle_counts = CycleCount::orderBy('created_at', 'desc')->get();
        return view('cycle_counts.index', compact('cycle_counts'));
    }

    public function add()
    {
        $available_supplies = Stock::all();

        // Create cycle count
        $cycle_count = CycleCount::create([
            'status' => 1,
            'type' => 1,
        ]);

        $cycle_count_details = array();

        foreach($available_supplies as $available_supply) {
            // Save cycle count detail into array
            $cycle_count_details[] = array(
                'supply' => $available_supply->supply->name,
                'stock_quantity' => $available_supply->quantity,
                'cycle_count_id' => $cycle_count->id,
                'supply_id' => $available_supply->supply_id,
                'measurement_unit_id' => $available_supply->supply->measurement_unit_id,
                'supply_location_id' => $available_supply->supply_location_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        // Insert cycle count details
        CycleCountDetail::insert($cycle_count_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Conteo completo creado',
            'type' => 'success',
        ]);

        return back();
    }

    public function add_partial()
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('cycle_counts.add_partial', compact('supplies', 'supply_locations'));
    }

    public function edit(CycleCount $cycle_count)
    {
        $cycle_count_details = collect($cycle_count->cycle_count_details)->sortBy([
            ['supply_location_id', 'asc'],
            ['supply_id', 'asc'],
        ]);

        return view('cycle_counts.edit', compact('cycle_count', 'cycle_count_details'));
    }

    public function edit_partial(CycleCount $cycle_count)
    {
        $cycle_count_details = collect($cycle_count->cycle_count_details)->sortBy([
            ['supply_location_id', 'asc'],
            ['supply_id', 'asc'],
        ]);

        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::orderBy('location', 'asc')->get();

        return view('cycle_counts.edit_partial', compact('cycle_count', 'cycle_count_details', 'supplies', 'supply_locations'));
    }

    public function store_partial(Request $request)
    {
        $data = $request->all();

        // Create cycle count
        $cycle_count = CycleCount::create([
            'status' => 2,
            'type' => 2,
            'responsible_id' => auth()->user()->id,
        ]);

        $cycle_count_details = array();

        if(isset($data['details'])) {
            for($i = 0; $i < count($data['details']['quantity']); $i++) {
                // Get related supply
                $supply = Supply::find($data['details']['supply_id'][$i]);

                $cycle_count_details[] = array(
                    'supply' => $supply->name,
                    'counted_quantity' => $data['details']['quantity'][$i],
                    'comments' => $data['details']['comments'][$i],
                    'cycle_count_id' => $cycle_count->id,
                    'supply_id' => $supply->id,
                    'measurement_unit_id' => $supply->measurement_unit_id,
                    'supply_location_id' => $data['details']['supply_location_id'][$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        // Insert cycle count details
        CycleCountDetail::insert($cycle_count_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Conteo parcial agregado',
            'type' => 'success',
        ]);

        return redirect('/cycle_counts');
    }

    public function update(Request $request, CycleCount $cycle_count)
    {
        $data = $request->all();

        // Update cycle count details
        if(isset($data['details'])) {
            for($i = 0; $i < count($data['details']['quantity']); $i++) { 
                $cycle_count_detail = CycleCountDetail::find($data['details']['cycle_count_detail_id'][$i]);
                $cycle_count_detail->counted_quantity = $data['details']['quantity'][$i];
                $cycle_count_detail->comments = $data['details']['comments'][$i];
                $cycle_count_detail->save();
            }
        }

        if($cycle_count->status == 1) {
            $cycle_count->status = 2;
            $cycle_count->responsible_id = auth()->user()->id;
            $cycle_count->save();
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Conteo completo actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function update_partial(Request $request, CycleCount $cycle_count)
    {
        $data = $request->all();

        // Remove old data
        CycleCountDetail::where('cycle_count_id', $cycle_count->id)->delete();

        // Update cycle count details
        if(isset($data['details'])) {
            for($i = 0; $i < count($data['details']['quantity']); $i++) { 
                // Get related supply
                $supply = Supply::find($data['details']['supply_id'][$i]);

                $cycle_count_details[] = array(
                    'supply' => $supply->name,
                    'counted_quantity' => $data['details']['quantity'][$i],
                    'comments' => $data['details']['comments'][$i],
                    'cycle_count_id' => $cycle_count->id,
                    'supply_id' => $supply->id,
                    'measurement_unit_id' => $supply->measurement_unit_id,
                    'supply_location_id' => $data['details']['supply_location_id'][$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        // Insert cycle count details
        CycleCountDetail::insert($cycle_count_details);

        request()->session()->flash('alertmessage', [
            'message' => 'Conteo parcial actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function finish(CycleCount $cycle_count)
    {
        // Update cycle count details
        foreach($cycle_count->cycle_count_details as $element) { 
            // Get related stock
            $supply_stock = Stock::where('supply_id', $element->supply_id)->where('supply_location_id', $element->supply_location_id)->first();

            if(!empty($element->counted_quantity)) {
                if($supply_stock->quantity != $element->counted_quantity) {
                    // Add log
                    $data = array(
                        'element_name' => $supply_stock->supply->name . ' - ' . $supply_stock->supply_location->location,
                        'element_key' => $supply_stock->supply->supply_key,
                        'previous_quantity' => $supply_stock->quantity,
                        'new_quantity' => $element->counted_quantity,
                        'model' => 'Stock',
                        'event' => 'ACTUALIZACIÓN DESDE CONTEOS CICLICOS',
                        'responsible_id' => auth()->user()->id,
                    );

                    ChangesLogHelper::add($data);
                }

                // Update stock quantity on cycle count detail
                $element->stock_quantity = $supply_stock->quantity;
                $element->save();

                // Update stock quantity
                $supply_stock->quantity = $element->counted_quantity;
                $supply_stock->save();
            }
        }

        $cycle_count->responsible_id = auth()->user()->id;
        $cycle_count->status = 3;
        $cycle_count->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Conteo finalizado y materias primas actualizadas en las ubicaciones correspondientes',
            'type' => 'success',
        ]);

        return redirect('/cycle_counts');
    }

    public function detail(CycleCount $cycle_count)
    {
        $cycle_count_details = collect($cycle_count->cycle_count_details)->sortBy([
            ['supply_location_id', 'asc'],
            ['supply_id', 'asc'],
        ]);

        return view('cycle_counts.detail', compact('cycle_count_details'));
    }

    public function download(CycleCount $cycle_count)
    {
        if($cycle_count->type == 1) {
            return Excel::download(new CycleCountExport($cycle_count->id), 'conteo_completo_' . date('Y-m-d', strtotime($cycle_count->created_at)) . '.xlsx');
        }

        return Excel::download(new CycleCountPartialExport($cycle_count->id), 'conteo_parcial_' . date('Y-m-d', strtotime($cycle_count->created_at)) . '.xlsx');
    }
}
