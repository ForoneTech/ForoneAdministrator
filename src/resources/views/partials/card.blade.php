<div class="col-xs-6">
    <div class="card @if(isset($blue)) blue @endif">
        <div class="card-heading">
            <h2>{{$title}}</h2>
            <small>{{$desc}}</small>
        </div>
        @if(isset($data))
            <div class="list-group list-group-lg no-bg">

                @foreach($data as $item)
                    <a href="{{$item['href']}}" class="list-group-item">
                    <span class="pull-right">
                      {{$item['value']}}
                    </span>
                        {{$item['label']}}
                    </a>
                @endforeach

            </div>
        @endif
    </div>
</div>