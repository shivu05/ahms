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
        $this->layout->navTitleFlag = false;
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

    function product_list() {
        $this->scripts_include->includePlugins(array('datatables', 'jq_validation'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Products";
        $this->layout->navDescr = "Product list";
        $data = array();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_product_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->purchase_model->get_product_list($input_array);
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data']);
        echo json_encode($response);
    }

    function add_product() {
        $this->scripts_include->includePlugins(array('jq_validation', 'chosen'), 'js');
        $this->scripts_include->includePlugins(array('chosen'), 'css');
        $this->layout->navTitleFlag = false;
        $this->layout->navTitle = "Pharmacy | Product";
        $this->layout->navDescr = "Product entry";
        $data = array();
        $data['suppliers'] = $this->purchase_model->get_purchase_items('SUPPLIER');
        $data['products'] = $this->purchase_model->get_purchase_items('PRODUCT');
        $data['mfgs'] = $this->purchase_model->get_purchase_items('MFG');
        $data['groups'] = $this->purchase_model->get_purchase_items('GROUP');
        $data['categories'] = $this->purchase_model->get_purchase_items('CATEGORY');
        $data['packaging_types'] = $this->get_packaging_types();
        $this->layout->data = $data;
        $this->layout->render();
    }

    function save_product() {
        $form_data = array(
            'product_unique_id' => $this->input->post('product_unique_id'),
            'supplier_id' => $this->input->post('supplier'),
            'product_master_id' => $this->input->post('product_id'),
            'product_batch' => $this->input->post('batch_no'),
            'packing_name' => $this->input->post('packing'),
            'product_mfg' => $this->input->post('mfr'),
            'product_type' => $this->input->post('category'),
            'product_group' => $this->input->post('group'),
            'manifacture_date' => $this->input->post('manufacturing_date'),
            'exp_date' => $this->input->post('expiry_date'),
            'vat' => $this->input->post('vat'),
            'purchase_rate' => $this->input->post('purchase_rate'),
            'mrp' => $this->input->post('mrp'),
            'sale_rate' => $this->input->post('sale_rate'),
            'discount' => $this->input->post('discount'),
            'pack_type' => $this->input->post('pack_type'),
            'reorder_point' => $this->input->post('reorder_point'),
            'weight' => $this->input->post('weight')
        );
        $is_inserted = $this->purchase_model->save_product($form_data);
        if ($is_inserted) {
            $uuid = $this->get_uuid();
            echo json_encode(array('status' => true, 'uuid' => $uuid));
        } else {
            echo json_encode(array('status' => false));
        }
    }

    function export_product_list() {
        $this->load->helper('to_excel');
        $search_criteria = NULL;
        $input_array = array();

        foreach ($this->input->post('search_form') as $search_data) {
            //$input_array[$search_data] = $val;
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $result = $this->purchase_model->get_product_list($input_array, true);
        $export_array = $result['data'];

        $headings = array(
            'product_name' => 'Product name',
            'product_batch' => 'Batch',
            'supplier_id' => 'Supplier name',
            'packing_name' => 'Packaging',
            'product_mfg' => 'MFR',
            'product_type' => 'Category',
            'quantity' => 'Quantity',
            'purchase_rate' => 'Purchase rate',
            'mrp' => 'MRP',
            'sale_rate' => 'Selling price',
            'vat' => 'VAT',
            'no_of_items_in_pack' => 'No.of items in pack',
            'pack_type' => 'Packing',
            'item_unit_cost' => 'Item unit cost',
            'no_of_sub_items' => 'No.of sub items',
            'sub_item_pack_type' => 'Sub item packing',
            'sub_item_unit_cost' => 'Sub item unit cost',
            'no_of_sub_items_in_pack' => 'No.of items in pack',
            'discount' => 'Discount',
            'reorder_point' => 'Reorder point',
            'weight' => 'Weight',
            'rack' => 'Pack',
            'manifacture_date' => 'Manufacturing date',
            'exp_date' => 'Expiry date'
        );
        $file_name = 'product_list' . date('dd-mm-Y') . '.xlsx';
        $freeze_column = '';
        $worksheet_name = 'Products';
        download_to_excel($export_array, $file_name, $headings, $worksheet_name, null, $search_criteria, TRUE);
        ob_end_flush();
    }

    function purchase_return() {
        $this->scripts_include->includePlugins(array('jq_validation', 'datatables', 'chosen'), 'js');
        $this->scripts_include->includePlugins(array('chosen', 'datatables'), 'css');
        $this->layout->navTitleFlag = false;
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
        $data['batch'] = $this->purchase_model->get_product_variables('product_batch', array('supplier_id' => $supplier_id));
        echo json_encode($data);
    }

    function get_product_details() {
        $product_id = $this->input->post('product_id');
        $batch = $this->input->post('batch');
        $data['product_info'] = $this->purchase_model->get_product_info($product_id, $batch);
        echo json_encode($data);
    }

    function add_purchase() {
        $this->scripts_include->includePlugins(array('jq_validation', 'datatables'), 'js');
        $this->scripts_include->includePlugins(array('datatables'), 'css');
        $this->load->model('pharmacy/product_master');
        $data['suppliers'] = $this->purchase_model->get_purchase_items('SUPPLIER');
        $data['temp_purchase_data'] = $this->purchase_model->get_temp_purchase_data();
        $data['bill_nos'] = $this->purchase_model->get_bill_nos();
        $data['products'] = $this->product_master->fetch_products_list();
        $data['temp_pdata'] = $this->product_master->fetch_temp_purchase_data();
        //pma($data['temp_pdata'],1);
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_product_params() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('pharmacy/product_master');
            $post_values = $this->input->post();
            $batchs = $this->product_master->filter(array('*'), $post_values);
            echo json_encode($batchs);
            exit;
        } else {
            echo json_encode(array('error' => 'Invalid request'));
        }
    }

    function save_temp_purchase_entry() {
        if ($this->input->is_ajax_request()) {
            $user_id = $this->rbac->get_uid();
            $post_values = $this->input->post();
            //pma($post_values,1);
            $insert_array = array(
                'supplier_id' => $post_values['supplier_id'],
                'billno' => $post_values['bill_no'],
                'date' => $post_values['bill_date'],
                'refno' => $post_values['ref_no'],
                'price' => $post_values['bill_amount'],
                'opbal' => $post_values['op_bal'],
                'product' => $post_values['product_id'],
                'batch' => $post_values['batch_id'],
                'pty' => $post_values['purchase_qty'],
                'fty' => $post_values['f_qty'],
                'prate' => $post_values['p_rate'],
                'mrp' => $post_values['mrp'],
                'discount' => $post_values['discount'],
                'vat' => $post_values['discount'],
                'btype' => $post_values['btype'],
                'total' => $post_values['total'],
                'total' => $post_values['total'],
                'user_id' => $user_id
            );
            $is_inserted = $this->purchase_model->save_temp_purchase_data($insert_array);
            if ($is_inserted) {
                echo json_encode(array('status' => 'ok'));
            } else {
                echo json_encode(array('status' => 'failed', 'error' => 'failed to insert'));
            }
        } else {
            echo json_encode(array('error' => 'Invalid request'));
        }
    }

    function delete_temp_purchase_data() {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $is_deleted = $this->db->delete('temp_purchase_entry');
            if ($is_deleted) {
                echo json_encode(array('status' => true));
            } else {
                echo json_encode(array('status' => false));
            }
        } else {
            echo json_encode(array('error' => 'Invalid request'));
        }
    }

    function store_purchase_entry() {
        $billno = $this->input->post('bill_no');
        $is_inserted = $this->purchase_model->save_purchase_entry($billno);
        if ($is_inserted) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }

    function export_patients_list_pdf() {
        $this->load->helper('pdf');
        $billno = $this->input->post('billno');
        if (!empty($billno) && $billno != '') {
            $result = $this->purchase_model->get_purchase_details($billno);
            $headers = array(
                'name' => array('name' => 'Product', 'align' => 'L', 'width' => '30'),
                'batch' => array('name' => 'Batch', 'align' => 'L', 'width' => '20'),
                'pty' => array('name' => 'P.Qty', 'align' => 'L', 'width' => '7'),
                'fty' => array('name' => 'F.QTY', 'align' => 'C', 'width' => '7'),
                'prate' => array('name' => 'P.rate', 'align' => 'L', 'width' => '10'),
                'mrp' => array('name' => 'MRP', 'align' => 'L', 'width' => '10'),
                'vat' => array('name' => 'VAT', 'align' => 'L', 'width' => '6'),
                'discount' => array('name' => 'Discount', 'align' => 'L', 'width' => '10'),
            );
            $html = generate_table_pdf($headers, $result);
            $title = array(
                'report_title' => 'Purchase details',
                'extradata' => '<p>Bill no:' . $billno . '</p>'
            );

            pdf_create($title, $html);
            exit;
        } else {
            show_error('Invalid request. Try again');
        }
    }

}
