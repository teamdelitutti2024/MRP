@extends('layouts.login')

@section('content')
<!-- Login wrapper -->
<div class="login-wrapper">
    <form action="{{ url('/login') }}" role="form" method="POST">
    {{ csrf_field() }}

    @include('layouts.messages')
    @include('layouts.errors')

        <div class="panel panel-default">
            <div class="panel-heading"><h6 class="panel-title"><i class="fa fa-user"></i> {{ __('Bienvenido a Delitutti ERP') }}</h6></div>
            <div class="panel-body">
                <div class="form-group has-feedback">
                    <label>{{ __('Nombre de usuario') }}</label>
                    <input type="text" class="form-control" placeholder="usuario@dominio.com" name="username" id="username">
                    <i class="fa fa-user form-control-feedback"></i>
                </div>

                <div class="form-group has-feedback">
                    <label>{{ __('Contraseña') }}</label>
                    <input type="password" class="form-control" placeholder="{{ __('Contraseña') }}" name="password" id="password">
                    <i class="fa fa-lock form-control-feedback"></i>
                </div>

                <div class="row form-actions">
                    <div class="col-xs-6">
                        <div class="checkbox">
                        <input type="hidden" name="remember_me" value="0">
                        <label>
                            <input type="checkbox" name="remember_me" class="styled" value="1">
                            {{ __('Recuérdame') }}
                        </label>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-bars"></i> {{ __('Iniciar sesión') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>  
<!-- /login wrapper -->
@endsection