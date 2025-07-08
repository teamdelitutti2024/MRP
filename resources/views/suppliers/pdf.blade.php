<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h1 class="title">{{ __('Proveedores Delitutti') }}</h1>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Dirección') }}</th>
                    <th>{{ __('Tiempo de entrega (no. días)') }}</th>
                    <th>{{ __('Método preferido de pago') }}</th>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Requiere factura') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier['supplier_key'] }}</td>
                        <td>{{ $supplier['name'] }}</td>
                        <td>{{ $supplier['address'] }}</td>
                        <td>{{ $supplier['delivery_time'] }}</td>
                        <td>{{ __(config('dictionaries.preferred_payment_methods.' . $supplier['preferred_payment_method'])) }}</td>
                        <td>{{ $supplier['category'] }}</td>
                        <td>{{ __(config('dictionaries.common_answers.' . $supplier['require_invoice'])) }}</td>
                        <td>{{ $supplier['created'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>