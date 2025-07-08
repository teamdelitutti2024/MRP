<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SupplierOrdersExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    protected $from_date;
    protected $to_date;
    protected $supplier_key;

    public function __construct($from_date, $to_date, $supplier_key)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supplier_key = $supplier_key;
    }

    /**
     * headings
     *
     * @return array
     */
    public function headings(): array
    {
        return array(
            'No Orden',
            'Clave Proveedor',
            'Proveedor',
            'Total Pedido',
            'Status',
            'Fecha Pedido',
        );
    }

    public function array(): array
    {        
        $data = array();
        $total = 0;

        $query = DB::table('supply_orders AS so')
            ->join('suppliers AS s', 'so.supplier_id', '=', 's.id') 
            ->select('so.id', 'so.total', 'so.status', 'so.created_at', 'so.supplier_id', 's.name', 's.supplier_key')
            ->where('s.supplier_key', $this->supplier_key)
            ->whereDate('so.created_at', '>=', $this->from_date);

        if(!empty($this->to_date)) {
            $query = $query->whereDate('so.created_at', '<=', $this->to_date);
        }

        $supplier_orders = $query->orderBy('so.created_at', 'asc')->get();
            
        foreach($supplier_orders as $element) {
            $total += $element->total;

            $data[] = array(
                $element->id,
                $element->supplier_key,
                $element->name,
                '$' . number_format($element->total, 2, '.', ','),
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
