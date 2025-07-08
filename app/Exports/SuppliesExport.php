<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuppliesExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function headings(): array
    {
        return array(
            'Clave',
            'Nombre',
            'Categoría',
            'Unidad de medida',
            'Standard pack',
            'Costo promedio',
        );
    }

    public function array(): array
    {
        $data = array();

        $query = DB::table('supplies')
            ->join('supply_categories', 'supplies.supply_category_id', '=', 'supply_categories.id')
            ->join('measurement_units', 'supplies.measurement_unit_id', '=', 'measurement_units.id')
            ->select('supplies.supply_key', 'supplies.name', 'supply_categories.name as category', 'measurement_units.measure as measure', 'supplies.standard_pack', 'supplies.average_cost');

        if(!empty($this->status) && $this->status != 'All') {
            $this->status == 1 ? $query->where('supplies.is_active', true) : $query->where('supplies.is_active', false);
        }

        $supplies = $query->orderBy('supplies.name', 'asc')->get();

        foreach($supplies as $element) {
            $data[] = array(
                $element->supply_key,
                $element->name,
                $element->category,
                $element->measure,
                $element->standard_pack,
                '$' . number_format($element->average_cost, 2, '.', ','),
            );
        }

        return $data;
    }
}
