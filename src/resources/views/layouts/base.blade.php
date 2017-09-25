<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $siteConfig['title'] }}</title>
    <meta name="description" content="{{ $siteConfig['description'] }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="stylesheet" href="{{ asset('vendor/forone/libs/assets/animate.css/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/libs/assets/font-awesome/css/font-awesome.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/libs/jquery/bootstrap/dist/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/libs/jquery/waves/dist/waves.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('vendor/forone/styles/material-design-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/styles/font.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/styles/app.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/styles/jquery.fancybox.css') }}" type="text/css" />

    {{--<!-- Chosen -->--}}
    <link href="{{ asset('vendor/forone/select/css/chosen/chosen.min.css') }}" rel="stylesheet"/>
    <!-- Endless -->
    <link href="{{ asset('vendor/forone/select/css/endless.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/forone/components/humane/themes/original.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('vendor/forone/components/remodal/dist/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/forone/components/remodal/dist/remodal-default-theme.css') }}">
    <link href="{{asset('vendor/forone/components/selectize/selectize.default.css')}}" rel="stylesheet">
    <script src="{{ asset('vendor/forone/libs/jquery/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('vendor/forone/components/selectize/selectize.min.js') }}"></script>
    {{--Add fileViewer--}}
    <link rel="stylesheet" href="{{ asset('vendor/forone/styles/Test-Photo.css') }}" type="text/css" />
    <script src="{{ asset('vendor/forone/scripts/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('vendor/forone/scripts/Test-Photo.js') }}"></script>
    @yield('head')
    <style>
        input {
            font-size: 16px;
        }

        .float-label .md-input ~ label {
            font-size: 1.2em;
        }

        .searchDiv {
            padding-left:0px;
        }

        @media (min-width: 480px) {
            .searchDiv{
                float: right;
            }
        }
    </style>

    <script>
        Array.prototype.contains = function(obj) {
            var i = this.length;
            while (i--) {
                if (this[i] == obj) {
                    return true;
                }
            }
            return false;
        }

        var init = [];
        window.onload = function () {
            init.forEach(function (f) {
                f();
            });
        };
    </script>

    @yield('css')

</head>
<body ng-app="app">
<div class="app" ui-view ng-controller="AppCtrl">

    @yield('app')

</div>

<script src="{{ asset('vendor/forone/scripts/jquery.fancybox.pack.js') }}"></script>
<script src="{{ asset('vendor/forone/libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('vendor/forone/libs/jquery/waves/dist/waves.js') }}"></script>

<script src="{{ asset('vendor/forone/scripts/ui-load.js') }}"></script>
<script src="{{ asset('vendor/forone/scripts/ui-jp.config.js') }}"></script>
<script src="{{ asset('vendor/forone/scripts/ui-jp.js') }}"></script>
<script src="{{ asset('vendor/forone/scripts/ui-nav.js') }}"></script>
<script src="{{ asset('vendor/forone/scripts/ui-toggle.js') }}"></script>
<script src="{{ asset('vendor/forone/scripts/ui-waves.js') }}"></script>

{{--<!-- Chosen -->--}}
<script src='{{ asset('vendor/forone/select/js/chosen.jquery.min.js') }}'></script>
{{--<!-- Endless -->--}}
<script src="{{ asset('vendor/forone/select/js/endless_form.js') }}"></script>

<script src="{{ asset('vendor/forone/components/humane/humane.min.js') }}"></script>
<script src="{{ asset('vendor/forone/components/remodal/dist/remodal.min.js') }}"></script>

@yield('js')

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
