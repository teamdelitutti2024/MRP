@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de preparado') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/prepared_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $prepared_product->name . ' ' . $prepared_product->product_key }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $prepared_product->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Ingredientes') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Cantidad a producir') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prepared_product->ingredients as $element)
                    <tr>
                        <td>{{ $element->supply->supply_key }}</td>
                        <td>{{ $element->supply->name }}</td>
                        <td>{{ $element->measurement_unit->measure }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->quantity_to_produce }}</td>
                        <td>{{ $element->supply_location->location }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Recursos') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Recurso') }}</th>
                    <th>{{ __('Tiempo (horas)') }}</th>
                    <th>{{ __('Cantidad a producir') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prepared_product->resources as $element)
                    <tr>
                        <td>{{ $element->resource->resource_key }}</td>
                        <td>{{ $element->resource->name }}</td>
                        <td>{{ $element->production_time }}</td>
                        <td>{{ $element->quantity_to_produce }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection