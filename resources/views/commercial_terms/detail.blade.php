@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de condición comercial') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $commercial_term->name }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Tipo') }}: {{ __(config('dictionaries.commercial_terms.'. $commercial_term->type)) }}</li>
        <li class="list-group-item">{{ __('Depósito') }}: {{ $commercial_term->deposit }}</li>
    </ul>
</div>
@if($commercial_term->payments_detail)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de pagos') }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Porcentaje') }}</th>
                        <th>{{ __('Días') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $payments_detail = json_decode($commercial_term->payments_detail, true);
                    @endphp 
                    @foreach($payments_detail as $element)
                        <tr>
                            <td>{{ $element['percentage'] . '%' }}</td>
                            <td>{{ $element['days'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Proveedores asociados') }}</h6>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Dirección') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($commercial_term->suppliers as $element)
                <tr>
                    <td>{{ $element->supplier_key }}</td>
                    <td>{{ $element->name }}</td>
                    <td>{{ $element->address }}</td>
                    <td>
                        <!-- Split button -->
                        <div class="btn-group pull-right">
                            <a href="{{ url('/suppliers/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/suppliers/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                            </ul>
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
