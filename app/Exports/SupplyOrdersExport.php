<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SupplyOrdersExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
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
            'No Orden',
            'Clave Proveedor',
            'Clave',
            'Materia Prima',
            'Unidad de Medida',
            'Cantidad Ordenada',
            'Cantidad Recibida',
            'Costo',
            'Total',
            'Status',
            'Fecha Pedido',
        );
    }

    public function array(): array
    {        
        $data = array();
        $total = 0;

        $query = DB::table('supply_order_details AS sod')
            ->join('supplies AS s', 'sod.supply_id', '=', 's.id') 
            ->join('supply_orders AS so', 'sod.supply_order_id', '=', 'so.id')
            ->join('measurement_units AS m', 'sod.measurement_unit_id', '=', 'm.id')
            ->join('suppliers AS su', 'so.supplier_id', '=', 'su.id')
            ->select('sod.supply_order_id', 'sod.supply', 'sod.quantity', 'sod.cost', 'sod.received_quantity', 'sod.supply_id', 'sod.measurement_unit_id', 's.supply_key', 'so.status', 'so.created_at', 'm.measure', 'su.supplier_key')
            ->where('s.supply_key', $this->supply_key)
            ->whereDate('so.created_at', '>=', $this->from_date);

        if(!empty($this->to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $this->to_date);
        }

        $supply_orders = $query->orderBy('sod.created_at', 'asc')->get();
            
        foreach($supply_orders as $element) {
            $total += $element->cost * $element->quantity;

            $data[] = array(
                $element->supply_order_id,
                $element->supplier_key,
                $element->supply_key,
                $element->supply,
                $element->measure,
                $element->quantity,
                $element->received_quantity,
                '$' . number_format($element->cost, 2, '.', ','),
                '$' . number_format($element->cost * $element->quantity, 2, '.', ','), 
                $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado'),
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
