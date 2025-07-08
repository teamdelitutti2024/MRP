@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Embarques de entrada') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/departure_shipments') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Embarque de salida') }}: <a href="{{ url('/departure_shipments/detail/' . $departure_shipment->id) }}"> #{{ $departure_shipment->id }}</a></h6>
    </div>
    <div class="panel-body">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Total') }}: ${{ number_format($departure_shipment->total, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.departure_shipments_status.' . $departure_shipment->status)) }}</li>
        <li class="list-group-item">{{ __('Fecha envío') }}: {{ $departure_shipment->shipment_date }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $departure_shipment->responsible_id) }}">{{ $departure_shipment->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $departure_shipment->created_at }}</li>
    </ul>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Embarques de entrada') }}</h6>
        @if(config('dictionaries.departure_shipments_status.' . $departure_shipment->status) != 'Entregado')
            <a href="{{ url('/inbound_shipments/add/' . $departure_shipment->id) }}"><span class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar embarque de entrada') }}</span></a>
        @endif
    </div>
    <div class="panel-body">
        @foreach($departure_shipment->inbound_shipments as $inbound_shipment)
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h6 class="panel-title">{{ __('Embarque de entrada') }}: #{{ $inbound_shipment->id }}</h6>
                    <a href="{{ url('/inbound_shipments/delete/' . $inbound_shipment->id) }}" onclick="return confirm('{{ __('¿Eliminar embarque?') }}');"><span class="pull-right btn btn-xs btn-danger">{{ __('Eliminar') }}</span></a>
                    <a href="{{ url('/inbound_shipments/detail/' . $inbound_shipment->id) }}"><span class="pull-right btn btn-xs btn-success">{{ __('Detalle') }}</span></a>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">{{ __('Fecha recepción') }}: {{ $inbound_shipment->received_date }}</li>
                        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $inbound_shipment->responsible_id) }}">{{ $inbound_shipment->responsible->name }}</a></li>
                        <li class="list-group-item">{{ __('Sucursal') }}: <a href="{{ url('/branches/detail/' . $inbound_shipment->branch_id) }}">{{ $inbound_shipment->branch->name }}</a></li>
                    </ul>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Producto') }}</th>
                                    <th>{{ __('Tamaño') }}</th>
                                    <th>{{ __('Cantidad recibida') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Justificación faltante') }}</th>
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
            </div>
        @endforeach
    </div>
</div>
@endsection