@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de embarque de salida') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/departure_shipments') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('ID') . ': ' . $departure_shipment->id }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($departure_shipment->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.departure_shipments_status.' . $departure_shipment->status)) }}</li>
        <li class="list-group-item">{{ __('Fecha envío') }}: {{ $departure_shipment->shipment_date }}</li>
        <li class="list-group-item">{{ __('ID Orden') }}: <a href="{{ url('/orders/detail/' . $departure_shipment->order_id) }}">{{ $departure_shipment->order_id }}</a></li>
        <li class="list-group-item">{{ __('Sucursal') }}: <a href="{{ url('/branches/detail/' . $departure_shipment->branch_id) }}">{{ $departure_shipment->branch->name }}</a></li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $departure_shipment->responsible_id) }}">{{ $departure_shipment->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $departure_shipment->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos enviados') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Precio unidad') }}</th>
                    <th>{{ __('Cantidad solicitada') }}</th>
                    <th>{{ __('Cantidad enviada') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departure_shipment->departure_shipment_details as $element)
                    <tr>
                        <td>{{ $element->product_name }}</td>
                        <td>{{ $element->product_size->name }}</td>
                        <td>${{ number_format($element->price, 2, '.', ',') }}</td>
                        <td>{{ $element->requested_quantity }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>${{ number_format($element->quantity * $element->price, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection