<?php
/**
 * User : YuGang Yang
 * Date : 6/10/15
 * Time : 18:49
 * Email: smartydroid@gmail.com
 */

namespace Forone\Controllers\Permissions;

use Forone\Controllers\BaseController;
use Forone\Permission;
use Forone\Role;
use Forone\Requests\CreatePermissionRequest;
use Forone\Requests\UpdatePermissionRequest;
//use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class CategoriesController extends BaseController {

    function __construct()
    {
        parent::__construct('categories', '新用户管理');
        $this->middleware('admin.permission:admin|2121');
    }

    public function index(Request $request)
    {
        $data = Permission::distinct()->get(['display_name'])->toArray();
        $level = [];
        foreach ($data as $key => $value) {
            switch ($value['display_name']) {
                case '3':
                    $display_label = '超级管理员';
                    break;
                case '2':
                    $display_label = '游客';
                    break;
                case '1':
                $display_label = '版主';
                    break;
                default:
                    $display_label = '未知';
                    break;
            }
            $arr = ['label'=>$display_label,'value'=>$value['display_name']];
            array_unshift($level, $arr);
        };
        array_unshift($level, ['label' => '所有', 'value'=>'']);

        $results = [
            'columns' => [
                ['新编号', 'id'],
                ['新系统名称', 'name'],
                ['用户等级', 'display_name',function ($data){
                    switch ($data) {
                        case '3':
                            $data = '超级管理员';
                            break;
                        case '2':
                            $data = '游客';
                            break;
                        case '1':
                        $data = '版主';
                            break;
                        default:
                            $data = '未知';
                            break;
                    }
                    return $data;
                }],
                ['新创建时间', 'created_at'],
                ['新更新时间', 'updated_at'],
                ['性别','sex'],
                ['部门','department'],
                ['操作', 'buttons', function ($data) {
                    $buttons = [
                        ['编辑']
                    ];
                    if(\Auth::user()->can('admin')){
                        $buttons[] = ['删除'];
                    }
                    return $buttons;
                }]
            ]
        ];

        $results['filters'] = [
            'level' => $level
        ];

        $all = $request->except(['page']);
        $paginate = Permission::orderBy('id','desc')->where('status','1');
        if(!sizeof($all)){
            $results['items'] = $paginate->paginate(5);
        }else{
            foreach ($all as $key => $value) {
                if ($key == 'keywords' && !empty($value)) {
                    $paginate->where('name', 'LIKE', '%'.urldecode($value).'%');
                }
                if ($key == 'level' && !empty($value)){
                    $paginate->where('display_name',$value);
                }
            }
            $paginate = $paginate->paginate(5);
            $results['items'] = $paginate->appends($all);
        }

        return $this->view('forone::' . $this->uri.'.index', compact('results','list_header'));
    }

    /**
     *
     * @return View
     */
    public function create()
    {
        return $this->view('forone::'.$this->uri.'.create');
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return View
     */
    public function store(CreatePermissionRequest $request)
    {
        Permission::create($request->except('id', '_token'));
        return $this->toIndex('保存成功');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Permission::find($id);
        if ($data) {
            return view('forone::' . $this->uri."/edit", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * update the specified resource status 0 in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = Permission::find($id);
        if ($data) {
            Permission::findOrFail($id)->update(['status'=>0]);
            return $this->toIndex('删除成功');
        } else {
            return $this->redirectWithError('数据删除失败');
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

        $data = $request->except('_token');
        Permission::findOrFail($id)->update($data);

        return $this->toIndex('编辑成功');
    }

}