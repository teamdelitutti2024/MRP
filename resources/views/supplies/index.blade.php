@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de materias primas') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Materias primas') }}</h6>
        <a href="#" data-target="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar materia prima') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplies as $element)
                    <tr>
                        <td>{{ $element->supply_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->supply_category_id ? $element->supply_category->name : '' }}</td>
                        <td>{{ $element->measurement_unit_id ? $element->measurement_unit->measure : ''}}</td>
                        @if($element->is_active)
                            <td><span class="label label-success">{{ __('Activa') }}</span></td>
                        @else
                            <td><span class="label label-danger">{{ __('Inactiva') }}</span></td>
                        @endif
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/supplies/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                    @if($element->is_active)
                                        <li><a href="{{ url('/supplies/change_status/' . $element->id . '/inactive') }}" onclick="return confirm('{{ __('¿Desactivar materia prima?') }}');" >{{ __('Desactivar') }}</a></li>
                                    @else
                                        <li><a href="{{ url('/supplies/change_status/' . $element->id . '/active') }}" onclick="return confirm('{{ __('¿Activar materia prima?') }}');" >{{ __('Activar') }}</a></li>
                                    @endif
                                </ul>
                            </div>
                            <!-- /Split button -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- modal add row -->
<div id="modal-add-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar materia prima') }}</h5>
            </div>
            <form action="{{ url('/supplies/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="alert alert-danger fade in widget-inner error-min_stock_greater-than-max_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad mínima de stock no puede ser mayor a la cantidad máxima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-min_stock_greater-than-reorder_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad mínima de stock no puede ser mayor a la cantidad de reorden')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-max_stock_less-than-min_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad máxima de stock no puede ser menor a la cantidad mínima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-max_stock_less-than-reorder_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad máxima de stock no puede ser menor a la cantidad de reorden')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-reorder_stock_greater-than-max_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad de reorden no puede ser mayor a la cantidad máxima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-reorder_stock_less-than-min_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad de reorden no puede ser menor a la cantidad mínima de stock')}}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" maxlength="90" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                                <select class="form-control" name="supply_key" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                                    @foreach(config('dictionaries.supply_keys') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock mínimo') }}</label>
                                <input type="text" class="form-control" name="min_stock" id="modal-add-min_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock máximo') }}</label>
                                <input type="text" class="form-control" name="max_stock" id="modal-add-max_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock reorden') }}</label>
                                <input type="text" class="form-control" name="reorder_stock" id="modal-add-reorder_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Safety stock') }}</label>
                                <input type="text" class="form-control" name="safety_stock" id="modal-add-safety_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Unidad de medida') }}</label>
                                <select class="form-control" name="measurement_unit_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una unidad de medida') }} -- </option>
                                    @foreach($measurement_units as $element)
                                        <option value="{{ $element->id }}">{{ $element->measure }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                                <select class="form-control" name="supply_category_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una categoría') }} -- </option>
                                    @foreach($supply_categories as $element)
                                        <option value="{{ $element->id }}">{{ $element->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Costo inicial') }}</label>
                                <input type="text" class="form-control" name="initial_cost" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>{{ __('Standard pack') }}</label>
                                <input type="text" class="form-control" name="standard_pack" id="modal-add-standard_pack" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" value="1" required>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Requiere IVA') }}</label>
                                <select class="form-control" name="requires_iva">
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Requiere IEPS') }}</label>
                                <select class="form-control" name="requires_ieps">
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cerrar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /modal add row -->

<!-- modal edit row -->
<div id="modal-edit-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar materia prima') }}</h5>
            </div>
            <form action="{{ url('/supplies/update') }}" role="form" method="POST" autocomplete="off" id="form-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="alert alert-danger fade in widget-inner error-min_stock_greater-than-max_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad mínima de stock no puede ser mayor a la cantidad máxima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-min_stock_greater-than-reorder_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad mínima de stock no puede ser mayor a la cantidad de reorden')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-max_stock_less-than-min_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad máxima de stock no puede ser menor a la cantidad mínima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-max_stock_less-than-reorder_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad máxima de stock no puede ser menor a la cantidad de reorden')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-reorder_stock_greater-than-max_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad de reorden no puede ser mayor a la cantidad máxima de stock')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-reorder_stock_less-than-min_stock" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad de reorden no puede ser menor a la cantidad mínima de stock')}}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" id="modal-name" maxlength="90" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                                <select class="form-control" name="supply_key" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                                    @foreach(config('dictionaries.supply_keys') as $key => $value)
                                        <option value="{{ $key }}" id="modal-supply_key-{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock mínimo') }}</label>
                                <input type="text" class="form-control" name="min_stock" id="modal-min_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock máximo') }}</label>
                                <input type="text" class="form-control" name="max_stock" id="modal-max_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Stock reorden') }}</label>
                                <input type="text" class="form-control" name="reorder_stock" id="modal-reorder_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Safety stock') }}</label>
                                <input type="text" class="form-control" name="safety_stock" id="modal-safety_stock" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Unidad de medida') }}</label>
                                <select class="form-control" name="measurement_unit_id" id="modal-measurement_unit_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una unidad de medida') }} -- </option>
                                    @foreach($measurement_units as $element)
                                        <option value="{{ $element->id }}" id="modal-measurement_unit_id-{{ $element->id }}">{{ $element->measure }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                                <select class="form-control" name="supply_category_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una categoría') }} -- </option>
                                    @foreach($supply_categories as $element)
                                        <option value="{{ $element->id }}" id="modal-category-{{ $element->id }}">{{ $element->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Costo inicial') }}</label>
                                <input type="text" class="form-control" name="initial_cost" id="modal-initial_cost" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>{{ __('Standard pack') }}</label>
                                <input type="text" class="form-control" name="standard_pack" id="modal-standard_pack" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Requiere IVA') }}</label>
                                <select class="form-control" name="requires_iva">
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}" id="modal-requires_iva-{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Requiere IEPS') }}</label>
                                <select class="form-control" name="requires_ieps">
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}" id="modal-requires_ieps-{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cerrar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /modal edit row -->
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('#modal-add-min_stock').change(function() {
            let min_stock = parseFloat($('#modal-add-min_stock').val());
            let max_stock = parseFloat($('#modal-add-max_stock').val());
            let reorder_stock = parseFloat($('#modal-add-reorder_stock').val());

            if(max_stock != '' && max_stock >= 0) {
                if(min_stock > max_stock) {
                    // Send alert about greater quantity
                    $('.error-min_stock_greater-than-max_stock').show();
                    $('.error-min_stock_greater-than-max_stock').fadeOut(5000);
                    $('#modal-add-min_stock').val('');
                }
            }

            if(reorder_stock != '' && reorder_stock >= 0) {
                if(min_stock > reorder_stock) {
                    // Send alert about greater quantity
                    $('.error-min_stock_greater-than-reorder_stock').show();
                    $('.error-min_stock_greater-than-reorder_stock').fadeOut(5000);
                    $('#modal-add-min_stock').val('');
                }
            }
        });

        $('#modal-add-max_stock').change(function() {
            let min_stock = parseFloat($('#modal-add-min_stock').val());
            let max_stock = parseFloat($('#modal-add-max_stock').val());
            let reorder_stock = parseFloat($('#modal-add-reorder_stock').val());

            if(min_stock != '' && min_stock >= 0) {
                if(max_stock < min_stock) {
                    // Send alert about less quantity
                    $('.error-max_stock_less-than-min_stock').show();
                    $('.error-max_stock_less-than-min_stock').fadeOut(5000);
                    $('#modal-add-max_stock').val('');
                }
            }

            if(reorder_stock != '' && reorder_stock >= 0) {
                if(max_stock < reorder_stock) {
                    // Send alert about less quantity
                    $('.error-max_stock_less-than-reorder_stock').show();
                    $('.error-max_stock_less-than-reorder_stock').fadeOut(5000);
                    $('#modal-add-max_stock').val('');
                }
            }
        });

        $('#modal-add-reorder_stock').change(function() {
            let min_stock = parseFloat($('#modal-add-min_stock').val());
            let max_stock = parseFloat($('#modal-add-max_stock').val());
            let reorder_stock = parseFloat($('#modal-add-reorder_stock').val());

            if(max_stock != '' && max_stock >= 0) {
                if(reorder_stock > max_stock) {
                    // Send alert about greater quantity
                    $('.error-reorder_stock_greater-than-max_stock').show();
                    $('.error-reorder_stock_greater-than-max_stock').fadeOut(5000);
                    $('#modal-add-reorder_stock').val('');
                }
            }

            if(min_stock != '' && min_stock >= 0) {
                if(reorder_stock < min_stock) {
                    // Send alert about less quantity
                    $('.error-reorder_stock_less-than-min_stock').show();
                    $('.error-reorder_stock_less-than-min_stock').fadeOut(5000);
                    $('#modal-add-reorder_stock').val('');
                }
            }
        });

        $('#modal-min_stock').change(function() {
            let min_stock = parseFloat($('#modal-min_stock').val());
            let max_stock = parseFloat($('#modal-max_stock').val());
            let reorder_stock = parseFloat($('#modal-reorder_stock').val());

            if(min_stock > max_stock) {
                // Send alert about greater quantity
                $('.error-min_stock_greater-than-max_stock').show();
                $('.error-min_stock_greater-than-max_stock').fadeOut(5000);
                $('#modal-min_stock').val('');
            }
            else if(min_stock > reorder_stock) {
                // Send alert about greater quantity
                $('.error-min_stock_greater-than-reorder_stock').show();
                $('.error-min_stock_greater-than-reorder_stock').fadeOut(5000);
                $('#modal-min_stock').val('');
            }
        });

        $('#modal-max_stock').change(function() {
            let min_stock = parseFloat($('#modal-min_stock').val());
            let max_stock = parseFloat($('#modal-max_stock').val());
            let reorder_stock = parseFloat($('#modal-reorder_stock').val());

            if(max_stock < min_stock) {
                // Send alert about less quantity
                $('.error-max_stock_less-than-min_stock').show();
                $('.error-max_stock_less-than-min_stock').fadeOut(5000);
                $('#modal-max_stock').val('');
            }
            else if(max_stock < reorder_stock) {
                // Send alert about less quantity
                $('.error-max_stock_less-than-reorder_stock').show();
                $('.error-max_stock_less-than-reorder_stock').fadeOut(5000);
                $('#modal-max_stock').val('');
            }
        });

        $('#modal-reorder_stock').change(function() {
            let min_stock = parseFloat($('#modal-min_stock').val());
            let max_stock = parseFloat($('#modal-max_stock').val());
            let reorder_stock = parseFloat($('#modal-reorder_stock').val());

            if(reorder_stock > max_stock) {
                // Send alert about greater quantity
                $('.error-reorder_stock_greater-than-max_stock').show();
                $('.error-reorder_stock_greater-than-max_stock').fadeOut(5000);
                $('#modal-reorder_stock').val('');
            }
            else if(reorder_stock < min_stock) {
                // Send alert about less quantity
                $('.error-reorder_stock_less-than-min_stock').show();
                $('.error-reorder_stock_less-than-min_stock').fadeOut(5000);
                $('#modal-reorder_stock').val('');
            }
        });

        $('.edit-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/supplies/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    if(data.measurement_unit_id) {
                        $('#modal-measurement_unit_id-' + data.measurement_unit_id).prop('selected', true);
                        $('#modal-measurement_unit_id').prop('disabled', true);
                    }
                    else {
                        $('#modal-measurement_unit_id').prop('disabled', false);
                    }

                    let supply_key = data.supply_key.split('-');
                    $('#modal-supply_key-' + supply_key[1]).prop('selected', true);

                    $('#modal-name').val(data.name);
                    
                    if(data.supply_category_id) {
                        $('#modal-category-' + data.supply_category_id).prop('selected', true);
                    }

                    $('#modal-initial_cost').val(data.initial_cost);
                    $('#modal-min_stock').val(data.min_stock);
                    $('#modal-max_stock').val(data.max_stock);
                    $('#modal-reorder_stock').val(data.reorder_stock);
                    $('#modal-safety_stock').val(data.safety_stock);
                    $('#modal-standard_pack').val(data.standard_pack);
                    $('#modal-requires_iva-' + data.requires_iva).prop('selected', true);
                    $('#modal-requires_ieps-' + data.requires_ieps).prop('selected', true);
                    $('#form-edit').attr('action', '{{ url('/supplies/update/') }}/' + row_id);
                }
            });
        });

        $('#form-edit').submit(function() {
            $('#modal-measurement_unit_id').prop('disabled', false);
        });
    });
</script>
@endsection