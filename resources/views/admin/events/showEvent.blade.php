@extends('admin.main')

@section('title')
{{ trans('adminlte::weevent.event') }}
@endsection

@section('main_css')
@endsection

@section('content_header')

<div class="row mb-2">
  <div class="col-12">
    <h1>@lang('adminlte::weevent.event') [{{ $event->title }}]</h1>
  </div>
</div>
@stop

@section('content')

<div class="row">
  <div class="col">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $event->youtube_video_id }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="form-group">
      <label>{{ trans('adminlte::weevent.link_event_client') }}</label>
      <input name="link_client" type="text" class="form-control" value="{{ config('app.host_app_client_url') }}/event/{{ $event->uuid }}" readonly>
    </div>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="form-group">
      <label>{{ trans('adminlte::weevent.link_event_admin') }}</label>
      <input name="link_admin" type="text" class="form-control" value="{{ config('app.host_app_client_url') }}/admin/{{ $event->uuid_admin }}" readonly>
    </div>
  </div>
</div>

@stop

@section('js')

@stop
