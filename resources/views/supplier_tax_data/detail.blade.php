@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de datos fiscales') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/suppliers/detail/' . $supplier_tax_data->supplier_id) }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Datos fiscales del proveedor') }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Razón social') }}: {{ $supplier_tax_data->business_reason}}</li>
        <li class="list-group-item">{{ __('RFC') }}: {{ $supplier_tax_data->RFC }}</li>
        <li class="list-group-item">{{ __('Dirección') }}: {{ $supplier_tax_data->street }}</li>
        <li class="list-group-item">{{ __('No. Exterior') }}: {{ $supplier_tax_data->outside_number }}</li>
        <li class="list-group-item">{{ __('No. Interior') }}: {{ $supplier_tax_data->inside_number }}</li>
        <li class="list-group-item">{{ __('Colonia') }}: {{ $supplier_tax_data->colony}}</li>
        <li class="list-group-item">{{ __('Código postal') }}: {{ $supplier_tax_data->zip_code }}</li>
        <li class="list-group-item">{{ __('Ciudad') }}: {{ $supplier_tax_data->city }}</li>
        <li class="list-group-item">{{ __('Estado') }}: {{ $supplier_tax_data->state }}</li>
        <li class="list-group-item">{{ __('País') }}: {{ $supplier_tax_data->country }}</li>
    </ul>
</div>
@endsection