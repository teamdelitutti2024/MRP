@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar recepción de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ url('/supply_orders/receptions/' . $supply_order->id) }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/supply_receptions/store') }}" role="form" method="POST" autocomplete="off" id="supply-reception">
    @csrf
    <input type="text" name="total" id="total" hidden>
    <input type="text" name="supply_order_id" value="{{ $supply_order->id }}" hidden>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de recepción') }}</h6>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><span class="mandatory">*</span> {{ __('Código') }}</label>
                        <input type="text" class="form-control" name="code" value="{{ count($supply_order->supply_receptions) > 0 ? $supply_order->supply_receptions[0]->code : ''  }}" maxlength="120" required {{ count($supply_order->supply_receptions) > 0 ? 'readonly' : '' }} >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de recepción') }}</h6>
            <span class="pull-right label label-info">{{ __('Total de recepción') }}: $<span id="dinamic-total">0.00</span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <div class="alert alert-danger fade in widget-inner error-greater-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad a recibir no puede ser mayor a la cantidad solicitada')}}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad a recibir no puede ser menor que 0') }}
                    </div>
                    <div class="alert alert-danger fade in widget-inner error-quantity-0" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La recepción de materia prima debe tener al menos una cantidad recibida mayor a 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th>{{ __('Materia prima') }}</th>
                            <th>{{ __('Unidad de medida') }}</th>
                            <th>{{ __('Cantidad disponible a recibir') }}</th>
                            <th>{{ __('Cantidad recibida') }}</th>
                            <th>{{ __('Costo') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($supply_order->supply_order_details as $element)
                            @if($element->quantity > $element->received_quantity)
                                <tr>
                                    <input type="text" name="details[supply_order_detail_id][]" value="{{ $element->id }}" hidden>
                                    <input type="text" name="details[supply_id][]" value="{{ $element->supply_id }}" hidden>
                                    <td class="mandatory-field"><span>*</span>{{ $element->supply }}</td>
                                    <td>{{ $element->measurement_unit->measure }}</td>
                                    <td><p style="display: inline;" id="requested_quantity-{{ $counter }}">{{ number_format($element->quantity - $element->received_quantity, 3, '.', '') }}</p></td>
                                    <td><input type="text" class="form-control quantities" name="details[quantity][]" id="quantity-{{ $counter }}" value="0" pattern="(0*[0-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal con 7 digitos enteros y 3 cifras decimales como máximo') }}" required></td>
                                    <td>$<p style="display: inline;" id="cost-{{ $counter }}">{{ number_format($element->cost, 2, '.', ',') }}</p></td>
                                    <td>$<p style="display: inline;" id="total-{{ $counter }}">0.00</p></td>
                                </tr>
                                @php $counter++; @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
    var total = 0;
    var counter = {{ $counter }};

    $(document).ready(function() {
        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() > 0) {
                if(parseFloat($(this).val()) > parseFloat($('#requested_quantity-' + row_id).text())) {
                    $('#total-' + row_id).text('0.00');
                    $('#quantity-' + row_id).val('');

                    // Send alert about greater quantity
                    $('.error-greater-than-quantity').show();
                    $('.error-greater-than-quantity').fadeOut(5000);
                }
                else {
                    new_total = $(this).val() * format_number_from_separator($('#cost-' + row_id).text());
                    $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                }
            }
            else if($(this).val() == 0) {
                $('#total-' + row_id).text('0.00');
            }
            else {
                $('#total-' + row_id).text('0.00');
                $('#quantity-' + row_id).val('');

                // Send alert about less than 0
                $('.error-less-than-quantity').show();
                $('.error-less-than-quantity').fadeOut(5000);
            }

            // Add row total to dinamic total
            total = total + new_total;
            $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
        });

        $('#supply-reception').submit(function() {
            var quantities_equal_to_zero = true;

            // Check if at any quantity is equal to 0
            for(let index = 0; index < counter; index++) {
                if($('#quantity-' + index).val() > 0) {
                    quantities_equal_to_zero = false;
                }
            }

            if(quantities_equal_to_zero) {
                // Send alert about quantity equal to 0
                $('.error-quantity-0').show();
                $('.error-quantity-0').fadeOut(5000);

                return false;
            }

            // Assign dinamic total to total input
            $('#total').val(format_number_from_separator($('#dinamic-total').text()));
        });
    });
</script>
@endsection