@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar tamaño de producto') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/products/detail/' . $product->id) }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/product_sizes/store') }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de tamaño de producto') }}</h6>
            <span class="pull-right label label-info">{{ __('Precio calculado') }}: $<span id="calculated-price">0.00</span></span>
        </div>
        <div class="panel-body">
            <input type="text" name="product_id" value="{{ $product->id }}" hidden>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" maxlength="60" required>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Complejidad') }}</label>
                        <select name="complexity" class="form-control">
                            <option value="0">{{ __('Ninguna') }}</option>
                            @foreach(config('dictionaries.product_complexities') as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Múltiplo de referencia para pedido') }}</label>
                        <input type="text" class="form-control" name="products_minimum_amount" pattern="[1-9][0-9]?" title="{{ __('Número entero positivo con 2 cifras como máximo') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Precio de venta') }}</label>
                        <input type="text" class="form-control" name="sale_price" pattern="(0*[1-9][0-9]*(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 2 cifras decimales como máximo') }}" required>
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Bases') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="bake-table">
                    <thead>
                        <tr>
                            <th style="width: 25%">{{ __('Clave') }}</th>
                            <th style="width: 25%">{{ __('Base') }}</th>
                            <th style="width: 25%">{{ __('Cantidad') }}</th>
                            <th style="width: 25%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-bake-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Preparados') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="prepared-table">
                    <thead>
                        <tr>
                            <th style="width: 25%">{{ __('Clave') }}</th>
                            <th style="width: 25%">{{ __('Preparado') }}</th>
                            <th style="width: 25%">{{ __('Cantidad') }}</th>
                            <th style="width: 25%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-prepared-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                    <tbody></tbody>
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
    var counter = 0;
    var counter_bake = 0;
    var counter_prepared = 0;
    var counter_resources = 0;
    var total = 0;
    var general_total = 0;

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
            html += '       <input type="text" name="ingredients[quantity][]" class="form-control quantities" id="quantity-' + counter + '" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" value="1" required>';
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
                    general_total = parseFloat(general_total) + parseFloat(cost);

                    // Update dinamic total
                    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));

                    // Update general total
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
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

                // Substract row total from general total
                general_total = general_total - row_total;
                $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
            }
        });

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            // Substract previous row total from general total
            general_total = general_total - previous_total;

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

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
        });

        $(document).on('change', '.quantitiesproduce', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            // Substract previous row total from general total
            general_total = general_total - previous_total;

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

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
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

                    // Substract previous row total from general total
                    general_total = general_total - previous_total;

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

                    // Update general total
                    general_total = general_total + new_total;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });
        });

        $('#add-bake-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <p style="display: inline;" id="bkey-' + counter_bake + '"></p>'
            html += '   </td>'
            html += '   <td>';
            html += '       <select name="bake_breads[bake_bread_size_id][]" class="form-control bake_breads" id="b-' + counter_bake + '">';
            @foreach($bake_breads as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <p style="display: none;" id="bcost-' + counter_bake + '"></p>';
            html += '       <p style="display: none;" id="btotal-' + counter_bake + '"></p>';
            html += '       <input type="text" name="bake_breads[quantity][]" class="form-control bake_breads_quantities" id="bquantity-' + counter_bake + '" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" value="1" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row-bake" id="delete_bake-' + counter_bake + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            // First bake bread
            var bake_bread_size_id = {{ $bake_breads[0]['id'] }};

            // Get bake bread
            $.ajax({
                url: '{{ url('/bake_bread_sizes/get_row') }}',
                data: 'id=' + bake_bread_size_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    var internal_count = counter_bake - 1;

                    $('#bkey-' + internal_count).text(data.bake_bread_size_key);
                    $('#bcost-' + internal_count).text(data.cost);
                    $('#btotal-' + internal_count).text(data.cost);

                    // Update general total
                    general_total = general_total + data.cost;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });

            counter_bake++;

            $('#bake-table > tbody').append(html);
        });

        $(document).on('click', '.del-row-bake', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar base?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#btotal-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from general total
                general_total = general_total - row_total;
                $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
            }
        });

        $(document).on('change', '.bake_breads', function(e) {
            var bake_bread_size_id = $(this).val();
            var row_id = $(this).attr('id').split("-")[1];

            // Get prepared product
            $.ajax({
                url: '{{ url('/bake_bread_sizes/get_row') }}',
                data: 'id=' + bake_bread_size_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#bkey-' + row_id).text(data.bake_bread_size_key);
                    $('#bcost-' + row_id).text(data.cost);

                    // Substract previous row total from general total
                    var previous_total = format_number_from_separator($('#btotal-' + row_id).text());
                    general_total = general_total - previous_total;

                    var new_total = 0;

                    if($('#bquantity-' + row_id).val() > 0) {
                        new_total = $('#bquantity-' + row_id).val() * data.cost;
                        $('#btotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                    }
                    else {
                        $('#btotal-' + row_id).text('0.00');
                    }

                    // Update general total
                    general_total = general_total + new_total;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });
        });

        $(document).on('change', '.bake_breads_quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from general total
            var previous_total = format_number_from_separator($('#btotal-' + row_id).text());
            general_total = general_total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $(this).val() * format_number_from_separator($('#bcost-' + row_id).text());
                $('#btotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#btotal-' + row_id).text('0.00');
                $('#bquantity-' + row_id).val('');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
        });

        $('#add-prepared-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <p style="display: inline;" id="pkey-' + counter_prepared + '"></p>'
            html += '   </td>'
            html += '   <td>';
            html += '       <select name="prepared_products[prepared_product_id][]" class="form-control prepared_products" id="p-' + counter_prepared + '">';
            @foreach($prepared_products as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <p style="display: none;" id="pcost-' + counter_prepared + '"></p>';
            html += '       <p style="display: none;" id="ptotal-' + counter_prepared + '"></p>';
            html += '       <input type="text" name="prepared_products[quantity][]" class="form-control prepared_products_quantities" id="pquantity-' + counter_prepared + '" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" value="1" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row-prepared" id="delete_prepared-' + counter_prepared + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            // First prepared product
            var prepared_product_id = {{ $prepared_products[0]['id'] }};

            // Get prepared product
            $.ajax({
                url: '{{ url('/prepared_products/get_row') }}',
                data: 'id=' + prepared_product_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    var internal_count = counter_prepared - 1;

                    $('#pkey-' + internal_count).text(data.product_key);
                    $('#pcost-' + internal_count).text(data.cost);
                    $('#ptotal-' + internal_count).text(data.cost);

                    // Update general total
                    general_total = general_total + data.cost;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });

            counter_prepared++;

            $('#prepared-table > tbody').append(html);
        });

        $(document).on('click', '.del-row-prepared', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar preparado?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#ptotal-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from general total
                general_total = general_total - row_total;
                $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
            }
        });

        $(document).on('change', '.prepared_products', function(e) {
            var prepared_product_id = $(this).val();
            var row_id = $(this).attr('id').split("-")[1];

            // Get prepared product
            $.ajax({
                url: '{{ url('/prepared_products/get_row') }}',
                data: 'id=' + prepared_product_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#pkey-' + row_id).text(data.product_key);
                    $('#pcost-' + row_id).text(data.cost);

                    // Substract previous row total from general total
                    var previous_total = format_number_from_separator($('#ptotal-' + row_id).text());
                    general_total = general_total - previous_total;

                    var new_total = 0;

                    if($('#pquantity-' + row_id).val() > 0) {
                        new_total = $('#pquantity-' + row_id).val() * data.cost;
                        $('#ptotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                    }
                    else {
                        $('#ptotal-' + row_id).text('0.00');
                    }

                    // Update general total
                    general_total = general_total + new_total;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });
        });

        $(document).on('change', '.prepared_products_quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from general total
            var previous_total = format_number_from_separator($('#ptotal-' + row_id).text());
            general_total = general_total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $(this).val() * format_number_from_separator($('#pcost-' + row_id).text());
                $('#ptotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#ptotal-' + row_id).text('0.00');
                $('#pquantity-' + row_id).val('');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
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
            html += '       <p style="display: none;" id="rcost-' + counter_resources + '"></p>';
            html += '       <p style="display: none;" id="rtotal-' + counter_resources + '"></p>';
            html += '       <input type="text" name="resources[production_time][]" class="form-control resources_times" id="rtime-' + counter_resources + '" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo')" value="1" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="resources[quantity_to_produce][]" class="form-control resources_quantities" id="rquantity-' + counter_resources + '" value="1" maxlength="3" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row-resource" id="delete_resource-' + counter_resources + '"><i class="fa fa-trash"></i></a>';
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
                    $('#rcost-' + internal_count).text(data.cost);
                    $('#rtotal-' + internal_count).text(data.cost);

                    // Update general total
                    general_total = parseFloat(general_total) + parseFloat(data.cost);
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });

            counter_resources++;

            $('#resources-table > tbody').append(html);
        });

        $(document).on('click', '.del-row-resource', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar recurso?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#rtotal-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from general total
                general_total = general_total - row_total;
                $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
            }
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
                    $('#rcost-' + row_id).text(data.cost);

                    // Substract previous row total from general total
                    var previous_total = format_number_from_separator($('#rtotal-' + row_id).text());
                    general_total = general_total - previous_total;

                    var new_total = 0;

                    if($('#rtime-' + row_id).val() > 0) {
                        new_total = $('#rtime-' + row_id).val() / $('#rquantity-' + row_id).val() * data.cost;
                        $('#rtotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                    }
                    else {
                        $('#rtotal-' + row_id).text('0.00');
                    }

                    // Update general total
                    general_total = general_total + new_total;
                    $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
                }
            });
        });

        $(document).on('change', '.resources_times', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from general total
            var previous_total = format_number_from_separator($('#rtotal-' + row_id).text());
            general_total = general_total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $(this).val() / $('#rquantity-' + row_id).val() * format_number_from_separator($('#rcost-' + row_id).text());
                $('#rtotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#rtotal-' + row_id).text('0.00');
                $('#rtime-' + row_id).val('');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
        });

        $(document).on('change', '.resources_quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from general total
            var previous_total = format_number_from_separator($('#rtotal-' + row_id).text());
            general_total = general_total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $('#rtime-' + row_id).val() / $(this).val() * format_number_from_separator($('#rcost-' + row_id).text());
                $('#rtotal-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#rtotal-' + row_id).text('0.00');
                $('#rtime-' + row_id).val('');

                // Send alert about less or equal than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Update general total
            general_total = general_total + new_total;
            $('#calculated-price').text(format_number_to_separator(general_total.toFixed(2)));
        });
    });
</script>
@endsection