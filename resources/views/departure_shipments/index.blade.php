@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de embarques de salida') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Embarques de salida') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha envío') }}</th>
                    <th>{{ __('ID Orden') }}</th>
                    <th>{{ __('Sucursal') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($departure_shipments as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                        @php
                        switch(config('dictionaries.departure_shipments_status.' . $element->status)) {
                            case 'Iniciado':
                                $label = 'warning';
                                break;
                            case 'Finalizado':
                                $label = 'success';
                                break;
                            case 'Parcialmente entregado':
                                $label = 'primary';
                                break;
                            case 'Entregado':
                                $label = 'default';
                                break;
                            default:
                                $label = 'info';
                        }
                        @endphp
                        <td><span class="label label-{{ $label }}">{{ __(config('dictionaries.departure_shipments_status.' . $element->status)) }}</span></td>
                        <td>{{ $element->shipment_date }}</td>
                        <td><a href="{{ url('/orders/detail/' . $element->order_id) }}">{{ $element->order_id }}</a></td>
                        <td><a href="{{ url('/branches/detail/' . $element->branch_id) }}">{{ $element->branch->name }}</a></td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/departure_shipments/edit/' . $element->id) }}" {{ config('dictionaries.departure_shipments_status.' . $element->status) == 'Iniciado' ? '' : 'disabled' }} class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if(config('dictionaries.departure_shipments_status.' . $element->status) == 'Iniciado')
                                        <li><a href="{{ url('departure_shipments/finish/' . $element->id) }}" onclick="return confirm('{{ __('¿Finalizar embarque de salida?') }}');">{{ __('Finalizar') }}</a></li>
                                    @endif
                                    <li><a href="{{ url('/departure_shipments/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                    @if(config('dictionaries.departure_shipments_status.' . $element->status) == 'Finalizado' || config('dictionaries.departure_shipments_status.' . $element->status) == 'Parcialmente entregado' || config('dictionaries.departure_shipments_status.' . $element->status) == 'Entregado')
                                        <li><a href="{{ url('departure_shipments/inbound_shipments/' . $element->id) }}">{{ __('Embarques de entrada') }}</a></li>
                                    @endif
                                </ul>
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