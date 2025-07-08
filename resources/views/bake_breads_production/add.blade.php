@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar producción de bases') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/bake_breads_production') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/bake_breads_production/store') }}" role="form" method="POST" autocomplete="off" id="bake-breads-production">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Bases') }}</h6>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th style="width: 60%">{{ __('Base') }}</th>
                            <th style="width: 20%">{{ __('Cantidad') }}</th>
                            <th style="width: 20%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($_GET['details']))
                            @for($i = 0; $i < count($_GET['details']['id']); $i++)
                                <tr>
                                    <td>
                                        <select name="details[id][]" class="form-control bake_bread_sizes select2">
                                            @foreach($bake_bread_sizes as $element)
                                                <option value="{{ $element->id }}" {{ $element->id == $_GET['details']['id'][$i] }}>{{ $element->bake_bread_size_key . ' ' . $element->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="details[quantity][]" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" value="{{ $_GET['details']['quantity'][$i] }}" required></td>
                                </tr>
                            @endfor
                        @else
                            <tr>
                                <td>
                                    <select name="details[id][]" class="form-control bake_bread_sizes select2">
                                        @foreach($bake_bread_sizes as $element)
                                            <option value="{{ $element->id }}">{{ $element->bake_bread_size_key . ' ' . $element->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="details[quantity][]" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" value="1" required></td>
                            </tr>
                        @endif
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
            html += '       <select name="details[id][]" class="form-control bake_bread_sizes select2">';
            @foreach($bake_bread_sizes as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->bake_bread_size_key . " " . $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control" name="details[quantity][]" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" value="1" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            $('#details-table > tbody').append(html);

            $('.bake_bread_sizes:last').select2({
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

        $(document).on('submit', '#bake-breads-production', function() {
            var resp = confirm('¿Finalizar producción? Esto actualizará las cantidades de las materias primas necesarias en las ubicaciones correspondientes');

            if(resp) {
                return true;
            }

            return false;
        });
    });
</script>
@endsection