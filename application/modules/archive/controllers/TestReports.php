<?php

class TestReports extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Archived reports";
    }

    public function index() {
        $this->layout->render();
    }

}
