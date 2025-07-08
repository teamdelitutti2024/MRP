@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ url('/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
    <script type="text/javascript" src="{{ url('js/jquery.datetimepicker.full.min.js') }}"></script>
@endsection

@php
    $today = date('Y-m-d');
    $datetimepicker_theme = 'default';
    if (date('G') < 7 || date('G') > 17) {
        $datetimepicker_theme = 'dark';
    }
@endphp

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar merma de productos') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Productos') }}</h6>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover" id="products-table">
                <thead>
                    <tr>
                        <th style="width: 30%"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <label><span class="mandatory">*</span> {{ __('Selecciona un producto') }}</label>
                            <select class="form-control product_sizes select2">
                                @foreach($product_sizes as $element)
                                    <option value="{{ $element->id }}">{{ $element->product_size_key . ' ' . $element->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="form-actions text-right">
            <button type="button" id="add-row" class="btn btn-primary">{{ __('Agregar') }}</button>
            <button type="button" id="clean" class="btn btn-primary">{{ __('Limpiar') }}</button>
        </div>
    </div>
</div>

<form action="{{ url('/declined_products/store') }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de merma') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="declined-table">
                    <thead>
                        <tr>
                            <th style="width: 10%">{{ __('Clave') }}</th>
                            <th style="width: 20%">{{ __('Producto') }}</th>
                            <th style="width: 20%">{{ __('Tamaño') }}</th>
                            <th style="width: 10%">{{ __('Cantidad merma') }}</th>
                            <th style="width: 15%">{{ __('Fecha') }}</th>
                            <th style="width: 15%">{{ __('Comentarios') }}</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" id="save-declined" value="{{ __('Guardar') }}" class="btn btn-primary" disabled>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
    var products = [];

    $(document).ready(function() {
        $('.select2').select2({
            language: "es"
        });

        $('#clean').click(function(e) {
            // Empty the declined products table
            $('#declined-table > tbody').empty();

            // Empty products array
            products = [];

            $('#save-declined').attr('disabled', true);
        });

        $('#add-row').click(function(e) {
            e.preventDefault();

            // Get current selected product
            var product_id = $('.product_sizes').val();

            // Filter product id from products array
            var element_found = products.filter(element => element == product_id);

            // Validate if product id does not exist on products array
            if(element_found.length == 0) {
                $.ajax({
                    url: '{{ url('/product_sizes/get_row') }}',
                    data: 'id=' + product_id,
                    dataType: 'json',
                    type: 'post',
                    cache: false,
                    success: function(data) {
                        // Add product id to products array
                        products.push(product_id);

                        var html = '';
                        html += '<tr>';
                        html += '   <td class="mandatory-field">';
                        html += '       <span>*</span>';
                        html += '       <p style="display: inline;">' + data.product_size_key + '</p>';
                        html += '   </td>';
                        html += '   <input type="hidden" name="details[product_size_id][]" value="' + data.id + '">';
                        html += '   <td><p style="display: inline;">' + data.product.name + '</p></td>';
                        html += '   <td><p style="display: inline;">' + data.name + '</p></td>';
                        html += '   <td>';
                        html += '       <input type="text" class="form-control" name="details[quantity][]" maxlength="3" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" value="1" required>';
                        html += '   </td>';
                        html += '   <td>';
                        html += '       <input type="text" class="form-control product-date" name="details[product_date][]" value="{{ $today }}" readonly required>';
                        html += '   </td>';
                        html += '   <td>';
                        html += '       <input type="text" class="form-control" name="details[comment][]" maxlength="90">';
                        html += '   </td>';
                        html += '   <td>';
                        html += '       <div class="table-controls">';
                        html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + product_id + '"><i class="fa fa-trash"></i></a>';
                        html += '       </div>';
                        html += '   </td>';
                        html += '</tr>';

                        $('#declined-table > tbody').append(html);

                        $.datetimepicker.setLocale('es');

                        $('.product-date').datetimepicker({
                            format: 'Y-m-d',
                            theme: '{{ $datetimepicker_theme }}',
                            timepicker: false,
                        });

                        $('#save-declined').attr('disabled', false);
                    }
                });
            }
            else {
                // Send alert about product already added
                alert('@lang("El producto ya fue agregado")');
            }
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');

            if(resp) {
                // Remove product id from products array
                products = products.filter(item => item !== row_id);

                // Disable save button if there are no products
                if(products.length == 0) {
                    $('#save-declined').attr('disabled', true);
                }

                $(this).parent().parent().parent().remove();
            }
        });
    });
</script>
@endsection