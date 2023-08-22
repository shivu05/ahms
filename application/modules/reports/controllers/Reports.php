<?php

/**
 * Description of Reports
 *
 * @author shivarajkumar.badige
 */
class Reports extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "IPD";
        $this->load->model('reports/patient_reports');
    }

    public function show_lab_report($id = null) {
        $lab_data = $this->patient_reports->fetch_patient_lab_details($id);
        $testname = explode(",", $lab_data['testName']);
        $lab_test_type = explode(",", $lab_data['lab_test_type']);
        $lab_cat_name = explode(",", $lab_data['lab_test_cat']);
        $testrange = explode("#", $lab_data['testrange']);
        $testvalue = explode("#", $lab_data['testvalue']);
        $table = '';
        $table .= '<table class="table table-bordered" style="width:100%"><tr><td>Name: ' . $lab_data['name'] . '</td><td>Age: ' . $lab_data['Age'] . '</td><td>Gender: ' . $lab_data['gender'] . '</td></tr>';
        $table .= '<tr><td>C.OPD: ' . $lab_data['OpdNo'] . '</td><td>C.IPD: ' . $lab_data['ipdno'] . '</td><td>Date : ' . $lab_data['testDate'] . '</td></tr>';
        $table .= '</table>';
        $category_list = array();
        $sub_category_list = array();
        $i = 0;
        $tr = '<br/><table nowrap="nowrap" class="table table-bordered" style="width:100%;background:#d3d3d3;">';
        $tr .= '<tr><td style="width:33%;">Test</td>';
        $tr .= '<td style="width:33%;">Values</td>';
        $tr .= '<td style="width:33%;">Range</td>';
        $tr .= '</tr>';
        $tr .= '</table>';
        $table .= $tr;
        foreach ($lab_cat_name as $row) {
            if (!in_array($row, $category_list)) {
                $table .= '<h3 style="text-align:center;">' . $row . '</h3>';
                array_push($category_list, $row);
            }
            if (!in_array($lab_test_type[$i], $sub_category_list)) {
                $table .= '<h5 style="text-align:left;">' . $lab_test_type[$i] . '</h5>';
                array_push($sub_category_list, $lab_test_type[$i]);
            }
            $tr = '<table nowrap="nowrap" class="table table-bordered" style="width:100%">';
            $tr .= '<tr><td style="width:33%;">' . $testname[$i] . '</td>';
            $tr .= '<td style="width:33%;">' . $testvalue[$i] . '</td>';
            $tr .= '<td style="width:33%;">' . $testrange[$i] . '</td>';
            $tr .= '</tr>';
            $tr .= '</table>';
            $i++;
            $table .= $tr;
        }

        $title = array(
            'report_title' => 'LAB REPORT',
        );
        $c_date = date('d_m_Y');
        generate_pdf($table, 'P', $title, 'LAB_REPORT_' . $c_date, true, true, 'I');
        exit;
    }
}
