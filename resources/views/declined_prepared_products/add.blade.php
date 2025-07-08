@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar merma de preparados') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/declined_prepared_products') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div></div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/declined_prepared_products/store') }}" role="form" method="POST" autocomplete="off">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Preparados') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad no puede ser menor o igual a 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th style="width: 40%">{{ __('Preparado') }}</th>
                            <th style="width: 20%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">{{ __('Comentarios') }}</th>
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
                                <select name="details[id][]" class="form-control prepared select2" id="element-0">
                                    @foreach($prepared_products as $element)
                                        <option value="{{ $element->id }}">{{ $element->product_key . ' ' . $element->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-0" value="1" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required></td>
                            <td><input type="text" class="form-control" name="details[comment][]" maxlength="90"></td>
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
            html += '       <select name="details[id][]" class="form-control prepared select2" id="element-' + counter + '">';
            @foreach($prepared_products as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->product_key . " " . $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-' + counter + '" value="1" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,2})?|0+\\.[0-9]{1,2})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo')">';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control" name="details[comment][]" maxlength="90">';
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
    });
</script>
@endsection