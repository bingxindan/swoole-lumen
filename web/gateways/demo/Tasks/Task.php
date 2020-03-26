<?php

namespace Gateway\Course\Tasks;

use Log;

class Task {

    protected $name = 'TaskInstance';

    public function __construct(){
    }

    public function taskName() {
        return $this->name;
    }

    public final function start() {
        Log::info("\n\n\n\n\n-------TASK START---------\n\n\n\n\n");
    }

    public function stop() {
        Log::info("\n\n\n\n\n-------TASK STOP---------\n\n\n\n\n");
    }
    
    protected function run() {
    }
}
