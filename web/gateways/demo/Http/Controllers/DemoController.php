<?php

namespace Gateway\Course\Http\Controllers;

use Log;
use Illuminate\Http\Request;

class DemoController extends Controller 
{
    /**
    * demo
    **/
    public function index(Request $request) 
    {
        $index = $request->input('index', 1);
        return CommonUtils::success($result);
    }
}
