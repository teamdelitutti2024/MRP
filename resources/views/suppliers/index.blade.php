@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de proveedores') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Proveedores') }}</h6>
        <a href="{{ url('/suppliers/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar proveedor') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Clave') }}</th>
                    <th>{{ __('Nombre') }}</th>
                    <th>{{ __('Contactos preferentes') }}</th>
                    <th>{{ __('Categorías') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $element)
                    <tr>
                        <td>{{ $element->supplier_key }}</td>
                        <td>{{ $element->name }}</td>
                        <td>
                            @foreach($element->contacts()->where('preferred', true)->get() as $contact)
                                <p>{{ $contact->name . ' ' . $contact->mobile }}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach($element->supplies_categories as $key => $category)
                                <p>{{ $category->name }}</p>
                            @endforeach
                        </td>
                        <td>{{ $element->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/suppliers/edit/' . $element->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/suppliers/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
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
@endsection