<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Reporte de Recepciones') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Fecha de entrega') }}</th>
                    <th>{{ __('Cantidad a recibir') }}</th>
                    <th>{{ __('Costo promedio') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($supplies as $element)
                    @php $total += $element['cost'] * $element['quantity']; @endphp
                    <tr>
                        <td>{{ $element['supply_key'] }}</td>
                        <td>{{ $element['supply'] }}</td>
                        <td>{{ $element['measure'] }}</td>
                        <td>{{ $element['delivery_date'] }}</td>
                        <td>{{ $element['quantity'] }}</td>
                        <td>${{ number_format($element['cost'], 2, '.', ',') }}</td>
                        <td>${{ number_format($element['cost'] * $element['quantity'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h1 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h1>
    </body>
</html>