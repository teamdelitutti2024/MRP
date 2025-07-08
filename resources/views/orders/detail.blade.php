@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de pedido') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('ID') . ': ' . $order->id }}</h6>
        @if(!$order->departure_shipment)
            <a href="{{ url('/departure_shipments/add/' . $order->id) }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar embarque de salida') }}</a>
        @else
            <a href="{{ url('/departure_shipments/detail/' . $order->departure_shipment->id) }}" class="pull-right btn btn-xs btn-success">{{ __('Detalle embarque de salida') }}</a>
        @endif
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($order->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Fecha entrega') }}: {{ $order->delivery_date }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.order_status.' . $order->status)) }}</li>
        <li class="list-group-item">{{ __('Sucursal') }}: <a href="{{ url('/branches/detail/' . $order->branch_id) }}">{{ $order->branch->name }}</a></li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $order->created }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos solicitados') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Precio unidad') }}</th>
                    <th>{{ __('Cantidad solicitada') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_products as $element)
                    <tr>
                        <td>{{ $element->product_size->product_size_key }}</td>
                        <td><a href="{{ url('/products/detail/' . $element->product_id) }}" target="blank">{{ $element->product->name }}</a></td>
                        <td>{{ $element->product_size->name }}</td>
                        <td>${{ number_format($element->price, 2, '.', ',') }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>${{ number_format($element->total_price, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection