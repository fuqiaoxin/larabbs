<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class PagesController extends Controller
{
    public function root(){

        return view('pages.root');
    }

    public function permissionDenied()
    {
        //
        if(config('administrator.permission')()){
            //return url(config('administrator.uri'));
            return redirect(url(config('administrator.uri')),302);
        }

        // 否则使用视图
        return view('pages.permission_denied');
    }
}
