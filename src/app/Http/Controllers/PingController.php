<?php

namespace App\Http\Controllers;

class PingController extends Controller {

    public function index() {
        return $this->sendSuccessResponse(NULL, 'System is up and running !!');
    }

}

?>
