@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de unidades de medida') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Unidades de medida') }}</h6>
        <a href="{{ url('/measurement_units/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar unidad (es) de medida') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Medida') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($measurement_units as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>{{ $element->measure }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/measurement_units/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/measurement_units/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                </ul>
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