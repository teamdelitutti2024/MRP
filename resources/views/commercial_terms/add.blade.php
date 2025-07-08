@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar condición comercial') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="row">
    <form action="{{ url('/commercial_terms/store') }}" role="form" method="POST" autocomplete="off">
        @csrf
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">{{ __('Información de condición comercial') }}</h6>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Nombre') }}</label>
                        <input type="text" class="form-control" name="name" maxlength="90" required autofocus>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Tipo') }}</label>
                        <select class="form-control" name="type" required>
                            <option hidden disabled selected value> -- {{ __('Selecciona una opción') }} -- </option>
                            @foreach(config('dictionaries.commercial_terms') as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Depósito (%)') }}</label>
                        <input type="text" class="form-control" name="deposit" id="deposit" pattern="(100|[1-9][0-9]?)" title="{{ __('Número entero entre 1 y 100') }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6 class="panel-title">{{ __('Detalle de pagos') }}</h6>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="commercial-terms-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%">{{ __('Porcentaje') }}</th>
                                    <th style="width: 40%">{{ __('Días') }}</th>
                                    <th style="width: 20%">
                                        <div class="table-controls">
                                            <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>
                    <div class="form-actions text-right">
                        <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('#add-row').click(function(e) {
            e.preventDefault();

            var html = '';

            html += '<tr>';
            html += '   <td class="mandatory-field">';
            html += '       <span>*</span>';
            html += '       <input type="text" class="form-control" name="payments_detail[percentage][]" pattern="(100|[1-9][0-9]?)" title="@lang('Número entero entre 1 y 100')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <input type="text" class="form-control" name="payments_detail[days][]" pattern="[1-9][0-9]?" title="@lang('Número entero positivo con 2 cifras como máximo')" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';
            
            $('#commercial-terms-table > tbody').append(html);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();
            $(this).parent().parent().parent().remove();
        });
    });
</script>
@endsection