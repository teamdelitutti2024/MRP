@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de pedidos de materia prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Pedidos de materia prima') }}</h6>
        <a href="{{ url('/supply_orders/add') }}" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar pedido de materia prima') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('Proveedor') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th>{{ __('Fecha entrega') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_orders as $element)
                    <tr>
                        <td>{{ $element->supplier->name }}</td>
                        @php
                        switch($element->status) {
                            case 1:
                                $label = 'info';
                                break;
                            case 2:
                                $label = 'danger';
                                break;
                            case 3:
                                $label = 'success';
                                break;
                            case 4:
                                $label = 'primary';
                                break;
                            case 5:
                                $label = 'default';
                                break;
                            default:
                                $label = 'info';
                        }
                        @endphp
                        <td><span class="label label-{{ $label }}">{{ __(config('dictionaries.supply_orders_status.' . $element->status)) }}</span></td>
                        <td>${{ number_format($element->total, 2, '.', ',') }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>{{ $element->delivery_date }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="{{ url('/supply_orders/edit/' . $element->id) }}" {{ config('dictionaries.supply_orders_status.' . $element->status) == 'En progreso' ? '' : 'disabled' }} class="btn btn-default btn-sm">{{ __('Editar') }}</a>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if(config('dictionaries.supply_orders_status.' . $element->status) == 'En progreso')
                                        @if(count($element->supply_order_details) > 0)
                                            <li><a  href="#modal-request" class="request" data-toggle="modal" data-id="{{ $element->id }}">{{ __('Solicitar') }}</a></li>
                                        @endif
                                        <li><a href="{{ url('/supply_orders/cancel/' . $element->id) }}" onclick="return confirm('{{ __('¿Cancelar pedido?') }}');" >{{ __('Cancelar') }}</a></li>
                                    @endif
                                    @if(config('dictionaries.supply_orders_status.' . $element->status) == 'Solicitado' || config('dictionaries.supply_orders_status.' . $element->status) == 'Parcialmente recibido' || config('dictionaries.supply_orders_status.' . $element->status) == 'Recibido')
                                        <li><a href="{{ url('/supply_orders/detail/' . $element->id) }}">{{ __('Detalle') }}</a></li>
                                        <li><a href="{{ url('/supply_orders/receptions/' . $element->id) }}">{{ __('Recepciones') }}</a></li>
                                        <li><a href="{{ url('/supply_orders/download/' . $element->id) }}">{{ __('Descargar') }}</a></li>
                                    @endif
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

<!-- modal request -->
<div id="modal-request" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Solicitar pedido') }}</h5>
            </div>
            <form id="form-request" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Fecha de solicitud') }}</label>
                        <input type="text" class="form-control datepicker-from-today" name="request_date" value="{{ date('Y-m-d') }}" readonly required>
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
<!-- modal request -->
@endsection

@section('footer')
<script type="text/javascript">
     $(document).ready(function() {
        $('.request').click(function() {
            var row_id = $(this).data('id');
            $('#form-request').attr('action', '{{ url('/supply_orders/request/') }}/' + row_id);
        });
     });
</script>
@endsection