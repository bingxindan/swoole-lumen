<?php

namespace Gateway\Demo\Http\Services;

use Log;
use Illuminate\Support\Facades\DB;

class DemoService extends Service 
{
    /**
    * 获取
    **/
    public static function get($id) 
    {
        if($id <= 0) {
            return [];
        }
        return $act;
    }
}
