<?php

/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 9:16 PM
 * Email: mani@forone.co
 */

namespace Forone\Providers;

use Form;
use Html;
use View;
use Illuminate\Support\ServiceProvider;

class ForoneFormServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->parseSpecialFields();
        $this->hiddenInput();
        $this->formText();
        $this->formRead();
        $this->formPassword();
        $this->formArea();
        $this->formRadio();
        $this->formCheckbox();
        $this->formAction();
        $this->formButton();
        $this->formLabel();
        $this->formSelect();
        $this->formMultiSelect();
        $this->formTagsInput();
        $this->formDate();
        $this->formTime();
        $this->ueditor();
        $this->formDropDown();
    }

    public static function parseValue($model, $name)
    {
        $arr = explode('-', $name);
        $dotArray = explode('.', $name);
        if (count($dotArray) == 2) {
            $arr = $dotArray;
        }
        if (count($arr) == 2) {
            return $model && (!is_array($model) && isset($model[$arr[0]][$arr[1]])) ? $model[$arr[0]][$arr[1]] : '';
        } else {
            return $model && (!is_array($model) || array_key_exists($name, $model)) ? $model[$name] : '';
        }
    }

    /**
     *ueditor
     */
    private function ueditor()
    {
        $handler = function ($name, $label, $percent = 0.5, $modal = false) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $js = View::make('forone::ueditor.ueditor');
            return $js . '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                             <script id="container" name=' . $name . ' type="text/plain">
                                    ' . $value . '
                            </script>
                            <script type="text/javascript">
                                var ue = UE.getEditor("container");
                            </script>
                          </div>
                    </div>';
        };
        Form::macro('ueditor', $handler);
    }

    /**
     * fill special fields data
     */

    private function parseSpecialFields()
    {
        Form::macro('parse', function ($inputData) {
            $fields = ['placeholder', 'percent', 'modal', 'label_col'];
            $data = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $inputData)) {
                    $data[$field] = $inputData[$field];
                } else {
                    $data[$field] = '';
                }
            }

            return $data;
        });
    }

    private function hiddenInput()
    {
        Form::macro('hidden_input', function ($name, $value = '') {
            return '<input type="hidden" value="' . $value . '" name="' . $name . '" id="' . $name . '">';
        });
    }

    private function formText()
    {
        $handler = function ($name, $label, $placeholder = '', $percent = 0.5, $modal = false) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $data = '';
            $input_col = 9;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"' : '';
            return '<div class="form-group col-sm-' . ($percent * 12) . '" ' . $style . '>
                        ' . Form::form_label($label, $data) . '
                        <div class="col-sm-' . $input_col . '">
                            <input name="' . $name . '" type="text" value="' . $value . '" class="form-control" placeholder="' . $placeholder . '">
                          </div>
                    </div>';
        };
        Form::macro('group_text', $handler);
        Form::macro('form_text', $handler);
    }

    private function formRead()
    {
        $handler = function ($name, $label,  $percent = 0.5, $color='',$value='') {
            if (!$value) {
                $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            }
            $data = '';
            $input_col = 9;
            return '<div class="form-group col-sm-' . ($percent * 12) . '" '  . '>
                        ' . Form::form_label($label, $data) . '
                        <div class="col-sm-' . $input_col . '">
                            <input  name="' . $name . '" type="text" readonly="readonly" value="' . $value . '" class="form-control" style="color:' . $color . '">
                          </div>
                    </div>';
        };
        Form::macro('group_read', $handler);
        Form::macro('form_read', $handler);
    }

    private function formPassword()
    {
        $handler = function ($name, $label, $placeholder = '', $percent = 0.5, $modal = false) {
            $data = '';
            $input_col = 9;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"' : '';
            return '<div class="form-group col-sm-' . ($percent * 12) . '" ' . $style . '>
                        ' . Form::form_label($label, $data) . '
                        <div class="col-sm-' . $input_col . '">
                            <input name="' . $name . '" type="password" class="form-control" placeholder="' . $placeholder . '">
                          </div>
                    </div>';
        };
        Form::macro('group_password', $handler);
        Form::macro('form_password', $handler);
    }

    private function formArea()
    {
        $handler = function ($name, $label, $placeholder = '', $percent = 0.5) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $data = '';
            $input_col = 9;
            $modal = false;
            if (is_array($placeholder)) {
                $data = Form::parse($placeholder);
                $placeholder = $data['placeholder'];
                $percent = $data['percent'] ? $data['percent'] : 0.5;
                $modal = $data['modal'] ? true : false;
                $input_col = $data['label_col'] ? 12 - $data['label_col'] : 9;
            }
            $style = $modal ? 'style="padding:0px"' : '';
            return '<div class="form-group col-sm-' . ($percent * 12) . '" ' . $style . '>
                        ' . Form::form_label($label, $data) . '
                        <div class="col-sm-' . $input_col . '">
                            <textarea id="' . $name . '" name="' . $name . '" rows="6" class="form-control" placeholder="' . $placeholder . '">' . $value . '</textarea>
                        </div>
                    </div>';
        };
        Form::macro('group_area', $handler);
        Form::macro('form_area', $handler);
    }

    private function formRadio()
    {
        $handler = function ($name, $label, $data, $percent = 1) {
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">';
            foreach ($data as $item) {
                if ($this->model) {
                    $checked = $this->model[$name] == $item[0] ? 'checked=true' : '';;
                } else {
                    $checked = sizeof($item) == 3 ? 'checked=' . $item[2] : '';
                }
                $result .= '<input ' . $checked . '" name="' . $name . '" type="radio" value="' . $item[0] . '">
                            <span style="vertical-align: middle;padding-right:10px">' . $item[1] . '</span>';
            }
            return $result . '</div></div>';
        };
        Form::macro('group_radio', $handler);
        Form::macro('form_radio', $handler);
    }

    private function formCheckbox()
    {
        $handler = function ($name, $label, $data, $percent = 1) {
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">';
            foreach ($data as $item) {
                if ($this->model) {
                    $checked = $this->model[$name] == $item[0] ? 'checked=true' : '';;
                } else {
                    $checked = sizeof($item) == 3 ? 'checked=' . $item[2] : '';
                }
                $result .= '<label class="checkbox-inline">';
                $result .= '<input ' . $checked . '" name="' . $name . '" type="checkbox" value="' . $item[0] . '">
                            <span style="vertical-align: middle;padding-right:10px">' . $item[1] . '</span>';
                $result .= '</label>';
            }
            return $result . '</div></div>';
        };
        Form::macro('group_checkbox', $handler);
        Form::macro('form_checkbox', $handler);
    }

    private function formAction()
    {
        Form::macro('form_action', function ($label) {
            return '<div class="form-group col-sm-12">
                        <button class="btn btn-fw btn-primary" type="submit">' . $label . '</button>
                    </div>';
        });
    }

    private function formButton()
    {
        Form::macro('form_button', function ($config, $data = []) {
            if (!is_array($config)) {
                $data = $config->data;
                $config = $config->config;
            }
            if (!array_key_exists('alert', $config)) {
                $config['alert'] = '确认吗？';
            }
            if (!array_key_exists('uri', $config)) {
                $config['uri'] = 'update';
            }
            if (!array_key_exists('id', $config)) {
                $config['id'] = $data['id'];
            }
            if (!array_key_exists('method', $config)) {
                $config['method'] = $config['uri'] == 'update'?"PATCH":'POST';
            }
            if(strpos($config['uri'], "http://") !== false){
                $uri = $config['uri'];
            }else if (strpos($config['uri'], '.')) {
                $uri = $config['method'] == 'POST' ? route($config['uri']) : route($config['uri'], ['id' => $config['id']]);
            } else {
                $uri = $this->url->current() . '/' . $config['uri'];
            }
            if (!array_key_exists('class', $config)) {
                $config['class'] = 'btn-default';
            }
            $target = '';
            if (array_key_exists('target', $config)) {
                $target = 'target="'.$config['target'].'"';
            }

            if ($config['method'] != 'GET') {
                $dataInputs = '';
                $patch = $config['method'] != 'POST' ? '<input type="hidden" name="_method" value="'.$config['method'].'">' : '';
                foreach ($data as $key => $value) {
                    $dataInputs .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                }
                $result = '<form style="float: left;margin-right: 5px;" action="' . $uri . '" method="POST">
                 <input type="hidden" name="id" value="' . $config['id'] . '">
                 ' . $patch . '
                 ' . $dataInputs . '
                 ' . Form::token() . '
                 <button type="submit" class="btn btn-default ' . $config['class'] . '" onclick="return confirm(\'' . $config['alert'] . '\')" >' . $config['name'] . '</button>
                 </form>';
            } else {
                $result = '<a style="margin-right:5px" '.$target.' href="' . $uri . '" class="btn '. $config['class'] . '">' . $config['name'] . '</a>';
            }

            return $result;
        });
    }

    private function formLabel()
    {
        Form::macro('form_label', function ($label, $modal = false) {
            $col = 3;
            if (is_array($modal)) {
                $col = $modal['label_col'] ? $modal['label_col'] : 3;
                $modal = $modal['modal'];
            }
            $style = $modal ? 'style="padding: 7px 0px;"' : '';
            return '<label class="col-sm-' . $col . ' control-label" ' . $style . '>' . $label . '</label>';
        });
    }

    private function formSelect()
    {
        Form::macro('form_select', function ($name, $label, $data, $percent = 0.5, $modal = false) {
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label, $modal) . '
                        <div class="col-sm-9"><select class="form-control" name="' . $name . '">';
            foreach ($data as $item) {
                $value = is_array($item) ? $item['value'] : $item;
                $label = is_array($item) ? $item['label'] : $item;
                $selected = '';
                if ($this->model) {
                    if (isset($this->model[$name])) {
                        $selected = $this->model[$name] == $value ? 'selected="selected"' : '';
                    }
                } else if (is_array($item)) {
                    $selected = sizeof($item) == 3 ? 'selected=' . $item[2] : '';
                }
                $result .= '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
            }

            return $result . '</select></div></div>';
        });
    }

    private function formTagsInput()
    {
        Form::macro('form_tags_input', function ($name, $label, $default='', $placeholder='', $percent = 0.5) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            if (!$value) {
                $value = $default;
            }
            $result = '<div class="form-group col-lg-' . ($percent * 12) . '">
                        ' . Form::form_label($label).'<div class="col-sm-9">
                        <input type="text" id="'.$name.'" name="'.$name.'" class="input-tags" placeholder="'.$placeholder.'" value="'.$value.'"></div></div>';
            $js = "<script>init.push(function(){jQuery('#" . $name . "').selectize({
            plugins: ['remove_button'],
            create:true,
            onDelete: function(values) {
                return confirm(values.length > 1 ? '确认删除' + values.length + '个选项?' : '确认删除 \"' + values[0] + '\"?');
            },
            onItemAdd: function(value){
                if(typeof itemAddHandler != 'undefined'){
                    itemAddHandler('".$name."',value);
                }
            },
            onItemRemove: function(value){
                if(typeof itemRemoveHandler != 'undefined'){
                    itemRemoveHandler('".$name."',value);
                }
            },
            onChange: function(value){
                if(typeof itemChangeHandler != 'undefined'){
                    itemChangeHandler('".$name."',value);
                }
            }
            });})</script>";
            return $result . $js;
        });
    }

    private function formMultiSelect()
    {
        Form::macro('form_multi_select', function ($name, $label, $data, $placeholder='', $percent = 0.5) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $value = $value ? explode(',', $value) : '' ;
            $options = '';
            foreach ($data as $item) {
                if (array_key_exists('children', $item)) {
                    $options .= '<optgroup label="'.$item['label'].'">';
                    foreach ($item['children'] as $option) {
                        $selected = $option['value'] && $value && in_array($option['value'].'',$value) ? 'selected="selected"' : '';
                        $options .= '<option value="'.$option['value'].'" '.$selected.'>'.$option['label'].'</option>';
                    }
                    $options .= '</optgroup>';
                }else{
                    $selected = $item['value'] && $value && in_array($item['value'].'',$value) ? 'selected="selected"' : '';
                    $options .= '<option value="'.$item['value'].'" '.$selected.'>'.$item['label'].'</option>';
                }
            }
            $result = '<div class="form-group col-lg-' . ($percent * 12) . '">
                        ' . Form::form_label($label).'<div class="col-sm-9"><select id="'.$name.'" name="'.$name.'[]" multiple placeholder="'.$placeholder.'">
                        '.$options.'</select></div></div>';
            $js = "<script>init.push(function(){jQuery('#" . $name . "').selectize({
            plugins: ['remove_button'],
            onDelete: function(values) {
                return confirm(values.length > 1 ? '确认删除' + values.length + '个选项?' : '确认删除 \"' + values[0] + '\"?');
            },
            onItemAdd: function(value){
                if(typeof itemAddHandler != 'undefined'){
                    itemAddHandler('".$name."',value);
                }
            },
            onItemRemove: function(value){
                if(typeof itemRemoveHandler != 'undefined'){
                    itemRemoveHandler('".$name."',value);
                }
            },
            onChange: function(value){
                if(typeof itemChangeHandler != 'undefined'){
                    itemChangeHandler('".$name."',value);
                }
            }
            });})</script>";
            return $result . $js;
        });
    }

    private function formDate()
    {
        Form::macro('form_date', function ($name, $label, $placeholder = '', $percent = 0.5) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            if (!is_string($placeholder)) {
                $percent = $placeholder;
            }
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">' .
                '<input id="' . $name . 'date" name="' . $name . '" type="text" value="' . $value . '" class="form-control" placeholder="' . $placeholder . '">';
            $js = "<script>init.push(function(){jQuery('#" . $name . "date').datetimepicker({format:'Y-m-d'});})</script>";
            return $result . '</div></div>' . $js;
        });
    }

    private function formTime()
    {
        Form::macro('form_time', function ($name, $label, $placeholder = '', $percent = 0.5) {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            if (!is_string($placeholder)) {
                $percent = $placeholder;
            }
            $result = '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">' .
                '<input id="' . $name . 'date" name="' . $name . '" type="text" value="' . $value . '" class="form-control" placeholder="' . $placeholder . '">';
            $js = "<script>init.push(function(){jQuery('#" . $name . "date').datetimepicker({format:'Y-m-d H:i'});})</script>";
            return $result . '</div></div>' . $js;
        });
    }

    private function formDropDown()
    {
        Form::macro('form_dropdown', function ($label, $menus = []) {
            $dropdownMenus = '';
            foreach ($menus as $menu) {
                if (array_key_exists('href', $menu) && $menu['href']) {
                    $dropdownMenus .= '<li><a href="' . $menu['href'] . '">' . $menu['label'] . '</a></li>';
                } else {
                    $dropdownMenus .= '<li class="divider"></li>';
                }
            }
            $result = '<div style="margin-right: 5px" class="btn-group dropdown">
          <button type="button" class="btn btn-default waves-effect" data-toggle="dropdown" aria-expanded="true">
                ' . $label . ' <span class="caret"></span>
          </button>
          <ul class="dropdown-menu animated fadeIn">'
                . $dropdownMenus . '
          </ul>
        </div>';
            return $result;
        });
    }
}