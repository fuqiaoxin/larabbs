<?php
use Spatie\Permission\Models\Permission;
return[
    // 页面标题
    'title' => '权限',

    // 模型单数,用作页面 '新建 $single'
    'single' => '权限',

    // 数据模型，用作数据的 CRUD
    'model' => Permission::class,

    // 设置当前页面的访问权限，通过返回布尔值来控制权限。
    // 返回 True 即通过权限验证，False 则无权访问并从 Menu 中隐藏
    'permission' => function(){
        return Auth::user()->can('manage_users');
    },

    // 对 CRUD 动作的单独权限控制, 通过返回布尔值来控制权限
    'action_permissions' => [

        // 控制 '新建按钮'的显示
        'create' => function($model){
            return true;
        },

        // 允许更新
        'update' => function($model){
            return true;
        },

        // 不允许删除
        'delete' => function($model){
            return false;
        },

        // 允许查看
        'view' => function($model){
            return true;
        }
    ],


    // 字段负责渲染『数据表格』，由无数的『列』组成
    'columns' => [

        // 列的标示，这是一个最小化『列』信息配置的例子，读取的是模型里对应的属性的值，如 $model->id
        'id' =>[
            'title' => 'ID'
        ],
        'name' => [
            // 数据表格里列的名称，默认会使用『列标识』
            'title' => '标识',
        ],

        'operation' => [
            'title' => '管理',
            'sortable' => false,

        ],
    ],

    // '模型表单' 设置项
    'edit_fields' => [
        'name' => [
            'title' => '标识(请慎重修改)',

            // 表单条目标题帮的 '提示信息'
            'hint' => '修改权限标识会影响代码的调用',
        ],
        'roles' => [
            'title' => '角色',
            'type' => 'relationship',
            'name_field' => 'name',
        ],
    ],

    // '数据过滤' 设置
    'filters' => [
        'name' => [
            'title' => '标识',
        ],

    ],

];