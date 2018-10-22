<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Generic_views extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    function top_search_form() {
        $this->layout->layout = 'login';
        $this->layout->render();
    }

}
