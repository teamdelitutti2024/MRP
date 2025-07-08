@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Recepciones de materia prima') }}</h5>
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
    <div class="panel-body">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($supply_order->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.supply_orders_status.' . $supply_order->status)) }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $supply_order->responsible_id) }}">{{ $supply_order->responsible->name }}</a></li>
    </ul>
    <br>
    <ul class="list-group">
    @foreach($supply_order->supply_order_details as $element)
        <li class="list-group-item">{{ $element->supply . ', ' . $element->quantity . ', ' . $element->measurement_unit->measure }}</li>
    @endforeach
    </ul>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Recepciones de materia prima') }}</h6>
        @if (config('dictionaries.supply_orders_status.' . $supply_order->status) != 'Recibido')
            <a href="{{ url('/supply_receptions/add/' . $supply_order->id) }}"><span class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar recepción de materia prima') }}</span></a>
        @endif
    </div>
    <div class="panel-body">
        @foreach($supply_order->supply_receptions as $reception)
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h6 class="panel-title">{{ __('Recepción de materia prima') }}: #{{ $reception->id }}</h6>
                    <a href="{{ url('/supply_receptions/detail/' . $reception->id) }}"><span class="pull-right btn btn-xs btn-success">{{ __('Detalle') }}</span></a>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">{{ __('Código') }}: {{ $reception->code }}</li>
                        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($reception->total, 2, '.', ',') }}</li>
                    </ul>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Materia prima') }}</th>
                                    <th>{{ __('Unidad de medida') }}</th>
                                    <th>{{ __('Cantidad recibida') }}</th>
                                    <th>{{ __('Costo') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reception->supply_reception_details as $element)
                                <tr>
                                    <td><a href="{{ url('/supplies/detail/' . $element->supply_order_detail->supply_id) }}">{{ $element->supply_order_detail->supply }}</a></td>
                                    <td><a href="{{ url('/measurement_units/detail/' . $element->supply_order_detail->measurement_unit_id) }}">{{ $element->supply_order_detail->measurement_unit->measure }}</a></td>
                                    <td>{{ $element->quantity }}</td>
                                    <td>${{ number_format($element->supply_order_detail->cost, 2, '.', ',') }}</td>
                                    <td>${{ number_format($element->quantity * $element->supply_order_detail->cost, 2, '.', ',') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection