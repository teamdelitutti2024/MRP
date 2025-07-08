@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de pedido de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/supply_orders') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Pedido de materia prima') }}: #{{ $supply_order->id }}</h6>
        <a href="{{ url('/suppliers/detail/' . $supply_order->supplier_id) }}"><span class="pull-right label label-info">{{ $supply_order->supplier->name }}</span></a>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($supply_order->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Tipo') }}: {{ __(config('dictionaries.supply_orders_types.' . $supply_order->type)) }}</li>
        <li class="list-group-item">{{ __('Fecha de creación') }}: {{ $supply_order->created_at }}</li>
        <li class="list-group-item">{{ __('Fecha de entrega') }}: {{ $supply_order->delivery_date }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.supply_orders_status.' . $supply_order->status)) }}</li>
        <li class="list-group-item">{{ __('Condición comercial') }}: <a href="{{ $supply_order->commercial_term_default ? url('/commercial_terms/detail/' . $supply_order->supplier->commercial_term->id) : url('/commercial_terms/detail/' . $supply_order->commercial_term_id) }}">{{ $supply_order->commercial_term_default ? $supply_order->supplier->commercial_term->name : $supply_order->commercial_term->name }}</a></li>
        <li class="list-group-item">{{ __('Requiere factura') }}: {{ __(config('dictionaries.common_answers.' . $supply_order->require_invoice)) }}</li>
        <li class="list-group-item">{{ __('Método preferido de pago') }}: {{ __(config('dictionaries.preferred_payment_methods.' . $supply_order->preferred_payment_method)) }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $supply_order->responsible_id) }}">{{ $supply_order->responsible->name }}</a></li>
    </ul>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle de pedido') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Precio de lista') }}</th>
                    <th>{{ __('Costo') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_order->supply_order_details as $element)
                    <tr>
                        <td><a href="{{ url('/supplies/detail/' . $element->supply_id) }}">{{ $element->supply }}</a></td>
                        <td><a href="{{ url('/measurement_units/detail/' . $element->measurement_unit_id) }}">{{ $element->measurement_unit->measure }}</a></td>
                        <td>{{ $element->quantity }}</td>
                        <td>${{ number_format($element->price, 2, '.', ',') }}</td>
                        <td>${{ number_format($element->cost, 2, '.', ',') }}</td>
                        <td>${{ number_format($element->quantity * $element->cost, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection