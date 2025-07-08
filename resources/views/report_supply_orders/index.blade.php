@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Órdenes de Compra por Materia Prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/supply_orders') }}" type="GET" >
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
                    <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                    <input type="text" class="form-control" name="supply_key" value="{{ $supply_key }}" placeholder="Clave de materia prima" required>
                </div>
                <div style="margin-top: 27px;">
                    <div class="col-sm-2">
                        <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary"> 
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supply_orders') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supply_orders/download') }}?from_date={{ $from_date }}&to_date={{ $to_date }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supply_orders/export') }}?from_date={{ $from_date }}&to_date={{ $to_date }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
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
                        <th>{{ __('Clave materia prima') }}</th>
                        <th>{{ __('Materia prima') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Cantidad solicitada') }}</th>
                        <th>{{ __('Cantidad recibida') }}</th>
                        <th>{{ __('Costo') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Fecha pedido') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($supply_orders as $element)
                        @php $total += $element->cost * $element->quantity; @endphp
                        <tr>
                            <td>{{ $element->supply_order_id }}</td>
                            <td>{{ $element->supplier_key }}</td>
                            <td>{{ $element->supply_key }}</td>
                            <td>{{ $element->supply }}</td>
                            <td>{{ $element->measure }}</td>
                            <td>{{ $element->quantity }}</td>
                            <td>{{ $element->received_quantity }}</td>
                            <td>${{ number_format($element->cost, 2, '.', ',') }}</td>
                            <td>${{ number_format(($element->cost * $element->quantity), 2, '.', ',') }}</td>
                            <td>{{ $element->status == 1 || $element->status == 3 || $element->status == 4 ? __('Abierto') : __('Cerrado') }}</td>
                            <td>{{ date('d-m-Y', strtotime($element->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h1 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h1>
        </div>
    </div>
</div>
@endsection