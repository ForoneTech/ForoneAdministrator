@include('forone::partials.header')

<body>
<div class="indigo app">

    <div class="center-block w-xxl w-auto-xs p-v-md">
        <div class="navbar">
            <div class="navbar-brand m-t-lg text-center">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" style="width: 24px; height: 24px;">
                    <path d="M 50 0 L 100 14 L 92 80 Z" fill="rgba(139, 195, 74, 0.5)" />
                    <path d="M 92 80 L 50 0 L 50 100 Z" fill="rgba(139, 195, 74, 0.8)"  />
                    <path d="M 8 80 L 50 0 L 50 100 Z" fill="#fff" />
                    <path d="M 50 0 L 8 80 L 0 14 Z" fill="rgba(255, 255, 255, 0.6)" />
                </svg>
                <span class="m-l inline">{{ $siteConfig['site_name'] }}</span>
            </div>
        </div>

        <div class="p-lg panel md-whiteframe-z1 text-color m">
            <div class="m-b text-sm">登录</div>
            <form name="form" action="{{ url('/admin/auth/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="md-form-group float-label">
                    <input type="email" name="email" class="md-input" ng-model="user.email" required>
                    <label>邮箱</label>
                </div>
                <div class="md-form-group float-label">
                    <input type="password" name="password" class="md-input" ng-model="user.password" required>
                    <label>密码</label>
                </div>
                <div class="m-b-md">
                    <label class="md-check">
                        <input type="checkbox" name="remember"><i class="indigo"></i> 记住登录
                    </label>
                </div>
                <button md-ink-ripple type="submit" class="md-btn md-raised pink btn-block p-h-md">登录</button>
            </form>
        </div>
    </div>
</div>

@include('forone::partials.scripts')

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
