@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Inventario de nivel 2') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Inventario de nivel 2') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Base') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($stock_level_2 as $element)
                    <tr>
                        <td>{{ $element->bake_bread_size->bake_bread_size_key }}</td>
                        <td>{{ $element->bake_bread_size->name }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/stock/level_2/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
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