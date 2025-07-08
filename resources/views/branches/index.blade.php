@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de sucursales') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Sucursales') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Dirección') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th>{{ __('Fecha apertura') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $element)
                    <tr>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->address }}</td>
                        @if($element->status)
                            <td><span class="label label-success">{{ __('Activa') }}</span></td>
                        @else
                            <td><span class="label label-danger">{{ __('Inactiva') }}</span></td>
                        @endif
                        <td>{{ $element->created }}</td>
                        <td>{{ $element->opening_date }}</td>
                        <td><a href="{{ url('/branches/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection