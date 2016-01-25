@extends('forone::layouts.master')
@section('title', '查看'.$page_name)
@section('main')
    {!! Form::panel_start('查看'.$page_name) !!}
    {!! Form::model($data,['url'=>Request::url().'/edit','class'=>'form-horizontal', 'method'=>'GET']) !!}
        @include('forone::'. $uri.'.form', ['show'=>true])
    {!! Form::panel_end('编辑') !!}
    {!! Form::close() !!}
@stop