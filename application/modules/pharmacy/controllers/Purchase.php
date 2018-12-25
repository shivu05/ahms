<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Purchase extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-medkit';
        $this->layout->title = "Pharmacy";
        $this->load->model('pharmacy/purchase_model');
    }

    function purchase_items() {
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Pharmacy";
        $this->layout->navDescr = "Purchase item master";
        $data = array();
        $data['pt_items'] = $this->purchase_model->get_purchase_types_master();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_purchase_master_items_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->purchase_model->get_purchase_variables($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function save_purchase_master_type() {
        $post_values = array(
            'type' => $this->input->post('add_type'),
            'name' => $this->input->post('add_name')
        );
        $is_inserted = $this->purchase_model->save_purchase_type_master($post_values);
        if ($is_inserted) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
        //redirect('add-purchase-type', 'refresh');
    }

    function add_product() {
        $this->scripts_include->includePlugins(array('jq_validation', 'chosen'), 'js');
        $this->scripts_include->includePlugins(array('chosen'), 'css');
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Pharmacy | Product";
        $this->layout->navDescr = "Product entry";
        $data = array();
        $data['suppliers'] = $this->purchase_model->get_purchase_items('SUPPLIER');
        $data['products'] = $this->purchase_model->get_purchase_items('PRODUCT');
        $data['mfgs'] = $this->purchase_model->get_purchase_items('MFG');
        $data['groups'] = $this->purchase_model->get_purchase_items('GROUP');
        $data['categories'] = $this->purchase_model->get_purchase_items('CATEGORY');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function save_product() {
        $form_data = array(
            'supplier' => $this->input->post('supplier'),
            'product_id' => $this->input->post('product_id'),
            'batch' => $this->input->post('batch_no'),
            'packing' => $this->input->post('packing'),
            'mfg' => $this->input->post('mfr'),
            'category' => $this->input->post('category'),
            'expirydate' => $this->input->post('expiry_date'),
            'vat' => $this->input->post('vat'),
            'minstock' => $this->input->post('min_stock'),
            'ordlevel' => $this->input->post('ord_level'),
            'rack' => $this->input->post('rack'),
            'p_rate' => $this->input->post('purchase_rate'),
            'mrp' => $this->input->post('mrp'),
            'pqty' => $this->input->post('pqty'),
            'fqty' => $this->input->post('fqty'),
            'discount' => $this->input->post('discount'),
        );
        $is_inserted = $this->purchase_model->save_product($form_data);
        if ($is_inserted) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }

    function purchase_return() {
        $this->scripts_include->includePlugins(array('jq_validation', 'datatables', 'chosen'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "Purchase return";
        $this->layout->navDescr = "Returnging purchased items";
        $data = array();
        $data['suppliers'] = $this->purchase_model->get_purchase_items('SUPPLIER');
        $data['bill_nos'] = $this->purchase_model->get_stock_variables('billno');
        $data['refnos'] = $this->purchase_model->get_stock_variables('refno');

        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_products_tobe_returned() {
        $input_array = array();
        /* foreach ($this->input->post('search_form') as $search_data) {
          $input_array[$search_data['name']] = $search_data['value'];
          } */
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->purchase_model->get_temp_products_return_data($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function get_product_list_by_supplier() {
        $supplier_id = $this->input->post('supplier_id');
        $data['products_list'] = $this->purchase_model->get_products_by_supplier($supplier_id);
        $data['batch'] = $this->purchase_model->get_product_variables('batch');
        echo json_encode($data);
    }

    function get_product_details() {
        $product_id = $this->input->post('product_id');
        $batch = $this->input->post('batch');
        $data['product_info'] = $this->purchase_model->get_product_info($product_id, $batch);
        echo json_encode($data);
    }

}
