<?php

/**
 * Description of Beds
 *
 * @author shiva
 */
class Beds extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Bed Management";
    }

    public function index() {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function bed_allocation() {
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $this->layout->data = $data;
        $this->layout->render();
    }
}
