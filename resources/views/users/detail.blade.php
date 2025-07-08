@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de usuario') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $user->name }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Nombre de usuario') }}: {{ $user->username }}</li>
        <li class="list-group-item">{{ __('Perfil') }}: {{ __(config('dictionaries.user_profiles.' . $user->profile)) }}</li>
        <li class="list-group-item">{{ __('Status') }}: {{ $user->status ? __('Activo') : __('Inactivo') }}
        </li>
        <li class="list-group-item">{{ __('Fecha creación') }}: {{ $user->created }}</li>
        <li class="list-group-item">{{ __('Fecha actualización') }}: {{ $user->modified }}</li>
    </ul>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Contactos') }}</h6>
        <a href="#" data-target="#modal-add-contact-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar') }}</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('Nombre') }}</th>
                <th>{{ __('Parentezco') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('No. Celular') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->contacts as $element)
                <tr>
                    <td>{{ $element->name }}</td>
                    <td>{{ $element->relationship }}</td>
                    <td>{{ $element->email }}</td>
                    <td>{{ $element->mobile }}</td>
                    <td>
                        <!-- Split button -->
                        <div class="btn-group pull-right">
                            <a href="#" class="btn btn-default btn-sm edit-contact-link" data-target="#modal-edit-contact-row" data-toggle="modal" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                        </div>
                        <!-- /Split button -->                  
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal add contact row -->
<div id="modal-add-contact-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar contacto') }}</h5>
            </div>
            <form action="{{ url('/user_contacts/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <input type="text" name="user_id" value="{{ $user->id }}" hidden>
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Parentezco') }}</label>
                                <input type="text" class="form-control" name="relationship" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('No. Celular') }}</label>
                                <input type="text" class="form-control" name="mobile" required>
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
<!-- /modal add contact row -->

<!-- modal edit contact row -->
<div id="modal-edit-contact-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar contacto') }}</h5>
            </div>
            <form action="{{ url('/user_contacts/update') }}" role="form" method="POST" autocomplete="off" id="form-contact-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" id="modal-contact_name" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Parentezco') }}</label>
                                <input type="text" class="form-control" name="relationship" id="modal-contact_relationship" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" id="modal-contact_email" required>
                            </div>
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('No. Celular') }}</label>
                                <input type="text" class="form-control" name="mobile" id="modal-contact_mobile" required>
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
<!-- /modal edit contact row -->
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('.edit-contact-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/user_contacts/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-contact_name').val(data.name);
                    $('#modal-contact_relationship').val(data.relationship);
                    $('#modal-contact_email').val(data.email);
                    $('#modal-contact_mobile').val(data.mobile);
                    $('#form-contact-edit').attr('action', '{{ url('/user_contacts/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection