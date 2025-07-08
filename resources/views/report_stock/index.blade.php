@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Cantidades de Inventario') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/stock') }}" type="GET" >
            <div class="row">
                <div class="col-sm-3">
                    <label>{{ __('Categoría') }}</label>
                    <select class="form-control" name="category">
                        <option value="All" selected {{ $category == "All" ? 'selected': '' }}>{{ __('TODAS') }}</option>
                        @foreach($supply_categories as $element)
                            <option value="{{ $element->id }}" {{ $category == $element->id ? 'selected': '' }}>{{ $element->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label>{{ __('Ubicación') }}</label>
                    <select class="form-control" name="location_id">
                        <option hidden disabled selected value> -- {{ __('Selecciona una ubicación') }} -- </option>
                        @foreach($supply_locations as $element)
                            <option value="{{ $element->id }}" {{ $location == $element->id ? 'selected': '' }}>{{ $element->location }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label>{{ __('Clave') }}</label>
                    <input type="text" class="form-control" name="supply_key" value="{{ $supply_key }}" placeholder="Clave de materia prima">
                </div>
                <div style="margin-top: 22px;">
                    <div class="col-sm-2">
                        <input type="submit" value="{{ __('Buscar') }}" class="btn btn-primary"> 
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/stock') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/stock/download') }}?category={{ $category }}&location_id={{ $location }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/stock/export') }}?category={{ $category }}&location_id={{ $location }}&supply_key={{ $supply_key }}" class="pull-right btn btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
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
                        <th>{{ __('Cantidad') }}</th>
                        <th>{{ __('Ubicación') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stock as $element)
                        <tr>
                            <td>{{ $element->cat_name }}</td>
                            <td>{{ $element->supply_key }}</td>
                            <td>{{ $element->name }}</td>
                            <td>{{ $element->measure }}</td>
                            <td>{{ $element->quantity }}</td>
                            <td>{{ $element->location }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection