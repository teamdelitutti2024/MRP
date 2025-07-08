@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Pedidos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/product_orders') }}" type="GET">
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
                        <a href="{{ url('/reports/product_orders') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/product_orders/download') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/product_orders/export') }}?from_date={{ $from_date }}&to_date={{ $to_date }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="datatable">
            
        </div>
    </div>
</div>
@endsection