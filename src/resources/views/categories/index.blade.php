@extends('forone::layouts.master')

@section('main')

    {!! Html::list_header([
	    'new'=>true,
	    'search'=>true,
	    'filters'=>$results['filters']
    ]) !!}

    {!! Html::datagrid($results) !!}
@stop