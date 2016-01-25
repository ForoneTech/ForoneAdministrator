<?php
/**
 * User : YuGang Yang
 * Date : 6/10/15
 * Time : 17:47
 * Email: smartydroid@gmail.com
 */

namespace Forone\Controllers\Permissions;


use Forone\Controllers\BaseController;
use Forone\Permission;
use Forone\Requests\CreateRoleRequest;
use Forone\Requests\UpdateRoleRequest;
use Forone\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * 角色
 * Class RolesController
 * @package App\Http\Controllers
 */
class RolesController extends BaseController {

    function __construct()
    {
        parent::__construct('roles', '角色');
    }

    public function index()
    {
        $results = [
            'columns' => [
                ['编号', 'id'],
                ['系统名称','name'],
                ['显示名称', 'display_name'],
                ['创建时间', 'created_at'],
                ['更新时间', 'updated_at'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [];
                    if ($data->name != config('defender.superuser_role', 'superuser')) {
                        $buttons = [
                            ['编辑'],
                        ];
                        array_push($buttons, ['分配权限', '#modal']);
                    }
                    return $buttons;
                }]
            ]
        ];

        $paginate = Role::orderBy('id', 'desc')->paginate();
        $results['items'] = $paginate;

        // 获取顶层权限
        $perms = Permission::all();

        foreach ($paginate as $role) {
            $role['permissions'] = $role->permissions();
        }

        return $this->view('forone::' . $this->uri.'.index', compact('results', 'perms'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view('forone::roles.create');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreateRoleRequest $request)
    {
        Role::create($request->except('_token'));
        return redirect()->route('admin.roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        if ($data) {
            return $this->view('forone::' . $this->uri."/edit", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $data = $request->except('id', '_token');
        Role::findOrFail($id)->update($data);

        return $this->toIndex();
    }

    /**
     * 分配权限
     */
    public function assignPermission(Request $request)
    {
        $role = Role::find($request->get('id'));
        $permissions = $request->except(['_token', 'id']);
        $role->detachPermissions($role->permissions());
        foreach($permissions as $name => $status){
            $permission = Permission::whereName($name)->first();
            if ($status == 'on') {
                $role->attachPermission($permission);
            }
        }
        return $this->toIndex('权限分配成功');
    }
}