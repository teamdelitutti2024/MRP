@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Inventario general') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Materias primas') }}</h6>
    </div>
    <div class="panel-body">
        @if(count($general_stock) == 0)
            <div class="alert alert-danger fade in widget-inner">
                <i class="fa fa-exclamation-circle"></i> {{ __('No se encontraron resultados') }}
            </div>
        @endif
        <form type="GET" action="{{ url('/general_stock') }}" autocomplete="off">
            <div class="row">
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="supply_key" value="{{ $supply_key }}" placeholder="Clave de materia prima">
                </div>
                <div class="col-sm-3">
                    <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary">
                    <a href="{{ url('/general_stock') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                </div>
            </div>
        </form>
        <br>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Clave') }}</th>
                        <th>{{ __('Materia prima') }}</th>
                        <th>{{ __('Cantidad') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Ubicación') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($general_stock as $element)
                        <tr>
                            <td>{{ $element->supply->supply_key }}</td>
                            <td><a href="{{ url('/supplies/detail/' . $element->supply_id) }}">{{ $element->supply->name }}</a></td>
                            <td>{{ $element->quantity }}</td>
                            <td><a href="{{ url('/measurement_units/detail/' . $element->supply->measurement_unit_id) }}">{{ $element->supply->measurement_unit->measure }}</a></td>
                            <td>{{ $element->supply_location->location }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-left: 785px; margin-top: 20px; padding-bottom:20px;">
            @if(count($general_stock) > 0)
                @if($general_stock->onFirstPage())
                    <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Primero') }}</a>
                    <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Anterior') }}</a>
                @else
                    <a href="{{ $general_stock->url(1) }}" class="btn btn-success btn-xs" aria-disabled="true">{{ __('Primero') }}</a>
                    <a href="{{ $general_stock->previousPageUrl() }}" class="btn btn-success btn-xs">{{ __('Anterior') }}</a>
                @endif
                @if($general_stock->hasMorePages())
                    <a href="{{ $general_stock->nextPageUrl() }}" class="btn btn-success btn-xs">{{ __('Siguiente') }}</a>
                    <a href="{{ $general_stock->url($general_stock->lastPage()) }}" class="btn btn-success btn-xs" aria-disabled="true">{{ __('Último') }}</a>
                @else
                    <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Siguiente') }}</a>
                    <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Último') }}</a>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection