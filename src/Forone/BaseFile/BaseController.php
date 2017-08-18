<?php

namespace App\Http\Controllers\BaseText;

use Illuminate\Http\Request;

use App\Models\BaseText;
use App\Http\Requests;
use Forone\Controllers\BaseController;

class BaseTextController extends BaseController
{
    const URI = 'base-text';
    const NAME = '';//填写汉字名称

    function __construct()
    {
        parent::__construct();
        view()->share('page_name', self::NAME);
        view()->share('uri', self::URI);
    }

    //自行定义验证规则
    protected $rules = [
        //'type'    => 'required',
    ];
    //自行添加验证提示消息
    protected $message = [
        //'type.required'    => '种类必选',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = [
            'columns' => [
                ['编号', 'id'],
                [
                    '操作',
                    'buttons',
                    function ($data) {
                        $buttons = [
                            ['查看'],
                            ['编辑'],
                        ];
                        //需要额外按钮可以解开注释
//                        array_push($buttons, [
//                            [
//                                'name'   => $data->check == 1 ? '驳回审核' : '审核',
//                                'uri'    => 'admin.service-check.check',
//                                'method' => 'GET',
//                                'id'     => $data->id,
//                                'class'  => 'btn-dark',
//                            ]
//                        ]);
                        return $buttons;
                    },
                ],
            ],
        ];
        $all = $request->except(['page']);
        //自行添加规则
        $paginate = BaseText::orderBy('created_at', 'desc');
        foreach ($all as $key => $value) {
            if ($key == 'keywords') {
                $paginate->where('id', $value);
            } else {
                $paginate->where($key, '=', $value);
            }
        }
        $results['items'] = $paginate->paginate()->appends($all);

        return $this->view(self::URI . '.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view(self::URI . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->message);
        $data = BaseText::create($request->except('_token'));
        if($data){
            $this->redirectWithError('添加成功');
        }
        return redirect('admin/' . self::URI);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = BaseText::find($id);
        if ($data) {
            return view(self::URI . "/show", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = BaseText::find($id);
        if ($data) {
            return view(self::URI . "/edit", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();
        $return_url = '';
        switch ($data['_method']) {
            case 'PATCH':
                $this->validate($request, ['id' => 'required']);
                $return_url = 'admin/' . self::URI;
                $id = $data['id'];
                break;
            case 'PUT':
                $this->validate($request, $this->rules);
                $return_url = 'admin/' . self::URI . '/' . $id;
                break;
        }
        $input = array_except($data, ['_method', '_token', 'id']);
        BaseText::find($id)->update($input);

        return redirect()->to($return_url);
    }
}
