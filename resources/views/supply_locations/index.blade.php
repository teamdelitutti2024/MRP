@extends('layouts.master')

@section('content')
<!-- Page title -->
<div class="page-title">
    <h5><i class="fa fa-tag"></i> {{ __('Administración de ubicaciones de materia prima') }}</h5>
</div>
<!-- /page title -->

@include('layouts.messages')
@include('layouts.errors')

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">{{ __('Ubicaciones de materia prima') }}</h6>
        <a href="#" data-target="#modal-add-row" data-toggle="modal" class="pull-right btn btn-xs btn-success"><i class="fa fa-plus"></i> {{ __('Agregar ubicación') }}</a>
    </div>
    <div class="datatable">
        <table class="table table-hover datatables">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Ubicación') }}</th>
                    <th>{{ __('Fecha creación') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($supply_locations as $element)
                    <tr>
                        <td>{{ $element->id }}</td>
                        <td>{{ $element->location }}</td>
                        <td>{{ $element->created_at }}</td>
                        <td>
                            <!-- Split button -->
                            <div class="btn-group pull-right">
                                <a href="#" class="btn btn-default btn-sm edit-link" data-toggle="modal" data-target="#modal-edit-row" data-id="{{ $element->id }}">{{ __('Editar') }}</a>
                            </div>
                            <!-- /Split button -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- modal add row -->
<div id="modal-add-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Agregar ubicación') }}</h5>
            </div>
            <form action="{{ url('/supply_locations/store') }}" role="form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Ubicación') }}</label>
                        <input type="text" class="form-control" name="location" maxlength="80" required>
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
<!-- /modal add row -->

<!-- modal edit row -->
<div id="modal-edit-row" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">{{ __('Editar ubicación') }}</h5>
            </div>
            <form action="{{ url('/supply_locations/update') }}" role="form" method="POST" autocomplete="off" id="form-edit">
                @csrf
                <div class="modal-body has-padding">
                    <div class="form-group">
                        <label><span class="mandatory">*</span> {{ __('Ubicación') }}</label>
                        <input type="text" class="form-control" name="location" id="modal-location" maxlength="80" required>
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
<!-- /modal edit row -->
@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        $('.edit-link').click(function() {
            var row_id = $(this).data('id');
            $.ajax({
                url: '{{ url('/supply_locations/get_row') }}',
                data: 'id=' + row_id,
                dataType: 'json',
                type: 'post',
                cache: false,
                success: function(data) {
                    $('#modal-location').val(data.location);
                    $('#form-edit').attr('action', '{{ url('/supply_locations/update/') }}/' + row_id);
                }
            });
        });
    });
</script>
@endsection