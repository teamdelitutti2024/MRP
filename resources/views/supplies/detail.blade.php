@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/supplies') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $supply->name . ' ' . $supply->supply_key }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('ID') }}: {{ $supply->id }}</li>
        <li class="list-group-item">{{ __('Categoría') }}: {{ $supply->supply_category_id ? $supply->supply_category->name : '' }}</li>
        <li class="list-group-item">{{ __('Costo promedio (por unidad)') }}: ${{ number_format($supply->average_cost, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Costo inicial') }}: ${{ number_format($supply->initial_cost, 2, '.', ',') }}</li>
        <li class="list-group-item">{{ __('Standard pack') }}: {{ $supply->standard_pack }}</li>
        <li class="list-group-item">{{ __('Stock mínimo') }}: {{ $supply->min_stock }}</li>
        <li class="list-group-item">{{ __('Stock máximo') }}: {{ $supply->max_stock }}</li>
        <li class="list-group-item">{{ __('Stock reorden') }}: {{ $supply->reorder_stock }}</li>
        <li class="list-group-item">{{ __('Safety stock') }}: {{ $supply->safety_stock }}</li>
        <li class="list-group-item">{{ __('Unidad de medida') }}: {{ $supply->measurement_unit_id ? $supply->measurement_unit->measure : '' }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ $supply->is_active ? __('Activa') : __('Inactiva') }}</li>
        <li class="list-group-item">{{ __('Requiere IVA') }}: {{ __(config('dictionaries.common_answers.' . $supply->requires_iva)) }}</li>
        <li class="list-group-item">{{ __('Requiere IEPS') }}: {{ __(config('dictionaries.common_answers.' . $supply->requires_ieps)) }}</li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $supply->created }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Proveedores asociados') }}</h6>
        <a href="#" data-target="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Asociar proveedor') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Precio de lista') }}</th>
                    <th>{{ __('Costo') }}</th>
                    <th>{{ __('Fecha asociación')}}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply->suppliers as $element)
                    <tr>
                        <td>{{ $element->supplier_key }}</td>
                        <td><a href="{{ url('/suppliers/detail/' . $element->id) }}">{{ $element->name }}</a></td>
                        <td>${{ number_format($element->association->price, 2, '.', ',') }}</td>
                        <td>${{ number_format($element->association->cost, 2, '.', ',') }}</td>
                        <td>{{ $element->association->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="{{ $element->association->id }}" >{{ __('Editar') }}</a>
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
                <h5 class="modal-title">{{ __('Asociar proveedor') }}</h5>
            </div>
            <form action="{{ url('/supplier_supplies/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <input type="text" name="supply_id" value="{{ $supply->id }}" hidden>
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Proveedor') }}</label>
                        <select class="form-control" name="supplier_id" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona un proveedor') }} -- </option>
                            @foreach($not_associated_suppliers as $element)
                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Precio de lista') }}</label>
                                <input type="text" class="form-control" name="price" pattern="(0*[1-9][0-9]*(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Costo') }}</label>
                                <input type="text" class="form-control" name="cost" pattern="(0*[1-9][0-9]*(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 2 cifras decimales como máximo') }}" required>
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
                <h5 class="modal-title">{{ __('Editar asociación de proveedor') }}</h5>
            </div>
            <form action="{{ url('/supplier_supplies/update') }}" role="form" method="POST" autocomplete="off" id="form-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Precio de lista') }}</label>
                                <input type="text" class="form-control" name="price" id="modal-price" pattern="(0*[1-9][0-9]*(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 2 cifras decimales como máximo') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Costo') }}</label>
                                <input type="text" class="form-control" name="cost" id="modal-cost" pattern="(0*[1-9][0-9]*(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 2 cifras decimales como máximo') }}" required>
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
        $('.edit-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/supplier_supplies/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-price').val(data.price);
                    $('#modal-cost').val(data.cost);
                    $('#form-edit').attr('action', '{{ url('/supplier_supplies/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection