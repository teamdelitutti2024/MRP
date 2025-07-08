@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar datos fiscales') }}</h5>
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
    <div class="panel-body">
        <form action="{{ url('/supplier_tax_data/update/' . $supplier_tax_data->id) }}" role="form" method="POST" autocomplete="off">
            @csrf
            <div class="form-group">
                <label><span class="mandatory">*</span> {{ __('Razón social') }}</label>
                <input type="text" class="form-control" name="business_reason" value="{{ $supplier_tax_data->business_reason }}" maxlength="90" required autofocus>
            </div>
            <div class="form-group">
                <label><span class="mandatory">*</span> {{ __('RFC') }}</label>
                <input type="text" class="form-control" name="RFC" minlength="13" maxlength="13" value="{{ $supplier_tax_data->RFC }}" required>
            </div>
            <div class="form-group">
                <label><span class="mandatory">*</span> {{ __('Calle') }}</label>
                <input type="text" class="form-control" name="street" value="{{ $supplier_tax_data->street }}" maxlength="90" required>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('No. Exterior') }}</label>
                        <input type="text" class="form-control" name="outside_number" value="{{ $supplier_tax_data->outside_number }}" maxlength="25" required>
                    </div>
                    <div class="col-sm-6">
                        <label>{{ __('No. Interior') }}</label>
                        <input type="text" class="form-control" name="inside_number" value="{{ $supplier_tax_data->inside_number }}" maxlength="25">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-8">
                        <label><span class="mandatory">*</span> {{ __('Colonia') }}</label>
                        <input type="text" class="form-control" name="colony" value="{{ $supplier_tax_data->colony }}" maxlength="120" required>
                    </div>
                    <div class="col-sm-4">
                        <label><span class="mandatory">*</span> {{ __('Código postal') }}</label>
                        <input type="text" class="form-control" name="zip_code" value="{{ $supplier_tax_data->zip_code }}" data-mask="99999" required>
                        <span class="help-block">99999</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <label><span class="mandatory">*</span> {{ __('Ciudad') }}</label>
                        <input type="text" class="form-control" name="city" value="{{ $supplier_tax_data->city }}" maxlength="120" required>
                    </div>
                    <div class="col-sm-4">
                        <label><span class="mandatory">*</span> {{ __('Estado') }}</label>
                        <input type="text" class="form-control" name="state" value="{{ $supplier_tax_data->state }}" maxlength="120" required>
                    </div>
                    <div class="col-sm-4">
                        <label><span class="mandatory">*</span> {{ __('País') }}</label>
                        <input type="text" class="form-control" name="country" value="{{ $supplier_tax_data->country }}" maxlength="120" required>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endsection