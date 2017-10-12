<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:26
 * Email: smartydroid@gmail.com
 */

return [
    'disable_routes' => false, //禁用自带routes，默认启用
    'auth' => [
        'administrator_table'      => 'admins',
        'administrator_auth_controller' => 'Forone\Controllers\Auth\AuthController'
    ],
    'site_config'                 => [
        'site_name'   => 'your site name',
        'title'       => 'your site title',
        'description' => 'you site description',
        'logo'        => 'vendor/forone/images/logo.png'
    ],
    'RedirectAfterLoginPath'      => 'admin/roles', // 登录后跳转页面
    'RedirectIfAuthenticatedPath' => 'admin/roles', // 如果授权后直接跳转到指定页面

    'menus'                       => [
        '权限设置' => [
            'icon'       => 'mdi-toggle-radio-button-on',
            'permission' => 'admin',
            'children'   => [
                '角色管理'  => [
                    'uri' => 'roles',
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

        'host'       => env('QINIU_HOST', 'http://7xlntj.com2.z0.glb.qiniucdn.com/'), //your qiniu host url
        'access_key' => env('QINIU_AK', '7uuXy55ekyLfIw9gwI2Jr4Oin_9qHIQQfXi4ijL1'), //for test
        'secret_key' => env('QINIU_SK', 'FX8P2NE_iE2TR0pwMkK1f3ZErGqGlsmjffCOIZUq'), //for test
        'bucket'     => env('QINIU_BT', 'poly')
    ],

    'oss'                       => [
        'host'       => env('OSS_HOST', 'http://you-Bucket.oss-cn-beijing.aliyuncs.com'),//外网访问地址;如果服务端用阿里云,可设置成内网地址
        'access_key' => env('OSS_ACCESS_KEY', 'Access Key ID'),//id
        'secret_key' => env('OSS_SECRET_KEY', '	Access Key Secret'),//key
        'base_path'     => env('OSS_BASE_PATH', ''),//bucket下的文件根目录,前后加/,例:/attach
        'max_file_size'=>env('OSS_MAX_SIZE','20mb'),//文件最大限制
        'prevent_duplicates'=>env('OSS_DUPLICATES',true),//不允许选取重复文件
        'mime_types' => env('OSS_MIME _TYPES','jpeg,jpg,gif,png,bmp,docx'),//上传的类型
        //图片默认服务规则,也可单独对每一个图片进行设置(在fileUpload第五个参数中设置);图片服务api:https://help.aliyun.com/document_detail/44686.html
        'process' => env('OSS_PROCESS','?x-oss-process=image/resize,m_lfit,h_80,w_80'),
    ],
    'column' => ['limit' => 0]//列表数量限制,0为不限制,操作列不算在内
];