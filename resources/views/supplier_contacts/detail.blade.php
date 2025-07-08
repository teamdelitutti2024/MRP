@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de contacto') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/suppliers/detail/' . $supplier_contact->supplier_id) }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información de contacto') }}</h6>
        <span class="pull-right label label-info">{{ $supplier_contact->supplier->name }}</span>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Nombre') }}: {{ $supplier_contact->name}}</li>
        <li class="list-group-item">{{ __('Departamento') }}: {{ $supplier_contact->department }}</li>
        <li class="list-group-item">{{ __('Email') }}: {{ $supplier_contact->email }}</li>
        <li class="list-group-item">{{ __('Teléfono') }}: {{ $supplier_contact->phone }}</li>
        <li class="list-group-item">{{ __('Celular') }}: {{ $supplier_contact->mobile }}</li>
        <li class="list-group-item">{{ __('Notas') }}: {{ $supplier_contact->notes }}</li>
        <li class="list-group-item">{{ __('Preferente') }}: {{ __(config('dictionaries.common_answers.' . $supplier_contact->preferred )) }}</li>
    </ul>
</div>
@endsection