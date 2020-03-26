<?php

class RPC {

    const TIMEOUT = 300;

    public static function call($method, $service, $args = array()) {
        $data = http_build_query($args);
        $options = array(
            'http' => array(
                'method' => $method,
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $data,
                'timeout' => 30 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($service, false, $context);
        return $result;
    }
}
