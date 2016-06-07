@extends('forone::layouts.master')

@section('main')

     {!! Html::list_header([
     'new'=>true,
     ]) !!}

     {!! Html::datagrid($results) !!}

     {!! Html::modal_start('modal','分配权限') !!}
     <div class="md-whiteframe-z0 bg-white">
         {!! Form::open(['method'=>'POST','url'=>'admin/roles/assign-permission','id'=>'form_id']) !!}
         {!! Form::hidden_input('id') !!}
         <div class="tab-content p m-b-md b-t b-t-2x">
             @foreach($perms as $perm)
                 <label class="md-switch"><input type="checkbox" name="{{ $perm->name }}"><i class="indigo"></i>{{ $perm->description ? $perm->display_name .'「'.$perm->description.'」' : $perm->display_name }}</label>
             @endforeach
         </div>
         {!! Form::close() !!}
     </div>
     {!! Html::modal_end() !!}
@stop

@section('js')
    @parent
    <script type="text/javascript">
        var datas = [];
        var data = '';

        function fillModal(id) {
            data = datas[id];
            data = JSON.parse(data);
            var permissions = [];
            var index=0;
            data['permissions'].forEach(function(perm) {
                permissions[index++] = perm['name'];
            });
            $('#form_id :input').each(function () {
                var name = $(this).attr('name');
                if (permissions.contains(name)) {
                    $(this).prop( "checked", true );
                } else {
                    $(this).prop( "checked", false );
                }
                if (data[name]) {
                    $(this).val(data[name]);
                }
            });
        }

        $(document).on('confirmation', '#modal', function () {
            $('#form_id').submit();
        });
    </script>
@endsection
