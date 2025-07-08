<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\CycleCountPerLocationSheet;
use App\Models\SupplyLocation;
use App\Models\CycleCountDetail;

class CycleCountExport implements WithMultipleSheets
{
    use Exportable;

    protected $cycle_count_id;

    public function __construct($cycle_count_id)
    {
        $this->cycle_count_id = $cycle_count_id;
    }

    public function sheets(): array
    {
        $sheets = array();

        $supply_locations = SupplyLocation::all();

        foreach($supply_locations as $supply_location) {
            $data = array();

            $data_per_location = CycleCountDetail::where('cycle_count_id', $this->cycle_count_id)->where('supply_location_id', $supply_location->id)->orderBy('supply', 'asc')->get();
            
            foreach($data_per_location as $element) {
                $cost = $element->sup->average_cost == 0 ? $element->sup->initial_cost : $element->sup->average_cost;

                $data[] = array(
                    $element->sup->supply_key,
                    $element->supply,
                    '$' . number_format($cost, 2, '.', ','),
                    $element->measurement_unit->measure,
                    $element->stock_quantity,
                    $element->counted_quantity,
                    $element->counted_quantity - $element->stock_quantity,
                    '$' . number_format(($element->counted_quantity - $element->stock_quantity) * $cost, 2, '.', ','),
                    $element->comments,
                );
            }

            $sheets[] = new CycleCountPerLocationSheet($supply_location->location, $data);
        }

        return $sheets;
    }
}
