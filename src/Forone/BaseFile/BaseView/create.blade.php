@extends('forone.layouts.master')
@section('title', '创建'.$page_name)
@section('main')
    {!! Form::panel_start('创建'.$page_name) !!}
    @if (\Illuminate\Support\Facades\Input::old())
        {!! Form::model(\Illuminate\Support\Facades\Input::old(),['url'=>'admin/'.$uri,'class'=>'form-horizontal']) !!}
    @else
        {!! Form::open(['url'=>'admin/'.$uri,'class'=>'form-horizontal']) !!}
    @endif
        @include($uri.'.form')
    {!! Form::panel_end('保存') !!}
    {!! Form::close() !!}
@stop