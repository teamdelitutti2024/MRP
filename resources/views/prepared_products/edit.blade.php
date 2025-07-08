@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar preparado') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/prepared_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/prepared_products/update/' . $prepared_product->id) }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                        <input type="text" class="form-control" name="product_key" value="{{ $prepared_product->product_key }}" readonly>
                    </div>
                    <div class="col-sm-8">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" maxlength="60" value="{{ $prepared_product->name }}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Ingredientes') }}</h6>
            <span class="pull-right label label-info">{{ __('Total') }}: $<span id="dinamic-total">0.00</span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="ingredients-table">
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad no puede ser vacía o menor o igual a 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th style="width: 10%">{{ __('Clave') }}</th>
                            <th style="width: 20%">{{ __('Materia prima') }}</th>
                            <th style="width: 15%">{{ __('Unidad de medida') }}</th>
                            <th style="width: 10%">{{ __('Cantidad') }}</th>
                            <th style="width: 10%">{{ __('Cantidad a producir') }}</th>
                            <th style="width: 5%">{{ __('Costo') }}</th>
                            <th style="width: 5%">{{ __('Total') }}</th>
                            <th style="width: 15%">{{ __('Ubicación') }}</th>
                            <th style="width: 10%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            $counter = 0; 
                        @endphp
                        @foreach($prepared_product->ingredients as $element)
                            @php
                                $cost = $element->supply->average_cost == 0 ? $element->supply->initial_cost / $element->supply->standard_pack : $element->supply->average_cost;
                                $total += $element->quantity / $element->quantity_to_produce * $cost;
                            @endphp
                            <tr>
                                <td class="mandatory-field">
                                    <span>*</span>
                                    <p style="display: inline;" id="key-{{ $counter }}">{{ $element->supply->supply_key }}</p>
                                </td>
                                <td>
                                    <select name="ingredients[supply_id][]" class="form-control supplies" id="{{ $counter }}">
                                        @foreach($supplies as $supply)
                                            <option value="{{ $supply->id }}" {{ $element->supply_id == $supply->id ? 'selected' : '' }}>{{ $supply->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="ingredients[measurement_unit_id][]" id="measurement_unit_id-{{ $counter }}" value="{{ $element->measurement_unit_id }}" hidden>
                                    <p style="display: inline;" id="measurement_unit-{{ $counter }}">{{ $element->measurement_unit->measure }}</p>
                                </td>
                                <td><input type="text" name="ingredients[quantity][]" class="form-control quantities" id="quantity-{{ $counter }}" value="{{ $element->quantity }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" required></td>
                                <td><input type="text" name="ingredients[quantity_to_produce][]" class="form-control quantitiesproduce" id="quantityproduce-{{ $counter }}" value="{{ $element->quantity_to_produce }}" maxlength="3" pattern="([1-9][0-9]?[0-9]?)" title="{{ __('Número entero entre 1 y 999') }}" required></td>
                                <td>$<p style="display: inline;" id="cost-{{ $counter }}">{{ number_format($cost, 2) }}</p></td>
                                <td>$<p style="display: inline;" id="total-{{ $counter }}">{{ number_format($element->quantity / $element->quantity_to_produce * $cost, 2) }}</p></td>
                                <td>
                                    <select name="ingredients[supply_location_id][]" class="form-control">
                                        @foreach($supply_locations as $supply_location)
                                            <option value="{{ $supply_location->id }}" {{ $element->supply_location_id == $supply_location->id ? 'selected' : '' }}>{{ $supply_location->location }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="table-controls">
                                        <a href="#" class="btn btn-danger btn-icon btn-xs del-row-ingredient" id="delete-{{ $counter }}"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Recursos') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="resources-table">
                    <thead>
                        <tr>
                            <th style="width: 20%">{{ __('Clave') }}</th>
                            <th style="width: 20%">{{ __('Recurso') }}</th>
                            <th style="width: 20%">{{ __('Unidad de medida') }}</th>
                            <th style="width: 15%">{{ __('Cantidad') }}</th>
                            <th style="width: 15%">{{ __('Cantidad a producir') }}</th>
                            <th style="width: 10%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-resource-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter_resources = 0; @endphp
                        @foreach($prepared_product->resources as $element)
                            <tr>
                                <td class="mandatory-field">
                                    <span>*</span>
                                    <p style="display: inline;" id="rkey-{{ $counter_resources }}">{{ $element->resource->resource_key }}</p>
                                </td>
                                <td>
                                    <select name="resources[resource_id][]" class="form-control resources" id="r-{{ $counter_resources }}">
                                        @foreach($resources as $resource)
                                            <option value="{{ $resource->id }}" {{ $element->resource_id == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><p style="display: inline;">{{ __('Horas') }}</p></td>
                                <td><input type="text" name="resources[production_time][]" class="form-control" value="{{ $element->production_time }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required></td>
                                <td><input type="text" name="resources[quantity_to_produce][]" class="form-control" value="{{ $element->quantity_to_produce }}" maxlength="3" pattern="(100|[1-9][0-9]?)" title="{{ __('Número entero entre 1 y 100') }}" required></td>
                                <td>
                                    <div class="table-controls">
                                        <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete_resource-{{ $counter_resources }}"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @php $counter_resources++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-actions text-right">
        <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
    var counter = {{ $counter }};
    var counter_resources = {{ $counter_resources }};
    var total = {{ $total }};

    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));

    $(document).ready(function() {
        $('#add-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <p style="display: inline;" id="key-' + counter + '"></p>'
            html += '   </td>'
            html += '   <td>';
            html += '       <select name="ingredients[supply_id][]" class="form-control supplies" id="' + counter + '">';
            @foreach($supplies as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="ingredients[measurement_unit_id][]" id="measurement_unit_id-' + counter + '" hidden>';
            html += '       <p style="display: inline;" id="measurement_unit-' + counter + '"></p>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="ingredients[quantity][]" class="form-control quantities" id="quantity-' + counter + '" value="1" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="ingredients[quantity_to_produce][]" class="form-control quantitiesproduce" id="quantityproduce-' + counter + '" value="1" maxlength="3" pattern="([1-9][0-9]?[0-9]?)" title="@lang('Número entero entre 1 y 999')" required>';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="cost-' + counter + '"></p></td>';
            html += '   <td>$<p style="display: inline;" id="total-' + counter + '"></p></td>';
            html += '   <td>';
            html += '       <select name="ingredients[supply_location_id][]" class="form-control">';
            @foreach($supply_locations as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->location }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row-ingredient" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            // First supply
            var supply_id = {{ $supplies[0]['id'] }};

            // Get supply
            $.ajax({
                url: '{{ url('/supplies/get_row') }}',
                data: 'id=' + supply_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    var internal_count = counter - 1;

                    var cost = (data.average_cost == 0) ? format_number_to_separator((data.initial_cost / data.standard_pack).toFixed(2)) : data.average_cost;

                    $('#key-' + internal_count).text(data.supply_key);
                    $('#measurement_unit_id-' + internal_count).val(data.measurement_unit_id);
                    $('#measurement_unit-' + internal_count).text(data.measure);
                    $('#cost-' + internal_count).text(cost);
                    $('#total-' + internal_count).text(cost);

                    total = parseFloat(total) + parseFloat(cost);

                    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
                }
            });

            counter++;

            $('#ingredients-table > tbody').append(html);
        });

        $(document).on('click', '.del-row-ingredient', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar ingrediente?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#total-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from dinamic total
                total = total - row_total;
                $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
            }
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                $(this).parent().parent().parent().remove();
            }
        });

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $(this).val() / $('#quantityproduce-' + row_id).val() * format_number_from_separator($('#cost-' + row_id).text());
                $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#total-' + row_id).text('0.00');
                $('#quantity-' + row_id).val('');
                $('#quantityproduce-' + row_id).val('1');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Add row total to dinamic total
            total = total + new_total;
            $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
        });

        $(document).on('change', '.quantitiesproduce', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $('#quantity-' + row_id).val() / $(this).val() * format_number_from_separator($('#cost-' + row_id).text());
                $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#total-' + row_id).text('0.00');
                $('#quantity-' + row_id).val('');
                $('#quantityproduce-' + row_id).val('1');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Add row total to dinamic total
            total = total + new_total;
            $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
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
                    // Substract previous row total from dinamic total
                    var previous_total = format_number_from_separator($('#total-' + row_id).text());
                    total = total - previous_total;

                    var cost = (data.average_cost == 0) ? format_number_to_separator((data.initial_cost / data.standard_pack).toFixed(2)) : data.average_cost;

                    $('#key-' + row_id).text(data.supply_key);
                    $('#measurement_unit_id-' + row_id).val(data.measurement_unit_id);
                    $('#measurement_unit-' + row_id).text(data.measure);
                    $('#cost-' + row_id).text(cost);

                    var new_total = 0;

                    if($('#quantity-' + row_id).val() > 0) {
                        new_total = $('#quantity-' + row_id).val() / $('#quantityproduce-' + row_id).val() * cost;
                        $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                    }
                    else {
                        $('#total-' + row_id).text('0.00');
                    }

                    // Add row total to dinamic total
                    total = total + new_total;
                    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
                }
            });
        });

        $('#add-resource-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <p style="display: inline;" id="rkey-' + counter_resources + '"></p>'
            html += '   </td>'
            html += '   <td>';
            html += '       <select name="resources[resource_id][]" class="form-control resources" id="r-' + counter_resources + '">';
            @foreach($resources as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td><p style="display: inline;"></p>@lang('Horas')</td>';
            html += '   <td>';
            html += '       <input type="text" name="resources[production_time][]" class="form-control" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="resources[quantity_to_produce][]" class="form-control" value="1" maxlength="3" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete_resource-' + counter_resources + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            // First resource
            var resource_id = {{ $resources[0]['id'] }};

            // Get resource
            $.ajax({
                url: '{{ url('/resources/get_row') }}',
                data: 'id=' + resource_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    var internal_count = counter_resources - 1;

                    $('#rkey-' + internal_count).text(data.resource_key);
                }
            });

            counter_resources++;

            $('#resources-table > tbody').append(html);
        });

        $(document).on('change', '.resources', function(e) {
            var resource_id = $(this).val();
            var row_id = $(this).attr('id').split("-")[1];

            // Get resource
            $.ajax({
                url: '{{ url('/resources/get_row') }}',
                data: 'id=' + resource_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#rkey-' + row_id).text(data.resource_key);
                }
            });
        });
    });
</script>
@endsection