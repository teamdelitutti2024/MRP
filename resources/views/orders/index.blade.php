@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de pedidos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Pedidos') }}</h6>
        <a href="{{ url('/orders/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar pedido') }}</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Fecha entrega') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                        <td>{{ $element->delivery_date }}</td>
                        @php
                            switch($element->status) {
                                case 0:
                                    $label = 'label-danger';
                                    break;
                                case 1:
                                    $label = 'label-primary';
                                    break;
                                case 2:
                                    $label = 'label-success';
                                    break;
                                default:
                                    $label = 'label-primary';
                            }
                        @endphp
                        <td><span class="label {{ $label }}">{{ __(config('dictionaries.order_status.' . $element->status)) }}</span></td>
                        <td>{{ $element->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/orders/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/orders/download/' . $element->id) }}">{{ __('Descargar PDF') }}</a></li>
                                    <li><a href="{{ url('/orders/projection/' . $element->id) }}">{{ __('Proyectar') }}</a></li>
                                </ul>
                            </div>
                            <!-- /Split button -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-left: 804px; margin-top: 20px; padding-bottom:20px;">
        @if(count($orders) > 0)
            @if($orders->onFirstPage())
                <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Primero') }}</a>
                <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Anterior') }}</a>
            @else
                <a href="{{ $orders->url(1) }}" class="btn btn-success btn-xs" aria-disabled="true">{{ __('Primero') }}</a>
                <a href="{{ $orders->previousPageUrl() }}" class="btn btn-success btn-xs">{{ __('Anterior') }}</a>
            @endif
            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="btn btn-success btn-xs">{{ __('Siguiente') }}</a>
                <a href="{{ $orders->url($orders->lastPage()) }}" class="btn btn-success btn-xs" aria-disabled="true">{{ __('Último') }}</a>
            @else
                <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Siguiente') }}</a>
                <a href="#" class="btn btn-success btn-xs disabled" aria-disabled="true">{{ __('Último') }}</a>
            @endif
        @endif
    </div>
</div>
@endsection