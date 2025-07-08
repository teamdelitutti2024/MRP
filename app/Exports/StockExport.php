<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $category;
    protected $location;
    protected $supply_key;

    public function __construct($category, $location, $supply_key)
    {
        $this->category = $category;
        $this->location = $location;
        $this->supply_key = $supply_key;
    }

    public function headings(): array
    {
        return array(
            'Categoría',
            'Clave',
            'Materia prima',
            'Unidad de medida',
            'Cantidad',
            'Ubicación',
        );
    }

    public function array(): array
    {
        $data = array();

        $query = DB::table('stock AS s')
            ->join('supplies AS su', 's.supply_id', '=', 'su.id')
            ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
            ->join('supply_locations AS sl', 's.supply_location_id', '=', 'sl.id')
            ->join('measurement_units AS m', 'su.measurement_unit_id', '=', 'm.id')
            ->select('s.quantity', 's.supply_id', 's.supply_location_id', 'su.supply_key', 'su.name', 'su.measurement_unit_id', 'su.average_cost', 'sc.name as cat_name', 'sl.location', 'm.measure');

        if(!empty($this->supply_key)) {
            $query->where('su.supply_key', $this->supply_key);
        }

        if(!empty($this->category) && $this->category != 'All') {
            $query->where('su.supply_category_id', $this->category);
        }

        if(!empty($this->location)) {
            $query->where('s.supply_location_id', $this->location);
        }

        $stock = $query->orderBy('cat_name', 'asc')->get();

        foreach($stock as $element) {
            $data[] = array(
                $element->cat_name,
                $element->supply_key,
                $element->name,
                $element->measure,
                $element->quantity,
                $element->location,
            );
        }

        return $data;
    }
}
