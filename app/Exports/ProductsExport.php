<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return array(
            'Clave',
            'Tamaño',
            'Producto',
            'Precio',
            'Complejidad',
            'Status',
        );
    }

    public function array(): array
    {
        $data = array();

        $products = DB::table('product_sizes')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->select('product_sizes.*', 'products.name as product_name', 'products.status')
            ->get();

        foreach($products as $element) {
            $data[] = array(
                $element->product_size_key,
                $element->name,
                $element->product_name,
                '$' . number_format($element->sale_price, 2, '.', ','),
                config('dictionaries.product_complexities.' . $element->complexity),
                config('dictionaries.common_status.' . $element->status),
            );
        }

        return $data;
    }
}
