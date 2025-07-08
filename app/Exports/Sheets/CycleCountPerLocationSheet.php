<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CycleCountPerLocationSheet implements FromArray, WithHeadings, WithTitle, WithStrictNullComparison, ShouldAutoSize
{
    private $title;
    private $data;

    public function __construct(string $title, array $data)
    {
        $this->title = $title;
        $this->data = $data;
    }

    /**
     * title
     * 
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * headings
     *
     * @return array
     */
    public function headings(): array
    {
        return array(
            'Clave',
            'Materia prima',
            'Costo promedio',
            'Unidad de medida',
            'Cantidad en stock',
            'Cantidad contabilizada',
            'Diferencia',
            'Monto diferencia',
            'Comentarios',
        );
    }

    /**
     * data
     *
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }
}