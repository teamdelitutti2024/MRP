@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de merma de preparado') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_prepared_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $declined_prepared_product->responsible_id) }}">{{ $declined_prepared_product->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Status') }}: {{ $declined_prepared_product->status ? __('Activa') : __('Revertida') }}</li>
        @if(!$declined_prepared_product->status)
            <li class="list-group-item">{{ __('Responsable de merma revertida') }}: <a href="{{ url('/users/detail/' . $declined_prepared_product->reversed_responsible_id) }}">{{ $declined_prepared_product->reversed_responsible->name }}</a></li>
        @endif
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $declined_prepared_product->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Preparado') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Comentarios') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $declined_prepared_product->prepared_product->product_key }}</td>
                    <td><a href="{{ url('/prepared_products/detail/' . $declined_prepared_product->prepared_product_id) }}">{{ $declined_prepared_product->prepared_product_name }}</a></td>
                    <td>{{ $declined_prepared_product->quantity }}</td>
                    <td>{{ $declined_prepared_product->comments }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle de la proyección') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Costo promedio') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($declined_prepared_product->prepared_product_supplies as $element)
                    @php
                        $total += $element['average_cost'] * $element['quantity'];
                    @endphp
                    <tr>
                        <td>{{ $element['supply_key'] }}</td>
                        <td>{{ $element['supply'] }}</td>
                        <td>{{ $element['measure'] }}</td>
                        <td>{{ $element['supply_location'] }}</td>
                        <td>{{ $element['quantity'] }}</td>
                        <td>{{ $element['average_cost'] }}</td>
                        <td>${{ number_format($element['quantity'] * $element['average_cost'], 2) }}</td>
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