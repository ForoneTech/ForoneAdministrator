@extends('forone::layouts.master')

@section('main')

     {!! Html::list_header([
     'new'=>true,
     ]) !!}

     {!! Html::datagrid($results) !!}

     {!! Html::modal_start('modal','编辑类型配置') !!}

     <div class="md-whiteframe-z0 bg-white">
         {!! Form::open(['method'=>'POST','url'=>'admin/admins/assign-role','id'=>'form_id']) !!}
         {!! Form::hidden_input('id') !!}
         <div class="tab-content p m-b-md b-t b-t-2x">
             @foreach($roles as $role)
                 <label class="md-switch"><input type="checkbox" name="{{ $role->name }}"><i class="indigo"></i>{{ $role->description ? $role->display_name .'「'.$role->description.'」' : $role->display_name }}</label>
             @endforeach
         </div>
         {!! Form::close() !!}
     </div>

     {!! Form::close() !!}
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
            data['roles'].forEach(function(perm) {
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