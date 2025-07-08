@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de departamentos') }} <small>{{ __('Ejemplo de subtitulo') }}</small></h5>
    <div class="btn-group">
        <a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i><span class="caret"></span></a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li><a href="#">One more line</a></li>
        </ul>
    </div>
</div>
<!-- /page title -->

<!-- Statistics -->
<ul class="row stats">
    <li class="col-xs-3"><a href="#" class="btn btn-default">52</a> <span>new pending tasks</span></li>
    <li class="col-xs-3"><a href="#" class="btn btn-default">520</a> <span>pending orders</span></li>
    <li class="col-xs-3"><a href="#" class="btn btn-default">14</a> <span>new opened tickets</span></li>
    <li class="col-xs-3"><a href="#" class="btn btn-default">48</a> <span>new user registrations</span></li>
</ul>
<!-- /statistics -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Departamentos') }}</h6>
        <a href="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar departamento') }}</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Nombre') }}</th>
                <th>{{ __('Saldo inicial') }}</th>
                <th>{{ __('Saldo actual') }}</th>
                <th>{{ __('Fecha de registro') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Marketing</td>
                <td>$10,000</td>
                <td>$8,434</td>
                <td>24-nov-2020 00:00:03</td>
                <td>
                    <!-- Split button -->
                    <div class="btn-group pull-right">
                        <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="" >{{ __('Editar') }}</a>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo url('/finance/departments/delete/'); ?>" onclick="return confirm('{{ __('¿Eliminar departamento?') }}');" >{{ __('Eliminar') }}</a></li>
                        </ul>
                    </div>                      
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection