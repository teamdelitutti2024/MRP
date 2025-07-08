@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Órdenes de Compra') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/orders') }}" type="GET" >
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>{{ __('Desde la fecha') }}</label>
                        <input type="text" class="form-control" id="from" name="from_date" value="{{ $from_date }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Hasta la fecha') }}</label>
                        <input type="text" class="form-control" id="to" name="to_date" value="{{ $to_date }}" readonly>
                    </div>
                </div>
                <div style="margin-top: 27px;">
                    <div class="col-sm-2">
                        <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary">
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/orders') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/orders/download') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/orders/export') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="datatable">
            <table class="table table-hover datatables">
                <thead>
                    <tr>
                        <th>{{ __('No. Orden') }}</th>
                        <th>{{ __('Clave proveedor') }}</th>
                        <th>{{ __('Proveedor') }}</th>
                        <th>{{ __('Fecha pedido') }}</th>
                        <th>{{ __('Fecha entrega') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($supply_orders as $element)
                        @php $total += $element->total; @endphp
                        <tr>
                            <td><a href="{{ url('/supply_orders/detail/' . $element->id) }}">{{ $element->id }}</a></td>
                            <td>{{ $element->supplier->supplier_key }}</td>
                            <td>{{ $element->supplier->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($element->created_at)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($element->delivery_date)) }}</td>
                            <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                            <td>{{ $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h1 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h1>
        </div>
    </div>
</div>
@endsection