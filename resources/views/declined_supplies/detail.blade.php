@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de merma') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_supplies') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Merma') }}: #{{ $declined_supply->id }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('ID') }}: {{ $declined_supply->id }}</li>
        <li class="list-group-item">{{ __('Materia prima') }}: <a href="{{ url('/supplies/detail/' . $declined_supply->supply_id) }}"> {{ $declined_supply->supply }}</a></li>
        <li class="list-group-item">{{ __('Cantidad merma') }}: {{ $declined_supply->lost_quantity }}</li>
        <li class="list-group-item">{{ __('Unidad de medida') }}: <a href="{{ url('/measurement_units/detail/' . $declined_supply->measurement_unit_id) }}">{{ $declined_supply->measurement_unit->measure }}</a></li>
        <li class="list-group-item">{{ __('Monto de la transacción') }}: ${{ number_format($declined_supply->transaction_amount, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Ubicación') }}: {{ $declined_supply->supply_location->location }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.declined_supplies_status.' . $declined_supply->status)) }}</li>
        <li class="list-group-item">{{ __('Categoría') }}: {{ __(config('dictionaries.declined_categories.' . $declined_supply->category)) }}</li>
        <li class="list-group-item">{{ __('Razón') }}: {{ $declined_supply->reason }}</li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $declined_supply->created_at}}</li>
        <li class="list-group-item">{{ __('Responsable activado') }}: <a href="{{ url('/users/detail/' . $declined_supply->enabled_responsible_id) }}">{{ $declined_supply->enabled_responsible->name }}</a></li>
        @if($declined_supply->status == 'inactive')
            <li class="list-group-item">{{ __('Fecha desactivado') }}: {{ $declined_supply->disabled_date }}</li>
            <li class="list-group-item">{{ __('Responsable desactivado') }}: <a href="{{ url('/users/detail/' . $declined_supply->disabled_responsible_id) }}">{{ $declined_supply->disabled_responsible->name }}</a></li>
        @endif
        @if($declined_supply->status == 'reversed')
            <li class="list-group-item">{{ __('Responsable de merma revertida') }}: <a href="{{ url('/users/detail/' . $declined_supply->reversed_responsible_id) }}">{{ $declined_supply->reversed_responsible->name }}</a></li>
        @endif
        @if($declined_supply->quarantine)
            <li class="list-group-item">{{ __('Cuarentena asociada') }}: <a href="{{ url('/quarantines/detail/' . $declined_supply->quarantine_id) }}">{{ $declined_supply->quarantine_id }}</a></li>
        @endif
    </ul>
</div>
@endsection