<!DOCTYPE html>
    <head>
        <style>
            .title {text-align: center;}
            table {width: 100%}
            table, th, td {border: 1px solid #000; border-collapse: collapse;}
        </style>
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('Pedido de materia prima No. ' . $supply_order['id']) }}</h2>
        <p>{{ __('Total') }}: ${{ number_format($supply_order['total'], 2, '.', ',') }}</p>
        <p>{{ __('Tipo') }}: {{ config('dictionaries.supply_orders_types.' . $supply_order['type']) }}</p>
        <p>{{ __('Fecha creación') }}: {{ $supply_order['created_at'] }}</p>
        <p>{{ __('Fecha entrega') }}: {{ $supply_order['delivery_date'] }}</p>
        <p>{{ __('Status') }}: {{ config('dictionaries.supply_orders_status.' . $supply_order['status']) }}</p>
        <p>{{ __('Condición comercial') }}: {{ $supply_order['commercial_term']->name }}</p>
        <p>{{ __('Requiere factura') }}: {{ config('dictionaries.common_answers.' . $supply_order['require_invoice']) }}</p>
        <p>{{ __('Método preferido de pago') }}: {{ config('dictionaries.preferred_payment_methods.' . $supply_order['preferred_payment_method']) }}</p>
        <p>{{ __('Responsable') }}: {{ $supply_order['responsible'] }}</p>
        <p>{{ __('Proveedor') }}: {{ $supply_order['supplier']->name }}</p>
        <h3>{{ __('Detalle de materias primas solicitadas') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Costo') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplies as $element)
                    <tr>
                        <td>{{ $element['supply_key'] }}</td>
                        <td>{{ $element['supply'] }}</td>
                        <td>{{ $element['measurement_unit'] }}</td>
                        <td>${{ number_format($element['cost'], 2, '.', ',') }}</td>
                        <td>{{ $element['quantity'] }}</td>
                        <td>${{ number_format($element['total'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>