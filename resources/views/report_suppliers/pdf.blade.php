<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h1 class="title">{{ __('Reporte de Proveedores') }}</h1>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('RFC') }}</th>
                    <th>{{ __('Teléfono') }}</th>
                    <th>{{ __('Correo') }}</th>
                    <th>{{ __('Contacto') }}</th>
                    <th>{{ __('Categorías') }}</th>
                    <th>{{ __('Método de pago') }}</th>     
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->supplier_key }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->rfc }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->contact }}</td>
                        <td>{{ implode(', ', array_column($supplier->categories, 'name')) }}</td>
                        <td>{{ config('dictionaries.preferred_payment_methods.' . $supplier->preferred_payment_method) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>