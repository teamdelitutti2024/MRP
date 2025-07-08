@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de sucursal') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/branches') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $branch->name }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Dirección') }}: {{ $branch->address }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ $branch->status ? __('Activa') : __('Inactiva') }}</li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $branch->created }}</li>
        <li class="list-group-item">{{ __('Fecha apertura') }}: {{ $branch->opening_date  }}</li>
        <li class="list-group-item">{{ __('Teléfono') }}: {{ $branch->phone }}</li>
    </ul>
</div>
@endsection