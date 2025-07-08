@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Productos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos') }}</h6>
        <a href="{{ url('/reports/products/export') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
        <a href="{{ url('/reports/products/download') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
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
                @foreach($products as $element)
                    <tr>
                        <td><a href="{{ url('/product_sizes/detail/' . $element->id) }}">{{ $element->product_size_key }}</a></td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->product_name }}</td>
                        <td>${{ number_format($element->sale_price, 2, '.', ',') }}</td>
                        <td>{{ __(config('dictionaries.product_complexities.' . $element->complexity)) }}</td>
                        <td>{{ __(config('dictionaries.common_status.' . $element->status)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection