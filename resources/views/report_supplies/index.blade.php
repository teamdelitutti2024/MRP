@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Materias Primas') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/supplies') }}" type="GET">
            <div class="row">
                <div class="col-sm-3">
                    <label>{{ __('Status') }}</label>
                    <select class="form-control" name="status">
                        <option value="All" selected {{ $status == "All" ? 'selected': '' }}>{{ __('Todas') }}</option>
                        @foreach(config('dictionaries.supply_status') as $key => $value)
                            <option value="{{ $key }}" {{ $status == $key ? 'selected': '' }}>{{ __($value) }}</option>
                        @endforeach
                    </select>
                    <br>
                </div>
                <div style="margin-top: 22px;">
                    <div class="col-sm-2">
                        <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary"> 
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supplies') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supplies/download') }}?status={{ $status }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/supplies/export') }}?status={{ $status }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <div class="datatable">
            <table class="table table-hover datatables">
                <thead>
                    <tr>
                        <th>{{ __('Clave') }}</th>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Categoría') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Standard pack') }}</th>
                        <th>{{ __('Costo promedio') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplies as $element)
                        <tr>
                            <td><a href="{{ url('/supplies/detail/' . $element->id) }}">{{ $element->supply_key }}</a></td>
                            <td>{{ $element->name }}</td>
                            <td>{{ $element->category }}</td>
                            <td>{{ $element->measure }}</td>
                            <td>{{ $element->standard_pack }}</td>
                            <td>${{ number_format($element->average_cost, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection