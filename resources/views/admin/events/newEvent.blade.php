@extends('admin.main')

@section('title')
{{ trans('adminlte::weevent.event') }}
@endsection

@section('main_css')

@endsection

@section('content_header')

<div class="row mb-2">
  <div class="col-12">
    <h1>@lang('adminlte::weevent.event')</h1>
  </div>
</div>

@stop

@section('content')

<livewire:multiform-event></livewire:multiform-event>

{{-- <div class="card">
    <!-- /.card-header -->
    <div class="card-body">
      <form id="frmEvent" method="POST" action="{{ route('event.store') }}">
@csrf
<div class="row">
  <div class="col-sm-12">
    <!-- text input -->
    <div class="form-group">
      <label>{{ trans('adminlte::weevent.title_of_event') }}</label>
      <input name="title" type="text" class="form-control" placeholder="Enter ..." value="título do evento">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <!-- textarea -->
    <div class="form-group">
      <label>{{ trans('adminlte::weevent.youtube_video_id') }}</label>
      <input name="youtube_video_id" type="text" class="form-control" placeholder="Enter ..." value="vCRJ-I3a_S0">
    </div>
  </div>
</div>


</form>
</div>
<!-- /.card-body -->

<div class="card-footer">
  <button form="frmEvent" type="submit" class="btn btn-primary">CADASTRAR</button>
</div>
</div> --}}

@stop

@section('js')

@stop
