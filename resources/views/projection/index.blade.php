@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Proyección de productos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/projection/download/1') }}" role="form" method="GET" autocomplete="off">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Productos') }}</h6>
            <span class="pull-right label label-info">{{ __('Total') }}: $<span id="dinamic-total">{{ $product_sizes[0]['price'] }}</span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad no puede ser menor o igual a 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th style="width: 40%">{{ __('Producto') }}</th>
                            <th style="width: 15%">{{ __('Precio') }}</th>
                            <th style="width: 15%">{{ __('Cantidad') }}</th>
                            <th style="width: 15%">{{ __('Total') }}</th>
                            <th style="width: 15%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="details[id][]" class="form-control product_sizes select2" id="product_size-0">
                                    @foreach($product_sizes as $element)
                                        <option value="{{ $element->id }}">{{ $element->product_size_key . ' ' . $element->name . ' - ' . $element->product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>$<p style="display: inline;" id="price-0">{{ $product_sizes[0]['price'] }}</p></td>
                            <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-0" maxlength="3" value="1"></td>
                            <td>$<p style="display: inline;" id="total-0">{{ $product_sizes[0]['price'] }}</p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Descargar proyección') }}" class="btn btn-primary">
                <button type="button" id="calculate" class="btn btn-primary">{{ __('Calcular proyección') }}</button>
            </div>
        </div>
    </div>
</form>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Detalle de la proyección') }}</h6>
        <span class="pull-right label label-info">{{ __('Total de proyección') }}: $<span id="dinamic-projection-total">0.00</span></span>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover" id="projection-table">
                <thead>
                    <tr>
                        <th style="width: 10%">{{ __('Clave') }}</th>
                        <th style="width: 25%">{{ __('Recurso') }}</th>
                        <th style="width: 10%">{{ __('Cantidad') }}</th>
                        <th style="width: 10%">{{ __('Cantidad disponible') }}</th>
                        <th style="width: 25%">{{ __('Unidad de medida') }}</th>
                        <th style="width: 10%">{{ __('Costo promedio') }}</th>
                        <th style="width: 10%">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
    var total = {{ $product_sizes[0]['price'] }};
    var counter = 0;

    $(document).ready(function() {
        $('.select2').select2({
            language: "es"
        });

        $('#add-row').click(function(e) {
            e.preventDefault();

            counter++;

            var html = '';
            html += '<tr>';
            html += '   <td>';
            html += '       <select name="details[id][]" class="form-control product_sizes select2" id="product_size-' + counter + '">';
            @foreach($product_sizes as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->product_size_key . " " . $element->name . " - " . $element->product->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="price-' + counter + '">{{ $product_sizes[0]["price"] }}</p></td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-' + counter + '" maxlength="3" value="1">';
            html += '   </td>';
            html += '   <td>$<p style="display: inline;" id="total-' + counter + '">{{ $product_sizes[0]["price"] }}</p></td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            $('#details-table > tbody').append(html);

            $('.product_sizes:last').select2({
                language: "es"
            });

            // Add row total to dinamic total
            new_total = {{ $product_sizes[0]['price'] }};
            total = total + new_total;
            $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
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

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;
            
            if(!Number.isInteger(+$(this).val())) {
                $('#total-' + row_id).text('0.00');
                $('#quantity-' + row_id).val('');

                // Send alert about wrong quantity
                alert('@lang("La cantidad no puede tener este formato")');
            }
            else {
                // Change the value of total for that row
                if($(this).val() > 0) {
                    new_total = $(this).val() * format_number_from_separator($('#price-' + row_id).text());
                    $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                }
                else {
                    $('#total-' + row_id).text('0.00');
                    $('#quantity-' + row_id).val('');

                    // Send alert about less or equal than 0
                    $('.error-less-than-quantity').show();
                    $('.error-less-than-quantity').fadeOut(5000);
                }
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
                    $('#total-' + row_id).text(format_number_to_separator(data.price));

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

        $(document).on('click', '#calculate', function() {
            // Empty the projection table
            $('#projection-table > tbody').empty();

            // Set projection total to 0
            $('#dinamic-projection-total').text('0.00');

            // Verify all quantities
            for (let i = 0; i <= counter; i++) {
                if($('#quantity-' + i).val() == '') {
                    alert('@lang("Todas las cantidades son requeridas")');
                    return false;
                }
            }

            $.ajax({
                url: '{{ url('/projection/calculate/1') }}',
                data: $('form').serialize(),
                type: 'post',
                cache: false,
                success: function(data) {
                    var html = '';
                    var projection_total = 0;

                    Object.entries(data).forEach(element => {
                        projection_total += element[1].average_cost * element[1].quantity;
                        html += '<tr>';
                        html += '   <td>' + element[1].supply_key + '</td>';
                        html += '   <td>' + element[1].supply + '</td>';
                        html += '   <td>' + element[1].quantity.toFixed(3) + '</td>';
                        html += '   <td>' + element[1].available_quantity + '</td>';
                        html += '   <td>' + element[1].measure + '</td>';
                        html += '   <td>$' + element[1].average_cost + '</td>';
                        html += '   <td>$' + (element[1].average_cost * element[1].quantity).toFixed(2) + '</td>';
                        html += '</tr>';
                    });

                    $('#projection-table > tbody').append(html);
                    $('#dinamic-projection-total').text(format_number_to_separator(projection_total.toFixed(2)));
                }
            });
        });
    });
</script>
@endsection