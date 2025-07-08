@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Proyección de preparados') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/projection/download/3') }}" role="form" method="GET" autocomplete="off">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Preparados') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 40%">{{ __('Preparado') }}</th>
                            <th style="width: 40%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($_GET['prepared_products']))
                            @foreach($_GET['prepared_products'] as $key => $prepared_product)
                                <tr>
                                    <td>
                                        <select name="details[id][]" class="form-control prepared select2" id="element-{{ $key }}">
                                            @foreach($prepared_products as $element)
                                                <option value="{{ $element->id }}" {{ $element->id == $prepared_product['id'] ? 'selected' : '' }}>{{ $element->product_key . ' ' . $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $key }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" value="{{ $prepared_product['quantity'] }}"></td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>
                                <select name="details[id][]" class="form-control prepared select2" id="element-0">
                                    @foreach($prepared_products as $element)
                                        <option value="{{ $element->id }}">{{ $element->product_key . ' ' . $element->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-0" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" value="1"></td>
                        </tr>
                        @endif
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
    var counter = {{ isset($_GET['prepared_products']) ? count($_GET['prepared_products']) - 1 : 0 }};
    var status = {{ isset($_GET['prepared_products']) ? 1 : 0 }};

    $(document).ready(function() {
        if(status == 1) {
            calculate_projection();
        }

        $('.select2').select2({
            language: "es"
        });

        $('#add-row').click(function(e) {
            e.preventDefault();

            counter++;

            var html = '';
            html += '<tr>';
            html += '   <td>';
            html += '       <select name="details[id][]" class="form-control prepared select2" id="element-' + counter + '">';
            @foreach($prepared_products as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->product_key . " " . $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-' + counter + '" maxlength="3" value="1">';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            $('#details-table > tbody').append(html);

            $('.prepared:last').select2({
                language: "es"
            });
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
            
            if(!Number.isInteger(+$(this).val())) {
                $('#quantity-' + row_id).val('');

                // Send alert about wrong quantity
                alert('@lang("La cantidad no puede tener este formato")');
            }
            else {
                // Change the value of total for that row
                if($(this).val() <= 0) {
                    $('#quantity-' + row_id).val('');

                    // Send alert about less or equal than 0
                    $('.error-less-than-quantity').show();
                    $('.error-less-than-quantity').fadeOut(5000);
                }
            }
        });

        $(document).on('click', '#calculate', function() {
            calculate_projection();
        });

        function calculate_projection() {
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
                url: '{{ url('/projection/calculate/3') }}',
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
        };
    });
</script>
@endsection