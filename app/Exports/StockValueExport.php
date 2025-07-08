<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Stock;

class StockValueExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $category;
    protected $location;
    protected $supply_key;
    protected $qty_search;

    public function __construct($category, $location, $supply_key, $qty_search)
    {
        $this->category = $category;
        $this->location = $location;
        $this->supply_key = $supply_key;
        $this->qty_search = $qty_search;
    }

    public function headings(): array
    {
        return array(
            'Categoría',
            'Clave',
            'Materia Prima',
            'Costo Unitario',
            'Cantidad',
            'Unidad de Medida',
            'Costo Total',
            'Ubicación',
        );
    }

    public function array(): array
    {
        $data = array();
        $total = 0;

        $query = DB::table('stock AS s')
            ->join('supplies AS su', 's.supply_id', '=', 'su.id')
            ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
            ->join('supply_locations AS sl', 's.supply_location_id', '=', 'sl.id')
            ->join('measurement_units AS m', 'su.measurement_unit_id', '=', 'm.id')
            ->select('s.quantity', 's.supply_id', 's.supply_location_id', 'su.supply_key', 'su.name', 'su.measurement_unit_id', 'su.average_cost', 'sc.name as cat_name', 'sl.location', 'm.measure');

        switch($this->qty_search) {
            case 1:
                $query->where('s.quantity', '>', 0);
                break;
            case 2:
                $query->where('s.quantity', '<', 0);
                break;
            default:
                // Do nothing
                break;
        }

        if(!empty($this->supply_key)) {
            $query->where('su.supply_key', $this->supply_key);
        }

        if(!empty($this->category) && $this->category != 'All') {
            $query->where('su.supply_category_id', $this->category);
        }

        if(!empty($this->location) && $this->location != 'All') {
            $query->where('s.supply_location_id', $this->location);
        }

        $stock = $query->orderBy('cat_name', 'asc')->get();
            
        foreach($stock as $element) {
            $total += $element->quantity * $element->average_cost;

            $data[] = array(
                $element->cat_name,
                $element->supply_key,
                $element->name,
                '$' . number_format($element->average_cost, 2, '.', ','),
                $element->quantity,
                $element->measure,
                '$' . number_format($element->quantity * $element->average_cost, 2, '.', ','),
                $element->location,
            );
        }

        $space = array(
            0 => array(),
        );

        $totals = array(
            0 => array('Total', '$' . number_format($total, 2, '.', ',')),
        );

        return array(
            $data,
            $space,
            $totals,
        );
    }
}
