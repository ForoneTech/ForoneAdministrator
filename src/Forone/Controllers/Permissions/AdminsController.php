<?php
/**
 * User : YuGang Yang
 * Date : 6/16/15
 * Time : 10:19
 * Email: smartydroid@gmail.com
 */

namespace Forone\Controllers\Permissions;

use Forone\Controllers\BaseController;
use Forone\Requests\CreateAdminRequest;
use Forone\Requests\UpdateAdminRequest;
use Forone\Role;
use Forone\Admin;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Request;

class AdminsController extends BaseController {

    function __construct()
    {
        parent::__construct('admins', '管理员');
    }

    public function index()
    {
        $results = [
            'columns' => [
                ['名称', 'name'],
                ['邮箱', 'email'],
                ['创建时间', 'created_at'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [
                        ['编辑']
                    ];
                    if (!$data->hasRole(config('defender.superuser_role', 'superuser'))) {
                        array_push($buttons, ['分配角色', '#modal']);
                    }
                    return $buttons;
                }]
            ]
        ];
        $roles = Role::all();
        $paginate = Admin::orderBy('created_at', 'desc')->paginate();
        $results['items'] = $paginate;

        foreach ($paginate as $user) {
            $user['roles'] = $user->roles()->get();
        }

        return $this->view('forone::' . $this->uri.'.index', compact('results', 'roles'));
    }

    public function create()
    {
        return $this->view('forone::' . $this->uri.'.create');
    }

    /**
     * @param CreateAdminRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(CreateAdminRequest $request, Registrar $registrar)
    {

        $registrar->create($request->only(['name', 'password', 'email']));
        return redirect()->route('admin.admins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $data = Admin::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . $this->uri. "/show", compact('data'));
        }else{
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Admin::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . $this->uri. "/edit", compact('data'));
        }else{
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UpdateAdminRequest $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $count = Admin::whereName($name)->where('id', '!=', $id)->count();
        if ($count > 0) {
            return $this->redirectWithError('名称不能重复');
        }
        $count = Admin::whereEmail($email)->where('id', '!=', $id)->count();
        if ($count > 0) {
            return $this->redirectWithError('邮箱不能重复');
        }
        Admin::findOrFail($id)->update($request->only(['name', 'email']));
        return redirect()->route('admin.admins.index');
    }

    /**
     * 分配角色
     */
    public function assignRole(Request $request)
    {
        $user = Admin::find($request->get('id'));
        $roles = $request->except(['_token', 'id']);
        $user->detachRoles($user->roles()->get());
        foreach($roles as $name => $status){
            $role = Role::whereName($name)->first();
            if ($status == 'on') {
                $user->attachRole($role);
            }
        }
        return $this->toIndex('角色分配成功');
    }

}