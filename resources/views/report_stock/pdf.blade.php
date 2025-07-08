<!DOCTYPE html>
<html>
    <head>    
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Reporte de Cantidades de Inventario') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Clave')}}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stock as $element)
                    <tr>
                        <td>{{ $element->cat_name }}</td>
                        <td>{{ $element->supply_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->measure }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->location }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>