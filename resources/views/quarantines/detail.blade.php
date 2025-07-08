@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de cuarentena') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/quarantines') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Cuarentena') }}: #{{ $quarantine->id }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('ID') }}: {{ $quarantine->id }}</li>
        <li class="list-group-item">{{ __('Materia prima') }}: <a href="{{ url('/supplies/detail/' . $quarantine->supply_id) }}"> {{ $quarantine->supply }}</a></li>
        <li class="list-group-item">{{ __('Cantidad cuarentena') }}: {{ $quarantine->quantity }}</li>
        <li class="list-group-item">{{ __('Unidad de medida') }}: <a href="{{ url('/measurement_units/detail/' . $quarantine->measurement_unit_id) }}">{{ $quarantine->measurement_unit->measure }}</a></li>
        <li class="list-group-item">{{ __('Monto de la transacción') }}: ${{ number_format($quarantine->transaction_amount, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Ubicación') }}: {{ $quarantine->supply_location->location }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.quarantines_status.' . $quarantine->status)) }}</li>
        <li class="list-group-item">{{ __('Categoría') }}: {{ __(config('dictionaries.quarantine_categories.' . $quarantine->category)) }}</li>
        <li class="list-group-item">{{ __('Razón') }}: {{ $quarantine->reason }}</li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $quarantine->created_at}}</li>
        <li class="list-group-item">{{ __('Responsable activado') }}: <a href="{{ url('/users/detail/' . $quarantine->enabled_responsible_id) }}">{{ $quarantine->enabled_responsible->name }}</a></li>
        @if($quarantine->status == 'inactive')
            <li class="list-group-item">{{ __('Fecha desactivado') }}: {{ $quarantine->disabled_date }}</li>
            <li class="list-group-item">{{ __('Responsable desactivado') }}: <a href="{{ url('/users/detail/' . $quarantine->disabled_responsible_id) }}">{{ $quarantine->disabled_responsible->name }}</a></li>
        @elseif($quarantine->status == 'to_declined')
            <li class="list-group-item">{{ __('Fecha cambio a merma') }}: {{ $quarantine->change_to_decreased_date }}</li>
            <li class="list-group-item">{{ __('Responsable cambio a merma') }}: <a href="{{ url('/users/detail/' . $quarantine->change_to_decreased_responsible_id) }}">{{ $quarantine->change_to_decreased_responsible->name }}</a></li>
        @endif
        @if($quarantine->declined_supply)
            <li class="list-group-item">{{ __('Merma asociada') }}: <a href="{{ url('/declined_supplies/detail/' . $quarantine->declined_supply->id) }}">{{ $quarantine->declined_supply->id }}</a></li>
        @endif
    </ul>
</div>
@endsection