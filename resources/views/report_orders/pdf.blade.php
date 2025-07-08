<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Reporte de Órdenes de Compra') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('No. Orden') }}</th>
                    <th>{{ __('Clave proveedor') }}</th>
                    <th>{{ __('Proveedor') }}</th>
                    <th>{{ __('Fecha pedido') }}</th>
                    <th>{{ __('Fecha entrega') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($supply_orders as $element)
                    @php $total += $element->total; @endphp
                    <tr>
                        <td>{{ $element->id }}</a></td>
                        <td>{{ $element->supplier->supplier_key }}</td>
                        <td>{{ $element->supplier->name }}</td>
                        <td>{{ date('d-m-Y', strtotime($element->created_at)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($element->delivery_date)) }}</td>
                        <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                        <td>{{ $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h3>
    </body>
</html>