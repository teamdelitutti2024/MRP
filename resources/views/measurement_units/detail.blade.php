@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de unidad de medida') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $measurement_unit->measure }}</h6>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('Equivalencia') }}</th>
                <th>{{ __('Unidad de medida') }}</th>
                <th>{{ __('Fecha de creación')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($measurement_unit->equivalences as $element)
                <tr>
                    <td>{{ $element->equivalence }}</td>
                    <td>{{ $element->target_measurement->measure }}</td>
                    <td>{{ $element->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection