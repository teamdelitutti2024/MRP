@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Detalle de proveedor') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/suppliers') }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ $supplier->name . ' ' . $supplier->supplier_key }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item">{{ __('Tiempo de entrega (no. días)') }}: {{ $supplier->delivery_time }}</li>
        <li class="list-group-item">{{ __('Dirección') }}: {{ $supplier->address }}</li>
        <li class="list-group-item">{{ __('Notas') }}: {{ $supplier->notes }}</li>
        <li class="list-group-item">{{ __('Información de pago') }}: {{ $supplier->payment_information }}</li>
        <li class="list-group-item">{{ __('Método preferido de pago') }}: {{ __(config('dictionaries.preferred_payment_methods.' . $supplier->preferred_payment_method)) }}</li>
        <li class="list-group-item">{{ __('Categoría') }}: {{ $supplier->category->name }}</li>
        <li class="list-group-item">{{ __('Condición comercial') }}: <a href="{{ url('/commercial_terms/detail/' . $supplier->commercial_term_id) }}">{{ $supplier->commercial_term->name }}</a></li>
        <li class="list-group-item">{{ __('Requiere factura') }}: {{ __(config('dictionaries.common_answers.' . $supplier->require_invoice)) }}</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Materias primas asociadas') }}</h6>
        <a href="#" data-target="#modal-add-supply-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Asociar materia prima') }}</a>
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
                @foreach($supplier->supplies as $element)
                    <tr>
                        <td>{{ $element->supply_key }}</td>
                        <td><a href="{{ url('/supplies/detail/' . $element->id) }}">{{ $element->name }}</a></td>
                        <td>${{ number_format($element->association->price, 2, '.', ',') }}</td>
                        <td>${{ number_format($element->association->cost, 2, '.', ',') }}</td>
                        <td>{{ $element->association->created }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-supply-row" data-id="{{ $element->association->id }}" >{{ __('Editar') }}</a>
                            </div>
                            <!-- /Split button -->                  
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Condición comercial asociada') }}</h6>
    </div>
    <ul class="list-group">
        <li class="list-group-item"><a href="{{ url('/commercial_terms/detail/' . $supplier->commercial_term->id) }}">{{ $supplier->commercial_term->name }}</a></li>
        <li class="list-group-item">{{ __('Tipo') }}: {{ $supplier->commercial_term->type }}</li>
        <li class="list-group-item">{{ __('Deposito') }}: {{ $supplier->commercial_term->deposit }}%</li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información bancaria') }}</h6>
        <a href="#" data-target="#modal-add-bank_data-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar') }}</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('Banco') }}</th>
                <th>{{ __('Titular') }}</th>
                <th>{{ __('No. Cuenta') }}</th>
                <th>{{ __('Clabe') }}</th>
                <th>{{ __('Fecha creación') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplier->bank_data as $element)
                <tr>
                    <td>{{ $element->bank }}</td>
                    <td>{{ $element->account_holder }}</td>
                    <td>{{ $element->account_number }}</td>
                    <td>{{ $element->clabe }}</td>
                    <td>{{ $element->created_at }}</td>
                    <td>
                        <!-- Split button -->
                        <div class="btn-group pull-right">
                            <a href="#" class="btn btn-default btn-sm edit-bank_data-link" data-target="#modal-edit-bank_data-row" data-toggle="modal" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                        </div>
                        <!-- /Split button -->                  
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Datos fiscales') }}</h6>
        @if(!$supplier->tax_data)
            <a href="{{ url('/supplier_tax_data/add/' . $supplier->id) }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar') }}</a>
        @endif
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('Razón social') }}</th>
                <th>{{ __('RFC') }}</th>
                <th>{{ __('Dirección') }}</th>
                <th>{{ __('Ciudad') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if($supplier->tax_data)
                <tr>
                    <td>{{ $supplier->tax_data->business_reason }}</td>
                    <td>{{ $supplier->tax_data->RFC }}</td>
                    <td>{{ $supplier->tax_data->street }}</td>
                    <td>{{ $supplier->tax_data->city }}</td>
                    <td>
                        <!-- Split button -->
                        <div class="btn-group pull-right">
                            <a href="{{ url('/supplier_tax_data/edit/' . $supplier->tax_data->id) }}" class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/supplier_tax_data/detail/' . $supplier->tax_data->id) }}">{{ __('Detalle') }}</a></li>
                            </ul>
                        </div>
                        <!-- /Split button -->                  
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
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
                <th>{{ __('Departamento') }}</th>
                <th>{{ __('No. Celular') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Preferente') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplier->contacts as $element)
                <tr>
                    <td>{{ $element->name }}</td>
                    <td>{{ $element->department }}</td>
                    <td>{{ $element->mobile }}</td>
                    <td>{{ $element->email }}</td>
                    <td>{{ __(config('dictionaries.common_answers.' . $element->preferred)) }}</td>
                    <td>
                        <!-- Split button -->
                        <div class="btn-group pull-right">
                            <a href="#" class="btn btn-default btn-sm edit-contact-link" data-target="#modal-edit-contact-row" data-toggle="modal" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/supplier_contacts/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                            </ul>
                        </div>
                        <!-- /Split button -->                  
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal add supply row -->
<div id="modal-add-supply-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Asociar materia prima') }}</h5>
            </div>
            <form action="{{ url('/supplier_supplies/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <input type="text" name="supplier_id" value="{{ $supplier->id }}" hidden>
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Materia prima') }}</label>
                        <select class="form-control" name="supply_id" id="supply_id" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona una materia prima') }} -- </option>
                            @foreach($not_associated_supplies as $element)
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
<!-- /modal add supply row -->

<!-- modal edit supply row -->
<div id="modal-edit-supply-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar asociación de materia prima') }}</h5>
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
<!-- /modal edit supply row -->

<!-- modal add bank data row -->
<div id="modal-add-bank_data-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar información bancaria') }}</h5>
            </div>
            <form action="{{ url('/supplier_bank_data/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <input type="text" name="supplier_id" value="{{ $supplier->id }}" hidden>
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Banco') }}</label>
                        <input type="text" class="form-control" name="bank" maxlength="90" required autofocus>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Titular de la cuenta') }}</label>
                        <input type="text" class="form-control" name="account_holder" maxlength="90" required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('No. Cuenta') }}</label>
                        <input type="text" class="form-control" name="account_number" maxlength="45" required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Clabe') }}</label>
                        <input type="text" class="form-control" name="clabe" maxlength="90" required>
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
<!-- /modal add bank data row -->

<!-- modal edit bank data row -->
<div id="modal-edit-bank_data-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar información bancaria') }}</h5>
            </div>
            <form action="{{ url('/supplier_bank_data/update') }}" role="form" method="POST" autocomplete="off" id="form-bank_data-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Banco') }}</label>
                        <input type="text" class="form-control" name="bank" id="modal-bank_data_bank" maxlength="90" required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Titular de la cuenta') }}</label>
                        <input type="text" class="form-control" name="account_holder" id="modal-bank_data_account_holder" maxlength="90"  required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('No. Cuenta') }}</label>
                        <input type="text" class="form-control" name="account_number" id="modal-bank_data_account_number" maxlength="45" required>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Clabe') }}</label>
                        <input type="text" class="form-control" name="clabe" id="modal-bank_data_clabe" maxlength="90" required>
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
<!-- /modal edit bank data row -->

<!-- modal add contact row -->
<div id="modal-add-contact-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar contacto') }}</h5>
            </div>
            <form action="{{ url('/supplier_contacts/store') }}" role="form" method="POST" autocomplete="off" onsubmit="return validateContactForm()">
                @csrf
                <input type="text" name="supplier_id" value="{{ $supplier->id }}" hidden>
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" maxlength="90" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Departamento') }}</label>
                                <input type="text" class="form-control" name="department" maxlength="90" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Preferente') }}</label>
                                <select class="form-control" name="preferred" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>{{ __('Email') }}</label>
                                <input type="text" class="form-control" id="modal-add-contact_email" name="email" maxlength="45">
                            </div>
                            <div class="col-sm-3">
                                <label>{{ __('Teléfono') }}</label>
                                <input type="text" class="form-control" name="phone" data-mask="999-999-9999">
                                <span class="help-block">999-999-9999</span>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('No. Celular') }}</label>
                                <input type="text" class="form-control" name="mobile" data-mask="999-999-9999" required>
                                <span class="help-block">999-999-9999</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Notas') }}</label>
                        <textarea rows="3" class="form-control" name="notes" maxlength="120"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cerrar') }}</button>
                    <input type="submit" value="{{ __('Guardar') }}"class="btn btn-primary">
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
            <form action="{{ url('/supplier_contacts/update') }}" role="form" method="POST" autocomplete="off" id="form-contact-edit" onsubmit="return validateUpdateContactForm()">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                                <input type="text" class="form-control" name="name" id="modal-contact_name" maxlength="90" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Departamento') }}</label>
                                <input type="text" class="form-control" name="department" id="modal-contact_department" maxlength="90" required>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('Preferente') }}</label>
                                <select class="form-control" name="preferred" required>
                                    <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                                    @foreach(config('dictionaries.common_answers') as $key => $value)
                                        <option value="{{ $key }}" id="modal-preferred-{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>{{ __('Email') }}</label>
                                <input type="text" class="form-control" name="email" id="modal-contact_email" maxlength="45">
                            </div>
                            <div class="col-sm-3">
                                <label>{{ __('Teléfono') }}</label>
                                <input type="text" class="form-control" name="phone" id="modal-contact_phone" data-mask="999-999-9999">
                                <span class="help-block">999-999-9999</span>
                            </div>
                            <div class="col-sm-3">
                                <label><span class="mandatory">*</span> {{ __('No. Celular') }}</label>
                                <input type="text" class="form-control" name="mobile" id="modal-contact_mobile" data-mask="999-999-9999" required>
                                <span class="help-block">999-999-9999</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Notas') }}</label>
                        <textarea rows="3" class="form-control" name="notes" id="modal-contact_notes" maxlength="120"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cerrar') }}</button>
                    <input type="submit" value="{{ __('Guardar') }}"class="btn btn-primary">
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

        $('.edit-bank_data-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/supplier_bank_data/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-bank_data_bank').val(data.bank);
                    $('#modal-bank_data_account_holder').val(data.account_holder);
                    $('#modal-bank_data_account_number').val(data.account_number);
                    $('#modal-bank_data_clabe').val(data.clabe);
                    $('#form-bank_data-edit').attr('action', '{{ url('/supplier_bank_data/update/') }}/' + row_id);
                }
            });
        });

        $('.edit-contact-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/supplier_contacts/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-contact_name').val(data.name);
                    $('#modal-contact_department').val(data.department);
                    $('#modal-contact_email').val(data.email);
                    $('#modal-contact_phone').val(data.phone);
                    $('#modal-contact_mobile').val(data.mobile);
                    $('#modal-contact_notes').val(data.notes);
                    $('#modal-preferred-' + data.preferred).attr('selected', 'selected');
                    $('#form-contact-edit').attr('action', '{{ url('/supplier_contacts/update/') }}/' + row_id);
                }
            });
        });
    });

    function validateContactForm() {
        const email = $('#modal-add-contact_email').val();
        if(email != '') {
            const emailRegex = /^[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]+)*\.[a-z]{2,}$/;
            if(!emailRegex.test(email)) {
                alert('@lang("El email no es válido")');
                return false;
            }
        }
    }

    function validateUpdateContactForm() {
        const email = $('#modal-contact_email').val();
        if(email != '') {
            if(!isEmail(email)) {
                alert('@lang("El email no es válido")');
                return false;
            }
        }
    }
</script>
@endsection