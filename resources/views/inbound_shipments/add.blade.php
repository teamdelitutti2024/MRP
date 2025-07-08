@extends('layouts.master')

@section('header')
    <link href="{{ url('/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ url('js/jquery.datetimepicker.full.min.js') }}"></script>
@endsection

@php
    $today = date('Y-m-d H:i');
    $datetimepicker_theme = 'default';
    if (date('G') < 7 || date('G') > 17) {
        $datetimepicker_theme = 'dark';
    }
@endphp

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar embarque de entrada') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/departure_shipments/inbound_shipments/' . $departure_shipment->id) }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/inbound_shipments/store') }}" role="form" method="POST" autocomplete="off" id="inbound-shipment">
    @csrf
    <input type="text" name="departure_shipment_id" value="{{ $departure_shipment->id }}" hidden>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de embarque') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><span class="mandatory">*</span> {{ __('Sucursal') }}</label>
                        <select class="form-control" name="branch_id" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona una sucursal') }} -- </option>
                            @foreach($available_branches as $element)
                                <option value="{{ $element->id }}">{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <div class="alert alert-danger fade in widget-inner error-date" style="display: none;">
                            <i class="fa fa-times" id="error"></i> {{ __('La fecha de recepción no puede ser menor a la fecha actual') }}
                        </div>
                        <label><span class="mandatory">*</span> {{ __('Fecha de recepción') }}</label>
                        <input type="text" class="form-control" name="received_date" id="received_date" value="{{ $today }}" readonly required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de embarque') }}</h6>
        </div>
        <div class="panel-body">
            <div class="alert alert-info fade in widget-inner">
                <i class="fa fa-exclamation-circle" id="error"></i> {{ __('Si no se desea agregar un producto al embarque de entrada, por favor eliminarlo de la lista') }}
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <thead>
                        <tr>
                            <th>{{ __('Producto') }}</th>
                            <th>{{ __('Tamaño') }}</th>
                            <th>{{ __('Cantidad disponible a recibir') }}</th>
                            <th>{{ __('Cantidad a recibir') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Justificación faltante') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($departure_shipment->departure_shipment_details as $element)
                            @if($element->quantity > $element->received_quantity)
                                <tr>
                                    <td class="mandatory-field"><span>*</span>{{ $element->product_name }}</td>
                                    <td>{{ $element->product_size->name  }}</td>
                                    <td><p style="display: inline;" id="available_quantity-{{ $counter }}">{{ $element->quantity - $element->received_quantity }}</p></td>
                                    <td>
                                        <input type="text" name="departure_shipment_details[]" value="{{ $element->id }}" hidden>
                                        <input type="text" maxlength="3" class="form-control quantities" name="quantities[]" id="quantity-{{ $counter }}" pattern="(100|[0-9][0-9]?)" title="{{ __('Número entero entre 0 y 100') }}" required>
                                    </td>
                                    <td>
                                        <select class="form-control" name="status[]" required>
                                            @foreach(config('dictionaries.inbound_shipment_details_status') as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="justifications[]" id="justification-{{ $counter }}" value="{{ $element->justification }}" maxlength="120"></td>
                                    <td>
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @php $counter++; @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" id="save-inbound-shipment" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
    var counter = {{ $counter }};

    $(document).ready(function() {
        $.datetimepicker.setLocale('es');

        const today = new Date();

        const today_input = '{{ $today }}';

        $('#received_date').datetimepicker({
            format: 'Y-m-d H:i',
            minDate: 0,
            step: 5,
            theme: '{{ $datetimepicker_theme }}',
            onChangeDateTime: function(current_time, input) {
                if(current_time < today) {
                    $('.error-date').show();
                    $('.error-date').fadeOut(5000);

                    $('#received_date').val(today_input);
                }
            }
        });

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            if(!Number.isInteger(+$(this).val())) {
                $('#quantity-' + row_id).val('');

                // Send alert about wrong quantity
                alert('@lang("La cantidad a recibir no puede tener este formato")');
            }
            else {
                // Change the value of total for that row
                if($(this).val() > 0) {
                    if($(this).val() > parseInt($('#available_quantity-' + row_id).text())) {
                        $('#quantity-' + row_id).val('');

                        // Send alert about greater quantity
                        alert('@lang("La cantidad a recibir no puede ser mayor a la cantidad disponible a recibir")');
                    }
                }
                else {
                    $('#quantity-' + row_id).val('');

                    // Send alert about less than 0
                    alert('@lang("La cantidad a recibir no puede ser menor o igual a 0")');
                }
            }
        });

        $('#inbound-shipment').submit(function() {
            let resp = confirm("@lang('¿Finalizar embarque de entrada?')");

            if(!resp) {
                return false;
            }
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var resp = confirm('¿Eliminar producto?');
            if(resp) {
                $(this).parent().parent().parent().remove();

                counter = counter - 1;

                if(counter == 0) {
                    $('#save-inbound-shipment').prop('disabled', true);
                }
            }
        });
    });
</script>
@endsection