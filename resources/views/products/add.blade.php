@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar producto') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información de producto') }}</h6>
    </div>
    <div class="panel-body">
        <form action="{{ url('/products/store') }}" role="form" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                <input type="text" class="form-control" name="name" maxlength="90" required>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Días de expiración') }}</label>
                        <input type="text" class="form-control" name="expiry_days" pattern="[1-9][0-9]?" title="{{ __('Número entero positivo con 2 cifras como máximo') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                        <select class="form-control" name="category_id" required>
                            <option value="0">{{ __('Ninguna') }}</option>
                            @foreach(config('dictionaries.product_categories') as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Status') }}</label>
                        <select class="form-control" name="status" required>
                            @foreach(config('dictionaries.common_status') as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('¿Es pastel?') }}</label>
                        <select class="form-control" name="is_cake" required>
                            @foreach(config('dictionaries.common_answers') as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Orden') }}</label>
                        <input type="text" class="form-control" name="product_order" pattern="[0-9]*" title="{{ __('Número entero') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Orden móvil') }}</label>
                        <input type="text" class="form-control" name="mobile_order" pattern="[0-9]*" title="{{ __('Número entero') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('Imagen') }}</label>
                <input type="file" class="form-control" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <label>{{ __('Descripción') }}</label>
                <textarea rows="3" class="form-control" name="description" maxlength="120"></textarea>
            </div>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endsection