<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $siteConfig['title'] }}</title>
    <meta name="description" content="{{ $siteConfig['description'] }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="stylesheet" href="{{ asset('vendor/forone/semantic/semantic.min.css') }}" type="text/css" />
    <script src="{{ asset('vendor/forone/common/jquery/jquery.min.js') }}"></script>
    <link src="{{ asset('vendor/forone/common/humane/themes/boldlight.css') }}" type="text/css" />
    <script src="{{ asset('vendor/forone/semantic/semantic.min.js') }}" type="text/javascript"></script>
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
<body ng-app="pushable">
<div class="html ui top attached segment html">
    <div class="main ui">
        <div class="html ui top attached segment">
            @yield('app')
        </div>
    </div>
</div>

@yield('js')

<script src="{{ asset('vendor/forone/common/humane/humane.min.js') }}"></script>

{{--<!-- Chosen -->--}}
<script src='{{ asset('vendor/forone/common/select/js/chosen.jquery.min.js') }}'></script>
{{--<!-- Endless -->--}}
<script src="{{ asset('vendor/forone/common/select/js/endless_form.js') }}"></script>
<script src="{{ asset('vendor/forone/common/remodal/dist/remodal.min.js') }}"></script>
<script src="{{ asset('vendor/forone/common/datetimepicker-master/jquery.datetimepicker.js') }}"></script>
<script src="{{ asset('vendor/forone/common/selectize/selectize.min.js') }}"></script>
<script src="{{ asset('vendor/forone/common/jquery/jquery.fancybox.pack.js') }}"></script>
<script>
    $(function(){

        $(document).on('blur', 'input, textarea', function (e) {
            $(this).val() ? $(this).addClass('has-value') : $(this).removeClass('has-value');
        });

    });
</script>

<script>
    $('.ui.dropdown').dropdown();
</script>

@if (isset($errors) && count($errors) > 0)
    <script>
    @foreach ($errors->all() as $error)
    humane.log('{{ $error }}');
    @endforeach
    </script>
@endif

</body>
</html>
