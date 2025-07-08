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
        <h2 class="title">{{ __('Pedido No. ' . $order['id']) }}</h2>
        <p>{{ __('Total') }}: ${{ number_format($order['total'], 2, '.', ',') }}</p>
        <p>{{ __('Fecha creación') }}: {{ $order['created_at'] }}</p>
        <p>{{ __('Fecha entrega') }}: {{ $order['delivery_date'] }}</p>
        <p>{{ __('Sucursal') }}: {{ $order['branch'] }}</p>
        <p>{{ __('Status') }}: {{ config('dictionaries.order_status.' . $order['status']) }}</p>
        <h3>{{ __('Detalle de productos') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Precio') }}</th>
                    <th>{{ __('Cantidad solicitada') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $element)
                    <tr>
                        <td>{{ $element['product_key'] }}</td>
                        <td>{{ $element['product'] }}</td>
                        <td>{{ $element['size'] }}</td>
                        <td>${{ number_format($element['price'], 2, '.', ',') }}</td>
                        <td>{{ $element['quantity'] }}</td>
                        <td>${{ number_format($element['total_price'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>