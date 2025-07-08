<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h1 class="title">{{ __('Reporte de Productos') }}</h1>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Precio') }}</th>
                    <th>{{ __('Complejidad') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->product_size_key }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>${{ number_format($product->sale_price, 2, '.', ',') }}</td>
                        <td>{{ __(config('dictionaries.product_complexities.' . $product->complexity)) }}</td>
                        <td>{{ __(config('dictionaries.common_status.' . $product->status)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>