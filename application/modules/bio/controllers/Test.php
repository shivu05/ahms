<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Test extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->db->trans_start();
        $sales_entry = $this->db->query("SELECT * FROM sales_entry")->result_array();
        if (!empty($sales_entry)) {
            $find = array('BD', 'TID', 'TDS', 'ML', 'MG');
            $i = 0;
            foreach ($sales_entry as $row) {
                $i++;
                $str = str_replace($find, '', trim($row['product']));
                $clean_str = trim(preg_replace('/[0-9]+/', '', $str));
                $query = "SELECT * FROM product_list WHERE product LIKE '%" . $clean_str . "%' LIMIT 1";
                $data = $this->db->query($query)->row_array();
                $query = "UPDATE sales_entry SET price ='" . $data['price'] . "' where id='" . $row['id'] . "'";
                $this->db->query($query);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo 'Failed';
        } else {
            $this->db->trans_commit();
            echo 'Successfully updated';
        }
    }

    function custom_rtrim($str, $keys=array()) {
        return rtrim($str, $key);
    }

    function update_monthly_no() {
        $count = $this->db->query("select count(*) as count from treatmentdata where monthly_sid=0;")->row_array();
        if (!empty($count) && $count['count'] > 0) {
            $this->db->trans_start();
            $this->db->query('UPDATE treatmentdata SET monthly_sid = 0;');
            $months_query = "SELECT MONTH(CameOn) as month,max(monthly_sid) as max_val FROM treatmentdata t GROUP BY MONTH(CameON)";
            $present_months = $this->db->query($months_query)->result_array();


            foreach ($present_months as $mon) {
                $max_value_check = $this->db->query("SELECT max(monthly_sid) as max_val FROM treatmentdata t where MONTH(CameON)='" . $mon['month'] . "'")->row_array();
                $prev_max_val = 0;
                if ($mon['month'] > 1) {
                    $prev_max_val = $max_value_check['max_val'];
                }
                $query = "UPDATE treatmentdata,(SELECT @a:= '" . ($prev_max_val) . "') AS a SET monthly_sid = @a:=@a+1 where MONTH(CameON)='" . $mon['month'] . "';";
                //pma($query);
                $patients_list = $this->db->query($query);
            }
            $this->db->trans_complete();
        } 
    }

}