@extends('forone::layouts.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/components/datetimepicker-master/jquery.datetimepicker.css') }}">
@endsection

@section('app')

@include('forone::partials.aside')

<div id="content" class="app-content" role="main">
    <div class="box">
        @include('forone::partials.navbar')
        <div class="box-row">
            <div class="box-cell">
                <div class="box-inner padding">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

    <script src="{{ asset('vendor/forone/components/datetimepicker-master/jquery.datetimepicker.js') }}"></script>

@endsection