@inject('ns', 'Forone\Services\NavService')
@foreach(config('forone.menus') as $title => $value)
    @if($ns->checkPermission($value))
    <li>

        @if(array_key_exists('children', $value) && count($value['children']))
            <a @if(array_key_exists('uri', $value)) href='{{ '/admin/'.$value['uri'] }}' @endif>
                <i class="fa {{ $value['icon'] }}"></i>
                <span class="nav-label">{{ $title }}</span>
                <span class="fa arrow"></span>
            </a>
        @else
            <a href="#">
                <i class="fa {{ $value['icon'] }}"></i>
                <span class="nav-label">{{ $title }}</span>
                <span class="fa arrow"></span>
            </a>
        @endif

        @if(array_key_exists('children', $value) && count($value['children']))
            <ul class="nav nav-second-level">
                @foreach($value['children'] as $childTitle => $chidrenValue)
                    @if($ns->checkPermission($chidrenValue))
                        <li>
                            <a class="J_menuItem" href="{{ '/admin/'.$chidrenValue['uri'] }}" data-index="0">{{ $childTitle }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif

    </li>
    @endif
@endforeach