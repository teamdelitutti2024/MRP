@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar proveedor') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información de proveedor') }}</h6>
    </div>
    <div class="panel-body">
        <form action="{{ url('/suppliers/update/' . $supplier->id) }}" role="form" method="POST" autocomplete="off">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                        <input type="text" class="form-control" name="supplier_key" value="{{ $supplier->supplier_key }}" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ $supplier->name }}" maxlength="120" required>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Método preferido de pago') }}</label>
                        <select class="form-control" name="preferred_payment_method" required>
                            @foreach(config('dictionaries.preferred_payment_methods') as $key => $value)
                                <option value="{{ $key }}" {{ $key == $supplier->preferred_payment_method ? 'selected': '' }}>{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><span class="mandatory">*</span> {{ __('Dirección') }}</label>
                <textarea rows="5" class="form-control" name="address" maxlength="120" required>{{ $supplier->address }}</textarea>
            </div>
            <div class="form-group">
                <label>{{ __('Notas') }}</label>
                <textarea rows="5" class="form-control" name="notes" maxlength="120">{{ $supplier->notes }}</textarea>
            </div>
            <div class="form-group">
                <label>{{ __('Información de pago') }}</label>
                <textarea rows="5" class="form-control" name="payment_information" maxlength="120">{{ $supplier->payment_information }}</textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label>{{ __('Tiempo de entrega (no. días)') }}</label>
                        <input type="text" class="form-control" name="delivery_time" value="{{ $supplier->delivery_time }}" pattern="[1-9][0-9]?" title="{{ __('Número entero positivo con 2 cifras como máximo') }}">
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                        <select class="form-control" name="supplier_category_id" required>
                            @foreach($supplier_categories as $element)
                                <option value="{{ $element->id }}" {{ $element->id == $supplier->supplier_category_id ? 'selected' : '' }}>{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Condición comercial') }}</label>
                        <select class="form-control" name="commercial_term_id" required>
                            @foreach($commercial_terms as $element)
                                <option value="{{ $element->id }}" {{ $element->id == $supplier->commercial_term_id ? 'selected' : '' }}>{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Requiere factura') }}</label>
                        <select class="form-control" name="require_invoice" required>
                            @foreach(config('dictionaries.common_answers') as $key => $value)
                                <option value="{{ $key }}" {{ $key == $supplier->require_invoice ? 'selected' : '' }}>{{ __($value) }}</option>
                            @endforeach
                        </select>
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