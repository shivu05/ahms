<?php

class System_info extends DVS_Controller {

    public function __construct() {
        parent::__construct();
        $this->initialize_rest_client('dvs');
    }

    function index() {
        if ($this->rbac->is_sadmin()) {
            $this->layout->render();
        } else {
            $this->layout->render(array('error' => '401'));
        }
    }

    function get_system_info() {
        echo $this->rest_client->get('webapi/sys_info/fetch_system_info');
    }

}
