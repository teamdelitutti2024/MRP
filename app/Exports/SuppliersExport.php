<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuppliersExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return array(
            'Clave',
            'Nombre',
            'RFC',
            'Teléfono',
            'Correo',
            'Contacto',
            'Categoría',
            'Método de pago',
        );
    }

    public function array(): array
    {
        $data = array();

        $suppliers = DB::table('suppliers AS s')
            ->leftJoin('supplier_tax_data AS st','s.id','=','st.supplier_id')
            ->leftJoin('supplier_contacts AS sc','s.id','=','sc.supplier_id')
            ->select('s.id', 's.name', 's.supplier_key', 's.address', 's.preferred_payment_method', 'st.rfc', 'st.supplier_id', 'sc.name as contact', 'sc.phone', 'sc.email')
            ->orderBy('name', 'asc')
            ->get();

        foreach($suppliers as $element) {
            // Get supplies categories for each supplier
            $supplies_categories = DB::table('suppliers AS s')
                ->join('supplier_supplies AS ss', 's.id', '=', 'ss.supplier_id')
                ->join('supplies AS su', 'ss.supply_id', '=', 'su.id')
                ->join('supply_categories AS sc', 'su.supply_category_id', '=', 'sc.id')
                ->select('sc.name')
                ->where('s.id', $element->id)
                ->distinct()
                ->get()
                ->toArray();

            $element->categories = $supplies_categories;

            $data[] = array(
                $element->supplier_key,
                $element->name,
                $element->rfc,
                $element->phone,
                $element->email,
                $element->contact,
                implode(', ', array_column($element->categories, 'name')),
                config('dictionaries.preferred_payment_methods.' . $element->preferred_payment_method),
            );
        }

        return $data;
    }
}
