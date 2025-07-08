@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Proyección de bases') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/projection/download/2') }}" role="form" method="GET" autocomplete="off">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Bases') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 40%">{{ __('Base') }}</th>
                            <th style="width: 40%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($_GET['bake_breads']))
                            @foreach($_GET['bake_breads'] as $key => $bake_bread)
                                <tr>
                                    <td>
                                        <select name="details[id][]" class="form-control bake_breads select2" id="element-{{ $key }}">
                                            @foreach($bake_bread_sizes as $element)
                                                <option value="{{ $element->id }}" {{ $element->id == $bake_bread['id'] ? 'selected' : '' }}>{{ $element->bake_bread_size_key . ' ' . $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $key }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" value="{{ $bake_bread['quantity'] }}"></td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>
                                <select name="details[id][]" class="form-control bake_breads select2" id="element-0">
                                    @foreach($bake_bread_sizes as $element)
                                        <option value="{{ $element->id }}">{{ $element->bake_bread_size_key . ' ' . $element->name }}</option>
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
                <button type="button" id="move" class="btn btn-primary">{{ __('Trasladar proyección a producción') }}</button>
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
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Bases asociadas') }}</h6>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover" id="projection-bake-breads-table">
                <thead>
                    <tr>
                        <th style="width: 40%">{{ __('Clave') }}</th>
                        <th style="width: 40%">{{ __('Base') }}</th>
                        <th style="width: 10%">{{ __('Cantidad') }}</th>
                        <th style="width: 10%">{{ __('Cantidad disponible') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Preparados asociados') }}</h6>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover" id="projection-prepared-products-table">
                <thead>
                    <tr>
                        <th style="width: 40%">{{ __('Clave') }}</th>
                        <th style="width: 40%">{{ __('Preparado') }}</th>
                        <th style="width: 20%">{{ __('Cantidad') }}</th>
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
    var counter = {{ isset($_GET['bake_breads']) ? count($_GET['bake_breads']) - 1 : 0 }};
    var status = {{ isset($_GET['bake_breads']) ? 1 : 0 }};

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
            html += '       <select name="details[id][]" class="form-control bake_breads select2" id="element-' + counter + '">';
            @foreach($bake_bread_sizes as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->bake_bread_size_key . " " . $element->name }}</option>';
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

            $('.bake_breads:last').select2({
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

            // regular expression for decimals more than 0 with 7 integer digits and 3 decimal digits as maximum
            var regex = new RegExp('^(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})$');
            
            if(!regex.test($(this).val())) {
                $('#quantity-' + row_id).val('');

                // Send alert about wrong quantity
                alert('@lang("La cantidad no puede tener este formato")');
            }
        });

        $(document).on('click', '#calculate', function() {
            calculate_projection();
        });

        $(document).on('click', '#move', function() {
            var resp = confirm('¿Trasladar proyección a producción?');
            if(resp) {
                window.location.href = '{{ url('/bake_breads_production/add') }}?' + $('form').serialize();
            }
        });

        function calculate_projection() {
            // Empty the projection tables
            $('#projection-table > tbody').empty();
            $('#projection-bake-breads-table > tbody').empty();
            $('#projection-prepared-products-table > tbody').empty();

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
                url: '{{ url('/projection/calculate/2') }}',
                data: $('form').serialize(),
                type: 'post',
                cache: false,
                success: function(data) {
                    var html = '';
                    var projection_total = 0;

                    Object.entries(data['supplies']).forEach(element => {
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

                    Object.entries(data['bake_breads']).forEach(element => {
                        var html = '';
                        html += '<tr>';
                        html += '   <td><a target="_blank" href="./bake_breads?bake_breads[0][id]=' + element[1].bake_bread_size_id + '&bake_breads[0][quantity]=' + element[1].quantity.toFixed(3) + '">' + element[1].bake_bread_size_key + '</a></td>';
                        html += '   <td>' + element[1].bake_bread_size + '</td>';
                        html += '   <td>' + element[1].quantity.toFixed(3) + '</td>';
                        html += '   <td>' + element[1].available_quantity + '</td>';
                        html += '</tr>';

                        $('#projection-bake-breads-table > tbody').append(html);
                    });

                    Object.entries(data['prepared_products']).forEach(element => {
                        var html = '';
                        html += '<tr>';
                        html += '   <td><a target="_blank" href="./prepared?prepared_products[0][id]=' + element[1].prepared_product_id + '&prepared_products[0][quantity]=' + element[1].quantity.toFixed(3) + '">' + element[1].prepared_product_key + '</a></td>';
                        html += '   <td>' + element[1].prepared_product + '</td>';
                        html += '   <td>' + element[1].quantity.toFixed(3) + '</td>';
                        html += '</tr>';

                        $('#projection-prepared-products-table > tbody').append(html);
                    });
                }
            });
        }
    });
</script>
@endsection