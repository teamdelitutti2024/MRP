@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de merma de base') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_bake_breads') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <ul class="list-group">
        <li class="list-group-item">{{ __('Responsable') }}: <a href="{{ url('/users/detail/' . $declined_bake_bread->responsible_id) }}">{{ $declined_bake_bread->responsible->name }}</a></li>
        <li class="list-group-item">{{ __('Status') }}: {{ $declined_bake_bread->status ? __('Activa') : __('Revertida') }}</li>
        @if(!$declined_bake_bread->status)
            <li class="list-group-item">{{ __('Responsable de merma revertida') }}: <a href="{{ url('/users/detail/' . $declined_bake_bread->reversed_responsible_id) }}">{{ $declined_bake_bread->reversed_responsible->name }}</a></li>
        @endif
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $declined_bake_bread->created_at }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Base') }}</th>
                    <th>{{ __('Cantidad merma') }}</th>
                    <th>{{ __('Fecha de base mermada') }}</th>
                    <th>{{ __('Comentarios') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $declined_bake_bread->bake_bread_size->bake_bread_size_key }}</td>
                    <td><a href="{{ url('/bake_bread_sizes/edit/' . $declined_bake_bread->bake_bread_size_id) }}">{{ $declined_bake_bread->bake_bread_size_name }}</a></td>
                    <td>{{ $declined_bake_bread->quantity }}</td>
                    <td>{{ $declined_bake_bread->bake_bread_date }}</td>
                    <td>{{ $declined_bake_bread->comments }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection