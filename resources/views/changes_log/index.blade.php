@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Log de cambios') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Log') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave/ID') }}</th>
                    <th>{{ __('Recurso') }}</th>
                    <th>{{ __('Módulo') }}</th>
                    <th>{{ __('Evento') }}</th>
                    <th>{{ __('Cantidad previa') }}</th>
                    <th>{{ __('Cantidad nueva') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Fecha') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($changes_log as $element)
                    <tr>
                        <td>{{ $element->element_key }}</td>
                        <td>{{ $element->element_name }}</td>
                        <td>{{ $element->model }}</td>
                        <td>{{ $element->event }}</td>
                        <td>{{ $element->previous_quantity }}</td>
                        <td>{{ $element->new_quantity }}</td>
                        <td><a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</a></td>
                        <td>{{ $element->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection