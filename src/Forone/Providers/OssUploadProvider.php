<?php
/**
 * User: JuChao
 * Date: 8/22/17
 * Time: 10:28 PM
 * Email: juchao1989@gmail.com
 */

namespace Forone\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Form;

class OssUploadProvider extends ServiceProvider
{

    static $single_inited;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->fileUpload();
        $this->fileViewer();
    }

    private function fileUpload()
    {
        $handler = function ($name, $label, $percent = 0.5, $rename = true, $process = '', $more = false) {
            $process = $process ? $process : config('forone.oss.process');
            $value = ForoneFormServiceProvider::parseValue($this->model, $name);

            if ($more) {
                $imgUrl = explode('|', $value);
            } else {
                $imgUrl = [$value];
            }

            $label_html = Form::form_label($label);
            $html = View::make('forone::upload.upload_oss')->with(['name' => $name, 'value' => $value, 'imgUrl' => $imgUrl, 'rename' => $rename, 'label_html' => $label_html, 'percent' => $percent, 'process' => $process, 'more' => $more])->render();
            if (!OssUploadProvider::$single_inited) {
                $html = View::make('forone::upload.upload_oss_js')->render() . $html;
                OssUploadProvider::$single_inited = true;
            }
            $rename_value = $rename ? 'random_name' : 'local_name';
            $rename_html = "<span class='hidden' id='rename' data-value='$rename_value'></span>";
            return $rename_html . $html;
        };
        Form::macro('oss_file_upload', $handler);
    }

    private function fileViewer()
    {
        Form::macro('oss_file_viewer', function ($name, $label = '文件浏览', $percent = 0.5, $process = '') {
            $process = $process ? $process : config('forone.oss.process');
            $value = $this->model ? ForoneFormServiceProvider::parseValue($this->model, $name) : $name;
            $result = '';
            if ($value) {
                $idvalue = rand() . '';
                $div = '<div id="' . $idvalue . 'div">';
                $urls = explode('|', $value);
                $file = '';
                foreach ($urls as $url){
                    $file .= '<a href="' . config('forone.oss.host') . $url . '" target="_blank" style="margin-right:8px">';
                    $file .= '<img src="' . config('forone.oss.host') . $url . $process . '" onerror="javascript:this.src=\'/vendor/forone/images/upload.png\'" data-src="' . config('forone.oss.host') . $url . $process . '">';
                    $file .= '</a>';
                }
                $result .= $div . $file;
            }

            return '<div class="form-group file_viewer col-sm-' . ($percent * 12) . '">
                        ' . Form::form_label($label) . '
                        <div class="col-sm-9">
                            <input id="' . $name . '" type="hidden" name="' . $name . '" type="text" value="' . $value . '">
                            <label id="' . $name . '_label"></label>
                            <div id="' . $name . '_div">' . $result . '</div>
                        </div>
                    </div>';
        });
    }

}