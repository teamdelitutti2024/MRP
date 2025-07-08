@extends('layouts.master')

@section('header')
    <link href="{{ url('/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ url('js/jquery.datetimepicker.full.min.js') }}"></script>
@endsection

@php
    $today = date('Y-m-d H:i');
    $datetimepicker_theme = 'default';
    if (date('G') < 7 || date('G') > 17) {
        $datetimepicker_theme = 'dark';
    }
@endphp

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar pedido') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/orders') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/orders/store') }}" role="form" method="POST" autocomplete="off" id="order">
    @csrf
    <input type="text" name="total" id="total" hidden>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de pedido') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Sucursal') }}</label>
                        <select class="form-control" name="branch_id" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona una sucursal') }} -- </option>
                            @foreach($branches as $element)
                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <div class="alert alert-danger fade in widget-inner error-date" style="display: none;">
                            <i class="fa fa-times" id="error"></i> {{ __('La fecha y hora de pedido no puede ser menor a la fecha y hora actual') }}
                        </div>
                        <label><span class="mandatory">*</span> {{ __('Fecha de pedido') }}</label>
                        <input type="text" class="form-control" name="delivery_date" id="delivery_date" value="{{ $today }}" readonly required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de pedido') }}</h6>
            <span class="pull-right label label-info">{{ __('Total de pedido') }}: $<span id="dinamic-total">0.00</span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad a solicitar no puede ser menor o igual a 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th style="width: 25%">{{ __('Producto') }}</th>
                            <th style="width: 20%">{{ __('Tamaño') }}</th>
                            <th style="width: 15%">{{ __('Precio') }}</th>
                            <th style="width: 15%">{{ __('Cantidad a solicitar') }}</th>
                            <th style="width: 15%">{{ __('Total') }}</th>
                            <th style="width: 10%">
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
                                <select name="details[product_id][]" class="form-control products" id="product-0" required>
                                    @foreach($products as $element)
                                        <option value="{{ $element->id }}">{{ $element->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="details[product_size_id][]" class="form-control product_sizes" id="product_size-0" required></select>
                            </td>
                            <td>$<p style="display: inline;" id="price-0"></p></td>
                            <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-0" maxlength="3" value="1" pattern="(100|[1-9][0-9]?)" title="{{ __('Número entero entre 1 y 100') }}" required></td>
                            <td>$<p style="display: inline;" id="total-0">0.00</p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary" id="submit-order">
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
    var total = 0;
    var counter = 0;

    function change_product_sizes(product_id, row_id) {
        $.ajax({
            url: '{{ url('/product_sizes/get_product_sizes') }}',
            data: 'id=' + product_id,
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function(data) {
                $('#product_size-' + row_id).empty();
                for (let i = 0; i < data.length; i++) {
                    $('#product_size-' + row_id).append($('<option>', { 
                        value: data[i].id,
                        text : data[i].name
                    }));

                    if(i == 0) {
                        // Substract previous row total from dinamic total
                        var previous_total = format_number_from_separator($('#total-' + row_id).text());
                        total = total - previous_total;

                        $('#price-' + row_id).text(format_number_to_separator(data[i].price));
                        $('#total-' + row_id).text(format_number_to_separator(data[i].price));

                        total = parseFloat(total) + parseFloat(data[i].price);

                        $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        $.datetimepicker.setLocale('es');

        const today = new Date();

        const today_input = '{{ $today }}';

        $('#delivery_date').datetimepicker({
            format: 'Y-m-d H:i',
            minDate: 0,
            step: 5,
            theme: '{{ $datetimepicker_theme }}',
            onChangeDateTime: function(current_time, input) {
                if(current_time < today) {
                    $('.error-date').show();
                    $('.error-date').fadeOut(5000);

                    $('#delivery_date').val(today_input);
                }
            }
        });

        // Get the first product id to populate the product sizes select
        var product_id = $('#product-0').val();

        // Populate product sizes select according to product
        change_product_sizes(product_id, counter);

        $('#add-row').click(function(e) {
            e.preventDefault();

            counter++;

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <select name="details[product_id][]" class="form-control products" id="product-' + counter + '" required>';
            @foreach($products as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <select name="details[product_size_id][]" class="form-control product_sizes" id="product_size-' + counter + '" required></select>';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="price-' + counter + '"></p></td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-' + counter + '" maxlength="3" value="1" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" required>';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="total-' + counter + '"></p></td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            $('#details-table > tbody').append(html);

            // Get the product id to populate the product sizes select
            var product_id = $('#product-' + counter).val();

            // Populate product sizes select according to product
            change_product_sizes(product_id, counter);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#total-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from dinamic total
                total = total - row_total;
                $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
            }
        });

        $(document).on('change', '.products', function(e) {
            var product_id = $(this).val();
            var row_id = $(this).attr('id').split("-")[1];

            change_product_sizes(product_id, row_id);
        });

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                new_total = $(this).val() * format_number_from_separator($('#price-' + row_id).text());
                $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
            }
            else {
                $('#total-' + row_id).text('0.00');
            }

            // Add row total to dinamic total
            total = total + new_total;
            $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
        });

        $(document).on('change', '.product_sizes', function(e) {
            var product_size_id = $(this).val();
            var row_id = $(this).attr('id').split("-")[1];

            $.ajax({
                url: '{{ url('/product_sizes/get_row') }}',
                data: 'id=' + product_size_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    // Substract previous row total from dinamic total
                    var previous_total = format_number_from_separator($('#total-' + row_id).text());
                    total = total - previous_total;

                    $('#price-' + row_id).text(format_number_to_separator(data.price));

                    var new_total = 0;

                    if($('#quantity-' + row_id).val() > 0) {
                        new_total = $('#quantity-' + row_id).val() * data.price;
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

        $('#order').submit(function() {
            // Assign dinamic total to total input
            $('#total').val(format_number_from_separator($('#dinamic-total').text()));
        });
    });
</script>
@endsection