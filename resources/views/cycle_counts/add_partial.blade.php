@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar conteo parcial') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/cycle_counts') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/cycle_counts/store_partial') }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de conteo parcial') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 10%">{{ __('Clave') }}</th>
                            <th style="width: 20%">{{ __('Materia prima') }}</th>
                            <th style="width: 15%">{{ __('Unidad de medida') }}</th>
                            <th style="width: 15%">{{ __('Ubicación') }}</th>
                            <th style="width: 15%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">{{ __('Comentarios') }}</th>
                            <th style="width: 5%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="mandatory-field">
                                <span>*</span>
                                <p style="display: inline;" id="key-0">{{ $supplies[0]->supply_key }}</p>
                            </td>
                            <td>
                                <select name="details[supply_id][]" class="form-control supplies" id="0">
                                    @foreach($supplies as $supply)
                                        <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <p style="display: inline;" id="measurement_unit-0">{{ $supplies[0]->measurement_unit->measure }}</p>
                            </td>
                            <td>
                                <select name="details[supply_location_id][]" class="form-control">
                                    @foreach($supply_locations as $supply_location)
                                        <option value="{{ $supply_location->id }}">{{ $supply_location->location }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-0" pattern="(0*[0-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal con 7 digitos enteros y 3 cifras decimales como máximo') }}" required></td>
                            <td><input type="text" class="form-control" name="details[comments][]" maxlength="120"></td>
                        </tr>
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
    var counter = 1;

    $(document).ready(function() {
        $('#add-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <p style="display: inline;" id="key-' + counter + '">{{ $supplies[0]->supply_key }}</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       <select name="details[supply_id][]" class="form-control supplies" id="' + counter + '">';
            @foreach($supplies as $supply)
            html += '           <option value="{{ $supply->id }}">{{ $supply->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <p style="display: inline;" id="measurement_unit-' + counter + '">{{ $supplies[0]->measurement_unit->measure }}</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       <select name="details[supply_location_id][]" class="form-control">';
            @foreach($supply_locations as $supply_location)
            html += '           <option value="{{ $supply_location->id }}">{{ $supply_location->location }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="details[quantity][]" class="form-control quantities" id="quantity-' + counter + '" pattern="(0*[0-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal con 7 digitos enteros y 3 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="details[comments][]" class="form-control" maxlength="120">';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';
            
            counter++;

            $('#details-table > tbody').append(html);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                $(this).parent().parent().parent().remove();
            }
        });

        $(document).on('change', '.supplies', function(e) {
            var supply_id = $(this).val();

            var row_id = $(this).attr('id');

            // Get supply
            $.ajax({
                url: '{{ url('/supplies/get_row') }}',
                data: 'id=' + supply_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#key-' + row_id).text(data.supply_key);
                    $('#measurement_unit-' + row_id).text(data.measurement_unit.measure);
                }
            });
        });
    });
</script>
@endsection