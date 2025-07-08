<?php

namespace App\Imports;

use App\Models\Stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Supply;
use App\Models\SupplyLocation;

class StockImport implements ToCollection, WithHeadingRow
{
    private $errors = array();
    private $success_count = 0;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $count = 1;

        foreach($rows as $row) {
            $supply = Supply::where('supply_key', $row['supply_key'])->where('is_active', true)->first();

            $count++;

            if(!$supply) {
                $this->errors[] = 'No se encontró la materia prima con clave "' . $row['supply_key'] . '" en la fila "' . $count . '", verifica que la materia prima exista y esté activa';
                continue;
            }

            $supply_location = SupplyLocation::where('location', $row['location'])->first();

            if(!$supply_location) {
                $this->errors[] = 'No se encontró la ubicación "' . $row['location'] . '" en la fila "' . $count . '"';
                continue;
            }

            if(!preg_match('/^(\d{1,7})(\.\d{1,3})?$/', $row['quantity'])) {
                $this->errors[] = 'La cantidad "' . $row['quantity'] . '" en la fila "' . $count . '" no cumple con el formato establecido';
                continue;
            }

            // Update stock
            Stock::where('supply_id', $supply->id)->where('supply_location_id', $supply_location->id)->update(['quantity' => $row['quantity']]);
            $this->success_count++;
        }
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function get_success_count()
    {
        return $this->success_count;
    }
}
