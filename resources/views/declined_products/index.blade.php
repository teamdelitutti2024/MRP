@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de mermas de productos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Mermas de productos') }}</h6>
        <a href="{{ url('/declined_products/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar merma de productos') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Producto') }}</th>
                    <th>{{ __('Tamaño') }}</th>
                    <th>{{ __('Cantidad merma') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha de producto mermado') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($declined_products as $element)
                    <tr>
                        <td>{{ $element->product_size->product_size_key }}</td>
                        <td>{{ $element->product_name }}</td>
                        <td>{{ $element->product_size_name }}</td>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->status ? __('Activa') : __('Revertida') }}</td>
                        <td>{{ $element->product_date }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/declined_products/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
                                @if($element->status)
                                    <a href="{{ url('/declined_products/revert/' . $element->id) }}" class="btn btn-default btn-sm" onclick="return confirm('{{ __('¿Revertir merma?') }}');">{{ __('Revertir') }}</a>
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