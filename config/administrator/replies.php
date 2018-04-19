<?php
use App\Models\Reply;
return[
    // 页面标题
    'title' => '回复',

    // 模型单数,用作页面 '新建 $single'
    'single' => '回复',

    // 数据模型，用作数据的 CRUD
    'model' => Reply::class,

    // 设置当前页面的访问权限，通过返回布尔值来控制权限。
    // 返回 True 即通过权限验证，False 则无权访问并从 Menu 中隐藏
    'permission' => function(){
        return Auth::user()->can('manage_users');
    },


    // 字段负责渲染『数据表格』，由无数的『列』组成
    'columns' => [

        // 列的标示，这是一个最小化『列』信息配置的例子，读取的是模型里对应的属性的值，如 $model->id
        'id' =>[
            'title'     => 'ID',
        ],

        'content' => [
            'title'     => '内容',
            'sortable'  => false,
            'output'    => function($value, $model){
                return '<div style="max-width: 260px">'. $value .'</div>';
            }
        ],
        'user' => [
            'title'     => '作者',
            'sortable'  => false,
            'output'    => function($value, $model){
                $avatar = $model->user->avatar;
                $value = empty($avatar) ? 'N/A' :'<img src="'.$avatar.'" style="height:22px;">';
                return model_link($value, $model);
            },
        ],
        'topic' => [
            'title'     => '话题',
            'sortable'  => false,
            'output'    => function($value, $model){
                return '<div style="width: 260px;">'. model_admin_link(e($model->topic->title),$model->topic) .'</div>';
            },
        ],


        'operation' => [
            'title'     => '管理',
            'sortable'  => false,
        ],
    ],

    // '模型表单' 设置项
    'edit_fields' => [
        'user' => [
            'title'                 => '用户',
            'type'                  => 'relationship',
            'name_field'            => 'name',

            // 自动补全，对于大数据量的对应关系，推荐开启自动补全 可防止一次性加载对系统造成负担
            'autocomplete'          => true,

            // 自动补全的搜索字段
            'search_fields'         => ["CONCAT(id,' ',name)"],

            // 自动补全排序
            'options_sort_field'    => 'id',
        ],
        'topic' => [
            'title'                 => '话题',
            'type'                  => 'relationship',
            'name_field'            => 'title',
            'search_fields'         => ["CONCAT(id,' ',title)"],
            'options_sort_field'    => 'id',
        ],
        'content' => [
            'title'                 => '回复内容',
            'type'                  => 'textarea',
        ],

    ],

    // '数据过滤' 设置
    'filters' => [

        'user' => [
            'title'                 => '用户',
            'type'                  => 'relationship',
            'name_field'            => 'name',
            'autocomplete'          => true,
            'search_fields'         => array("CONCAT(id,' ',name)"),
            'options_sort_field'    => 'id',
        ],
        'topic' => [
            'title'                 => '话题',
            'type'                  => 'relationship',
            'name_fields'           => 'title',
            'autocomplete'          =>true,
            'search_fields'         => array("CONCAT(id,' ',title)"),
            'options_sort_field'    => 'id',
        ],
        'content' => [
            'title' => '回复内容',
        ],
    ],

    'rules' => [
        'content' => 'required',
    ],

    'messages' => [
        'title.required' => '请填写回复内容',
    ],


];