<?php

class Common_methods extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    function date_dept_selection_form($url) {
        $data['submit_url'] = base_url($url);
        $data['dept_list'] = $this->get_department_list('array');
        $this->load->view('common_methods/common_methods/date_dept_selection_form',$data);
    }

}