实在招不到人，在此打个广告，招Laravel工程师，请联系 mani@nxdai.com 或 1283233833

ForoneAdministrator 是一款基于Laravel5.2封装的后台管理系统，集成了[Entrust](https://github.com/Zizaco/entrust)权限管理，并针对业务的增删改查进行了视图和业务层的封装，有助于进行后台管理系统的快速研发。

- [安装初始化](#init)
- [forone配置](#config)
- [权限控制](#permission)
- [1分钟完成分类模块](#demo)
- [视图控件](#controllers)
    - [数据列表头 - Html::list_header](#list_header)
    - [数据列表 - Html::datagrid](#datagrid)
    - [下拉列表选择 - Form::form_select](#form_select)
    - [单选Radio - Form::form_radio](#form_radio)
    - [时间控件 - Form::form_time](#form_time)
    - [日期控件 - Form::form_date](#form_date)
    - [单行文本输入框 - From::form_text](#form_text)
    - [多行文本输入框 - From::form_area](#form_area)
    - [单文件上传 - Form::single_file_upload](#single_upload)
    - [多文件上传 - Form::multi_file_upload](#multi_upload)
    - [文件浏览 - Form::file_viewer](#file_viewer)
    - [富文本编辑器 - Form::ueditor](#ueditor)
- [提高研发效率的几个自定义命令](#commands)

### 效果图

#### PC端

![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/login.png)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/roles-index.jpg)

#### 移动端

![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-login.png)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-roles-index.jpg)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-aside.jpg)

<a href="#init"></a>

<a id="user-content-init" href="#init"></a>
### 安装初始化

系统要求:

- Laravel 5.2+
- PHP 5.5.9+

由于不可抗力因素，最好在`compoer.json`里加入如下配置后再开始安装，设置国内的`composer`镜像同时也可设置直接从国内git服务器上下载。
由于使用的entrust还处于dev状态，所以需要将composer.json里的`minimum-stability` 设置为 `dev`

```json
"repositories": [
  {"type": "git", "url": "http://git.nxdai.com/mani/ForoneAdministrator.git"},
  {"type": "composer", "url": "http://packagist.phpcomposer.com"},
  {"packagist": false}
],
"minimum-stability" : "dev"
```

> 由于启用了 `"minimum-stability" : "dev"`，Laravel的`dev`版本会导致paginate出错，请在`composer.json`里将laravel版本设置为确定的版本号，例如`5.1.4`

使用composer进行安装
5.2.x版本
```
composer require forone/administrator:5.2.x-dev

```

编辑 `config/app.php` 注册 `providers` 和 `aliases`

```php
'providers' => [
    Collective\Html\HtmlServiceProvider::class,
    Forone\Providers\ForoneServiceProvider::class,
]
```

```php
'aliases' => [
    'Form' => Collective\Html\FormFacade::class,
    'Html' => Collective\Html\HtmlFacade::class,
    'Entrust'   => Zizaco\Entrust\EntrustFacade::class,
]
```

发布资源，运行下面命令会自动生成默认的配置文件并复制静态文件和数据库文件

```
php artisan vendor:publish
```

修改`.env`通过环境变量设置初始管理员账号密码

`ADMIN_EMAIL`默认为`admin@admin.com`
`ADMIN_PASSWORD`默认为`admin`

系统初始化

```
php artisan forone:init
```
用户表默认使用admins表，用户模型使用Forone类的Admin模型，在laravel自带的auth.app内配置model项,

```
'model' => 'Forone\Admin'

```
5.2.0之后laravel版本，在用户模型内请务必删除继承的AuthorizableContract类，否则会报错

为`App\User`添加Entrust的Trait，以便使用一些封装的方法
```
use Authenticatable, CanResetPassword, EntrustUserTrait;
```
使用`EntrustUserTrait`的时候注意需要引入该类：
```
use Zizaco\Entrust\Traits\EntrustUserTrait;
```

现在就可以使用`.env`里的管理员账号密码登陆了

<a id="user-content-config" href="#config"></a>
### forone配置

```php
return [
    'site_config'                 => [
        'site_name'   => '站点名称',
        'title'       => '站点标题',
        'description' => '站点描述',
        'logo'        => '站点logo地址'
    ],
    'RedirectAfterLoginPath'      => 'admin/roles', // 登录后跳转页面
    'RedirectIfAuthenticatedPath' => 'admin/roles', // 如果授权后直接跳转到指定页面

    'menus'                       => [
        '系统设置' => [
            'icon'       => 'mdi-toggle-radio-button-on', //菜单icon
            'permission' => 'admin',                      //菜单显示所需权限,多权限以数组的方式添加 ['admin','test']
            'children'   => [                             //菜单的子菜单数组
                '角色管理'  => [
                    'uri' => 'roles',                     //菜单对应的uri
                ],
                '权限管理'  => [
                    'uri' => 'permissions',
                ],
                '管理员管理' => [
                    'uri' => 'admins',
                ]
            ],
        ],
    ],

    'qiniu'                       => [
        'host'       => 'http://share.u.qiniudn.com',               //your qiniu host url
        'access_key' => '-S31BNj77Ilqwk5IN85PIBoGg8qlbkqwULiraG0x', //for test
        'secret_key' => 'QoVdaBFZITDp9hD7ytvUKOMAgohKaB8oa11FJdxN', //for test
        'bucket'     => 'share'
    ]
];
```

<a id="user-content-permission" href="#permission"></a>
### 权限控制

权限控制主要分两部分，一部分是控制菜单是否显示，通过菜单的`permission`属性即可完成，另一部分是控制路由，通过`admin.permission`中间件传参来进行控制即可，主要有两种使用场景：

- 在routes里进行权限控制，这种用法是直接使用middleware进行自动判定的，更多的别的路由过滤的用法请看`entrust`文档
```php
Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth', 'admin.permission:admin']], function () {
```

- 在Controller里对Controller的所有请求进行权限控制
```php
function __construct()
{
    parent::__construct('admins', '管理员');
    $this->middleware('admin.permission:admin|test'); //需要admin及test的权限才可以访问该Controller
}
```

<a id="user-content-demo" href="#demo"></a>

### 1分钟完成分类管理模块

> 以最简单的模块为实例，假设数据库已建好，需要创建一个分类管理模块

1. 复制`PermissionsController`并粘贴更名为`CategoriesController`；复制`views/permissions`文件夹并粘贴更名为`views/categories`
2. 编辑`CategoriesController`，修改以下几处：
    - 修改类名为文件名
    - 修改构造函数的uri和name为 `parent::__construct('categories', '分类管理');`
    - 批量修改`Permission`为`Category`
    - 修改`index`里的数据列表显示项
    - `Request`类视情况调整
3. 编辑`views/categories/form.blade.php`，修改输入项及描述名称
4. 编辑`routes.php`添加路由 `Route::resource('categories', 'CategoriesController');`
5. 编辑`forone.php`添加菜单 `"分类管理"=>["uri"=>"categories"]`

> 复杂的模块可能在index或者其它部分有更复杂的改动，总体上来说`Controller`的结构和基本功能代码及`views`的都可以复用

<a id="user-content-controllers" href="#controllers"></a>
### 视图控件

详细描述封装好的便于使用的数据控件

<a id="user-content-datagrid" href="#datagrid"></a>
#### 数据列表

用法：`{!! Html::datagrid($results) !!}`

数据：数据源为数组

```php
'columns' => [
    ['流水号', 'id', function ($id) {
        return '';
    }],
    ['金额', 'amount'],
    ['操作', 'buttons', 100, function () {
        $buttons = [
            ['查看']
        ];
        return $buttons;
    }],
]
```

数据项参数：

1. 列名称
2. 数据项的属性，其中`buttons`是固定的按钮列使用属性
3. 有以下几种情况：
    - 可以为空，就按默认情况显示
    - 可以为数字，为显示的列宽
    - 可以为函数，用以处理数据项并返回显示结果，包括根据不同权限返回不同结果等，作为函数的时候也可以直接返回`html`代码以显示任意内容
4. 为函数，作为`buttons`项的时候，返回的按钮数组有以下几种情况：
    - `查看`,`编辑`默认会跳转到查看和编辑页面
    - `启用`,`禁用`默认会单独修改数据项的`enabled`字段
    - 点击按钮后需要修改某个字段为某个值，比如审核通过或者驳回之类：
    `[['name'=>'审核','class'=>'btn-danger'],['tested'=>'true','other'=>'somevalue']]`
    第一个数组描述按钮的名称和样式，第二个数组描述需要更改的字段和值
    - 点击按钮后需要调用某个接口并传参数：`[['name' => '审核', 'uri' => 'lastInstance.get', 'method' => 'GET','id'=>$project->id],[]]`,uri使用路由名称
    - 点击按钮后需要弹出某个弹出框，`['配置','#modal']`，就会弹出来id为`modal`的弹出框

<a id="user-content-list_header" href="#list_header"></a>
#### 数据列表头 - 新增、检索、过滤筛选等

用法：
```php
{!! Html::list_header([
    'new'=>true,
    'search'=>true,
    'title'=>'数据列表标题',
    'filters'=>$results['filters']
    ]) !!}
```

数据项参数：

1. `new` 表示是否显示`新增`按钮，点击后跳转到创建页面
2. `search` 表示是否显示`检索`输入框，输入检索内容后，默认以`keywords`为参数传递到后端接口，相当于`?keywords=xxx`
3. `title` 标题
4. `filters` 数据源为数组，如下：

```php
$results['filters'] = [
    'status' => [
        ['label' => '所有状态', 'value'=>''],
        ['label' => '状态1', 'value' => 0]
    ],
    'other' => [
        ['label' => '其它过滤', 'value'=>''],
        ['label' => '过滤1', 'value' => 0]
    ]
];
```

`status`和`other`是该数据项的字段名，它们对应的数组是显示出来供选择的选项，选择后会自动提交请求，相当于`?status=''&other=''`。
相应的在Controller的`index`方法里，添加很简单的代码即可实现分页的同时自动加上相应的参数，并根据参数过滤相应的内容，如下：

```php
$all = $request->except(['page']);
$paginate = Model::orderBy('id', 'desc');
//如果没有筛选条件直接返回分页数据
if (!sizeof($all)) {
    $paginate = $paginate->paginate();
}else{
    //遍历筛选条件
    foreach ($all as $key => $value) {
        if ($key == 'keywords') { //检索的关键词，定义检索关键词的检索语句
            $paginate->where('name', 'LIKE', '%'.$value.'%');
        }else{
            //可以根据不同的检索条件的不同值进行不同的语句组合，比如状态为7的数据加多筛选条件
            if ($key == 'status' && $value == 7) {
                $paginate->where($key, '=', 1)
                        ->where('time', '<', Carbon::now())
                        ->whereRaw(' `a` > `b` ')
                        ->orWhere($key, '=', $value);
            } else { //正常来说就只加where即可
                $paginate->where($key, '=', $value);
            }
        }
    }
    $paginate = $paginate->paginate();
}
$results = [
    'items' => $paginate->appends($all),
];
```

> 针对简单内容的筛选，基本上检索代码都可以直接Copy使用，仅需修改`Model`即可

<a id="user-content-form_select" href="#form_select"></a>
#### 下拉列表选择

用法：
```php
Form::form_select('type_id', '标的类型', [
    ['label'=>'名称', 'value'=>'']
],0.5,false)
```

参数：

1. 字段名
2. 数据项的Label名称
3. 下拉列表数据，label表示显示出来的内容，value表示存储的时候使用的内容
4. 长度，默认是bootstratp整行的一半，等同于`col-md-6`
5. 是否用于`modal`，因为modal样式有些差异，所以加了这个参数

<a id="user-content-form_radio" href="#form_radio"></a>
#### 单选radio

用法：
```php
{!! Form::form_radio('risk_level', '风险等级', [
[0, 'A', true],
[1, 'B'],
[2, 'C'],
[3, 'D'],
[4, 'E'],
[5, 'F']
], 0.5) !!}
```

参数：

1. 字段名
2. 数据项的Label名称
3. 数据内容，包括
    - 存储时用的数据
    - 显示出来的名称
    - 是否默认选中
4. 长度，默认是bootstratp整行的一半，等同于`col-md-6`，radio因为经常比较多，默认是`1`

<a id="user-content-form_time" href="#form_time"></a>
#### 时间控件

用法：
```php
{!! Form::form_time('time','开始时间','如 2015-06-06 08:00:00') !!}
```

参数：

1. 字段名
2. label名称
3. 提示文字

<a id="user-content-form_date" href="#form_date"></a>
#### 日期控件

用法：
```php
{!! Form::form_date('date','开始日期','如 2015-06-06') !!}
```

参数：

1. 字段名
2. label名称
3. 提示文字

<a id="user-content-form_text" href="#form_text"></a>
#### 单行文本输入

用法：
```php
{!! Form::form_text('column','字段名称','提示文字') !!}
```

<a id="user-content-form_area" href="#form_area"></a>
#### 多行文本输入

用法：
```php
{!! Form::form_area('column','字段名称','提示文字') !!}
```

<a id="user-content-single_upload" href="#single_upload"></a>
#### 单文件上传

用法：
```php
{!! Form::single_file_upload('field_name', 'label') !!}
```

参数：

1. 字段名
2. 项名称
3. 项宽度，默认`0.5`
4. 上传平台，目前默认且仅支持`qiniu`

<a id="user-content-multi_upload" href="#multi_upload"></a>
#### 多文件上传

用法：
```php
{!! Form::multi_file_upload('field_name', 'label') !!}
```

参数：

1. 字段名
2. 项名称
3. 是否显示图片描述输入框
4. 项宽度，默认`0.5`
5. 上传平台，目前默认且仅支持`qiniu`

<a id="user-content-file_viewer" href="#file_viewer"></a>
#### 文件浏览器

用法：
```php
{!! Form::file_viewer('field_name', 'label') !!}
```

参数：

1. 字段名
2. 项名称
3. 项宽度，默认`0.5`


<a id="user-content-ueditor" href="#ueditor"></a>
#### 富文本编辑器

用法：
```php
{!! Form::ueditor('name', 'label') !!}
```

参数：

1. 字段名
2. 项名称
3. 项宽度，默认`0.5`



<a id="user-content-commands" href="#commands"></a>
#### 提高研发效率的几个自定义命令

- `php artisan forone:init` 系统初始化命令，只可运行一次。
- `php artisan db:backup` 通过`iseed`库自动备份当前数据库的数据到Seeder文件里，解决研发时测试数据同步或临时数据结构变更测试数据面临清空等问题。并可根据migrations的文件顺序进行合理的排序，避免由于依赖关系引起的后续数据填充问题。
- `php artisan db:clear` 清空数据库，心情不爽的时候用一下，感觉棒棒哒。
- `php artisan db:upgrade` 升级数据库，可能加了新的字段等，会自动填充Seeder文件里的数据，升级之前最好先备份下数据。
- `php artisan forone:copy` 可复制一些文件到实际项目里，比如复制routes文件以便自定义route
