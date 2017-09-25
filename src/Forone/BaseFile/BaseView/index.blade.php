@extends('forone.layouts.master')
@section('main')
    {!! Html::list_header([
    'new'    => true,
    'search' => '请输入编号',
    ]) !!}
    {!! Html::datagrid($results) !!}
@stop