@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar unidad de medida') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/measurement_units/update/' . $measurement_unit->id) }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de unidad de medida') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Unidad de medida') }}</label>
                        <input type="text" class="form-control" name="measure" value="{{ $measurement_unit->measure }}" maxlength="90" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de equivalencias') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="equivalences-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">{{ __('') }}</th>
                            <th style="width: 40%">{{ __('Unidad de medida') }}</th>
                            <th style="width: 40%">{{ __('Equivalencia') }}</th>
                            <th style="width: 15%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($measurement_unit->equivalences as $element)
                            <tr>
                                <td class="mandatory-field">
                                    <span>*</span>
                                    <a href="{{ url('/measurement_units/edit/' . $element->target_measurement_id) }}" target="_blank">{{ __('Ir a') }}</a>
                                </td>
                                <td>
                                    <select name="equivalences[target_measurement_id][]" class="form-control">
                                        @foreach($measurement_units as $item)
                                            <option value="{{ $item->id }}" {{ $element->target_measurement_id == $item->id ? 'selected' : '' }}>{{ $item->measure }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="equivalences[equivalence][]" class="form-control" value="{{ $element->equivalence }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" required>
                                </td>
                                <td>
                                    <div class="table-controls">
                                        <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
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

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('#add-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">'
            html += '       <span>*</span>'
            html += '   </td>'
            html += '   <td>';
            html += '       <select name="equivalences[target_measurement_id][]" class="form-control">';
            @foreach($measurement_units as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->measure }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="equivalences[equivalence][]" class="form-control" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';
            
            $('#equivalences-table > tbody').append(html);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var resp = confirm('¿Eliminar equivalencia?');
            if(resp) {
                $(this).parent().parent().parent().remove();
            }
        });
    });
</script>
@endsection