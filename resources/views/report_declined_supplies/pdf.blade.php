<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Reporte de Mermas') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad merma') }}</th>
                    <th>{{ __('Costo total') }}</th>
                    <th>{{ __('Razón') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($declined as $element)
                    @php $total += $element->transaction_amount; @endphp
                    <tr>
                        <td>{{ $element->category }}</td>
                        <td>{{ $element->supply_key }}</td>
                        <td>{{ $element->supply }}</td>
                        <td>{{ $element->measure }}</td>
                        <td>{{ $element->lost_quantity }}</td>
                        <td>${{ number_format($element->transaction_amount, 2, '.', ',')}}</td>
                        <td>{{ $element->reason }}</td>
                        <td>{{ date('d-m-Y', strtotime($element->created_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <h3 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h3>
        </table>           
    </body>
</html>