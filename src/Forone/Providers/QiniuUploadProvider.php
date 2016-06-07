<?php
/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 9:16 PM
 * Email: mani@forone.co
 */

namespace Forone\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Form;

class QiniuUploadProvider extends ServiceProvider
{

    static $single_inited;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->singleFileUpload();
        $this->multiFilesUpload();
        $this->fileViewer();
    }

    private function singleFileUpload()
    {
        $handler = function ($name, $label, $percent = 0.5,$platform="qiniu") {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $url = $value ? config('forone.qiniu.host') . $value : '/vendor/forone/images/upload_add.png';
            $js = View::make('forone::upload.upload')->with(['name'=>$name])->render();
            if(!QiniuUploadProvider::$single_inited){
                $js = View::make('forone::upload.upload_js')->render() . $js;
                QiniuUploadProvider::$single_inited = true;
            }
            return $js.'<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="' . $name . '" type="hidden" name="' . $name . '" type="text" value="' . $value . '">
                            <img style="width:58px;height:58px;cursor:pointer;" id="' . $name . '_img" src="' . $url . '">
                        </div>
                    </div>';
        };
        Form::macro('single_file_upload', $handler);
    }

    private function multiFilesUpload()
    {
        Form::macro('multi_file_upload', function ($name, $label, $with_description=true, $percent=0.5, $platform="qiniu") {
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);
            $url = '/vendor/forone/images/upload_add.png';
            $uploaded_items = '';
            if ($value) {
                $items = explode('|', $value);
                foreach ($items as $item) {
                    $details = explode('~', $item);
                    $idvalue = rand().'';
                    $div = '<div id="'.$idvalue.'div" style="float:left;width:68px;margin-right: 20px">';
                    if(preg_match("/.pdf/", $details[0])){
                        $img = '<img onclick="removeMultiUploadItem(\'' . $idvalue . 'div\',\''.$name.'\')" style="width: 68px; height: 68px;cursor:pointer"
                        src="/vendor/forone/images/upload.png">';
                    }else{
                        $img = '<img onclick="removeMultiUploadItem(\'' . $idvalue . 'div\',\''.$name.'\')" style="width: 68px; height: 68px;cursor:pointer"
                        src="'.config('forone.qiniu.host').$details[0].'?imageView2/1/w/68/h/68">';
                    }

                    $uploaded_items .= $div . $img;
                    $v = '';
                    if (sizeof($details) == 2) {
                        $v = "value='$details[1]'";
                    }
                    $uploaded_items .= '<input '.$v.' onkeyup="fillMultiUploadInput(\''.$name.'\')" style="width: 68px;float: left" placeholder="图片描述"></div>';
                }
            }

            $js = View::make('forone::upload.upload')->with(['multi'=>true,'name'=>$name, 'with_description'=>$with_description])->render();
            if(!QiniuUploadProvider::$single_inited){
                $js = View::make('forone::upload.upload_js')->render() . $js;
                QiniuUploadProvider::$single_inited = true;
            }

            return $js.'<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="'.$name.'" type="hidden" name="' . $name . '" type="text" value="'.$value.'">
                            <img style="width:58px;height:58px;cursor:pointer;float:left;margin-right:20px;" id="'.$name.'_img" src="'.$url.'">
                            <label id="'.$name.'_label"></label>
                            <div id="'.$name.'_div">'.$uploaded_items.'</div>
                        </div>
                    </div>';
        });
    }

    private function fileViewer()
    {
        Form::macro('file_viewer', function ($name, $label='文件浏览', $percent = 0.5) {
            $value = $this->model ? ForoneFormServiceProvider::parseValue($this->model, $name) : $name;
            $result = '';
            if ($value) {
                $items = explode('|', $value);
                foreach ($items as $item) {
                    $details = explode('~', $item);
                    $idvalue = rand().'';
                    $div = '<div id="'.$idvalue.'div" style="float:left;width:68px;margin-right: 20px">';
                    $file = '<a href="'.config('forone.qiniu.host').$details[0].'" target="_blank">';
                    if(preg_match("/.pdf/", $details[0])){
                        $file .= '<img style="width: 68px; height: 68px;cursor:pointer" src="/vendor/forone/images/upload.png">';
                    }else{
                        $file .= '<img style="width: 68px; height: 68px;cursor:pointer" src="'.config('forone.qiniu.host').$details[0].'?imageView2/1/w/68/h/68">';
                    }
                    $file .= '</a>';

                    $result .= $div . $file;
                    $v = '';
                    if (sizeof($details) == 2) {
                        $v = "value='$details[1]'";
                    }
                    $result .= '<input '.$v.' style="width: 68px;float: left" placeholder="图片描述"></div>';
                }
            }

            return '<div class="form-group col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="'.$name.'" type="hidden" name="' . $name . '" type="text" value="'.$value.'">
                            <label id="'.$name.'_label"></label>
                            <div id="'.$name.'_div">'.$result.'</div>
                        </div>
                    </div>';
        });
    }
}