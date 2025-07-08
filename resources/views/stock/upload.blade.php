@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Actualizar inventario') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Cargar archivo de inventario') }}</h6>
    </div>
    <div class="panel-body">
        <form action="{{ url('/stock/update_stock') }}" role="form" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <p><strong>{{ __('¡Importante!') }}</strong></p>
                <ul>
                    <li>{{ __('Carga un archivo con extensión .xlsx que contenga los siguientes encabezados: supply_key, location, quantity.') }}</li>
                    <li>{{ __('El archivo no debe contener filas vacías.') }}</li>
                    <li>{{ __('La cantidad por materia prima a actualizar debe ser un número decimal mayor o igual a 0 con 7 digitos enteros y 3 cifras decimales como máximo.') }}</li>
                </ul>
                <label><span class="mandatory">*</span> {{ __('Archivo') }}</label>
                <input type="file" class="form-control" name="stock" accept=".xls, .xlsx" required>
            </div>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Cargar') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@if(isset($success_count) && isset($errors_result))
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Resultados') }}</h6>
        </div>
        <div class="panel-body">
            <p><strong style="color: green">{{ __('Se actualizaron ' . $success_count . ' registros' ) }}</strong></p>
            @if(!empty($errors_result))
                <p><strong style="color: red">{{ __('¡Errores!') }}</strong></p>
                @foreach($errors_result as $element)
                    <p>{{ $element }}</p>
                @endforeach
            @endif
        </div>
    </div>
@endif
@endsection