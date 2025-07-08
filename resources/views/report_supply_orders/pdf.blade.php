<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Órdenes de Compra por Materia Prima') }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('No. Orden') }}</th>
                        <th>{{ __('Clave proveedor') }}</th>
                        <th>{{ __('Clave materia prima') }}</th>
                        <th>{{ __('Materia prima') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Cantidad solicitada') }}</th>
                        <th>{{ __('Cantidad recibida') }}</th>
                        <th>{{ __('Costo') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Fecha pedido') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($supply_orders as $element)
                        @php $total += $element->cost * $element->quantity; @endphp
                        <tr>
                            <td>{{ $element->supply_order_id }}</td>
                            <td>{{ $element->supplier_key }}</td>
                            <td>{{ $element->supply_key }}</td>
                            <td>{{ $element->supply }}</td>
                            <td>{{ $element->measure }}</td>
                            <td>{{ $element->quantity }}</td>
                            <td>{{ $element->received_quantity }}</td>
                            <td>${{ number_format($element->cost, 2, '.', ',') }}</td>
                            <td>${{ number_format(($element->cost * $element->quantity), 2, '.', ',') }}</td>
                            <td>{{ $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado') }}</td>
                            <td>{{ date('d-m-Y', strtotime($element->created_at)) }}</td>                        
                        </tr>
                    @endforeach                 
                </tbody>
            </table>
            <h3 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h3>
    </body>
</html>