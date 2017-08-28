<?php

namespace Forone\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick:create-crud {table_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $base_path = base_path();
        $app_path = app_path();
        $tableName = $this->argument('table_name');
        $uri = Str::snake(camel_case(str_singular($tableName)),'-');

        $fileName = studly_case(str_singular($tableName));
        $columns = \DB::select(DB::raw("select COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT from information_schema.columns where table_schema = '". env('DB_DATABASE') . "'  and table_name = '".$tableName."'"));
        if (!$columns) {
            throw new \Exception('请先创建 数据表--> ' . $tableName);
        }
        $column_str = '';
        $form_str = '';
        $num = 0;
        collect($columns)->each(function($item) use (&$column_str,&$form_str,&$num) {
            if($item->COLUMN_NAME == 'created_at') {
                $num++;
            }
            if($item->COLUMN_NAME == 'updated_at') {
                $num++;
            }
            if(!$item->COLUMN_COMMENT) {
                switch ($item->COLUMN_NAME) {
                    case 'id':
                        $item->COLUMN_COMMENT = '编号';
                        break;
                    case 'created_at':
                        $item->COLUMN_COMMENT = '创建时间';
                        break;
                    case 'updated_at':
                        $item->COLUMN_COMMENT = '更新时间';
                        break;
                }

            }
            //这里格式设置好了, 勿格式化
            if($item->COLUMN_NAME == 'enabled') {

            } else {
                $column_str .= "['{$item->COLUMN_COMMENT}', '$item->COLUMN_NAME' ], \n                ";
            }
            if ($item->COLUMN_NAME == 'id' || $item->COLUMN_NAME == 'created_at' || $item->COLUMN_NAME == 'updated_at' || $item->COLUMN_NAME == 'enabled' ) {
            } else {
                if (strpos($item->COLUMN_TYPE,'time') !== false) {
                    $form_str .= "{!! Form::form_time('".$item->COLUMN_NAME."', '".$item->COLUMN_COMMENT."','".$item->COLUMN_COMMENT."') !!} \n";
                } elseif (strpos($item->COLUMN_TYPE, 'text') !== false) {
                    $form_str .= "{!! Form::ueditor('".$item->COLUMN_NAME."', '".$item->COLUMN_COMMENT."') !!} \n";
                }else {
                    $form_str .= "{!! Form::form_text('".$item->COLUMN_NAME."','".$item->COLUMN_COMMENT."','".$item->COLUMN_COMMENT."') !!} \n";
                }
            }
        });

        $source_direcotry = $base_path . '/vendor/forone/administrator/src/Forone/BaseFile';

        //controller 替换
        $distController = $base_path . '/app/Http/Controllers/' . $fileName . '/' ;
        $controllerFile = file_get_contents($source_direcotry . '/BaseController.php');
        $controllerFile = str_replace("['编号', 'id'],",$column_str,$controllerFile);
        $controllerFile = str_replace("BaseText",$fileName,$controllerFile);
        $controllerFile = str_replace("base-text",$uri,$controllerFile);

        if ( !@mkdir($distController) && !is_dir($distController)) {
            throw new \Exception('无法创建文件夹');
            // dir doesn't exist, make it
        }
        file_put_contents($distController . $fileName . "Controller.php", $controllerFile);

        //Model 替换
        $modelFile = file_get_contents($source_direcotry . '/BaseModel.php');
        $modelFile = str_replace('BaseModel', $fileName, $modelFile);
        $modelFile = str_replace('BaseTable', $tableName, $modelFile);
        if ($num != 2) {
            $modelFile = str_replace('public $timestamps = true;', 'public $timestamps = false;', $modelFile);
        }
        $distModel= $base_path . '/app/Models/';

        if ( !@mkdir($distModel) && !is_dir($distModel)) {
            throw new \Exception('无法创建文件夹');
            // dir doesn't exist, make it
        }

        file_put_contents($distModel . $fileName . ".php", $modelFile);
        $distView = resource_path() . '/views/' . $uri ;
        if ( !@mkdir($distView) && !is_dir($distView)) {
            throw new \Exception('无法创建文件夹');
            // dir doesn't exist, make it
        }
        $files = File::allFiles($source_direcotry . '/BaseView');
        /* Returns false if src doesn't exist */
        foreach ($files as $file) {
            copy($file, $distView.  '/' .  $file->getFileName());
        }

        $viewFile = file_get_contents($distView  . '/form.blade.php');
        $viewFile = str_replace("{!! Form::form_text('number','范例2','范例2') !!}",$form_str,$viewFile);
        file_put_contents($distView  . '/form.blade.php',$viewFile);

        //route替换
        $route_file_path = $app_path . '/http/routes.php';
        $routeFile = file_get_contents($route_file_path);

        if(strpos($routeFile,"Route::resource('{$uri}'") === false) {
            $routeFile .= "\nRoute::group(['prefix' => 'admin', 'middleware' => ['web','auth', 'permission:admin'],'namespace'=>'{$fileName}'], function () {
    Route::resource('".$uri."', '".$fileName."Controller');
});";
            file_put_contents($route_file_path,$routeFile);
        }

        //forone.php 替换
        $forone_file_path = $base_path . '/config/forone.php';
        $forone_file = file_get_contents($forone_file_path);
        $pattern = '/\s*\'menus\'\s*.*\[/i';
        $replacement = "\n    'menus'                       => [
        '{$fileName}' => [
            'icon' => 'mdi-action-settings-input-svideo',
            'uri' => '{$uri}',
        ],
        ";
        if (strpos($forone_file,"'{$fileName}' => [") === false) {
            $forone_file = preg_replace($pattern, $replacement, $forone_file);
            file_put_contents($forone_file_path,$forone_file);
        }


        $this->info('Finished');

    }
}
