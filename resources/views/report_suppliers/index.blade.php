@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Proveedores') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Proveedores') }}</h6>
        <a href="{{ url('/reports/suppliers/export') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
        <a href="{{ url('/reports/suppliers/download') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
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
                @foreach($suppliers as $element)
                    <tr>
                        <td><a href="{{ url('/suppliers/detail/' . $element->id) }}">{{ $element->supplier_key }}</a></td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->rfc }}</td>
                        <td>{{ $element->phone }}</td>
                        <td>{{ $element->email }}</td>
                        <td>{{ $element->contact }}</td>
                        <td>{{ implode(', ', array_column($element->categories, 'name')) }}</td>
                        <td>{{ config('dictionaries.preferred_payment_methods.' . $element->preferred_payment_method) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection