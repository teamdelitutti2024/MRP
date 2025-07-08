@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de embarque de entrada') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/inbound_shipments') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('ID') . ': ' . $inbound_shipment->id }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Fecha recepción') }}: {{ $inbound_shipment->received_date }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $inbound_shipment->responsible_id) }}">{{ $inbound_shipment->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Sucursal') }}: <a href="{{ url('/branches/detail/' . $inbound_shipment->branch_id) }}">{{ $inbound_shipment->branch->name }}</a></li>
        <li class="list-group-item">{{ __('# Embarque de salida') }}: <a href="{{ url('/departure_shipments/detail/' . $inbound_shipment->departure_shipment_id) }}">{{ $inbound_shipment->departure_shipment_id }}</a></li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $inbound_shipment->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos recibidos') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Cantidad recibida') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Justificación') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inbound_shipment->inbound_shipment_details as $element)
                    <tr>
                        <td>{{ $element->product_name }}</td>
                        <td>{{ $element->product_size->name  }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ __(config('dictionaries.inbound_shipment_details_status.' . $element->status)) }}</td>
                        <td>{{ $element->justification }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection