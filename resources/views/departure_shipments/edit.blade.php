@extends('layouts.master')

@section('header')
    <link href="{{ url('/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ url('js/jquery.datetimepicker.full.min.js') }}"></script>
@endsection

@php
    $shipment_date = date('Y-m-d H:i', strtotime($departure_shipment->shipment_date));
    $datetimepicker_theme = 'default';
    if (date('G') < 7 || date('G') > 17) {
        $datetimepicker_theme = 'dark';
    }
@endphp

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar embarque de salida') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/departure_shipments') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/departure_shipments/update/' . $departure_shipment->id) }}" role="form" method="POST" autocomplete="off" id="departure-shipment">
    @csrf
    <input type="text" name="total" id="total" hidden>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de embarque de salida') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-danger fade in widget-inner error-date" style="display: none;">
                            <i class="fa fa-times" id="error"></i> {{ __('La fecha de envío no puede ser menor a la fecha actual') }}
                        </div>
                        <p>{{ __('ID Orden') }}: <a href="{{ url('/orders/detail/' . $departure_shipment->order_id) }}">{{ $departure_shipment->order_id }}</a></p>
                        <p>{{ __('Sucursal') }}: <a href="{{ url('/branches/detail/' . $departure_shipment->branch_id) }}">{{ $departure_shipment->branch->name }}</a></p>
                        <br>
                        <label><span class="mandatory">*</span> {{ __('Fecha de envío') }}</label>
                        <input type="text" class="form-control" name="shipment_date" id="shipment_date" value="{{ $shipment_date }}" readonly required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de embarque de salida') }}</h6>
            <span class="pull-right label label-info">{{ __('Total de embarque de salida') }}: $<span id="dinamic-total">{{ number_format($departure_shipment->total, 2, '.', ',') }}</span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 20%">{{ __('Producto') }}</th>
                            <th style="width: 20%">{{ __('Tamaño') }}</th>
                            <th style="width: 10%">{{ __('Precio unidad') }}</th>
                            <th style="width: 15%">{{ __('Cantidad solicitada') }}</th>
                            <th style="width: 15%">{{ __('Cantidad a enviar') }}</th>
                            <th style="width: 10%">{{ __('Total') }}</th>
                            <th style="width: 10%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($departure_shipment->departure_shipment_details as $element)
                            <tr>
                                <td class="mandatory-field">
                                    @if($element->requested_quantity > 0)
                                        <input type="hidden" name="details[product_id][]" value="{{ $element->product_id }}">
                                        {{ $element->product_name }}
                                    @else
                                        <select name="details[product_id][]" class="form-control products" id="product-{{ $counter }}" required>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $element->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>';
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <td>
                                    @if($element->requested_quantity > 0)
                                        <input type="hidden" name="details[product_size_id][]" value="{{ $element->product_size_id }}">
                                        {{ $element->product_size->name }}
                                    @else
                                        <select name="details[product_size_id][]" class="form-control product_sizes" id="product_size-{{ $counter }}" required>
                                            @foreach($element->product->product_sizes as $product_size)
                                                <option value="{{ $product_size->id }}" {{ $element->product_size_id == $product_size->id ? 'selected' : '' }}>{{ $product_size->name }}</option>';
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <input type="hidden" name="details[requested_quantity][]" value="{{ $element->requested_quantity }}">
                                <td>$<p style="display: inline;" id="price-{{ $counter }}">{{ number_format($element->price, 2, '.', ',') }}</p></td>
                                <td><p style="display: inline;">{{ $element->requested_quantity }}</p></td>
                                <td>
                                    @if($element->requested_quantity > 0)
                                        <input type="text" maxlength="3" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $counter }}" value="{{ $element->quantity }}" pattern="(100|[0-9][0-9]?)" title="{{ __('Número entero entre 0 y 100') }}" required>
                                    @else
                                        <input type="text" maxlength="3" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $counter }}" value="{{ $element->quantity }}" pattern="(100|[1-9][0-9]?)" title="{{ __('Número entero entre 1 y 100') }}" required>
                                    @endif                                    
                                </td>
                                <td>$<p style="display: inline;" id="total-{{ $counter }}">{{ number_format($element->quantity * $element->price, 2, '.', ',') }}</p></td>
                                <td>
                                    @if($element->requested_quantity == 0)
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-{{ $counter }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    @endif
                                </td>
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

@section('footer')
<script type="text/javascript">
    var total = {{ $departure_shipment->total }};
    var counter = {{ $counter }};

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

        $('#shipment_date').datetimepicker({
            format: 'Y-m-d H:i',
            minDate: 0,
            step: 5,
            theme: '{{ $datetimepicker_theme }}',
            scrollInput: false,
            onChangeDateTime: function(current_time, input) {
                if(current_time < today) {
                    $('.error-date').show();
                    $('.error-date').fadeOut(5000);

                    $('#shipment_date').val('{{ $shipment_date }}');
                }
            }
        });

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
            html += '   <td><input type="hidden" name="details[requested_quantity][]" value="0"><p style="display: inline;">0</p></td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-' + counter + '" maxlength="3" value="1" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" required>';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="total-' + counter + '">0.00</p></td>';
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

            console.log(product_id, row_id);

            change_product_sizes(product_id, row_id);
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

        $('#departure-shipment').submit(function() {
            // Assign dinamic total to total input
            $('#total').val(format_number_from_separator($('#dinamic-total').text()));
        });
    });
</script>
@endsection