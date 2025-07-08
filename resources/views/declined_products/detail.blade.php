@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de merma de producto') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $declined_product->responsible_id) }}">{{ $declined_product->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Status') }}: {{ $declined_product->status ? __('Activa') : __('Revertida') }}</li>
        @if(!$declined_product->status)
            <li class="list-group-item">{{ __('Responsable de merma revertida') }}: <a href="{{ url('/users/detail/' . $declined_product->reversed_responsible_id) }}">{{ $declined_product->reversed_responsible->name }}</a></li>
        @endif
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $declined_product->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Cantidad merma') }}</th>
                    <th>{{ __('Fecha de producto mermado') }}</th>
                    <th>{{ __('Precio') }}</th>
                    <th>{{ __('Comentarios') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $declined_product->product_size->product_size_key }}</td>
                    <td><a href="{{ url('/products/detail/' . $declined_product->product_id) }}">{{ $declined_product->product_name }}</a></td>
                    <td><a href="{{ url('/product_sizes/detail/' . $declined_product->product_size_id) }}">{{ $declined_product->product_size_name }}</a></td>
                    <td>{{ $declined_product->quantity }}</td>
                    <td>{{ $declined_product->product_date }}</td>
                    <td>${{ number_format($declined_product->price, 2, '.', ',') }}</td>
                    <td>{{ $declined_product->comments }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection