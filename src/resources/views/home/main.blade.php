@extends('forone::layouts.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/common/datetimepicker-master/jquery.datetimepicker.css') }}">
@endsection

@section('app')

    {!! Form::panel_start('告知客户') !!}
    {!! Form::model('',['method'=>'POST','route'=>['admin.roles.assign-role','1'],'class'=>'ui form']) !!}
    {!! Form::group_text('mobile','手机号码') !!}
    {!! Form::form_area('mobile','手机号码') !!}

    {!! Form::group_text('jobtitle','岗位职位') !!}

    {!! Form::form_date('local_live_date','起始居住时间') !!}

    {!! Form::form_time('local_live_time','起始居住时间') !!}


    {!! Form::form_select('education', '教育程度', [
        ['label'=>'初中及以下', 'value'=>0],
        ['label'=>'高中或中专', 'value'=>1],
        ['label'=>'大学专科', 'value'=>2],
        ['label'=>'大学本科', 'value'=>3],
        ['label'=>'研究生及以上', 'value'=>4]
        ], 0.5) !!}

    {!! Form::form_multi_select('education_1', '教育程度', [
       ['label'=>'', 'value'=>0],
       ['label'=>'高中或中专', 'value'=>1],
       ['label'=>'大学专科', 'value'=>2],
       ['label'=>'大学本科', 'value'=>3],
       ['label'=>'研究生及以上', 'value'=>4]
       ], 0.5) !!}

    {!! Form::form_tags_input('tags','tags') !!}

    {!! Form::group_checkbox('risk_level', '风险等级', [
                                [0, 'A', true],
                                [1, 'B'],
                                [2, 'C'],
                                [3, 'D'],
                                [4, 'E'],
                                [5, 'F']
                                ], 0.5) !!}

    {!! Form::form_radio('know', '是否知晓贷款', [
       [0, '不知道', true],
       [1, '知道']
       ], 0.5) !!}

    {!! Form::multi_file_upload('field_name', 'label') !!}

    {!! Form::group_text('text', 'label') !!}

    {!! Form::group_password('password', 'label') !!}

    {!! Form::ueditor('name', 'label') !!}



    {!! Form::form_action('保存') !!}


    {!! Form::close() !!}

    {{--{!! Form::form_button(['name'=>'解冻全部金额:888','uri'=>'admin.roles.assign-role','id'=>'999'],[]) !!}--}}

    {{--{!! Form::panel_end('保存') !!}--}}

@endsection