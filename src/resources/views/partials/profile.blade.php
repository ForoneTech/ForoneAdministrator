<div class="p hidden-folded blue-50"
     style="background-image:url({{ asset('vendor/forone/images/bg.png') }}); background-size:cover">
    <div class="rounded w-64 bg-white inline pos-rlt">
        <img src="{{ asset('vendor/forone/images/a1.jpg') }}" class="img-responsive rounded">
    </div>
    <a class="block m-t-sm" {{--ui-toggle-class="hide, show" target="#nav, #account"--}}>
        <span class="block font-bold">{{ $currentUser['name'] }}</span>
        {{ $currentUser['email'] }}
    </a>
</div>