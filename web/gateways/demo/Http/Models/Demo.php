<?php

namespace Gateway\Course\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * get 
 */
class Activity extends BaseModel {

    protected $table = 'demo';

    protected $primaryKey = 'id';

    public $timestamps = false; // 关闭自动更新时间戳

    public static $fields = ['id',];

    public static function buildQuery($query, $params) {
        if(array_key_exists('id', $params)) {
            self::setQuery($query, 'id', $params['id']);
        }
        if(array_key_exists('status', $params)) {
            self::setQuery($query, 'status', $params['status']);
        }
        return $query;
    }
}
