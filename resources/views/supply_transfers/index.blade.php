@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Transferencias de materia prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Transferencias') }}</h6>
        <a href="{{ url('/supply_transfers/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar transferencia') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Cantidad transferida') }}</th>
                    <th>{{ __('Ubicación de origen') }}</th>
                    <th>{{ __('Ubicación de destino') }}</th>
                    <th>{{ __('Fecha') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_transfers as $element)
                    <tr>
                        <td><a href="{{ url('/supplies/detail/' . $element->supply_id) }}">{{ $element->supply }}</a></td>
                        <td><a href="{{ url('/measurement_units/detail/' . $element->measurement_unit_id) }}">{{ $element->measurement_unit->measure }}</a></td>
                        <td>{{ $element->transferred_quantity }}</td>
                        <td>{{ $element->source_location->location }}</td>
                        <td>{{ $element->destination_location->location }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/supply_transfers/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
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