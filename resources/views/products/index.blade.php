@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de productos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos') }}</h6>
        <a href="{{ url('/products/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar producto') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $element)
                    <tr>
                        <td>{{ $element->name }}</td>
                        <td>{{ __(config('dictionaries.product_categories.' . $element->category_id)) }}</td>
                        @if($element->status)
                            <td><span class="label label-success">{{ __('Activo') }}</span></td>
                        @else
                            <td><span class="label label-danger">{{ __('Inactivo') }}</span></td>
                        @endif
                        <td>{{ $element->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/products/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/products/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                    @if($element->status)
                                        <li><a href="{{ url('/products/change_status/' . $element->id . '/inactive') }}" onclick="return confirm('{{ __('¿Desactivar producto?') }}');" >{{ __('Desactivar') }}</a></li>
                                    @else
                                        <li><a href="{{ url('/products/change_status/' . $element->id . '/active') }}" onclick="return confirm('{{ __('¿Activar producto?') }}');" >{{ __('Activar') }}</a></li>
                                    @endif
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