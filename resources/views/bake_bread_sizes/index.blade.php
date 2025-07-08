@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de bases') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Bases') }}</h6>
        <a href="{{ url('/bake_bread_sizes/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar base') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Complejidad') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bake_bread_sizes as $element)
                    <tr>
                        <td>{{ $element->bake_bread_size_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>{{ __(config('dictionaries.product_complexities.' . $element->complexity)) }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/bake_bread_sizes/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
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