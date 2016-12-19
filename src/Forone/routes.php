<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:30
 * Email: smartydroid@gmail.com
 */

Route::get('/', function () {
    return redirect('/admin/auth/login');
});
Route::controllers([
    'admin/auth' => config('forone.auth.administrator_auth_controller', 'Forone\Controllers\Auth\AuthController'),
]);

//admin 
Route::group(['prefix' => 'admin', 'middleware' => ['admin.auth', 'admin.permission:admin|2121']], function () {

    Route::group(['namespace' => '\Forone\Controllers\Permissions'], function () {
        Route::get('/categories/del/{id}','CategoriesController@destroy')->where('id','[0-9]+');
        Route::resource('roles', 'RolesController');
        Route::resource('permissions', 'PermissionsController');
        Route::resource('admins', 'AdminsController');
        Route::resource('navs', 'NavsController');
        Route::resource('categories', 'CategoriesController');
        Route::post('roles/assign-permission', ['as' => 'admin.roles.assign-permission', 'uses' => 'RolesController@assignPermission']);
        Route::post('admins/assign-role', ['as' => 'admin.roles.assign-role', 'uses' => 'AdminsController@assignRole']);
    });

});

//upload
Route::get('/admin/qiniu/upload-token', ['as'=>'admin.qiniu.upload-token', 'uses'=>'Forone\Controllers\Upload\QiniuController@token']);