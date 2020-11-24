@extends('adminlte::page')

@push('css')
<link rel="stylesheet" href="{{ asset('css/all.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700">
@yield('main_css')
@endpush

@section('content_top_nav_right')
@if (count(config('app.languages')) > 1)
@include('admin.locale-dropdown')
@endif
@endsection
