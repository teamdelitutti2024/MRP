<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\SupplyOrder;

class OrdersExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $from_date;
    protected $to_date;

    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function headings(): array
    {
        return array(
            'No Orden',
            'Clave Proveedor',
            'Proveedor',
            'Fecha Pedido',
            'Fecha Entrega',
            'Total Pedido',
            'Status',
        );
    }

    public function array(): array
    {
        $data = array();
        $total = 0;

        $query = SupplyOrder::whereDate('created_at', '>=', $this->from_date);

        if(!empty($to_date)) {
            $query = $query->whereDate('created_at', '<=', $this->to_date);
        }

        $supply_orders = $query->orderBy('created_at', 'asc')->get();

        foreach($supply_orders as $element) {
            $total += $element->total;

            $data[] = array(
                $element->id,
                $element->supplier->supplier_key,
                $element->supplier->name,
                date('d-m-Y', strtotime($element->created_at)),    
                date('d-m-Y', strtotime($element->delivery_date)),  
                '$' . number_format($element->total, 2, '.', ','),
                $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado'),
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
