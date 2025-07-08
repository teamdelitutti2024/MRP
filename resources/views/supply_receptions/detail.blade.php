@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de recepción de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Recepción de materia prima') }}: #{{ $supply_reception->id }}</h6>
        <a href="{{ url('/supply_orders/detail/' . $supply_reception->supply_order_id) }}"><span class="pull-right label label-info">{{ __('Pedido de materia prima') }}: #{{ $supply_reception->supply_order_id }}</span></a>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Código') }}: {{ $supply_reception->code }}</li>
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($supply_reception->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $supply_reception->responsible_id) }}">{{ $supply_reception->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Proveedor') }}: <a href="{{ url('/suppliers/detail/' . $supply_reception->supply_order->supplier_id) }}">{{ $supply_reception->supply_order->supplier->name }}</a></li>
        <li class="list-group-item">{{ __('Fecha de creación') }}: {{ $supply_reception->created_at }}</li>
    </ul>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle de recepción') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad solicitada') }}</th>
                    <th>{{ __('Cantidad recibida') }}</th>
                    <th>{{ __('Costo') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_reception->supply_reception_details as $element)
                    <tr>
                        <td><a href="{{ url('/supplies/detail/' . $element->supply_order_detail->supply_id) }}">{{ $element->supply_order_detail->supply }}</a></td>
                        <td><a href="{{ url('/measurement_units/detail/' . $element->supply_order_detail->measurement_unit_id) }}">{{ $element->supply_order_detail->measurement_unit->measure }}</a></td>
                        <td>{{ $element->supply_order_detail->quantity }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>${{ number_format($element->supply_order_detail->cost, 2, '.', ',') }}</td>
                        <td>${{ number_format($element->supply_order_detail->cost * $element->quantity, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection