ForoneAdministrator 是一款基于Laravel5.1封装的后台管理系统，集成了[Entrust](https://github.com/Zizaco/entrust)权限管理，并针对业务的增删改查进行了视图和业务层的封装，有助于进行后台管理系统的快速研发。

### 效果图

#### PC端

![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/login.png)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/roles-index.jpg)

#### 移动端

![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-login.png)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-roles-index.jpg)
![](https://raw.githubusercontent.com/ForoneTech/screenshots/master/laravel-admin/mobile-aside.jpg)

### 初始化

系统要求:

- Laravel 5.1+
- PHP 5.5.9+

由于不可抗力因素，最好在`compoer.json`里加入如下配置后再开始安装，设置国内的`composer`镜像同时也可设置直接从国内git服务器上下载

```
"repositories": [
  {"type": "git", "url": "http://git.forone.co/mani/ForoneAdministrator.git"},
  {"type": "composer", "url": "http://packagist.phpcomposer.com"},
  {"packagist": false}
]
```

使用composer进行安装
```
composer require forone/admin:~1.0.0
```

> 由于使用的entrust还处于dev状态，所以需要将composer.json里的`minimum-stability` 设置为 `dev`。

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