<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use App\Models\Equivalence;

class MeasurementUnitsController extends Controller
{
    public function index()
    {
        $measurement_units = MeasurementUnit::orderBy('measure', 'asc')->get();
        return view('measurement_units.index', compact('measurement_units'));
    }

    public function add()
    {
        return view('measurement_units.add');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        foreach($data['measurement_units'] as $key => $value) {
            // Create or update the measurement units
            MeasurementUnit::updateOrCreate(
                ['measure' => $value],
                ['measure' => $value]
            );
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Unidad (es) de medida agregada (s)',
            'type' => 'success',
        ]);

        return redirect('/measurement_units');
    }

    public function edit(MeasurementUnit $measurement_unit)
    {
        $measurement_units = MeasurementUnit::where('measure', '<>', $measurement_unit->measure)->orderBy('measure', 'asc')->get();
        return view('measurement_units.edit', compact('measurement_unit', 'measurement_units'));
    }

    public function update(Request $request, MeasurementUnit $measurement_unit)
    {
        $validated_data = $request->validate([
            'measure' => 'required',
            'equivalences' => '',
        ]);

        $measurement_unit->measure = $validated_data['measure'];
        $measurement_unit->save();

        // Delete old equivalences
        Equivalence::where('source_measurement_id', $measurement_unit->id)->delete();

        // Update and create the equivalences
        if(isset($validated_data['equivalences'])) {
            for($i = 0; $i < count($validated_data['equivalences']['equivalence']); $i++) {
                Equivalence::updateOrCreate(
                    ['source_measurement_id' => $measurement_unit->id, 'target_measurement_id' => $validated_data['equivalences']['target_measurement_id'][$i]],
                    ['equivalence' => $validated_data['equivalences']['equivalence'][$i]]
                );
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Unidad de medida y equivalencias actualizadas',
            'type' => 'success',
        ]);

        return back();
    }

    public function detail(MeasurementUnit $measurement_unit)
    {
        return view('measurement_units.detail', compact('measurement_unit'));
    }
}
