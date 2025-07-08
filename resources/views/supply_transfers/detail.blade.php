@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de transferencia de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/supply_transfers') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información') }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('ID') }}: {{ $supply_transfer->id }}</li>
        <li class="list-group-item">{{ __('Materia prima') }}: <a href="{{ url('/supplies/detail/' . $supply_transfer->supply_id) }}">{{ $supply_transfer->supply }}</a></li>
        <li class="list-group-item">{{ __('Unidad de medida') }}: <a href="{{ url('/measurement_units/detail/' . $supply_transfer->measurement_unit_id) }}">{{ $supply_transfer->measurement_unit->measure }}</a></li>
        <li class="list-group-item">{{ __('Cantidad transferida') }}: {{ $supply_transfer->transferred_quantity }}</li>
        <li class="list-group-item">{{ __('Monto de la transacción') }}: ${{ number_format($supply_transfer->transaction_amount, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Ubicación de origen') }}: {{ $supply_transfer->source_location->location }}</li>
        <li class="list-group-item">{{ __('Ubicación de destino') }}: {{ $supply_transfer->destination_location->location }}</li>
        <li class="list-group-item">{{ __('Comentario') }}: {{ $supply_transfer->comment }}</li>
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $supply_transfer->responsible_id) }}">{{ $supply_transfer->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Fecha') }}: {{ $supply_transfer->created_at }} </li>
    </ul>
</div>
@endsection