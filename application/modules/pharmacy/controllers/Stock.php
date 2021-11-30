<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stock
 *
 * @author Shivaraj
 */
class Stock extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-medkit';
        $this->layout->title = "Pharmacy";
        $this->load->model('pharmacy/stock_model');
        $this->load->model('pharmacy/purchase_model');
    }

    function stock_entry() {
        $this->scripts_include->includePlugins(array('jq_validation', 'chosen'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Pharmacy";
        $this->layout->navDescr = "Stock entry";
        $data = array();
        $data['suppliers'] = $this->purchase_model->get_purchase_items('SUPPLIER');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function save_stock() {
        $post_values = $this->input->post();
        $this->stock_model->save_stock($post_values);
        //pma($products);
    }

    function get_batch() {
        $product_id = $this->input->post('product_id');
        $this->load->model('pharmacy/product_master');
        $where = array('product_id' => $product_id);
        $columns = array('product_batch', 'mrp', 'purchase_rate', 'vat', 'discount');
        $result = $this->product_master->get_product_info($where, $columns);
        echo json_encode($result);
    }

    function stock_list() {
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_stock_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->stock_model->get_stock_list($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

}

?>
