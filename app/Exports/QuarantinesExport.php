<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuarantinesExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $from_date;
    protected $to_date;
    protected $supply_key;

    public function __construct($from_date, $to_date, $supply_key)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supply_key = $supply_key;
    }

    public function headings(): array
    {
        return array(
            'Categoría',
            'Clave',
            'Materia Prima',
            'Unidad de Medida',
            'Cantidad en Cuarentena',
            'Costo Total',
            'Ubicación',
            'Razón',
            'Fecha Creación',
        );
    }

    public function array(): array
    {
        $data = array();
        $total = 0;

        $query = DB::table('quarantines AS q')
            ->join('supplies AS s', 'q.supply', '=', 's.name')
            ->join('measurement_units AS m', 'q.measurement_unit_id', '=', 'm.id')
            ->join('supply_locations AS sl', 'q.supply_location_id', '=', 'sl.id')
            ->select('q.supply', 'q.transaction_amount', 'q.quantity', 'q.category', 'q.reason', 'q.created_at', 'q.measurement_unit_id', 'q.supply_location_id', 'm.measure', 'sl.location', 's.supply_key', 's.average_cost')
            ->whereDate('q.created_at', '>=', $this->from_date);
        
        if(!empty($this->to_date)) {
            $query->whereDate('q.created_at', '<=', $this->to_date);
        }

        if(!empty($this->supply_key)) {
            $query->where('s.supply_key', $this->supply_key);
        }

        $quarantines = $query->orderBy('q.created_at', 'asc')->get();
            
        foreach($quarantines as $element) {
            $total += $element->transaction_amount;

            $data[] = array(
                $element->category,
                $element->supply_key,
                $element->supply,
                $element->measure,
                $element->quantity,
                '$' . number_format($element->transaction_amount, 2, '.', ','),
                $element->location,
                $element->reason,
                date('d-m-Y', strtotime($element->created_at)),
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
