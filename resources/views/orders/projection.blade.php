@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Proyección de pedido') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/orders') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle de la proyección') }}</h6>
        <a href="{{ url('/orders/download_projection/' . $order->id) }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar proyección') }}</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Recurso') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Cantidad disponible') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Costo promedio') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($supplies as $element)
                    @php
                        $total += $element['average_cost'] * $element['quantity'];
                    @endphp
                    <tr>                        
                        <td>{{ $element['supply_key'] }}</td>
                        <td>{{ $element['supply'] }}</td>
                        <td>{{ $element['quantity'] }}</td>
                        <td>{{ $element['available_quantity'] }}</td>
                        <td>{{ $element['measure'] }}</td>
                        <td>${{ $element['average_cost'] }}</td>
                        <td>${{ number_format($element['average_cost'] * $element['quantity'], 2) }}</td>
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