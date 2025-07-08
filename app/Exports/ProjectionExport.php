<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\SuppliesHelper;
use App\ProductsHelper;

class ProjectionExport implements FromArray, ShouldAutoSize
{
    use Exportable;

    protected $products;
    protected $type;

    public function __construct($products, $type)
    {
        $this->products = $products;
        $this->type = $type;
    }

    public function array(): array
    {
        $products_result = $supplies_result = $products = $supplies = array();

        switch($this->type) {
            case 1:
                $production_heading = 'Producto';
                break;

            case 2:
                $production_heading = 'Base';
                break;

            case 3:
                $production_heading = 'Preparado';
                break;
            
            default:
                $production_heading = '';
                break;
        }

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
            'Cantidad disponible',
            'Unidad de medida',
            'Costo promedio',
            'Total'
        );

        $total_supplies = 0;

        switch($this->type) {
            case 2:
                $products = ProductsHelper::get_bake_breads_list($this->products);
                $supplies = SuppliesHelper::calculate_bake_breads_supplies($this->products);
                break;

            case 3:
                $products = ProductsHelper::get_prepared_products_list($this->products);
                $supplies = SuppliesHelper::calculate_prepared_products_supplies($this->products);
                break;

            default:
                $products = ProductsHelper::get_products_list($this->products);
                $supplies = SuppliesHelper::calculate_products_supplies($this->products);
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

        foreach($supplies as $element) {
            $total_supplies += $element['average_cost'] * $element['quantity'];

            $supplies_result[] = array(
                $element['supply_key'],
                $element['supply'],
                $element['quantity'],
                $element['available_quantity'],
                $element['measure'],
                '$' . $element['average_cost'],
                '$' . number_format($element['average_cost'] * $element['quantity'], 2, '.', ','),
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
