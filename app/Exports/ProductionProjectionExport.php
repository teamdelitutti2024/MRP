<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\SuppliesHelper;
use App\ProductsHelper;

class ProductionProjectionExport implements FromArray, ShouldAutoSize
{
    use Exportable;

    protected $production;
    protected $products;
    protected $type;

    public function __construct($production, $products, $type)
    {
        $this->production = $production;
        $this->products = $products;
        $this->type = $type;
    }

    public function array(): array
    {
        $products_result = $supplies_result = $products = array();

        $production_heading = $this->type == 1 ? 'Producto' : 'Base';

        // First heading
        $products_result[] = array(
            $production_heading,
            'Cantidad',
        );

        // Second heading
        $supplies_result[] = array(
            'Clave',
            'Recurso',
            'Cantidad',
            'Ubicación',
            'Unidad de medida',
            'Costo promedio',
            'Total'
        );

        $total_supplies = 0;

        switch($this->type) {
            case 2:
                $products = ProductsHelper::get_bake_breads_list_from_production($this->products);
                break;

            default:
                $products = ProductsHelper::get_products_list_from_production($this->products);
                break;
        }

        foreach($products as $element) {
            $products_result[] = array(
                $element['product'],
                $element['quantity'],
            );
        }

        $space = array(
            0 => array(),
        );

        foreach($this->production->production_supplies as $element) {
            $total_supplies += $element->average_cost * $element->quantity;

            $supplies_result[] = array(
                $element->supply_key,
                $element->supply,
                $element->quantity,
                $element->supply_location,
                $element->measure,
                '$' . $element->average_cost,
                '$' . number_format($element->average_cost * $element->quantity, 2, '.', ','),
            );
        }

        $total_supplies_result = array(
            0 => array(),
			1 => array('Total', '$' . number_format($total_supplies, 2, '.', ',')),
        );

        return array(
            $products_result,
            $space,
            $supplies_result,
            $total_supplies_result,
        );
    }
}
