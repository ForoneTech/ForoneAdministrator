@include('forone::partials.header')

<body>
<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui teal image header">
            <img src="{{ asset('vendor/forone/images/logo.png') }}" class="image">
            <div class="content">
                Log-in to your account
            </div>
        </h2>
        <form class="ui large form" action="{{ url('admin/auth/login') }}"  method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input type="text" name="email" placeholder="邮箱地址" ng-model="user.email">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" name="password" ng-model="user.password" placeholder="密码">
                    </div>
                </div>
                <div class="ui fluid large teal submit button">登录</div>
            </div>
            <div class="ui error message"></div>
        </form>
    </div>
</div>
@include('forone::partials.scripts')
<script>
    $(document)
            .ready(function() {
                $('.ui.form')
                        .form({
                            fields: {
                                email: {
                                    identifier  : 'email',
                                    rules: [
                                        {
                                            type   : 'empty',
                                            prompt : 'Please enter your e-mail'
                                        },
                                        {
                                            type   : 'email',
                                            prompt : 'Please enter a valid e-mail'
                                        }
                                    ]
                                },
                                password: {
                                    identifier  : 'password',
                                    rules: [
                                        {
                                            type   : 'empty',
                                            prompt : 'Please enter your password'
                                        },
                                        {
                                            type   : 'length[5]',
                                            prompt : 'Your password must be at least 6 characters'
                                        }
                                    ]
                                }
                            }
                        })
                ;
            })
    ;
</script>

<script>
    $(function(){
        $(document).on('blur', 'input, textarea', function (e) {
            $(this).val() ? $(this).addClass('has-value') : $(this).removeClass('has-value');
        });
    });
</script>
@if (count($errors) > 0)
    <script>
        @foreach ($errors->all() as $error)
        humane.log('{{ $error }}');
        @endforeach
    </script>
@endif

</body>
</html>
