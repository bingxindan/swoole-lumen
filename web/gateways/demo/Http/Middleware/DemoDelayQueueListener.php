<?php

namespace Gateway\Course\Http\Middleware;

use Log;
use Illuminate\Support\Facades\DB;

class DemoDelayQueueListener extends Listener {

    const BUS = 'demo.ttl.exchange';
    const EVENT_DLX_600= 'delay.600';
    const EVENT_DLX_86400= 'delay.86400';


    public function __construct() {
        $this->setup();
    }

    public function queueName() {
        return 'listener.demo.delay';
    }

    public function setup() {
        $this->on(self::BUS, self::EVENT_DLX_600, 'onDelayQueue600');
        $this->on(self::BUS, self::EVENT_DLX_86400, 'onDelayQueue86400');
    }

    /**
     * @param $bus
     * @param $event
     * @param $payload
     */
    public function onDelayQueue600($bus, $event, $payload) {
        Log::INFO("onDelayQueue600|$bus|$event|".json_encode($payload));
        EventBus::emit($payload['bus'], $payload['event'], $payload);
    }


    /**
     * @param $bus
     * @param $event
     * @param $payload
     */
    public function onDelayQueue86400($bus, $event, $payload) {
        Log::INFO("onDelayQueue86400|$bus|$event|".json_encode($payload));
        EventBus::emit($payload['bus'], $payload['event'], $payload);
    }

}
