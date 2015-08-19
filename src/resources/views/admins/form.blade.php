
{!! Form::group_text('name','用户名字','请输入用户名称') !!}
{!! Form::group_text('email','邮箱','请输入邮箱') !!}

@if (str_is('admin.admins.create', Route::current()->getName()))
    {!! Form::group_password('password','密码','请输入密码') !!}
@endif

@section('js')
    @parent
@stop