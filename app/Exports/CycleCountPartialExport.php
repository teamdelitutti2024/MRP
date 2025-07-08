<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\CycleCount;
use App\Models\Stock;

class CycleCountPartialExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $cycle_count_id;

    public function __construct($cycle_count_id)
    {
        $this->cycle_count_id = $cycle_count_id;
    }

    public function headings(): array
    {
        return array(
            'Clave',
            'Materia prima',
            'Ubicación',
            'Costo promedio',
            'Unidad de medida',
            'Cantidad en stock',
            'Cantidad contabilizada',
            'Diferencia',
            'Monto diferencia',
            'Comentarios',
        );
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $cycle_count = CycleCount::find($this->cycle_count_id);

        $data = array();

        foreach($cycle_count->cycle_count_details as $element) {
            $cost = $element->sup->average_cost == 0 ? $element->sup->initial_cost : $element->sup->average_cost;

            // Get related stock
            $supply_stock = Stock::where('supply_id', $element->supply_id)->where('supply_location_id', $element->supply_location_id)->first();

            $data[] = array(
                $element->sup->supply_key,
                $element->supply,
                $element->supply_location->location,
                '$' . number_format($cost, 2, '.', ','),
                $element->measurement_unit->measure,
                $supply_stock->quantity,
                $element->counted_quantity,
                $element->counted_quantity - $supply_stock->quantity,
                '$' . number_format(($element->counted_quantity - $supply_stock->quantity) * $cost, 2, '.', ','),
                $element->comments,
            );
        }

        return $data;
    }
}
