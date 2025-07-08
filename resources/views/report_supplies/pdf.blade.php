<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h1 class="title">{{ __('Reporte de Materias Primas') }}</h1>
        <table>
            <thead>
                <tr>
                  <th>{{ __('Clave') }}</th>
                  <th>{{ __('Nombre') }}</th>
                  <th>{{ __('Categoría') }}</th>
                  <th>{{ __('Unidad de medida') }}</th>
                  <th>{{ __('Standard pack') }}</th>
                  <th>{{ __('Costo promedio') }}</th>    
                </tr>
            </thead>
            <tbody>
                @foreach($supplies as $supply)
                    <tr>
                        <td>{{ $supply->supply_key }}</td>
                        <td>{{ $supply->name }}</td>
                        <td>{{ $supply->category }}</td>
                        <td>{{ $supply->measure }}</td>
                        <td>{{ $supply->standard_pack }}</td>
                        <td>${{ number_format($supply->average_cost, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>