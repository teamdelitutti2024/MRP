@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de recursos') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Recursos') }}</h6>
        <a href="#" data-target="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar recurso') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Costo') }}</th>
                    <th>{{ __('Descripción') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $element)
                    <tr>
                        <td>{{ $element->resource_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->cost }}</td>
                        <td>{{ $element->description }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td><a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="{{ $element->id }}">{{ __('Editar') }}</a></td>
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
                <h5 class="modal-title">{{ __('Agregar recurso') }}</h5>
            </div>
            <form action="{{ url('/resources/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" maxlength="90" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Costo') }}</label>
                                <input type="text" class="form-control" name="cost" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Descripción') }}</label>
                        <textarea rows="3" class="form-control" name="description" maxlength="120"></textarea>
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
                <h5 class="modal-title">{{ __('Editar recurso') }}</h5>
            </div>
            <form action="{{ url('/resources/update') }}" role="form" method="POST" autocomplete="off" id="form-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Clave') }}</label>
                                <input type="text" class="form-control" name="resource_key" id="modal-resource_key" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" id="modal-name" maxlength="60" required>
                            </div>
                            <div class="col-sm-4">
                                <label><span class="mandatory">*</span> {{ __('Costo') }}</label>
                                <input type="text" class="form-control" name="cost" id="modal-cost" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,2})?|0+\.[0-9]{1,2})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 2 cifras decimales como máximo') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Descripción') }}</label>
                        <textarea rows="3" class="form-control" name="description" id="modal-description" maxlength="120"></textarea>
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
                url: '{{ url('/resources/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-resource_key').val(data.resource_key);
                    $('#modal-name').val(data.name);
                    $('#modal-cost').val(data.cost);
                    $('#modal-description').val(data.description);
                    $('#form-edit').attr('action', '{{ url('/resources/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection