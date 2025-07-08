@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de producción') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/bake_breads_production') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $production->responsible_id) }}">{{ $production->responsible->name }}</a></li>
        @if($production->reversed_responsible_id)
            <li class="list-group-item">{{ __('Responsable de producción revertida') }}: <a href="{{ url('/users/detail/' . $production->reversed_responsible_id) }}">{{ $production->reversed_responsible->name }}</a></li>
        @endif
        <li class="list-group-item">{{ __('Status') }}: {{ $production->status ? __('Activa') : __('Revertida') }}</li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $production->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Bases') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Base') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($production->production_details as $element)
                    <tr>
                        <td>{{ $element->bake_bread_size->bake_bread_size_key }}</td>
                        <td><a href="{{ url('/bake_bread_sizes/edit/' . $element->bake_bread_size_id) }}">{{ $element->bake_bread_size_name }}</a></td>
                        <td>{{ $element->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Materias primas utilizadas') }}</h6>
        <a href="{{ url('/bake_breads_production/download_projection/' . $production->id) }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar reporte de producción') }}</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Recurso') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Costo promedio') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($production->production_supplies as $element)
                    @php
                        $total += $element->average_cost * $element->quantity;
                    @endphp
                    <tr>                        
                        <td>{{ $element->supply_key }}</td>
                        <td>{{ $element->supply }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->supply_location }}</td>
                        <td>{{ $element->measure }}</td>
                        <td>${{ $element->average_cost }}</td>
                        <td>${{ number_format($element->average_cost * $element->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ __('Total:') }}</td>
                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection