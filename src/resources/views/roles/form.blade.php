@if(!isset($edit))
{!! Form::group_text('name','系统名称','请输入角色系统名称') !!}
@endif
{!! Form::group_text('display_name','显示名称','请输入角色显示名称') !!}
{!! Form::group_text('description','角色描述','请输入角色描述') !!}

@section('js')
    @parent
@stop