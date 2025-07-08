@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de conteo') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/cycle_counts') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                    <th>{{ __('Cantidad contabilizada') }}</th>
                    <th>{{ __('Comentarios') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cycle_count_details as $element)
                    <tr>
                        <td>{{ $element->sup->supply_key }}</td>
                        <td><a href="{{ url('/supplies/detail/' . $element->supply_id) }}">{{ $element->supply }}</a></td>
                        <td><a href="{{ url('/measurement_units/detail/' . $element->measurement_unit_id) }}">{{ $element->measurement_unit->measure }}</a></td>
                        <td>{{ $element->supply_location->location }}</td>
                        <td>{{ $element->counted_quantity }}</td>
                        <td>{{ $element->comments }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection