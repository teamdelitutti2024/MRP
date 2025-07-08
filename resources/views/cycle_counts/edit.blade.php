@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar conteo completo') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/cycle_counts') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/cycle_counts/update/' . $cycle_count->id) }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de conteo completo') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
                        @php $counter = 0; @endphp
                        @foreach($cycle_count_details as $element)
                            <tr>
                                <td>{{ $element->sup->supply_key }}</td>
                                <input type="text" name="details[cycle_count_detail_id][]" value="{{ $element->id }}" hidden>
                                <td>{{ $element->supply }}</td>
                                <td>{{ $element->measurement_unit->measure }}</td>
                                <td>{{ $element->supply_location->location }}</td>
                                <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $counter }}" value="{{ $element->counted_quantity }}" pattern="(0*[0-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal con 7 digitos enteros y 3 cifras decimales como máximo') }}"></td>
                                <td><input type="text" class="form-control" name="details[comments][]" value="{{ $element->comments }}" maxlength="120"></td>
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
@endsection