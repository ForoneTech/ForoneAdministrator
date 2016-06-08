<aside id="aside" class="app-aside modal fade" role="menu">
    <div class="left">
        <div class="box bg-white">
            <div class="navbar md-whiteframe-z1 no-radius blue">
                <!-- brand -->
                <a class="navbar-brand">
                    <img src="{{ asset($siteConfig['logo']) }}" alt="." style="width: 24px; height: 24px;">
                    <span class="hidden-folded m-l inline">{{ $siteConfig['site_name'] }}</span>
                </a>
                <!-- / brand -->
            </div>

            <div class="box-row">
                <div class="box-cell scrollable hover">
                    <div class="box-inner">
                        @include('forone::partials.profile')
                        <div id="nav">
                            <nav ui-nav>
                                <ul class="nav">
                                    @inject('ns', 'Forone\Services\NavService')
                                    @foreach(config('forone.menus') as $title => $value)
                                        @if($ns->checkPermission($value))
                                        <li class="{{ $ns->isActive($value) }}">
                                            <a md-ink-ripple @if(array_key_exists('uri', $value)) href='{{ '/admin/'.$value['uri'] }}' @endif >
                                                <i class="icon {{ $value['icon'] }} i-20"></i>
                                                @if(array_key_exists('children', $value) && count($value['children']))
                                                    <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                </span>
                                                @endif
                                                @if(array_key_exists('tag', $value))
                                                    <i class="pull-right up"><b class="label bg-info">{{!strpos($value['tag'], '::') ? $value['tag'] : call_user_func($value['tag'])}}</b></i>
                                                @endif
                                                <span class="font-normal">{{ $title }}</span>
                                            </a>
                                            @if(array_key_exists('children', $value) && count($value['children']))
                                                <ul class="nav nav-sub">
                                                    @foreach($value['children'] as $childTitle => $chidrenValue)
                                                        @if($ns->checkPermission($chidrenValue))
                                                        <li class="{{ $ns->isActive($chidrenValue) }}">
                                                            <a md-ink-ripple href='{{ '/admin/'.$chidrenValue['uri'] }}'>{{ $childTitle }}</a>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div><!-- / box-inner -->
                </div><!-- / box-cell -->
            </div><!-- / box-row -->

            <nav>
                <ul class="nav b-t b">
                    <li class="m-v-sm b-b b"></li>
                    <li>
                        <a md-ink-ripple href="{{ url('admin/auth/logout') }}">
                            <i class="icon mdi-action-exit-to-app i-20"></i>
                            <span>退出登录</span>
                        </a>
                    </li>
                    <li>
                        <div class="nav-item" ui-toggle-class="folded" target="#aside">
                            <label class="md-check">
                                <input type="checkbox">
                                <i class="purple no-icon"></i>
                                <span class="hidden-folded">收起侧边栏</span>
                            </label>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</aside>