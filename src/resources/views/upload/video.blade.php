<script type='text/javascript'>
    init.push(function(){
        var name = "{{$name}}";
        Qiniu.uploader({
            browse_button: "{{$name}}_img",
            uptoken_url: '{{ route("admin.qiniu.video-token") }}',
            unique_names: true,
            domain: '{{config('forone.qiniu.host')}}',
            max_file_size: '{{config('forone.qiniu.max_file_size')}}' ? '{{config('forone.qiniu.max_file_size')}}' : '150mb',
            flash_swf_url: '/vendor/forone/components/qiniu/plupload/Moxie.swf',
            max_retries: 3,
            chunk_size: '4mb',
            auto_start: true,
            init: {
                'FilesAdded': function(up, files) {
                    plupload.each(files, function(file) {
                                @if(isset($multi))
                        var reader = new FileReader();
                        reader.onload = function(e){
                            var item = '<div id="'+file.id+'div" style="float:left;width:68px;margin-right: 20px">' +
                                    '<img ' +
                                    'onclick="removeMultiUploadItem(\''+file.id+'div\',\''+name+'\')" ' +
                                    'id="'+file.id+'" ' +
                                    'style="width: 68px; height: 68px;cursor:pointer" ' +
                                    'src="'+ e.target.result+'">' +
                                    '<img id="'+file.id+'loading" src="/vendor/forone/components/qiniu/loading.gif">';
                            @if(isset($with_description) && $with_description)
                                    item+='<input type="text" onkeyup="fillMultiUploadInput(\''+name+'\')" style="width: 68px;float: left" placeholder="图片描述">';
                            @else
                                    item+='</div>';
                            @endif
                            $("#{{$name}}_div").append(item);
                        }
                        reader.readAsDataURL(file.getNative());
                        @endif
                    });
                },
                'UploadProgress': function(up, file) {
                    $('#progress').val(file.percent);

                },
                'FileUploaded': function(up, file, info) {
                    var domain = up.getOption('domain');
                    console.log(info);
                    var res = $.parseJSON(info);
                    var sourceLink = domain + res.key;
                    var source =  '<source src=" '+sourceLink+' " type="video/mp4">'
                    @if(!isset($multi))
                    $("source").attr("src",sourceLink);
                    $("video").attr("src",sourceLink).html(source);
                    $("#{{$name}}").attr("value",res.persistentId);
                    $(".video-js").css("display",'block');
                    $("video").css("display",'block');
                    @else
                        $("#"+file.id).attr("src",sourceLink);
                    $("#"+file.id+"loading").remove();
                    fillMultiUploadInput(name);
                    @endif
                },
                'Error': function(up, err, errTip) {
                },
                'UploadComplete': function() {

                }
            }
        });
    });
</script>