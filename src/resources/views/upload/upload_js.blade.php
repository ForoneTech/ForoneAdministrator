<script src="/vendor/forone/components/qiniu/plupload/plupload.full.min.js"></script>
<script src="/vendor/forone/components/qiniu/qiniu.min.js"></script>

<script>
    function fillMultiUploadInput(filed_name){
        var imgs = $("#"+filed_name+"_div").find('img');
        var inputs = $("#"+filed_name+"_div").find('input');
        var urls = [];
        var items = [];
        imgs.each(function () {
            var s =$(this).attr('value');
            urls.push(s);
        });
        $.each(urls,function(index,item){
            var label = inputs[index].value;
            if(label) {
                item += '~' + label;
            }
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