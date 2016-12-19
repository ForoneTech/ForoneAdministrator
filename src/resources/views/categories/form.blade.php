@if(!isset($edit))
{!! Form::group_text('name','新系统名称','请输入权限系统名称') !!}
@endif
{!! Form::group_text('description','新权限描述','请输入权限描述') !!}
{!! Form::form_text('department','部门名称','部门名称') !!}
{!!Form::form_select('display_name', '用户等级', [
    ['label'=>'游客', 'value'=>'1'],
    ['label'=>'版主', 'value'=>'2'],
    ['label'=>'超级管理员', 'value'=>'3'],
],0.5,false)!!}
{!! Form::form_radio('sex', '性别', [
['男', '男', true],
['女', '女'],
['保密', '保密']
], 0.5) !!}
@section('js')
    @parent
@stop