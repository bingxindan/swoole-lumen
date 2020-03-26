<?php

namespace Gateway\Course\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * 课程
 */
class BaseModel extends Model {

    public $timestamps = false; // 关闭自动更新时间戳

    public static function setQuery($query, $key, $value) {
        if($value === [] || $value === null) {
            return;
        }
        if(is_array($value)) {
            return $query->whereIn($key, $value);
        }
        return $query->where($key, '=', $value);
    }
}
