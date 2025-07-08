@extends('layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
@endsection

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar transferencia (s) de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/supply_transfers') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información de transferencias de materia prima') }}</h6>
    </div>
    <div class="panel-body">
        <form action="{{ url('/supply_transfers/store') }}" role="form" method="POST" autocomplete="off">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover" id="supply_transfers-table">
                    <thead>
                        <tr>
                            <th style="width: 20%">{{ __('Materia prima') }}</th>
                            <th style="width: 10%">{{ __('Cantidad a transferir') }}</th>
                            <th style="width: 20%">{{ __('Ubicación de origen') }}</th>
                            <th style="width: 20%">{{ __('Ubicación de destino') }}</th>
                            <th style="width: 20%">{{ __('Comentario') }}</th>
                            <th style="width: 10%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control supply_transfers select2" name="supply_transfers[supply_id][]" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una materia prima') }} -- </option>
                                    @foreach($supplies as $element)
                                        <option value="{{ $element->id }}">{{ $element->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="supply_transfers[transferred_quantity][]" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" required>
                            </td>
                            <td>
                                <select class="form-control" name="supply_transfers[source_location_id][]" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una ubicación') }} -- </option>
                                    @foreach($supply_locations as $element)
                                        <option value="{{ $element->id }}">{{ $element->location }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="supply_transfers[destination_location_id][]" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una ubicación') }} -- </option>
                                    @foreach($supply_locations as $element)
                                        <option value="{{ $element->id }}">{{ $element->location }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="supply_transfers[comment][]" maxlength="90">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@if(isset($transfers_success) && isset($transfers_errors))
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Resultados') }}</h6>
        </div>
        <div class="panel-body">
            @if(!empty($transfers_success))
                <p><strong style="color: green">{{ __('Transferencias exitosas:') }}</strong></p>
                @foreach($transfers_success as $element)
                    <p>{{ $element }}</p>
                @endforeach
            @endif
            @if(!empty($transfers_errors))
                <p><strong style="color: red">{{ __('¡Errores!') }}</strong></p>
                @foreach($transfers_errors as $element)
                    <p>{{ $element }}</p>
                @endforeach
            @endif
        </div>
    </div>
@endif
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({
            language: "es"
        });

        $('#add-row').click(function(e) {
            e.preventDefault();
            
            var html = '';
            html += '<tr>';
            html += '   <td>';
            html += '       <select class="form-control supply_transfers select2" name="supply_transfers[supply_id][]" required>';
            html += '           <option hidden disabled selected value> -- {{ __("Selecciona una materia prima") }} -- </option>';
            @foreach($supplies as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="supply_transfers[transferred_quantity][]" class="form-control" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <select class="form-control" name="supply_transfers[source_location_id][]" required>';
            html += '           <option hidden disabled selected value> -- {{ __("Selecciona una ubicación") }} -- </option>';
            @foreach($supply_locations as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->location }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <select class="form-control" name="supply_transfers[destination_location_id][]" required>';
            html += '           <option hidden disabled selected value> -- {{ __("Selecciona una ubicación") }} -- </option>';
            @foreach($supply_locations as $element)
            html += '           <option value="{{ $element->id }}">{{ $element->location }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="supply_transfers[comment][]" class="form-control" maxlength="90">';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';
            
            $('#supply_transfers-table > tbody').append(html);

            $('.supply_transfers:last').select2({
                language: "es"
            });
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                $(this).parent().parent().parent().remove();
            }
        });
    });
</script>
@endsection