@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de mermas de materias primas') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Mermas') }}</h6>
        <a href="#" data-target="#modal-add-declined-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar merma') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Cantidad merma') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($declined_supplies as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>{{ $element->supply }}</td>
                        <td>{{ $element->lost_quantity }}</td>
                        <td>{{ $element->measurement_unit->measure }}</td>
                        @php
                            $label = '';
                            switch ($element->status) {
                                case 'active':
                                    $label = 'success';
                                    break;

                                case 'inactive':
                                    $label = 'danger';
                                    break;

                                case 'reversed':
                                    $label = 'warning';
                                    break;
                                
                                default:
                                    $label = 'default';
                                    break;
                            }    
                        @endphp
                        <td><span class="label label-{{ $label }}">{{ __(config('dictionaries.declined_supplies_status.' . $element->status)) }}</span></td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/declined_supplies/detail/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Detalle') }}</a>
                                @if($element->status == 'active' || $element->status == 'inactive')
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($element->status == 'active')
                                            <li><a href="{{ url('/declined_supplies/change_status/' . $element->id . '/inactive') }}" onclick="return confirm('{{ __('¿Desactivar merma?') }}');">{{ __('Desactivar') }}</a></li>
                                            <li><a href="#" class="edit-declined-link" data-toggle="modal" data-target="#modal-edit-declined-row" data-id="{{ $element->id }}">{{ __('Editar') }}</a></li>
                                            <li><a href="{{ url('/declined_supplies/revert/' . $element->id) }}" onclick="return confirm('{{ __('¿Revertir merma?') }}');">{{ __('Revertir') }}</a></li>
                                        @endif
                                        @if($element->status == 'inactive')
                                            <li><a href="{{ url('/declined_supplies/change_status/' . $element->id . '/active') }}" onclick="return confirm('{{ __('¿Activar merma?') }}');">{{ __('Activar') }}</a></li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                            <!-- /Split button -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- modal add declined row -->
<div id="modal-add-declined-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar merma') }}</h5>
            </div>
            <form id="form-add-declined" action="{{ url('/declined_supplies/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <label><span class="mandatory">*</span> {{ __('Materia prima') }}</label>
                                <select class="form-control" name="supply_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una materia prima') }} -- </option>
                                    @foreach($supplies as $supply)
                                        <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Cantidad merma') }}</label>
                                <input type="text" class="form-control" name="lost_quantity" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Ubicación') }}</label>
                                <select class="form-control" name="supply_location_id" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una ubicación') }} -- </option>
                                    @foreach($supply_locations as $supply_location)
                                        <option value="{{ $supply_location->id }}">{{ $supply_location->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                                <select class="form-control" name="category" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una categoría') }} -- </option>
                                    @foreach(config('dictionaries.declined_categories') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Razón') }}</label>
                        <textarea rows="5" class="form-control" name="reason" maxlength="120" required></textarea>
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
<!-- /modal add declined row -->

<!-- modal edit declined row -->
<div id="modal-edit-declined-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar merma') }}</h5>
            </div>
            <form id="form-edit-declined" action="{{ url('/declined_supplies/update') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Cantidad merma') }}</label>
                        <input type="text" class="form-control" name="lost_quantity" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" id="modal_declined-lost_quantity" required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Categoría') }}</label>
                        <select class="form-control" name="category" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona una categoría') }} -- </option>
                            @foreach(config('dictionaries.declined_categories') as $key => $value)
                                <option id="modal_declined-category-{{ $key }}" value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Razón') }}</label>
                        <textarea rows="5" class="form-control" name="reason" id="modal_declined-reason" required></textarea>
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
<!-- /modal edit declined row -->
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('.edit-declined-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/declined_supplies/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal_declined-lost_quantity').val(data.lost_quantity);
                    $('#modal_declined-category-' + data.category).prop('selected', true);
                    $('#modal_declined-reason').val(data.reason);
                    $('#form-edit-declined').attr('action', '{{ url('/declined_supplies/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection