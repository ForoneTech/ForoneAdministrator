<script src="/vendor/forone/components/qiniu/plupload/plupload.full.min.js"></script>
<script src="/vendor/forone/components/qiniu/qiniu.min.js"></script>

<script>
    function fillMultiUploadInput(filed_name){
        var imgs = $("#"+filed_name+"_div").find('img');
        var urls = [];
        var items = [];
        imgs.each(function () {
            var s =$(this)[0].src;
            s = s.replace('?imageView2/1/w/68/h/68', '');
            s = s.replace('{{config('forone.qiniu.host')}}', '');
            urls.push(s);
        });
        $.each(urls,function(index,item){
            items.push(item);
        })
        var value = items.join('|')
        $('#'+filed_name).attr('value', value);
    }

    function removeMultiUploadItem(id, name){
        $('#'+id).remove();
        fillMultiUploadInput(name);
    }
</script>