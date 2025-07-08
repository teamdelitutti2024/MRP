@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de inventario') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/stock/level_2') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Base') }}: <a href="{{ url('/bake_bread_sizes/edit/' . $stock_level_2->bake_bread_size_id) }}">{{ $stock_level_2->bake_bread_size->bake_bread_size_key . ' ' . $stock_level_2->bake_bread_size->name }}</a></li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Cantidad anterior') }}</th>
                    <th>{{ __('Motivo') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Fecha') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $element)
                    <tr>
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->last_quantity }}</td>
                        <td>{{ __($element->reason) }}</td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</a></td>
                        <td>{{ $element->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection