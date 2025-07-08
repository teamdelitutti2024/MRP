@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Recepciones de Materia Prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/supply_receptions') }}" type="GET">
            <p></p>
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
                        <a href="{{ url('/reports/supply_receptions') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ url('/reports/supply_receptions/download') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supply_receptions/export') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="datatable">
            <table class="table table-hover datatables">
                <thead>
                    <tr>
                        <th style="width: 10%">{{ __('Clave') }}</th>
                        <th style="width: 20%">{{ __('Materia prima') }}</th>
                        <th style="width: 20%">{{ __('Unidad de medida') }}</th>
                        <th style="width: 15%">{{ __('Fecha de entrega') }}</th>
                        <th style="width: 15%">{{ __('Cantidad a recibir') }}</th>
                        <th style="width: 10%">{{ __('Costo promedio') }}</th>
                        <th style="width: 10%">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($supplies as $element)
                        @php $total += $element['cost'] * $element['quantity']; @endphp
                        <tr>
                            <td>{{ $element['supply_key'] }}</td>
                            <td>{{ $element['supply'] }}</td>
                            <td>{{ $element['measure'] }}</td>
                            <td>{{ $element['delivery_date'] }}</td>
                            <td>{{ $element['quantity'] }}</td>
                            <td>${{ number_format($element['cost'], 2, '.', ',') }}</td>
                            <td>${{ number_format($element['cost'] * $element['quantity'], 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h1 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h1>
        </div>
    </div>
</div>
@endsection