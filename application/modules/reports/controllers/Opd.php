<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Opd
 *
 * @author Shivaraj
 */
ini_set("memory_limit", "-1");
set_time_limit(0);

//require_once './vendor/autoload.php';

class Opd extends SHV_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports/opd_model');
        $this->layout->navIcon = 'fa fa-users';
        $this->layout->title = "OPD";
    }

    function index() {
        $this->layout->navTitleFlag = true;
        $this->layout->navTitle = "OPD patients";
        $this->layout->navDescr = "Out Patient Department";
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $data = array();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    function get_patients_list() {
        $input_array = array();
        foreach ($this->input->post('search_form') as $search_data) {
            $input_array[$search_data['name']] = $search_data['value'];
        }
        $input_array['start'] = $this->input->post('start');
        $input_array['length'] = $this->input->post('length');
        $input_array['order'] = $this->input->post('order');
        $data = $this->opd_model->get_opd_patients($input_array);
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        //return $return;
        $response = array("recordsTotal" => $data['total_rows'], "recordsFiltered" => $data['found_rows'], 'data' => $data['data'], 'statistics' => $return);
        echo json_encode($response);
    }

    function get_statistics() {
        $input_array = array();
        $input_array['start_date'] = $this->input->post('start_date');
        $input_array['end_date'] = $this->input->post('end_date');
        $input_array['department'] = $this->input->post('department');
        $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "')")->result_array();
        mysqli_next_result($this->db->conn_id); //imp
        echo json_encode(array('statistics' => $return));
    }

    /*
     * OPD EXPORT PDF
     */

    function export_to_pdf() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $data = array();
        $input_array = array();

        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $result = $this->opd_model->get_opd_patients($input_array, true);
        $patients = $result['data'];

        $this->load->library('pdf');
        // $pdf = $mpdf = new \mPDF();
        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $rep_meta_data = array(
            'report_title' => 'OPD REPORT',
        );
        $pdf->setData($rep_meta_data);

        // set document information
        $pdf->setPrintHeader(TRUE);
        // set header and footer fonts
        $pdf->SetFont('helveticaB', '', 9);
// ---------------------------------------------------------
        // add a page
        // set some text to print
        // print a block of text using Write()
        $pdf->SetFillColor(140, 142, 145);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(140, 142, 145);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        $pdf->SetMargins(5, 32, 10, true);
        $pdf->AddPage('L');

        $pdf->cell(15, 6, 'Sl.No', 1, 0, 'C', 1);
        $pdf->cell(15, 6, 'C.OPD', 1, 0, 'C', 1);
        $pdf->cell(10, 6, 'Type', 1, 0, 'C', 1);
        $pdf->cell(40, 6, 'Name', 1, 0, 'C', 1);
        $pdf->cell(10, 6, 'Age', 1, 0, 'C', 1);
        $pdf->cell(10, 6, 'Sex', 1, 0, 'L', 1);
        $pdf->cell(20, 6, 'Place', 1, 0, 'L', 1);

        $pdf->cell(40, 6, 'Diagnosis', 1, 0, 'L', 1);
        $pdf->cell(45, 6, 'Treatment', 1, 0, 'L', 1);
        $pdf->cell(30, 6, 'Doctor', 1, 0, 'L', 1);
        $pdf->cell(30, 6, 'Department', 1, 0, 'L', 1);
        $pdf->cell(20, 6, 'Date', 1, 0, 'L', 1);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('helveticaB', '', 9);
        // Data
        $fill = 0;
        $i = 0;

        foreach ($patients as $patient) {
            $i++;
            $page_break = $pdf->gety();
            if ($pdf->gety() == 176) {
                $pdf->AddPage();
            }
            $x_axis = $pdf->getx();
            $c_width = 20; // cell width 
            $c_height = 12; // cell height 
            $pdf->vcell(15, $c_height, $x_axis, $i, NULL, 'C');
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(15, $c_height, $x_axis, $patient['OpdNo'], NULL, 'C'); // pass all values inside the cell 
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(10, $c_height, $x_axis, $patient['PatType'], NULL, 'C');
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(40, $c_height, $x_axis, $patient['FirstName'] . ' ' . $patient['LastName']);
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(10, $c_height, $x_axis, $patient['Age'], NULL, 'C');
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(10, $c_height, $x_axis, get_short_gender($patient['gender']), NULL, 'C');
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(20, $c_height, $x_axis, $patient['city']);
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(40, $c_height, $x_axis, $patient['diagnosis'], 15);
            $x_axis = $pdf->getx();
            $pdf->vcell(45, $c_height, $x_axis, $patient['Trtment']);
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(30, $c_height, $x_axis, $patient['attndedby'], 16);
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(30, $c_height, $x_axis, $patient['department'], 15);
            $x_axis = $pdf->getx(); // now get current pdf x axis value
            $pdf->vcell(20, $c_height, $x_axis, $patient['CameOn']);
            $pdf->Ln();
            $fill = !$fill;
        }

        ob_end_clean();
        return $pdf->Output('opd_report.pdf', 'I');
    }

    function export_to_pdfcell() {
        //ini_set("memory_limit", '-1');

        ini_set('memory_limit', '2096M'); //var_dump(ini_get('memory_limit'));
        set_time_limit(0);

        $input_array = array();
        foreach ($this->input->post() as $search_data => $val) {
            $input_array[$search_data] = $val;
        }
        $result = $this->opd_model->get_opd_patients($input_array, true);
        $patients = $result['data'];
        $mpdf = new \mPDF();
        $mpdf->simpleTables = true;
        $mpdf->packTableData = true;
        //$html = 'this is mpdf test page';

        /*
          $this->load->library('pdf');
          $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);

          // set document information
          $pdf->setPrintHeader(TRUE);
          // set header and footer fonts
          $pdf->SetFont('Helvetica', '', 10);
          // ---------------------------------------------------------
          // add a page
          // set some text to print
          // print a block of text using Write()
          /*$pdf->SetFillColor(140, 142, 145);
          $pdf->SetTextColor(255);
          $pdf->SetDrawColor(140, 142, 145);
          $pdf->SetLineWidth(0.3);
          $pdf->SetFont('', 'B');
          $pdf->SetMargins(5, 32, 10, true); */
        /* $pdf->SetMargins(5, 32, 10, true);
          $pdf->AddPage('L');


          $pdf->writeHTML($content, true, false, true, false, '');

          $pdf->Output('opd_report.pdf', 'I');
          exit; */
        $content = '<table border = "1" cellspacing = "0" cellpadding = "0">';
        $content .= '<thead>';
        $content .= '<tr>';
        $content .= '<th>Sl.No</th><th>OPD</th><th>Type</th><th>Name</th><th>Age</th><th>Gender</th><th>City</th><th>Diagnosis</th><th>Treatemnet</th>' .
                '<th>Doctor</th><th>Department</th>';
        $content .= '</tr>';
        $content .= '</thead>';
        $content .= '<tbody>';
        $i = 1;
        foreach ($patients as $patient) {
            $content .='<tr>';
            $content .='<td>' . $i . '</td>';
            $content .='<td>' . $patient['OpdNo'] . '</td>';
            $content .='<td>' . $patient['PatType'] . '</td>';
            $content .='<td>' . $patient['FirstName'] . ' ' . $patient['LastName'] . '</td>';
            $content .='<td>' . $patient['Age'] . '</td>';
            $content .='<td>' . $patient['gender'] . '</td>';
            $content .='<td>' . $patient['city'] . '</td>';
            $content .='<td>' . $patient['diagnosis'] . '</td>';
            $content .='<td>' . $patient['Trtment'] . '</td>';
            $content .='<td>' . $patient['attndedby'] . '</td>';
            $content .='<td>' . $patient['department'] . '</td>';
            //$content .='<td>' . $patient['CameOn'] . '</td>';
            $content .='</tr>';
            $i++;
        }
        $content .= '</tbody>';

        $content .= '</table>';
        $mpdf->WriteHTML($content);
        $mpdf->Output(); // opens in browser
    }

}
