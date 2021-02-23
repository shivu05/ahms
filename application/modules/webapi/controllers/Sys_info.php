<?php

require APPPATH . 'modules/webapi/libraries/REST_Server.php';

class Sys_info extends REST_Server {

    public function __construct() {
        parent::__construct();
        $this->request->format = 'json';
    }

    function fetch_system_info_get() {
        $system_info = array();
        $up_time = exec("uptime");
        $system_info['up_time'] = array(
            'title' => 'Up time',
            'data' => $up_time,
        );
        $this->response($system_info, 200);
    }

}
