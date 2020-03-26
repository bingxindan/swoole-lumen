<?php

namespace Bxd\Db;

use Log;

class Slave
{
    /**
     * 获取从库key
     */
    static public function key() {
        static $key;
        if ($key) return $key;
        $key = '';
        // 获取随机列表
        $list = self::_loadConnList();
        // 空列表
        if (empty($list)) return $key;
        // 轮询ping
        foreach ($list as $k) {
            if (self::_tryConnect($k)) {
                $key = $k;
                break;
            }
        }
        return $key;
    }

    /**
     * 尝试连接
     */
    static private function _tryConnect($key)
    {
        $config = config('database.connections.' . $key);
        $result = true;
        try {
            $host = $config['host'];
            $port = $config['port'];
            $database = $config['database'];
            $name = $config['username'];
            $pass = $config['password'];
            $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$database", $name, $pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $msg = sprintf("[Bxd\Db\Slave connect fail] [key=%s,msg=%s]", $key, $e->getMessage());
            Log::error($msg);
            $result = false;
        }
        $pdo = null;
        return $result;
    }

    /**
     * 加载从库列表
     */
    static private function _loadConnList ()
    {
        $list = json_decode(env('DB_SLAVE'), true);
        $list = array_keys($list);
        shuffle($list);
        return $list;
    }

}
