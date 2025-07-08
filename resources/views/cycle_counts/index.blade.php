@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de conteos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Conteos') }}</h6>
        <a href="{{ url('/cycle_counts/add') }}" class="pull-right btn btn-xs btn-success" onclick="return confirm('{{ __('¿Crear conteo completo?') }}');"><i class="fa fa-plus"></i> {{ __('Crear conteo completo') }}</a>
        <a href="{{ url('/cycle_counts/add_partial') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Crear conteo parcial') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Tipo') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Responsable') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cycle_counts as $element)
                    @php
                    $label = '';
                    switch ($element->status) {
                        case '1':
                            $label = 'success';
                            break;

                        case '2':
                            $label = 'info';
                            break;

                        case '3':
                            $label = 'primary';
                            break;
                        
                        default:
                            $label = 'success';
                            break;
                    }    
                    @endphp
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>{{ __(config('dictionaries.cycle_counts_types.' . $element->type)) }}</td>
                        <td><span class="label label-{{ $label }}">{{ __(config('dictionaries.cycle_counts_status.' . $element->status)) }}</span></td>
                        <td>
                            @if($element->responsible_id)
                                <a href="{{ url('/users/detail/' . $element->responsible_id) }}">{{ $element->responsible->name }}</a>
                            @else
                                {{ __('Sin responsable') }}
                            @endif
                        </td>
                        <td>{{ $element->created_at }}</td>    
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ $element->type == 1 ? url('/cycle_counts/edit/' . $element->id) : url('/cycle_counts/edit_partial/' . $element->id) }}" {{ config('dictionaries.cycle_counts_status.' . $element->status) != 'Finalizado' && (!$element->responsible_id || $element->responsible_id == auth()->user()->id) ? '' : 'disabled' }} class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/cycle_counts/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                    <li><a href="{{ url('/cycle_counts/download/' . $element->id) }}">{{ __('Descargar') }}</a></li>
                                    @if($element->status == 2)                                    
                                        <li><a href="{{ url('/cycle_counts/finish/' . $element->id) }}" onclick="return confirm('{{ __('¿Está seguro que desea finalizar el conteo? Esto actualizará las cantidades de las materias primas en las ubicaciones correspondientes') }}')">{{ __('Finalizar') }}</a></li>
                                    @endif
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