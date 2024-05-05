<?php

//defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Autoclave
 *
 * @author shivaraj
 */
class Autoclave extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('register/autoclave');
    }

    function index() {
       // $data['grid_data'] = $this->autoclave->get();
        //$this->layout->data = $data;
        $this->layout->render();
    }
}
