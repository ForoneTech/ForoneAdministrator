@extends('forone::layouts.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/common/datetimepicker-master/jquery.datetimepicker.css') }}">
@endsection

@section('app')

<div id="content" class="app-content" role="main">
    <div class="box">
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
    <script src="{{ asset('vendor/forone/common/datetimepicker-master/jquery.datetimepicker.js') }}"></script>
@endsection