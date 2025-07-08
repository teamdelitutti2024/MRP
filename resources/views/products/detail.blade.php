@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de producto') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $product->name }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Días de expiración') }}: {{ $product->expiry_days }}</li>
        <li class="list-group-item">{{ __('Categoría') }}: {{ $product->category_id ? __(config('dictionaries.product_categories.' . $product->category_id)) : __('Ninguna') }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ __(config('dictionaries.common_status.' . $product->status)) }}</li>
        <li class="list-group-item">{{ __('¿Es pastel?') }}: {{ __(config('dictionaries.common_answers.' . $product->is_cake)) }}</li>
        <li class="list-group-item">{{ __('Orden') }}: {{ $product->product_order }}</li>
        <li class="list-group-item">{{ __('Orden móvil') }}: {{ $product->mobile_order }}</li>
        <li class="list-group-item">{{ __('Imagen') }}:
        @if($product->photo)
            <img src="{{ url('/storage/' . $product->photo) }}" width="120" height="55">
        @else
            {{ __('Sin imagen') }}
        @endif
        </li>
        <li class="list-group-item">{{ __('Descripción') }}: {{ $product->description ? $product->description : __('Sin descripción') }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Tamaños') }}</h6>
        <a href="{{ url('/product_sizes/add/' . $product->id) }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar tamaño') }}</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Precio de venta') }}</th>
                    <th>{{ __('Complejidad') }}</th>
                    <th>{{ __('Fecha creación')}}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->product_sizes as $element)
                    <tr>
                        <td>{{ $element->product_size_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>${{ number_format($element->sale_price, 2, '.', ',') }}</td>
                        <td>{{ __(config('dictionaries.product_complexities.' . $element->complexity)) }}</td>
                        <td>{{ $element->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/product_sizes/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                            </div>
                            <!-- /Split button -->                  
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection