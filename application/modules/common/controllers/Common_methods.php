<?php

class Common_methods extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    function date_dept_selection_form() {
        $this->layout->render();
    }

}
