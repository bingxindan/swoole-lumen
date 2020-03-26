<?php

namespace Gateway\Demo\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController 
{
    private $request = null;

    public function __construct(Request $request) 
    {
        $this->request = $request;
    }

}
