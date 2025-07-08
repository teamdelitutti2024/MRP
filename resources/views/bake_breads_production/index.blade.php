@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de producción de bases') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Registros de producción de bases') }}</h6>
        <a href="{{ url('/bake_breads_production/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar producción') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bake_breads_production as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</td>
                        <td>{{ $element->status ? __('Activa') : __('Revertida') }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/bake_breads_production/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
                                @if($element->status)
                                    <a href="{{ url('/bake_breads_production/revert/' . $element->id) }}" onclick="return confirm('¿Revertir producción?')" class="btn btn-default btn-sm">{{ __('Revertir') }}</a>
                                @endif
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