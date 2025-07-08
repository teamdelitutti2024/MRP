@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de usuarios') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Usuarios') }}</h6>
        <a href="#" data-target="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar usuario') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Nombre de usuario') }}</th>
                    <th>{{ __('Perfil') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $element)
                    <tr>
                        <td>{{ $element->name }}</td>
                        <td>{{ $element->username }}</td>
                        <td>{{ __(config('dictionaries.user_profiles.' . $element->profile)) }}</td>
                        @if($element->status)
                            <td><span class="label label-success">{{ __('Activo') }}</span></td>
                        @else
                            <td><span class="label label-danger">{{ __('Inactivo') }}</span></td>
                        @endif
                        <td>{{ $element->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/users/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
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
                <h5 class="modal-title">{{ __('Agregar usuario') }}</h5>
            </div>
            <form action="{{ url('/users/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre de usuario') }}</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Perfil') }}</label>
                                <select class="form-control" name="profile" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                                    @foreach(config('dictionaries.user_profiles') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Contraseña') }}</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Confirmar contraseña') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
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
                <h5 class="modal-title">{{ __('Editar usuario') }}</h5>
            </div>
            <form action="{{ url('/users/update') }}" role="form" method="POST" autocomplete="off" id="form-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" id="modal-name" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre de usuario') }}</label>
                                <input type="text" class="form-control" name="username" id="modal-username" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Perfil') }}</label>
                                <select class="form-control" name="profile" required>
                                    @foreach(config('dictionaries.user_profiles') as $key => $value)
                                        <option value="{{ $key }}" id="modal-profile-{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>{{ __('Contraseña') }}</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="col-sm-6">
                                <label>{{ __('Confirmar contraseña') }}</label>
                                <input type="password" class="form-control" name="password_confirmation">
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
                url: '{{ url('/users/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-name').val(data.name);
                    $('#modal-username').val(data.username);
                    if(data.profile) {
                        $('#modal-profile-' + data.profile).prop('selected', true);
                    }
                    $('#form-edit').attr('action', '{{ url('/users/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection