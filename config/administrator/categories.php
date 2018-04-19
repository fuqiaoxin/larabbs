<?php
use App\Models\Category;
return[
    // 页面标题
    'title' => '分类',

    // 模型单数,用作页面 '新建 $single'
    'single' => '分类',

    // 数据模型，用作数据的 CRUD
    'model' => Category::class,

    // 设置当前页面的访问权限，通过返回布尔值来控制权限。
    // 返回 True 即通过权限验证，False 则无权访问并从 Menu 中隐藏
    'permission' => function(){
        return Auth::user()->can('manage_users');
    },

    // 对 CRUD 动作的单独权限控制 ,其他动作不指定默认通过
    'action_permissions' => [
        // 删除权限控制
        'delete' => function(){
            // 只有站长才能删除话题分类
            return Auth::user()->hasRole('Founder');
        },
    ],

    // 字段负责渲染『数据表格』，由无数的『列』组成
    'columns' => [

        // 列的标示，这是一个最小化『列』信息配置的例子，读取的是模型里对应的属性的值，如 $model->id
        'id' =>[
            'title'             => 'ID',
        ],

        'name' => [
            'title'             => '名称',
            'sortable'          => false,
        ],
        'description' => [
            'title'             => '描述',
            'sortable'          => false,
        ],
        'operation' => [
            'title'             => '管理',
            'sortable'          => false,
        ],
    ],

    // '模型表单' 设置项
    'edit_fields' => [
        'name' => [
            'title'             => '名称',
        ],
        'description' => [
            'title'             => '描述',
            'type'              => 'textarea',
        ],
    ],

    // '数据过滤' 设置
    'filters' => [
        'id' => [
            // 过滤表单条目显示名称
            'title'             => '分类 ID',
        ],
        'name' => [
            'title'             => '名称',
        ],
        'description' => [
            'title'             => '描述',
        ],
    ],

    'rules' => [
        'name'                  => 'required|min:1|unique:categories',
    ],

    'messages' => [
        'name.unique'           => '分类名在数据库里有重复，请选用其他名称',
        'name.required'         => '请确保名称至少有一个字符',
    ],


];