@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de recepciones de materia prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Recepciones de materia prima') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Código') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('# Pedido de materia prima') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_receptions as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td><span class="label label-success">{{ $element->code }}</span></td>
                        <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                        <td><a href="{{ url('/supply_orders/detail/' . $element->supply_order->id) }}">#{{ $element->supply_order->id }}</a></td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</a></td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/supply_receptions/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
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