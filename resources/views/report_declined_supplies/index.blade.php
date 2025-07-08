@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Mermas') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/declined_supplies') }}" type="GET">
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
                    <label>{{ __('Clave') }}</label>
                    <input type="text" class="form-control" name="supply_key" value="{{ $supply_key }}" placeholder="Clave de materia prima">
                </div>
                <div style="margin-top: 27px;">
                    <div class="col-sm-2">
                        <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary"> 
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/declined_supplies') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/declined_supplies/download') }}?from_date={{ $from_date }}&to_date={{ $to_date }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/declined_supplies/export') }}?from_date={{ $from_date }}&to_date={{ $to_date }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="datatable">
            <table class="table table-hover datatables">
                <thead>
                    <tr>
                        <th>{{ __('Categoría') }}</th>
                        <th>{{ __('Clave') }}</th>
                        <th>{{ __('Materia prima') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Cantidad merma') }}</th>
                        <th>{{ __('Costo total') }}</th>
                        <th>{{ __('Ubicación') }}</th>
                        <th>{{ __('Razón') }}</th>
                        <th>{{ __('Fecha creación') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($declined_supplies as $element)
                        @php $total += $element->transaction_amount; @endphp
                        <tr>
                            <td>{{ $element->category }}</td>
                            <td>{{ $element->supply_key }}</a></td>
                            <td>{{ $element->supply }}</a></td>
                            <td>{{ $element->measure }}</a></td>
                            <td>{{ $element->lost_quantity }}</td>
                            <td>${{ number_format($element->transaction_amount, 2, '.', ',')}}</td>
                            <td>{{ $element->location }}</td>
                            <td>{{ $element->reason }}</td>
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