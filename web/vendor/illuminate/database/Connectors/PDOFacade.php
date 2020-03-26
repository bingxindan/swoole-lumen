<?php

namespace Illuminate\Database\Connectors;

use Illuminate\Support\Str;
use PDO;
use Exception;
use Doctrine\DBAL\Driver\PDOConnection;
use Log;

class PDOFacade
{
    private static $_connections;

    private $dsn;  //host

    private $username;

    private $password;

    private $options;

    public function __construct($dsn, $username, $password, $options)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->_bindConnection();
    }

    /**
     * 使用PDO原生方法，增加检测超时重连机制
     * @param  $method
     * @param  $args
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args)
    {
        try {
            return $this->_invokeMethod($method, $args);
        } catch (Exception $e) {
            Log::warn(sprintf('catch Exception[code:%s][message:%s]' . PHP_EOL, $e->getCode(), $e->getMessage()));
            if (!$this->_excludeLostConnection($e)) {//其他异常
                throw $e;
            }
            //增加重试机制
            $this->_bindConnection(true);
            return $this->_invokeMethod($method, $args);
        }
    }

    /**
     * 创建pdo
     */
    private function _bindConnection($ifRefreshCon = false)
    {
        if (!$ifRefreshCon && isset(self::$_connections[$this->dsn])) {
            return self::$_connections[$this->dsn];
        }
        if (class_exists(PDOConnection::class)) {
            return self::$_connections[$this->dsn] = new PDOConnection($this->dsn, $this->username, $this->password, $this->options);
        }

        return self::$_connections[$this->dsn] = new PDO($this->dsn, $this->username, $this->password, $this->options);
    }

    private function _invokeMethod($method, $arguments)
    {
        $func = [self::$_connections[$this->dsn], $method];
        if (!is_callable($func)) {
            throw new Exception('PDO方法不存在', 1);
        }
        return self::$_connections[$this->dsn]->$method(...$arguments);
    }


    /**
     * 定位连接超时
     */
    private function _excludeLostConnection(Exception $e)
    {
        if(isset(self::$_connections[$this->dsn]) && self::$_connections[$this->dsn]->inTransaction()) {
            Log::warn('事务中异常,抛出异常');
            return false;
        }

        $message = $e->getMessage();

        return Str::contains($message, [
            'server has gone away',
            'Error while sending',
//            'Lost connection',
//            'is dead or not enabled',
//            'server closed the connection unexpectedly',
//            'SSL connection has been closed unexpectedly',
//            'Error writing data to the connection',
//            'Resource deadlock avoided',
        ]);
    }
}
