<?php

/**
 * Description of Sales
 *
 * @author Shiv
 */
class Sales extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->title = "Sales";
        $this->load->model('pharmacy/sales_model');
    }

    public function index() {
        $this->scripts_include->includePlugins(array('typeahead'));
        $data = array();
        $data['product_list'] = $this->sales_model->get_product_list();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function fetch_patient_data() {
        if ($this->input->is_ajax_request()) {
            $opd = $this->input->post('opd');
            $data = $this->sales_model->get_patient_data($opd);
            $this->layout->data = $data;
            echo $this->layout->render(array('view' => 'pharmacy/sales/fetch_patient_data'), true);
        }
    }

    public function fetch_patient_details() {
        if ($this->input->is_ajax_request()) {
            pma($this->input->post());
            echo $opd = $this->input->post('opd', true);
            echo $type = $this->input->post('type', true);
        }
    }

    public function update_stocks() {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $n = count($post_values['product_id']);
            $digits = 4;
            $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            for ($i = 0; $i < $n; $i++) {
                $insert_data = array(
                    'billno' => $four_digit_random_number,
                    'opd_no' => $post_values['opd_no'],
                    'treat_id' => $post_values['treat_id'],
                    'date' => $post_values['treat_date'],
                    'product_id' => $post_values['product_id'][$i],
                    'qty' => $post_values['qty'][$i],
                    'sub_total' => $post_values['sub_total'][$i],
                    'unit_price' => $post_values['unit_price'][$i],
                );
                $this->sales_model->update_stock($insert_data);
            }
            echo json_encode(array('status' => 'ok'));
        }
    }

}
