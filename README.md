ForoneAdministrator 是一款基于Laravel5.1封装的后台管理系统，集成了[Entrust](https://github.com/Zizaco/entrust)权限管理，并针对业务的增删改查进行了视图和业务层的封装，有助于进行后台管理系统的快速研发。

- [安装初始化](#init)
- [forone配置](#config)
- [权限控制](#permission)
- [1分钟完成分类模块](#demo)
- [视图控件](#controllers)
    - [数据列表](#datagrid)

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

- Laravel 5.1+
- PHP 5.5.9+

由于不可抗力因素，最好在`compoer.json`里加入如下配置后再开始安装，设置国内的`composer`镜像同时也可设置直接从国内git服务器上下载。
由于使用的entrust还处于dev状态，所以需要将composer.json里的`minimum-stability` 设置为 `dev`

```
"repositories": [
  {"type": "git", "url": "http://git.forone.co/mani/ForoneAdministrator.git"},
  {"type": "composer", "url": "http://packagist.phpcomposer.com"},
  {"packagist": false}
],
"minimum-stability" : "dev"
```

使用composer进行安装
```
composer require forone/admin:~1.0.0
```

编辑 `config/app.php` 注册 `providers` 和 `aliases`

```php
'providers' => [
    Forone\Admin\Providers\ForoneServiceProvider::class
]
```

```php
'aliases' => [
    'Form'      => Illuminate\Html\FormFacade::class,
    'Html'      => Illuminate\Html\HtmlFacade::class,
    'Entrust'   => Zizaco\Entrust\EntrustFacade::class
]
```

发布资源，运行下面命令会自动生成默认的配置文件并复制静态文件和数据库文件

```
php artisan vendor:publish
```

系统初始化

```
php artisan forone:init
```

为`App\User`添加Entrust的Trait，以便使用一些封装的方法
```
use Authenticatable, CanResetPassword, EntrustUserTrait;
```

现在就可以使用forone.php配置文件里的管理员账号密码登陆了

<a id="user-content-config" href="#config"></a>
### forone配置

```
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
            'permission' => 'admin',                      //菜单显示所需权限
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
1. 在routes里进行权限控制，这种用法是直接使用middleware进行自动判定的，更多的别的路由过滤的用法请看`entrust`文档
```
Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth', 'admin.permission:admin']], function () {
```
2. 在Controller里对Controller的所有请求进行权限控制
```
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
    1. 修改类名为文件名
    2. 修改构造函数的uri和name为 `parent::__construct('categories', '分类管理');`
    3. 批量修改`Permission`为`Category`
    4. 修改`index`里的数据列表显示项
    5. `Request`类视情况调整
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
```
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
    1. 可以为空，就按默认情况显示
    2. 可以为数字，为显示的列宽
    3. 可以为函数，用以处理数据项并返回显示结果，包括根据不同权限返回不同结果等
4. 为函数，作为`buttons`项的时候，返回的按钮数组有以下几种情况：
    1. `查看`,`编辑`默认会跳转到查看和编辑页面
    2. `启用`,`禁用`默认会单独修改数据项的`enabled`字段
    3. 点击按钮后需要修改某个字段为某个值，比如审核通过或者驳回之类：
    `[['name'=>'测试','class'=>'btn-danger'],['tested'=>'true','other'=>'somevalue']]`
    第一个数组描述按钮的名称和样式，第二个数组描述需要更改的字段和值
    4. 点击按钮后需要弹出某个弹出框，`['配置','#modal']`，就会弹出来id为`modal`的弹出框
