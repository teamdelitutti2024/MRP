@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar producción de productos') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/products_production') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/products_production/store') }}" role="form" method="POST" autocomplete="off" id="products-production">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Productos') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 60%">{{ __('Producto') }}</th>
                            <th style="width: 20%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="details[id][]" class="form-control product_sizes select2">
                                    @foreach($product_sizes as $element)
                                        <option value="{{ $element->id }}">{{ $element->product_size_key . ' ' . $element->name . ' - ' . $element->product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control" name="details[quantity][]" maxlength="3" value="1" pattern="[1-9][0-9]?[0-9]?" title="{{ __('Número entero positivo con 3 cifras como máximo') }}" required></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Finalizar') }}" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
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
            html += '       <select name="details[id][]" class="form-control product_sizes select2">';
            @foreach($product_sizes as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->product_size_key . " " . $element->name . " - " . $element->product->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control" name="details[quantity][]" maxlength="3" value="1" pattern="[1-9][0-9]?[0-9]?" title="@lang('Número entero positivo con 3 cifras como máximo')" required>';
            html += '   </td>';
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
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                $(this).parent().parent().parent().remove();
            }
        });

        $(document).on('submit', '#products-production', function() {
            var resp = confirm('¿Finalizar producción? Esto actualizará las cantidades de las materias primas necesarias en las ubicaciones correspondientes');

            if(resp) {
                return true;
            }

            return false;
        });
    });
</script>
@endsection