@extends('admin.main')

@section('title')
{{ trans('adminlte::weevent.events') }}
@endsection

@section('main_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
@endsection

@section('content_header')

<div class="row mb-2">
  <div class="col-8">
    <h1>@lang('adminlte::weevent.events')</h1>
  </div>
  <div class="col-4">
    <a href="{{ route('event.create') }}" class="btn btn-info float-right elevation-1">{{ strtoupper(trans('adminlte::weevent.new_event')) }}</a>
  </div>
</div>

@stop

@section('content')


<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="tblEvents" class="hover stripe">
          <thead>
            <tr>
              <th>ID</th>
              <th>@lang('adminlte::weevent.title')</th>
              <th>{{ __('adminlte::weevent.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($events as $event)
            <tr>
              <td class="dt-body-center">{{ $event->id }}</td>
              <td>{{ $event->title }}</td>
              <td class="d-flex justify-content-between">
                <a href="{{ route('event.show', ['event' => $event]) }}" class="btn p-0" style="color: #17b83a;"><i class="far fa-eye"></i></a>
                <a href="{{ route('event.edit', ['event' => $event]) }}" class="btn p-0" style="color: #17a2b8;"><i class="fas fa-pen"></i></a>
                <form action="{{ route('event.destroy', ['event' => $event]) }}" method="post">
                  @csrf
                  @method('delete')
                  <button type="submit" class="btn p-0" style="color: #e20909"><i class="far fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>ID</th>
              <th>{{ __('adminlte::weevent.title') }}</th>
              <th>{{ __('adminlte::weevent.action') }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

  </div>
  <!-- /.col -->
</div>


@stop

@section('js')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
<script>
  $(document).ready(function() {
    $('#tblEvents').DataTable({
      language: @json(trans('adminlte::datatables'))
      , "autoWidth": false
      , "columnDefs": [{
          "width": "30px"
          , "targets": [0]
        }
        , {
          "width": "50px"
          , "targets": [2]
        }
      ]
    });
  });

</script>
@stop
