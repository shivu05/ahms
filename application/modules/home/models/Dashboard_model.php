<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dashboard_model
 *
 * @author Shivaraj
 */
class Dashboard_model extends CI_Model {

    function get_gender_wise_patients() {
        $where = '';
        if ($this->rbac->is_doctor()) {
            $where = " AND t.attndedby='" . $this->rbac->get_name() . "'";
        }
        $query = "SELECT COALESCE(SUM(case when p.gender='Male' then 1 else 0 end),0) males,
            COALESCE(SUM(case when p.gender='Female' then 1 else 0 end),0) females,
            count(*) total FROM patientdata p,treatmentdata t WHERE t.OpdNo = p.OpdNo and p.gender is not null $where;";
        return $this->db->query($query)->result_array();
    }

    function get_departmentwise_patient_count() {
        $result = $this->db->query("CALL get_patient_count()");
        $return = $result->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }
    
    function get_ipd_patients_count(){
        $result = $this->db->query("CALL get_ipd_patient_count()");
        $return = $result->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        return $return;
    }

    function get_today_opd_count() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('treatmentdata', array('ID', 'CameOn'))) {
                return 0;
            }

            $this->db->from('treatmentdata');
            $this->db->where('DATE(CameOn) = ' . $this->db->escape(date('Y-m-d')), null, false);
            return (int) $this->db->count_all_results();
        });
    }

    function get_today_ipd_admissions() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('inpatientdetails', array('IpNo', 'DoAdmission'))) {
                return 0;
            }

            $this->db->from('inpatientdetails');
            $this->db->where('DATE(DoAdmission) = ' . $this->db->escape(date('Y-m-d')), null, false);
            return (int) $this->db->count_all_results();
        });
    }

    function get_today_discharges() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('inpatientdetails', array('IpNo', 'DoDischarge'))) {
                return 0;
            }

            $this->db->from('inpatientdetails');
            $this->db->where('DATE(DoDischarge) = ' . $this->db->escape(date('Y-m-d')), null, false);
            $this->db->where('DoDischarge IS NOT NULL', null, false);
            $this->db->where('DoDischarge !=', '--');
            $this->db->where('DoDischarge !=', '');
            return (int) $this->db->count_all_results();
        });
    }

    function get_today_revenue() {
        return $this->_safe_metric(function() {
            $today = date('Y-m-d');

            if ($this->_has_table_fields('billing_payments', array('payment_amount', 'payment_date'))) {
                $this->db->from('billing_payments');
                $payment_rows = (int) $this->db->count_all_results();
                if ($payment_rows > 0) {
                    return $this->_sum_for_date('billing_payments', 'payment_amount', 'payment_date', $today);
                }
            }

            if ($this->_has_table_fields('billing_invoices', array('invoice_date'))) {
                $this->db->from('billing_invoices');
                $invoice_rows = (int) $this->db->count_all_results();
                if ($invoice_rows > 0) {
                    if ($this->db->field_exists('amount_paid', 'billing_invoices')) {
                        return $this->_sum_for_date('billing_invoices', 'amount_paid', 'invoice_date', $today);
                    }
                    if ($this->db->field_exists('total_amount', 'billing_invoices')) {
                        return $this->_sum_for_date('billing_invoices', 'total_amount', 'invoice_date', $today);
                    }
                }
            }

            if ($this->_has_table_fields('patient_bill_details', array('bill_amt', 'bill_date'))) {
                return $this->_sum_for_date('patient_bill_details', 'bill_amt', 'bill_date', $today);
            }

            return 0;
        });
    }

    function get_bed_occupancy_percentage() {
        return $this->_safe_metric(function() {
            if ($this->_has_table_fields('bed_details', array('bedno', 'bedstatus'))) {
                $this->db->from('bed_details');
                $this->db->where('bedno !=', 0);
                $total_beds = (int) $this->db->count_all_results();
                if ($total_beds === 0) {
                    return $this->_master_bed_occupancy_percentage();
                }

                // Occupied beds are stored as "Not Available" in bed_details in the current schema.
                $this->db->from('bed_details');
                $this->db->where('bedno !=', 0);
                $this->db->where('LOWER(bedstatus) != ' . $this->db->escape('available'), null, false);
                $occupied_beds = (int) $this->db->count_all_results();

                return round(($occupied_beds / $total_beds) * 100, 2);
            }

            return $this->_master_bed_occupancy_percentage();
        });
    }

    function get_month_opd_count() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('treatmentdata', array('ID', 'CameOn'))) {
                return 0;
            }

            $this->db->from('treatmentdata');
            $this->db->where('YEAR(CameOn) = ' . $this->db->escape(date('Y')), null, false);
            $this->db->where('MONTH(CameOn) = ' . $this->db->escape(date('m')), null, false);
            return (int) $this->db->count_all_results();
        });
    }

    function get_month_ipd_count() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('inpatientdetails', array('IpNo', 'DoAdmission'))) {
                return 0;
            }

            $this->db->from('inpatientdetails');
            $this->db->where('YEAR(DoAdmission) = ' . $this->db->escape(date('Y')), null, false);
            $this->db->where('MONTH(DoAdmission) = ' . $this->db->escape(date('m')), null, false);
            return (int) $this->db->count_all_results();
        });
    }

    function get_current_year_total_patients() {
        return $this->_safe_metric(function() {
            if (!$this->_has_table_fields('patientdata', array('OpdNo', 'entrydate'))) {
                return 0;
            }

            $this->db->from('patientdata');
            $this->db->where('YEAR(entrydate) = ' . $this->db->escape(date('Y')), null, false);
            return (int) $this->db->count_all_results();
        });
    }

    private function _has_table_fields($table, $fields) {
        if (!$this->db->table_exists($table)) {
            return false;
        }

        foreach ($fields as $field) {
            if (!$this->db->field_exists($field, $table)) {
                return false;
            }
        }

        return true;
    }

    private function _safe_metric($callback, $default = 0) {
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        try {
            $value = call_user_func($callback);
            $this->db->db_debug = $db_debug;
            return is_numeric($value) ? $value : $default;
        } catch (Exception $e) {
            $this->db->db_debug = $db_debug;
            log_message('error', 'Dashboard metric failed: ' . $e->getMessage());
            return $default;
        }
    }

    private function _sum_for_date($table, $amount_field, $date_field, $date) {
        $this->db->select('COALESCE(SUM(' . $amount_field . '),0) total_amount', false);
        $this->db->from($table);
        $this->db->where('DATE(' . $date_field . ') = ' . $this->db->escape($date), null, false);
        $query = $this->db->get();
        if (!$query) {
            return 0;
        }
        $row = $query->row_array();

        return isset($row['total_amount']) ? (float) $row['total_amount'] : 0;
    }

    private function _master_bed_occupancy_percentage() {
        if (!$this->_has_table_fields('master_bed_info', array('no_of_beds'))) {
            return 0;
        }

        $this->db->select('COALESCE(SUM(no_of_beds),0) total_beds', false);
        $row = $this->db->get('master_bed_info')->row_array();
        $total_beds = isset($row['total_beds']) ? (int) $row['total_beds'] : 0;

        if ($total_beds === 0 || !$this->_has_table_fields('inpatientdetails', array('IpNo', 'status'))) {
            return 0;
        }

        // Fallback when bed_details is missing: active IPD patients approximate occupied beds.
        $this->db->from('inpatientdetails');
        $this->db->where('LOWER(status) = ' . $this->db->escape('stillin'), null, false);
        $occupied_beds = (int) $this->db->count_all_results();

        return round(($occupied_beds / $total_beds) * 100, 2);
    }

}
