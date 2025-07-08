@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de embarques de entrada') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Embarques de entrada') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Fecha recepción') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Sucursal') }}</th>
                    <th>{{ __('# Embarque de salida') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($inbound_shipments as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>{{ $element->received_date }}</td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</a></td>
                        <td><a href="{{ url('/branches/detail/' . $element->branch_id) }}">{{ $element->branch->name }}</a></td>
                        <td><a href="{{ url('/departure_shipments/detail/' . $element->departure_shipment_id) }}">#{{ $element->departure_shipment_id }}</a></td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/inbound_shipments/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
                            </div>
                            <!-- /Split button -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection