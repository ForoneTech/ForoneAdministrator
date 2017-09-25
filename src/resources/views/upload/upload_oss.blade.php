<div class="form-group {{'col-sm-'.$percent*12}}">
    {!! $label_html !!}
    <div class="col-sm-9" id="{{$name.'_div'}}">
        @foreach($imgUrl as $url)
            @if($url)
                <img id="{{!$more ? $name.'_img' : ''}}" src="{{config('forone.oss.host').$url.$process}}" onerror="javascript:this.src='/vendor/forone/images/upload.png'" data-src="{{config('forone.oss.host').$url.$process}}">
            @endif
        @endforeach
        @if($more || $value=='')
            <img style="width: 68px; height: 68px;cursor:pointer" id="{{$name.'_img'}}"
                 src="/vendor/forone/images/upload_add.png">
        @endif
        <input type="hidden" name="{{$name}}" id="{{$name}}" data-img-div="" value="{{$value}}">
    </div>
</div>

<script>
    accessid = '';
    accesskey = '';
    host = '';
    policyBase64 = '';
    signature = '';
    callbackbody = '';
    filename = '';
    key = '';
    expire = 0;
    g_object_name = '';
    g_object_name_type = '';
    now = timestamp = Date.parse(new Date()) / 1000;

    function send_request() {
        var xmlhttp = null;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        if (xmlhttp != null) {
            serverUrl = '{{ route("admin.oss.config") }}',
                    xmlhttp.open("GET", serverUrl, false);
            xmlhttp.send(null);
            return xmlhttp.responseText
        }
        else {
            alert("Your browser does not support XMLHTTP.");
        }
    }
    ;

    function check_object_radio() {
        g_object_name_type = $("#rename").data('value');
    }

    function get_signature() {
        //可以判断当前expire是否超过了当前时间,如果超过了当前时间,就重新取一下.3s 做为缓冲
        now = timestamp = Date.parse(new Date()) / 1000;
        if (expire < now + 3) {
            body = send_request()
            var obj = eval("(" + body + ")");
            host = obj['host']
            policyBase64 = obj['policy']
            accessid = obj['accessid']
            signature = obj['signature']
            expire = parseInt(obj['expire'])
            callbackbody = obj['callback']
            key = obj['dir']
            return true;
        }
        return false;
    }
    ;

    function random_string(len) {
        len = len || 32;
        var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        var maxPos = chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }

    function get_suffix(filename) {
        pos = filename.lastIndexOf('.')
        suffix = ''
        if (pos != -1) {
            suffix = filename.substring(pos)
        }
        return suffix;
    }

    function calculate_object_name(filename) {
        if (g_object_name_type == 'local_name') {
            g_object_name += "${filename}"
        }
        else if (g_object_name_type == 'random_name') {
            suffix = get_suffix(filename)
            g_object_name = key + random_string(10) + suffix
        }
        return ''
    }

    function get_uploaded_object_name(filename) {
        if (g_object_name_type == 'local_name') {
            tmp_name = g_object_name
            tmp_name = tmp_name.replace("${filename}", filename);
            return tmp_name
        }
        else if (g_object_name_type == 'random_name') {
            return g_object_name
        }
    }

    function set_upload_param(up, filename, ret) {
        if (ret == false) {
            ret = get_signature()
        }
        g_object_name = key;
        if (filename != '') {
            suffix = get_suffix(filename)
            calculate_object_name(filename)
        }
        new_multipart_params = {
            'key': g_object_name,
            'policy': policyBase64,
            'OSSAccessKeyId': accessid,
            'success_action_status': '200', //让服务端返回200,不然，默认会返回204
            'callback': callbackbody,
            'signature': signature,
        };

        up.setOption({
            'url': host,
            'multipart_params': new_multipart_params
        });

        up.start();
    }

    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: '{{$name.'_img'}}',
        //multi_selection: false,
        url: 'http://oss.aliyuncs.com',

        filters: {
            mime_types: [ //允许上传的类型
                {title: "file types", extensions: '{{config('forone.oss.mime_types')}}'},
            ],
            max_file_size: '{{config('forone.oss.max_file_size')}}', //最大只能上传10mb的文件
            prevent_duplicates: '{{config('forone.oss.prevent_duplicates')}}' //不允许选取重复文件
        },

        init: {
            FilesAdded: function (up, files) {
                {{-- 是否多文件上传 --}}
                        @if(!$more)
                if ($("#{{$name}}").data('img-div')) {
                    $("#" + $("#{{$name}}").data('img-div')).remove();
                }
                @endif
                plupload.each(files, function (file) {
                    var item = '<div id="' + file.id + 'div" style="float:left;width:68px;margin-right: 20px">' +
                            '<progress  id="progress' + file.id + '"' + 'style="width: 68px;" value="0" max="100"></progress>' +
                            '<label id="' + file.id + 'label" style="position:absolute; width: 68px; text-overflow: ellipsis; overflow: hidden; color: #ffffff"></label>' +
                            '<img id="' + file.id + 'loading" src="/vendor/forone/components/qiniu/loading.gif"></div>';
                    $("#{{$name}}_div").append(item);
                });
                set_upload_param(uploader, '', false);
            },

            BeforeUpload: function (up, file) {
                check_object_radio();
                set_upload_param(up, file.name, true);
            },

            UploadProgress: function (up, file) {
                var progress = '#progress' + file.id;
                $(progress).val(file.percent);
            },

            FileUploaded: function (up, file, info) {
                if (info.status == 200) {
                    url = '/vendor/forone/images/upload.png';
                    if(file.type.indexOf("image") > 0 )
                    {
                        url = '{{config('forone.oss.host').config('forone.oss.base_path')}}/' + get_uploaded_object_name(file.name) + '{{$process}}';
                    }
                    $("#" + file.id + 'loading').attr('src', url)
                    {{-- 是否多文件上传 --}}
                    @if(!$more)
                        $("#{{$name}}").attr("value", '{{config('forone.oss.base_path')}}/' + get_uploaded_object_name(file.name));
                        $("#" + '{{$name.'_img'}}').remove();
                        $("#{{$name}}").data("img-div", file.id + 'div');
                    @else
                    if ($("#{{$name}}").val() == '') {
                        $("#{{$name}}").attr("value", '{{config('forone.oss.base_path')}}/' + get_uploaded_object_name(file.name));
                    } else {
                        $("#{{$name}}").attr("value", $("#{{$name}}").val() + '|' + '{{config('forone.oss.base_path')}}/' + get_uploaded_object_name(file.name));
                    }
                    @endif
                }
                else {
                    console.log(info.response);
                }
            },

            Error: function (up, err) {
                if (err.code == -600) {
                    console.log("选择的文件太大了,可以根据应用情况，在upload.js 设置一下上传的最大大小");
                }
                else if (err.code == -601) {
                    console.log("选择的文件后缀不对,可以根据应用情况，在upload.js进行设置可允许的上传文件类型");
                }
                else if (err.code == -602) {
                    console.log("这个文件已经上传过一遍了");
                }
                else {
                    console.log(err.response);
                }
            }
        }
    });

    uploader.init();


</script>