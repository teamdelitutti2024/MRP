@php
use App\Models\SupplierSupply;
@endphp

@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Editar pedido de materia prima') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<form action="{{ url('/supply_orders/update/' . $supply_order->id) }}" role="form" method="POST" autocomplete="off" id="supply-order">
    @csrf
    <input type="text" name="total" id="total" hidden>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Información de pedido') }}</h6>
            <a href="{{ url('/suppliers/detail/' . $supply_order->supplier_id) }}"><span class="pull-right label label-info">{{ $supply_order->supplier->name }}</span></a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Condición comercial') }}</label>
                        <select class="form-control" name="commercial_term_id" required>
                            @php 
                                $commercial_term_id = $supply_order->commercial_term_id ? $supply_order->commercial_term_id : $supply_order->supplier->commercial_term_id;
                            @endphp
                            @foreach($commercial_terms as $element)
                                <option value="{{ $element->id }}" {{ $commercial_term_id == $element->id ? 'selected' : '' }}>{{ $element->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Requiere factura') }}</label>
                        <select class="form-control" name="require_invoice" id="require_invoice" required>
                            @foreach(config('dictionaries.common_answers') as $key => $value)
                                <option value="{{ $key }}" {{ $supply_order->require_invoice == $key ? 'selected' : '' }} >{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Método preferido de pago') }}</label>
                        <select class="form-control" name="preferred_payment_method" id="preferred_payment_method" required>
                            @foreach(config('dictionaries.preferred_payment_methods') as $key => $value)
                                <option value="{{ $key }}" {{ $supply_order->preferred_payment_method == $key ? 'selected' : '' }} >{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label><span class="mandatory">*</span> {{ __('Tipo') }}</label>
                        <select class="form-control" name="type" required>
                            @foreach(config('dictionaries.supply_orders_types') as $key => $value)
                                <option value="{{ $key }}" {{ $supply_order->type == $key ? 'selected' : '' }}>{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Detalle de pedido') }}</h6>
            <span class="pull-right label label-info">{{ __('Total de pedido') }}: $<span id="dinamic-total"></span></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover" id="details-table">
                    <div class="alert alert-danger fade in widget-inner error-less-than-quantity" style="display: none;">
                        <i class="fa fa-times" id="error"></i> {{ __('La cantidad a solicitar no puede ser menor que 0') }}
                    </div>
                    <thead>
                        <tr>
                            <th style="width: 25%">{{ __('Materia prima') }}</th>
                            <th style="width: 20%">{{ __('Unidad de medida') }}</th>
                            <th style="width: 15%">{{ __('Cantidad') }}</th>
                            <th style="width: 10%">{{ __('Precio de lista') }}</th>
                            <th style="width: 10%">{{ __('Costo') }}</th>
                            <th style="width: 10%">{{ __('Total') }}</th>
                            <th style="width: 10%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                            $total = 0;
                            $count_details = count($supply_order->supply_order_details);
                        @endphp
                        @foreach($supply_order->supply_order_details as $element)
                            @php $supplier_supply = SupplierSupply::where('supplier_id', $supply_order->supplier_id)->where('supply_id', $element->supply_id)->first(); @endphp
                            @if($element->sup->is_active)
                                <tr>
                                    <td class="mandatory-field">
                                        <span>*</span>
                                        <select name="details[supply_id][]" class="form-control supplies" id="{{ $counter }}">
                                            @foreach($supply_order->supplier->supplies as $supply)
                                                @if($supply->is_active)
                                                    <option value="{{ $supply->id }}" {{ $supply->id == $element->supply_id ? 'selected' : '' }}>{{ $supply->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="details[measurement_unit_id][]" id="measurement_unit_id-{{ $counter }}" value="{{ $element->measurement_unit_id }}" hidden>
                                        <p style="display: inline;" id="measurement_unit-{{ $counter }}">{{ $element->measurement_unit->measure }}</p>
                                    </td>
                                    <td>
                                        <input type="text" name="details[quantity][]" class="form-control quantities" id="quantity-{{ $counter }}" value="{{ $element->quantity }}" pattern="(0*[1-9][0-9]{0,6}(\.[0-9]{1,3})?|0+\.[0-9]{1,3})" title="{{ __('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo') }}" required>
                                    </td>
                                    <td>
                                        $<p style="display: inline;" id="price-{{ $counter }}">{{ number_format($supplier_supply->price, 2, '.', ',') }}</p>
                                    </td>
                                    <td>
                                        $<p style="display: inline;" id="cost-{{ $counter }}">{{ number_format($supplier_supply->cost, 2, '.', ',') }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $total += $element->quantity * $supplier_supply->cost;
                                        @endphp
                                        $<p style="display: inline;" id="total-{{ $counter }}">{{ number_format($element->quantity * $supplier_supply->cost, 2, '.', ',') }}</p>
                                    </td>
                                    <td>
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-{{ $counter }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @php $counter++; @endphp
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
    var counter = {{ $count_details }};
    var total = {{ $total }};

    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));

    $(document).ready(function() {
        $('#add-row').click(function(e) {
            e.preventDefault();

            var html = '';
            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <select name="details[supply_id][]" class="form-control supplies" id="' + counter + '">';
            @foreach($supplies as $element)
            html += '           <option value="{{ $element->association->supply_id }}">{{ $element->name }}</option>';
            @endforeach
            html += '       </select>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="details[measurement_unit_id][]" id="measurement_unit_id-' + counter + '" value="{{ $supplies[0]->association->measurement_unit_id }}" hidden>';
            html += '       <p style="display: inline;" id="measurement_unit-' + counter + '">{{ $supplies[0]->association->measure }}</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" name="details[quantity][]" class="form-control quantities" id="quantity-' + counter + '" pattern="(0*[1-9][0-9]{0,6}(\\.[0-9]{1,3})?|0+\\.[0-9]{1,3})" title="@lang('Número decimal mayor a 0 con 7 digitos enteros y 3 cifras decimales como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       $<p style="display: inline;" id="price-' + counter + '">{{ number_format($supplies[0]->association->price, 2, ".", ",") }}</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       $<p style="display: inline;" id="cost-' + counter + '">{{ number_format($supplies[0]->association->cost, 2, ".", ",") }}</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       $<p style="display: inline;" id="total-' + counter + '">0.00</p>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row" id="delete-' + counter + '"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';

            counter++;

            $('#details-table > tbody').append(html);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();

            var row_id = $(this).attr('id').split("-")[1];

            var resp = confirm('¿Eliminar elemento?');
            if(resp) {
                // Get total of the row to be deleted
                var row_total = format_number_from_separator($('#total-' + row_id).text());

                $(this).parent().parent().parent().remove();

                // Substract row total from dinamic total
                total = total - row_total;
                $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
            }
        });

        $(document).on('change', '.supplies', function(e) {
            var supply_id = $(this).val();
            var supplier_id = {{ $supply_order->supplier_id }};

            var row_id = $(this).attr('id');

            // Get cost and price for the supply and supplier
            $.ajax({
                url: '{{ url('/supplier_supplies/get_supplier_supplies') }}',
                data: 'supply_id=' + supply_id + '&supplier_id=' + supplier_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    // Change value of price, cost and total for that row
                    $('#price-' + row_id).text(format_number_to_separator(data.price));
                    $('#cost-' + row_id).text(format_number_to_separator(data.cost));
                    $('#measurement_unit-' + row_id).text(data.measure);
                    $('#measurement_unit_id-' + row_id).val(data.measurement_unit_id);

                    // Substract previous row total from dinamic total
                    var previous_total = format_number_from_separator($('#total-' + row_id).text());
                    total = total - previous_total;

                    var new_total = 0;

                    if($('#quantity-' + row_id).val() > 0) {
                        new_total = $('#quantity-' + row_id).val() * data.cost;
                        $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
                    }
                    else {
                        $('#total-' + row_id).text('0.00');
                    }

                    // Add row total to dinamic total
                    total = total + new_total;
                    $('#dinamic-total').text(format_number_to_separator(total.toFixed(2)));
                }
            });
        });

        $(document).on('change', '.quantities', function(e) {
            var row_id = $(this).attr('id').split("-")[1];

            // Substract previous row total from dinamic total
            var previous_total = format_number_from_separator($('#total-' + row_id).text());
            total = total - previous_total;

            var new_total = 0;

            // Change the value of total for that row
            if($(this).val() >= 0) {
                new_total = $(this).val() * format_number_from_separator($('#cost-' + row_id).text());
                $('#total-' + row_id).text(format_number_to_separator(new_total.toFixed(2)));
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

        $('#supply-order').submit(function() {
            // Assign dinamic total to total input
            $('#total').val(format_number_from_separator($('#dinamic-total').text()));
        })
    });
</script>
@endsection