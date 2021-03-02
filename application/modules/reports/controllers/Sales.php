<?php

/**
 * Description of Sales
 *
 * @author Abhilasha
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends SHV_Controller {

    private $_is_admin = false;

    public function __construct() {
        parent::__construct();
        $this->_is_admin = $this->rbac->is_admin();
        $this->load->model('sales_entry');
    }

    function opd() {
        $this->layout->title = "Pharmcy";
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Pharmcay";
        $this->layout->navDescr = "OPD pharmacy report";
        $this->scripts_include->includePlugins(array('datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $data['top_form'] = modules::run('common_methods/common_methods/date_dept_selection_form', 'reports/Test/export_xray_to_pdf');
        $data['dept_list'] = $this->get_department_list('array');
        $data['is_admin'] = $this->_is_admin;
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_pharmacy_report() {
        $data['title'] = "Pharmacy report";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $dept = $this->input->post('department');
        $data["is_print"] = false;
        $data["patient"] = $this->sales_entry->getSalesPharmacy($start_date, $end_date, $dept, 0);
        $this->load->view('reports/sales/opd_pharmacy_report_vw', $data);
    }

}
