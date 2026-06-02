<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Read-only dashboard queries for executive hospital analytics.
 */
class Executive_dashboard_model extends CI_Model
{
    public function get_dashboard_data()
    {
        return array(
            'snapshot' => array(
                'today_opd' => $this->get_today_opd_count(),
                'today_ipd' => $this->get_today_ipd_admissions(),
                'today_revenue' => $this->get_today_revenue(),
                'bed_occupancy' => $this->get_bed_occupancy_percentage()
            ),
            'month_summary' => array(
                'opd_count' => $this->get_month_opd_count(),
                'ipd_count' => $this->get_month_ipd_count(),
                'admissions' => $this->get_month_ipd_count(),
                'discharges' => $this->get_month_discharges(),
                'panchakarma_count' => $this->get_month_panchakarma_count(),
                'lab_count' => $this->get_month_lab_count()
            ),
            'department_performance' => $this->get_department_performance(),
            'revenue_analytics' => array(
                'last_12_months' => $this->get_last_12_months_revenue(),
                'opd_revenue' => $this->get_month_revenue_by_type('OPD'),
                'ipd_revenue' => $this->get_month_revenue_by_type('IPD'),
                'pharmacy_revenue' => $this->get_month_pharmacy_revenue()
            ),
            'patient_trends' => array(
                'opd' => $this->get_monthly_opd_trend(),
                'ipd' => $this->get_monthly_ipd_trend()
            ),
            'bed_analytics' => $this->get_bed_analytics(),
            'panchakarma' => array(
                'today_therapies' => $this->get_today_panchakarma_count(),
                'month_therapies' => $this->get_month_panchakarma_count(),
                'top_therapies' => $this->get_top_panchakarma_therapies()
            )
        );
    }

    public function get_today_opd_count()
    {
        return $this->count_by_date('treatmentdata', 'CameOn', date('Y-m-d'));
    }

    public function get_today_ipd_admissions()
    {
        return $this->count_by_date('inpatientdetails', 'DoAdmission', date('Y-m-d'));
    }

    public function get_today_revenue()
    {
        return $this->get_revenue_sum(date('Y-m-d'), date('Y-m-d'));
    }

    public function get_bed_occupancy_percentage()
    {
        $bed = $this->get_bed_analytics();
        return $bed['occupancy_percentage'];
    }

    public function get_month_opd_count()
    {
        return $this->count_between_dates('treatmentdata', 'CameOn', $this->month_start(), $this->month_end());
    }

    public function get_month_ipd_count()
    {
        return $this->count_between_dates('inpatientdetails', 'DoAdmission', $this->month_start(), $this->month_end());
    }

    public function get_month_discharges()
    {
        return $this->count_between_dates('inpatientdetails', 'DoDischarge', $this->month_start(), $this->month_end());
    }

    public function get_month_lab_count()
    {
        if ($this->has_table_fields('labregistery', array('testDate'))) {
            return $this->count_between_dates('labregistery', 'testDate', $this->month_start(), $this->month_end());
        }
        if ($this->has_table_fields('labregistery', array('tested_date'))) {
            return $this->count_between_dates('labregistery', 'tested_date', $this->month_start(), $this->month_end());
        }
        return 0;
    }

    public function get_month_panchakarma_count()
    {
        return $this->count_between_dates('panchaprocedure', 'date', $this->month_start(), $this->month_end());
    }

    public function get_today_panchakarma_count()
    {
        return $this->count_by_date('panchaprocedure', 'date', date('Y-m-d'));
    }

    public function get_bed_analytics()
    {
        return $this->safe_query(function () {
            if (!$this->has_table_fields('bed_details', array('bedno', 'bedstatus'))) {
                return array('total_beds' => 0, 'occupied_beds' => 0, 'available_beds' => 0, 'occupancy_percentage' => 0);
            }

            $this->db->from('bed_details');
            $this->db->where('bedno !=', '0');
            $total = (int)$this->db->count_all_results();

            $this->db->from('bed_details');
            $this->db->where('bedno !=', '0');
            $this->db->where('bedstatus', 'Available');
            $available = (int)$this->db->count_all_results();

            // Legacy bed_details marks unavailable beds as occupied for dashboard purposes.
            $occupied = max(0, $total - $available);
            $percentage = $total > 0 ? round(($occupied / $total) * 100, 2) : 0;

            return array(
                'total_beds' => $total,
                'occupied_beds' => $occupied,
                'available_beds' => $available,
                'occupancy_percentage' => $percentage
            );
        }, array('total_beds' => 0, 'occupied_beds' => 0, 'available_beds' => 0, 'occupancy_percentage' => 0));
    }

    public function get_department_performance()
    {
        $departments = array();
        foreach ($this->get_department_opd_counts() as $row) {
            $key = $this->department_key($row['department']);
            $departments[$key]['department'] = $row['department'];
            $departments[$key]['opd_count'] = (int)$row['total'];
        }
        foreach ($this->get_department_ipd_counts() as $row) {
            $key = $this->department_key($row['department']);
            if (!isset($departments[$key])) {
                $departments[$key]['department'] = $row['department'];
            }
            $departments[$key]['ipd_count'] = (int)$row['total'];
        }
        foreach ($this->get_department_revenue_counts() as $row) {
            $key = $this->department_key($row['department']);
            if (!isset($departments[$key])) {
                $departments[$key]['department'] = $row['department'];
            }
            $departments[$key]['revenue'] = (float)$row['total'];
        }

        foreach ($departments as &$row) {
            $row['opd_count'] = isset($row['opd_count']) ? $row['opd_count'] : 0;
            $row['ipd_count'] = isset($row['ipd_count']) ? $row['ipd_count'] : 0;
            $row['revenue'] = isset($row['revenue']) ? $row['revenue'] : 0;
        }
        unset($row);

        usort($departments, function ($a, $b) {
            return ($b['opd_count'] + $b['ipd_count']) - ($a['opd_count'] + $a['ipd_count']);
        });

        return array_values($departments);
    }

    public function get_last_12_months_revenue()
    {
        $labels = array();
        $values = array();
        for ($i = 11; $i >= 0; $i--) {
            $start = date('Y-m-01', strtotime("-{$i} months"));
            $end = date('Y-m-t', strtotime($start));
            $labels[] = date('M Y', strtotime($start));
            $values[] = (float)$this->get_revenue_sum($start, $end);
        }
        return array('labels' => $labels, 'values' => $values);
    }

    public function get_month_revenue_by_type($type)
    {
        return $this->safe_query(function () use ($type) {
            if ($this->has_table_fields('billing_invoices', array('invoice_date', 'invoice_type', 'amount_paid'))) {
                $this->db->select_sum('amount_paid', 'total');
                $this->db->from('billing_invoices');
                $this->db->where('invoice_type', $type);
                $this->date_between_where('invoice_date', $this->month_start(), $this->month_end());
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }
            return 0;
        }, 0);
    }

    public function get_month_pharmacy_revenue()
    {
        return $this->safe_query(function () {
            if ($this->has_table_fields('medicine_sales', array('sale_date', 'paid_amount'))) {
                $this->db->select_sum('paid_amount', 'total');
                $this->db->from('medicine_sales');
                $this->date_between_where('sale_date', $this->month_start(), $this->month_end());
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }

            if ($this->has_table_fields('sales_entry', array('date', 'price', 'qty'))) {
                $this->db->select('SUM(price * qty) as total', false);
                $this->db->from('sales_entry');
                $this->date_between_where('date', $this->month_start(), $this->month_end());
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }

            return 0;
        }, 0);
    }

    public function get_monthly_opd_trend()
    {
        return $this->monthly_count_trend('treatmentdata', 'CameOn');
    }

    public function get_monthly_ipd_trend()
    {
        return $this->monthly_count_trend('inpatientdetails', 'DoAdmission');
    }

    public function get_top_panchakarma_therapies()
    {
        return $this->safe_query(function () {
            if (!$this->has_table_fields('panchaprocedure', array('treatment', 'date'))) {
                return array();
            }

            $this->db->select('COALESCE(NULLIF(treatment, ""), "Unspecified") as therapy, COUNT(*) as total', false);
            $this->db->from('panchaprocedure');
            $this->date_between_where('date', $this->month_start(), $this->month_end());
            $this->db->group_by('treatment');
            $this->db->order_by('total', 'DESC');
            $this->db->limit(5);
            return $this->db->get()->result_array();
        }, array());
    }

    private function get_revenue_sum($from, $to)
    {
        return $this->safe_query(function () use ($from, $to) {
            if ($this->has_table_fields('billing_payments', array('payment_date', 'payment_amount')) && $this->table_has_rows('billing_payments')) {
                $this->db->select_sum('payment_amount', 'total');
                $this->db->from('billing_payments');
                $this->date_between_where('payment_date', $from, $to);
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }

            if ($this->has_table_fields('billing_invoices', array('invoice_date', 'amount_paid')) && $this->table_has_rows('billing_invoices')) {
                $this->db->select_sum('amount_paid', 'total');
                $this->db->from('billing_invoices');
                $this->date_between_where('invoice_date', $from, $to);
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }

            if ($this->has_table_fields('patient_bill_details', array('bill_date', 'bill_amt'))) {
                $this->db->select_sum('bill_amt', 'total');
                $this->db->from('patient_bill_details');
                $this->date_between_where('bill_date', $from, $to);
                $row = $this->db->get()->row_array();
                return (float)@$row['total'];
            }

            return 0;
        }, 0);
    }

    private function get_department_opd_counts()
    {
        return $this->safe_query(function () {
            if (!$this->has_table_fields('treatmentdata', array('department', 'CameOn'))) {
                return array();
            }
            $this->db->select('COALESCE(NULLIF(department, ""), "Unspecified") as department, COUNT(*) as total', false);
            $this->db->from('treatmentdata');
            $this->date_between_where('CameOn', $this->month_start(), $this->month_end());
            $this->db->group_by('department');
            return $this->db->get()->result_array();
        }, array());
    }

    private function get_department_ipd_counts()
    {
        return $this->safe_query(function () {
            if (!$this->has_table_fields('inpatientdetails', array('department', 'DoAdmission'))) {
                return array();
            }
            $this->db->select('COALESCE(NULLIF(department, ""), "Unspecified") as department, COUNT(*) as total', false);
            $this->db->from('inpatientdetails');
            $this->date_between_where('DoAdmission', $this->month_start(), $this->month_end());
            $this->db->group_by('department');
            return $this->db->get()->result_array();
        }, array());
    }

    private function get_department_revenue_counts()
    {
        return $this->safe_query(function () {
            if ($this->has_table_fields('billing_invoices', array('department', 'invoice_date', 'amount_paid'))) {
                $this->db->select('COALESCE(NULLIF(department, ""), "Unspecified") as department, SUM(amount_paid) as total', false);
                $this->db->from('billing_invoices');
                $this->date_between_where('invoice_date', $this->month_start(), $this->month_end());
                $this->db->group_by('department');
                return $this->db->get()->result_array();
            }

            if ($this->has_table_fields('patient_bill_details', array('bill_treat_id', 'bill_date', 'bill_amt')) && $this->has_table_fields('treatmentdata', array('ID', 'department'))) {
                $this->db->select('COALESCE(NULLIF(treatmentdata.department, ""), "Unspecified") as department, SUM(patient_bill_details.bill_amt) as total', false);
                $this->db->from('patient_bill_details');
                $this->db->join('treatmentdata', 'treatmentdata.ID = patient_bill_details.bill_treat_id', 'left');
                $this->date_between_where('patient_bill_details.bill_date', $this->month_start(), $this->month_end());
                $this->db->group_by('treatmentdata.department');
                return $this->db->get()->result_array();
            }

            return array();
        }, array());
    }

    private function monthly_count_trend($table, $date_field)
    {
        $labels = array();
        $values = array();
        for ($i = 11; $i >= 0; $i--) {
            $start = date('Y-m-01', strtotime("-{$i} months"));
            $end = date('Y-m-t', strtotime($start));
            $labels[] = date('M Y', strtotime($start));
            $values[] = $this->count_between_dates($table, $date_field, $start, $end);
        }
        return array('labels' => $labels, 'values' => $values);
    }

    private function count_by_date($table, $date_field, $date)
    {
        return $this->count_between_dates($table, $date_field, $date, $date);
    }

    private function count_between_dates($table, $date_field, $from, $to)
    {
        return $this->safe_query(function () use ($table, $date_field, $from, $to) {
            if (!$this->has_table_fields($table, array($date_field))) {
                return 0;
            }
            $this->db->from($table);
            $this->date_between_where($date_field, $from, $to);
            return (int)$this->db->count_all_results();
        }, 0);
    }

    private function date_between_where($field, $from, $to)
    {
        $this->db->where('DATE(' . $field . ') >=', $from);
        $this->db->where('DATE(' . $field . ') <=', $to);
    }

    private function table_has_rows($table)
    {
        return $this->safe_query(function () use ($table) {
            if (!$this->db->table_exists($table)) {
                return false;
            }
            $this->db->from($table);
            return $this->db->count_all_results() > 0;
        }, false);
    }

    private function has_table_fields($table, $fields)
    {
        return $this->safe_query(function () use ($table, $fields) {
            if (!$this->db->table_exists($table)) {
                return false;
            }
            foreach ($fields as $field) {
                if (!$this->db->field_exists($field, $table)) {
                    return false;
                }
            }
            return true;
        }, false);
    }

    private function safe_query($callback, $default)
    {
        $debug = $this->db->db_debug;
        $this->db->db_debug = false;
        try {
            $result = call_user_func($callback);
            $this->db->db_debug = $debug;
            return $result === null ? $default : $result;
        } catch (Exception $e) {
            $this->db->db_debug = $debug;
            return $default;
        }
    }

    private function department_key($department)
    {
        $department = trim((string)$department);
        return $department === '' ? 'unspecified' : strtolower($department);
    }

    private function month_start()
    {
        return date('Y-m-01');
    }

    private function month_end()
    {
        return date('Y-m-t');
    }
}
