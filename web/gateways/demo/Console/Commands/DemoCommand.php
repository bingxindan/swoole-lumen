<?php

namespace Gateway\Course\Console\Commands;

use Log;
use Illuminate\Console\Command;

/**
 * demo
 * Class Command
 * @package Gateway\Demo\Console\Commands
 */
class DemoCommand extends Command {

    protected $signature = 'DemoCommand {quantity=10000}';

    protected $description = '描述';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $quantity = $this->argument('quantity');
        // consume
        if ($quantity < 1) {
            echo "参数有误请勿结束\n";
            return [];
        }
        foreach (range(1, $quantity) as $v) {
            $data = [];
            if (empty($data)) {
                echo "无数据\n";
                return [];
            }
        }
    }
}
