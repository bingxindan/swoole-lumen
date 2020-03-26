<?php

return [
    'use' => 'bingxindan',
    'properties' => [
        'bingxindan' => [
            'host'					=> env('MQ.DAVDIAN.HOST'),
            'port'          		=> env('MQ.DAVDIAN.PORT'),
            'username'        		=> env('MQ.DAVDIAN.USERNAME'),
            'password'        		=> env('MQ.DAVDIAN.PASSWORD'),
            'vhost'           		=> '/',
            'connect_options' 		=> [],
			'ssl_options'           => [],

			'exchange'              => 'bus.happy',
            'exchange_type'         => 'topic',
            'exchange_passive'      => false,
            'exchange_durable'      => true,
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],

			'queue_force_declare'   => false,
            'queue_passive'         => false,  // 队列持久化
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

			'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
			'timeout'               => 3,
            'persistent'            => false,
        ],
    ],
];
