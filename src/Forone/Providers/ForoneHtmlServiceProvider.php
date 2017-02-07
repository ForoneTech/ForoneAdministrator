<?php
/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 9:16 PM
 * Email: mani@forone.co
 */

namespace Forone\Providers;

use App\AdminMessage;
use Form;
use Html;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\ServiceProvider;

class ForoneHtmlServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->groupLabel();
        $this->panelStart();
        $this->panelEnd();
        $this->modalButton();
        $this->modalStart();
        $this->modalEnd();
        $this->json();
        $this->datagridHeader();
        $this->dataGrid();
        $this->datagridFooter();
    }

    public static function parseValue($model, $name)
    {
        $arr = explode('.', $name);
        if (sizeof($arr) == 2) {
            return $model && (!is_array($model) || array_key_exists($arr[0], $model)) ? $model[$arr[0]][$arr[1]] : '';
        } else {
            return $model && (!is_array($model) || array_key_exists($name, $model)) ? $model[$name] : '';
        }
    }

    private function dataGrid()
    {
        Html::macro('datagrid', function ($data) {

            $html = '<table class="table m-b-none" data-sort="false" ui-jp="footable">';
            $columns = $data['columns'];
            $items = $data['items'];
            $heads = [];
            $widths = [];
            $fields = [];
            $functions = [];

            // build table head
            $html .= '<thead><tr>';
            foreach ($columns as $index => $column) {
                array_push($heads, $column[0]); // title
                array_push($fields, $column[1]); // fields
                $size = sizeof($column);
                switch ($size) {
                    case 2:
                        array_push($widths, 0); // width
                        break;
                    case 3:
                        if (is_int($column[2])) {
                            array_push($widths, $column[2]);
                        } else {
                            array_push($widths, 0);
                            $functions[$column[1] . $index] = $column[2];
                        }
                        break;
                    case 4:
                        array_push($widths, $column[2]);
                        $functions[$column[1] . $index] = $column[3];
                        break;
                }
            }

            foreach ($heads as $head) {
                $index = array_search($head, $heads);
                $class = '';
                $dataToggle = '';
                if ($index == 0) {
                    $first = 'footable-first-column ';
                    $dataToggle = 'data-toggle="true"';
                    $class .= $first;
                }
                if ($index == sizeof($heads)) {
                    $class .= 'footable-last-column ';
                }
                if ($index <= 1) {
                    $class .= 'footable-visible ';
                } else {
                    if ($index < 4) {
                        $dataToggle .= ' data-hide="phone"';
                    } else {
                        $dataToggle .= ' data-hide="tablet,phone"';
                    }
                }

                if ($widths[$index]) {
                    $dataToggle .= ' style="width:' . $widths[$index] . 'px"';
                }

                $item = '<th ' . $dataToggle . ' class="' . $class . '" >' . $head . '</th>';
                $html .= $item;
            }
            $html .= '</tr></thead>';

            $html .= '<tbody>';
            if ($items) {
                foreach ($items as $item) {
                    $html .= '<tr>';
                    foreach ($fields as $index => $field) {
                        $html .= $widths[$index] ? '<td style="width: ' . $widths[$index] . 'px">' : '<td>';
                        if ($field == 'buttons') {
                            $buttons = $functions[$field . $index]($item);
                            $dropDown = [];
                            $showMore = count($buttons) > 3;
                            foreach ($buttons as $button) {
                                $size = sizeof($button);
                                $normalButton = false;
                                if ($size == 1) {
                                    $value = $button[0];
                                    $normalButton = array_search($value, ['禁用', '启用', '查看', '编辑']);
                                    switch ($value) {
                                        case '禁用':
                                            $html .= Form::form_button([
                                                'name'  => $value,
                                                'id'    => $item->id,
                                                'class' => 'bg-warning'
                                            ], ['enabled' => false]);
                                            break;
                                        case '启用':
                                            $html .= Form::form_button([
                                                'name'  => $value,
                                                'id'    => $item->id,
                                                'class' => 'btn-success'
                                            ], ['enabled' => true]);
                                            break;
                                        case '查看':
                                            if ($showMore) {
                                                $dropDown[] = [
                                                    'label' => '查看',
                                                    'href'  => $this->url->current() . '/' . $item['id']
                                                ];
                                            } else {
                                                $html .= '<a style="margin-right:3px" href="' . $this->url->current() . '/' . $item['id'] . '">
                                                    <button class="btn btn-default">查看</button></a>';
                                            }
                                            break;
                                        case '编辑':
                                            if ($showMore) {
                                                $dropDown[] = [
                                                    'label' => '编辑',
                                                    'href'  => $this->url->current() . '/' . $item['id'] . '/edit'
                                                ];
                                            } else {
                                                $html .= '<a style="margin-right:5px" href="' . $this->url->current() . '/' . $item['id'] . '/edit">
                                                    <button  class="btn btn-default">编辑</button></a>';
                                            }
                                            break;
                                    }
                                }
                                if ($normalButton === false) {
                                    $getButton = sizeof($button) > 2 ? true : false;
                                    $config = $getButton ? $button : $button[0];
                                    $data = $getButton || $size == 1 ? [] : $button[1];
                                    if (is_string($data) && strripos($data, '#') == 0) {
                                        $html .= Form::modal_button($config, $data, $item);
                                    } else {
                                        if (array_key_exists('method', $config) && $config['method'] == 'GET') {
                                            $uri = array_key_exists('uri', $config) ? $config['uri'] : '';
                                            $config['uri'] = $uri;
                                            if ($showMore) {
                                                if (strpos($config['uri'], '.')) {
                                                    if ($config['method'] == 'POST') {
                                                        $uri = route($config['uri']);
                                                    } else {
                                                        if ($data) {
                                                            $uri = route($config['uri'], $data);
                                                        } else {
                                                            $uri = route($config['uri'], ['id' => $config['id']]);
                                                        }
                                                    }
                                                } else {
                                                    $uri = $this->url->current() . '/' . $config['uri'];
                                                }
                                                $dropDown[] = ['label' => $config['name'], 'href' => $uri];
                                            } else {
                                                $html .= Form::form_button($config, $data);
                                            }
                                        } else {
                                            $config['id'] = $item->id;
                                            $html .= Form::form_button($config, $data);
                                        }
                                    }
                                }
                            }
                            if (!empty($dropDown)) {
                                $html .= Form::form_dropdown('更多', $dropDown);
                            }
                        } else {
                            if (array_key_exists($field . $index, $functions)) {
                                if (is_array($item)) {
                                    $value = array_key_exists($field, $item) ? $item[$field] : '';
                                } else {
                                    $value = $item->{$field} ? $item->{$field} : '';
                                }
                                $value = $functions[$field . $index]($value);
                            } else {
                                $arr = explode('.', $field);
                                if (sizeof($arr) == 2) {
                                    $value = $item[$arr[0]][$arr[1]];
                                } else {
                                    if (is_array($item)) {
                                        $value = array_key_exists($field, $item) ? $item[$field] : '';
                                    } else {
                                        $value = $item->{$field} ? $item->{$field} : '';
                                    }
                                }
                            }
                            $html .= $value . '</td>';
                        }
                    }
                    $html .= '</tr>';
                }
            }
            $html .= '<tbody>';

            $html .= '<tfoot>';
            $html .= ' <tr>';
            $html .= '    <td colspan="10" class="text-center">';
            $html .= $items && !is_array($items) ? $items->render() : '';
            $html .= '  </td>';
            $html .= ' </tr>';
            $html .= '</tfoot>';
            $html .= '</table>';
            $js = "<script>init.push(function(){
                   $('.fancybox').fancybox({
                    openEffect  : 'none',
                    closeEffect : 'none'
  });
                });</script>";
            $html .= $js;

            return $html;
        });
    }

    private function groupLabel()
    {
        Form::macro('group_label', function ($name, $label) {
            $value = ForoneHtmlServiceProvider::parseValue($this->model, $name);

            return '<div class="control-group">
                        <label for="title" class="control-label">' . $label . '</label>
                        <div class="controls">
                            <label for="title" class="control-label">' . $value . '</label>
                        </div>
                    </div>';
        });
    }


    public function panelStart()
    {
        Form::macro('panel_start', function ($title = '') {
            return '<div class="panel panel-default">
                        <div class="panel-heading bg-white">
                            <span class="font-bold">' . $title . '</span>
                        </div>
                    <div class="panel-body">';
        });
    }

    public function panelEnd()
    {
        Form::macro('panel_end', function ($label = '') {
            if (!$label) {
                return '</div></div>';
            }
            if (is_array($label)) {
                $buttons = '';
                foreach ($label as $button) {
                    if (is_string($button)) {
                        $buttons .= '
                            <button type="submit" class="btn btn-info">' . $button . '</button>
                        ';
                    } elseif (!is_array($button) && get_class($button) == 'Forone\Models\Button') {
                        $buttons .= Form::form_button($button);
                    } elseif (!is_array($button[0])) {
                        $buttons .= Form::form_dropdown($button[0], $button[1]);
                    } else {
                        $buttons .= Form::form_button(array_get($button,0),array_get($button,1));
                    }
                }
                $result = '</div><footer class="panel-footer" style="height: 70px">
                            ' . $buttons . '
                        </footer></div>';
            } else {
                $result = '</div><footer class="panel-footer">
                            <button type="submit" class="btn btn-info">' . $label . '</button>
                        </footer></div>';
            }

            return $result;
        });
    }

    public function modalButton()
    {
        Form::macro('modal_button', function ($label, $modal, $data, $class = 'waves-effect') {
            $jsonData = json_encode($data);
            $id = is_array($data) ? $data['id'] : $data->{'id'};
            $html = '<a href="' . $modal . '" style="margin-right:5px;"><button onclick="fillModal(\'' . $id . '\')" class="btn btn-default ' . $class . '" >' . $label . '</button></a>';
            $js = "<script>init.push(function(){datas['" . $id . "']=" . json_encode($jsonData) . ";})</script>";
            return $html . $js;
        });
    }

    private function modalStart()
    {
        Html::macro('modal_start', function ($id, $title) {
            $html = '<div id="' . $id . '" class="remodal" data-remodal-id="' . $id . '">
                    <input type="hidden">
                    <div>
                        <span style="font-size: 20px">' . $title . '</span>
                    </div>
                    <div class="panel-body" style="margin: 35px 0px;padding: 0;">';

            return $html;
        });
    }

    private function json()
    {
        Html::macro('json', function ($data) {
            return '<pre><code>' . json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) . '</code></pre>';
        });
    }

    private function modalEnd()
    {
        Html::macro('modal_end', function () {
            return '</div><div><button data-remodal-action="cancel" class="remodal-cancel" style="margin-right: 20px;">取消</button>
        <button data-remodal-action="confirm" class="remodal-confirm">确认</button></div>';
        });
    }

    private function datagridHeader()
    {
        $handler = function ($data) {
            $html = '<div class="panel panel-default">';
            $title = isset($data['title']) ? $data['title'] : '';
            $html .= '<div class="panel-heading">' . $title . '</div>';
            $html .= '<div class="panel-body b-b b-light">';
            if (array_key_exists('new', $data)) {
                $html .= '<a href="' . $this->url->current() . '/create" class="btn btn-primary">&#43; 新增</a>';
            }
            if (!empty($data['buttons'])) {
                foreach ($data['buttons'] as $button) {
                    $html .= Form::form_button($button);
                }
            }
            if (array_key_exists('filters', $data)) {

                $result = '';
                foreach ($data['filters'] as $key => $value) {
                    $result .= '<div class="col-sm-2" style="padding-left: 0px;width: 8%">
                        <select class="form-control" name="' . $key . '">';
                    foreach ($value as $item) {
                        $value = is_array($item) ? $item['value'] : $item;
                        $label = is_array($item) ? $item['label'] : $item;
                        $selected = '';
                        $urlValue = Input::get($key);
                        if ($urlValue != null) {
                            $selected = $urlValue == $item['value'] ? 'selected="selected"' : '';
                        }
                        $result .= '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
                    }
                    $result .= '</select></div>';
                }

                $js = "<script>init.push(function(){
                        $('select').change(function(){
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    paramObject[arr[0]] = arr[1];
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+decodeURIComponent($.param(paramObject)) : baseUrl;
                        });
                    })</script>";
                $html .= $result . $js;
            }

            if (array_key_exists('time', $data)) {

                $datetime = function ($name, $holder) {
                    $result = '<div class="form-group" style="width: 150px; float: left; padding-right: 15px;">
                        <div>' .
                        '<input id="' . $name . '" name="' . $name . '" type="text" value="' . Input::get($name) . '" class="form-control" placeholder="' . $holder . '">';
                    $js = "<script>init.push(function(){jQuery('#$name').datetimepicker({format:'Y-m-d H:i'});})</script>";
                    $time = $result . '</div></div>' . $js;

                    $js = "<script>init.push(function(){
                        jQuery('#$name').datetimepicker({
                          timepicker:false,
                          onChangeDateTime:function(dp,input){
                                var params = window.location.search.substring(1);
                                var paramObject = {};
                                var paramArray = params.split('&');
                                paramArray.forEach(function(param){
                                    if(param){
                                        var arr = param.split('=');
                                        paramObject[arr[0]] = arr[1];
                                    }
                                });
                                var baseUrl = window.location.origin+window.location.pathname;
                                var date = $('#$name');
                                if(date.val()){
                                    paramObject[date.attr('name')] = date.val();
                                }else{
                                    delete paramObject[date.attr('name')];
                                }
                                var href = $.param(paramObject) ? baseUrl+'?'+decodeURIComponent($.param(paramObject)) : baseUrl;
                                window.location.href = href;
                              }
                        });
                    })</script>";

                    return $time . $js;
                };

                $html .= $datetime('begin', '起始时间') . $datetime('end', '截止时间');
            }

            if (array_key_exists('priceStart', $data)) {

                $priceStart = is_bool($data['priceStart']) ? '价格' : $data['priceStart'];
                $html .= '<div class="col-md-3" style="padding-left:0px;width: 8%">
                                <input id="priceStartInput" type="text" class="form-control input" name="priceStart" value="' . Input::get('priceStart') . '" placeholder="' . $priceStart . '"  />
                            </div>';
                $js = "<script>init.push(function(){
                    $('#priceStartInput').keyup(function(event){
                        if(event.keyCode == 13){
                            console.log('do search');
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    paramObject[arr[0]] = decodeURIComponent(arr[1]);
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                 if($('#priceEndInput').val())
                                    {
                                        if(parseFloat($('#priceEndInput').val()) >= parseFloat($(this).val())){
                                             paramObject[$('#priceEndInput').attr('name')] = $('#priceEndInput').val();
                                        }else{
                                            alert('范围有错');
                                            return;
                                        }
                                    }
                                 paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+$.param(paramObject) : baseUrl;
                        }
                    });
                });</script>";
                $html .= $js;
            }

            if (array_key_exists('priceEnd', $data)) {

                $priceEnd = is_bool($data['priceEnd']) ? '价格' : $data['priceEnd'];
                $html .= '<div class="col-md-3" style="padding-left:0px;width: 8%">
                                <input id="priceEndInput" type="text" class="form-control input" name="priceEnd" value="' . Input::get('priceEnd') . '" placeholder="' . $priceEnd . '"  />
                            </div>';
                $js = "<script>init.push(function(){
                    $('#priceEndInput').keyup(function(event){
                        if(event.keyCode == 13){
                            console.log('do search');
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    paramObject[arr[0]] = arr[1];
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                if($('#priceStartInput').val())
                                {
                                    if(parseFloat($('#priceStartInput').val()) <= parseFloat($(this).val())){
                                         paramObject[$('#priceStartInput').attr('name')] = $('#priceStartInput').val();
                                    }else{
                                        alert('范围有错');
                                        return;
                                    }
                                }
                                paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+$.param(paramObject) : baseUrl;
                        }
                    });
                });</script>";
                $html .= $js;
            }

            if (array_key_exists('search', $data)) {
                $search = is_bool($data['search']) ? '请输入您想检索的信息' : $data['search'];
                $html .= '<div class="col-md-3" style="padding-left:0px; float: right;width: 17%">
                                <input id="keywordsInput" type="text" class="form-control input" name="keywords" value="' . Input::get('keywords') . '" placeholder="' . $search . '"  />
                            </div>';
                $js = "<script>init.push(function(){
                    $('#keywordsInput').keyup(function(event){
                        if(event.keyCode == 13){
                            console.log('do search');
                            var params = window.location.search.substring(1);
                            var paramObject = {};
                            var paramArray = params.split('&');
                            paramArray.forEach(function(param){
                                if(param){
                                    var arr = param.split('=');
                                    if(arr[0] !== 'page'){
                                        paramObject[arr[0]] = arr[1];
                                    }
                                }
                            });
                            var baseUrl = window.location.origin+window.location.pathname;
                            if($(this).val()){
                                paramObject[$(this).attr('name')] = $(this).val();
                            }else{
                                delete paramObject[$(this).attr('name')];
                            }
                            window.location.href = $.param(paramObject) ? baseUrl+'?'+$.param(paramObject) : baseUrl;
                        }
                    });
                });</script>";
                $html .= $js;
            }

            $html .= '</div>';

            return $html;
        };
        Html::macro('list_header', $handler);
        Html::macro('datagrid_header', $handler);
    }

    private function datagridFooter()
    {
        Html::macro('footer', function ($data) {
            return $data ? $data->render() : '';
        });
    }
}
