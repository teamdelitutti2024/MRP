@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Reporte de Valoración de Inventario') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ url('/reports/stock_value') }}" type="GET" >
            <div class="row">
                <div class="col-sm-3">
                    <label>{{ __('Categoría') }}</label>
                    <select class="form-control" name="supply_categories">
                        <option value="All" selected {{ $category == "All" ? 'selected': '' }}>{{ __('TODAS') }}</option>
                        @foreach($supply_categories as $element)
                            <option value="{{ $element->id }}" {{ $category == $element->id ? 'selected': '' }}>{{ $element->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label>{{ __('Ubicación') }}</label>
                    <select class="form-control" name="location_id">
                        <option value="All" selected {{ $location == "All" ? 'selected': '' }}>{{ __('TODAS') }}</option>
                        @foreach($supply_locations as $element)
                            <option value="{{ $element->id }}" {{ $location == $element->id ? 'selected': '' }}>{{ $element->location }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label><span class="mandatory">*</span> {{ __('Cantidades') }}</label>
                    <select class="form-control" name="qty_search" required>
                        <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                        @foreach(config('dictionaries.stock_quantities') as $key => $value)
                            <option value="{{ $key }}" {{ $qty_search == $key ? 'selected': '' }}>{{ __($value) }}</option>
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
                        <a href="{{ url('/reports/stock_value') }}" class="btn btn-default">{{ __('Limpiar') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/stock_value/download') }}?supply_categories={{ $category }}&location_id={{ $location }}&qty_search={{ $qty_search }}&supply_key={{ $supply_key }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar PDF') }}</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url('/reports/stock_value/export') }}?supply_categories={{ $category }}&location_id={{ $location }}&qty_search={{ $qty_search }}&supply_key={{ $supply_key }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-download"></i> {{ __('Descargar Excel') }}</a>
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
                        <th>{{ __('Costo unitario') }}</th>
                        <th>{{ __('Cantidad') }}</th>
                        <th>{{ __('Unidad de medida') }}</th>
                        <th>{{ __('Costo total') }}</th>
                        <th>{{ __('Ubicación') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($stock as $element)
                        @php $total += $element->average_cost * $element->quantity; @endphp
                        <tr>
                            <td>{{ $element->cat_name }}</td>
                            <td>{{ $element->supply_key }}</td>
                            <td>{{ $element->name }}</a></td>     
                            <td>${{ $element->average_cost }}</a></td>
                            <td>{{ $element->quantity }}</td>
                            <td>{{ $element->measure }}</a></td>
                            <td>${{ number_format($element->average_cost * $element->quantity, 2, '.', ',') }}</a></td>
                            <td>{{ $element->location }}</td>	
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h1 class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</h1>
        </div>
    </div>
</div>
@endsection