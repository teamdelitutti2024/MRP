<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\SuppliesHelper;
use App\Models\SupplyOrder;

class SupplyReceptionsExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
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
            'Clave',
            'Materia prima',
            'Unidad de medida',
            'Fecha de entrega',
            'Cantidad a recibir',
            'Costo promedio',
            'Total',
        );
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $query = SupplyOrder::whereIn('status', [3, 4, 5])->where('delivery_date', '>=', $this->from_date);

        if(!empty($this->to_date)) {
            $query = $query->where('delivery_date', '<=', $this->to_date);
        }

        $supply_orders = $query->get();

        $supplies = SuppliesHelper::get_supplies_from_supply_orders($supply_orders);

        $data = array();
        $total = 0;

        foreach($supplies as $element) {
            $total += $element['cost'] * $element['quantity'];

            $data[] = array(
                $element['supply_key'],
                $element['supply'],
                $element['measure'],
                $element['delivery_date'],
                $element['quantity'],
                '$' . number_format($element['cost'], 2, '.', ','),
                '$' . number_format($element['cost'] * $element['quantity'], 2, '.', ','),
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
