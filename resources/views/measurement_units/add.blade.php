@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Agregar unidad (es) de medida') }}</h5>
    <div class="btn-group">
        <a href="{{ URL::previous() }}" class="btn btn-link btn-lg btn-icon"><i class="fa fa-arrow-circle-left"></i>{{ __('Regresar') }}</a>
    </div>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Información de unidades de medida') }}</h6>
    </div>
    <div class="panel-body">
        <form action="{{ url('/measurement_units/store') }}" role="form" method="POST" autocomplete="off">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover" id="measurement-units-table">
                    <thead>
                        <tr>
                            <th style="width: 80%">{{ __('Unidad (es) de medida') }}</th>
                            <th style="width: 20%">
                                <div class="table-controls">
                                    <a href="#" class="btn btn-default btn-icon btn-xs" id="add-row"><i class="fa fa-plus"></i></a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="mandatory-field">
                                <span>*</span>
                                <input type="text" name="measurement_units[]" class="form-control" maxlength="90" autofocus required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="form-actions text-right">
                <input type="submit" value="{{ __('Guardar') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
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
            html += '       <input type="text" name="measurement_units[]" class="form-control" maxlength="90" required>';
            html += '   </td>';
            html += '   <td>';
            html += '       <div class="table-controls">';
            html += '           <a href="#" class="btn btn-danger btn-icon btn-xs del-row"><i class="fa fa-trash"></i></a>';
            html += '       </div>';
            html += '   </td>';
            html += '</tr>';
            
            $('#measurement-units-table > tbody').append(html);
        });

        $(document).on('click', '.del-row', function(e) {
            e.preventDefault();
            $(this).parent().parent().parent().remove();
        });
    });
</script>
@endsection