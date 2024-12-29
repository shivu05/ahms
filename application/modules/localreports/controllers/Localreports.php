<?php

require_once './vendor/autoload.php';

class Localreports extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function _fetch_year() {
        $year = $this->rbac->get_selected_year();
        return substr(base64_decode($year), -4);
    }

    private function removefiles($path) {
        if ($path != '') {
            $files = glob($path); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }
    }

    public function opd() {
        $year = $this->_fetch_year();
        if (trim($year) != '') {
            //$this->removefiles('./public/OPD/*');
            $this->load->model('reports/opd_model');
            $this->load->helper('mpdf');
            $this->load->helper('download');
            $this->layout->title = 'OPD Report';
            ini_set("memory_limit", "-1");
            set_time_limit(0);
            ini_set("pcre.backtrack_limit", "5000000");
            $input_array['start_date'] = $year . '-01-01';
            $input_array['end_date'] = $year . '-12-31';
            $current_data = date('Y-m-d');
            if ($input_array['end_date'] < $current_data) {
                
            } else {
                $input_array['end_date'] = $current_data;
            }
            $query = $this->db->query("select max(CameOn) max_date from treatmentdata")->row_array();
            $input_array['end_date'] = $query['max_date'];
            $input_array['department'] = 1;

            $return['total_rows'] = $this->db->query('SELECT ID FROM treatmentdata t JOIN patientdata p ON t.OpdNo=p.OpdNo WHERE CameOn >="' . $input_array['start_date'] . '" AND CameOn <="' . $input_array['end_date'] . '" and trim(t.CameOn) != ""')->num_rows();
            $total = $return['total_rows'];
            // echo $total;exit;
            $j = $k = 0;
            $html = array();
            $step = 0;
            while ($total > $step) {
                $j++;
                $query = 'SELECT t.sequence as serial_number,t.monthly_sid as msd,t.OpdNo,t.deptOpdNo,t.PatType,CONCAT(COALESCE(FirstName,"")," ",COALESCE(LastName,"")) as name,Age,gender,address,city,t.diagnosis,t.Trtment,t.AddedBy,
                (REPLACE((t.department),"_"," ")) department,CameOn,d.ref_room ref_dept 
                FROM treatmentdata t JOIN patientdata p JOIN deptper d 
                WHERE t.OpdNo=p.OpdNo AND t.department=d.dept_unique_code 
                AND CameOn >="' . $input_array['start_date'] . '" AND CameOn <="' . $input_array['end_date'] . '" AND trim(CameOn) != "" ORDER BY t.ID LIMIT ' . $step . ',2000'; //LIMIT ' . $step . ',2000

                $result['data'] = $this->db->query($query)->result_array();
                $step = $step + 2000;
                $data['opd_patients'] = $result['data'];
                $data['department'] = $input_array['department'];
                $return = NULL;
                if ($total < $step) {
                    $return = $this->db->query("call get_opd_patients_count('" . $input_array['department'] . "','" . $input_array['start_date'] . "','" . $input_array['end_date'] . "','')")->result_array();
                    mysqli_next_result($this->db->conn_id); //imp
                }
                $data['opd_stats'] = $return;
                $this->layout->data = $data;
                $this->layout->headerFlag = false;
                $html[] = $this->layout->render(array('view' => 'localreports/opd/export_opd'), true);
                //if($j==2)
                //pma($html,1);
            }

            $print_dept = ($input_array['department'] == 1) ? "CENTRAL" : strtoupper($input_array['department']);
            $title = array(
                'report_title' => 'OPD REGISTER',
                'department' => $print_dept,
                'start_date' => format_date($input_array['start_date']),
                'end_date' => format_date($input_array['end_date'])
            );

            $file_name = './public/OPD/' . SHORT_NAME . '_OPD_REPORT_' . $year;
            $this->localgenerate_pdf($html, 'L', $title, $file_name, true, true, 'F');
            echo '<p style="color:green;font-weight:bold;">Report exported successfully</p>';
            force_download($file_name . '.pdf', NULL);
            exit;
        } else {
            echo '<p style="color:red;font-weight:bold;">Invalid request please contact admin</p>';
        }
    }

    function localgenerate_pdf($html, $page_orientation = 'L', $title = NULL, $filename = 'ahms_report', $header_flag = true, $footer_flag = true, $output = "D") {
        $CI = & get_instance();
        ini_set("pcre.backtrack_limit", "10000000");
        $config = $CI->db->get('config');
        $config = $config->row_array();
        $orientation = (empty($pstyle)) ? $config["printing_style"] : $pstyle;
        //Html page design
        $header = '<div class="row"><div class="col first">'
                . '<img src="data:image/png;base64,' . base64_encode($config['logo_img']) . '" width="80px" height="80px"/>
                    </div>
                    <div class="col half">
                    <h2 align="center">' . $config["college_name"] . '</h2>
                    </div></div><hr>';
        $footer = '<table width="100%" style="color:#000000;border-top-style: solid;font-size:10px;">
    <tr>
        <td width="33%"></td>
        <td width="33%" align="center">{PAGENO}</td>
        <td width="33%" style="text-align: right;">©Ayush softwares </td>
    </tr>
</table>';

        $top_heading = "";
        if (!empty($title)) {
            $department = (isset($title['department'])) ? '<b>DEPARTMENT</b>:' . $title['department'] . '' : '';
            $report_title = (isset($title['report_title'])) ? $title['report_title'] : '';
            $from_date = (isset($title['start_date'])) ? '<b>FROM:</b>' . $title['start_date'] : '';
            $to_date = (isset($title['end_date'])) ? '<b>TO: </b>' . $title['end_date'] : '';

            $top_heading .= '<table width="100%" style="border: none;">';
            $top_heading .= '<tr>';
            $top_heading .= '<td width="33%">' . $department . '</td>
            <td width="33%" text-align:"center"; align="center"><h2>' . $report_title . '</h2></td>
                <td width="33%" style="text-align:center;">' . $from_date . '&nbsp;' . $to_date . '</td>';
            $top_heading .= '</tr>';
            $top_heading .= '</table><br/><br/>';
            $top_heading .= @$title['extradata'];
        }


        //$html = '<body>' . $top_heading . $html . '</body>';
        $mt = 2;
        if ($header_flag) {
            $mt = 38;
        }
        $configs = array(
            'mode' => 'utf-8',
            'orientation' => $page_orientation,
            'margin_top' => $mt
        );
        ob_clean();
        $mpdf = new \Mpdf\Mpdf($configs);
        $mpdf->SetTitle('VHMS');
        $mpdf->SetCreator('Ayush Softwares');
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->SetCompression(true);
        $mpdf->table_error_report_param = true;
        //$css = '<style type="text/css">' . file_get_contents(FCPATH . '/assets/css/print_table.css') . '</style>';
        $css = file_get_contents(FCPATH . '/assets/css/print_table.css');
        if ($header_flag) {
            $mpdf->SetHTMLHeader($header);
        }
        if ($footer_flag) {
            $mpdf->SetHTMLFooter($footer);
        }
        $mpdf->use_kwt = true;
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->useSubstitutions = false;
        $mpdf->simpleTables = true;
        $mpdf->packTableData = false;

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        if (!empty($html)) {
            $i = 0;
            foreach ($html as $content) {
                if ($i == 0) {
                    $content = $top_heading . $content;
                }
                $mpdf->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);
                $i++;
                // break;
            }
        }
        //$page_count = $mpdf->page;
        $mpdf->Output($filename . '.pdf', $output);
    }

    function export_opd_sales() {
        echo 'Export started<br/>';
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $start_date = '2024-01-01';
        $end_date = '2024-12-20';
        $data["is_print"] = true;
        $dept_condition = '';

        $query = "select @a:=@a+1 serial_number,t.OpdNo,s.treat_id,group_concat(s.product) as product,group_concat(s.batch) as batch, group_concat(s.qty) as qty,
                group_concat(s.id) as product_ids,ucfirst(REPLACE((t.department),'_',' ')) dept,t.deptOpdNo,t.PatType,CONCAT(p.FirstName,' ',p.LastName) as name,
                t.AddedBy,t.CameOn ,p.Age,p.gender
                from sales_entry s 
                JOIN treatmentdata t ON s.treat_id=t.ID 
                JOIN patientdata p ON t.OpdNo=p.OpdNo, (SELECT @a:= 0) AS a
                WHERE s.date >= '" . $start_date . "' AND s.date <= '" . $end_date . "' AND ipdno is null $dept_condition
                group by s.treat_id ";

        $result = $this->db->query("select count(*) as num from (" . $query . ") A")->row();
        //echo $query;exit;
        //$result->num_rows();
        $total = $result->num;
        $step = $j = 0;
        $html = array();
        while ($total > $step) {
            $j++;
            $resultset = $this->db->query($query . ' ' . 'LIMIT ' . $step . ',2000');
            $step = $step + 2000;
            $data["patient"] = $resultset->result();
            $html[] = $this->load->view('localreports/sales/opd_pharmacy_report_vw', $data, true);
        }
        $print_dept = "CENTRAL";
        $title = array(
            'report_title' => 'OPD MEDICINE DISPENCE REGISTER',
            'department' => $print_dept,
            'start_date' => format_date($start_date),
            'end_date' => format_date($end_date)
        );
        //echo count($html);exit;
        $file_name = './public/PHARMACY/' . SHORT_NAME . '_OPD_PHARMA_REPORT';
        $this->localgenerate_pdf($html, 'L', $title, $file_name, true, true, 'F');
        echo 'Task completed<br/>';
        echo '<p style="color:green;font-weight:bold;">Report exported successfully</p>';
        force_download($file_name . '.pdf', NULL);
        exit;
        exit;
    }

    function kshara() {
        echo 'KSHARA';
        $query = "select * from vhms_main.data_reference_table";
        $result_set = $this->db->query($query)->result_array();
        //pma($result_set);
        foreach ($result_set as $row) {
            $query = "INSERT INTO ksharsutraregistery (OpdNo, ksharsType, ksharsDate, treatId, ksharaname, surgeon, asssurgeon, anaesthetic, anesthesia_type) select OpdNo,ksharsType,CameOn, a.ID, ksharaname, surgeon, asssurgeon, anaesthetic, anesthesia_type 
                from treatmentdata a,vhms_main.ksharasutra_reference b where a.diagnosis=b.diagnosis and a.CameOn='" . $row['current_date'] . "' and a.department='SHALYA_TANTRA' order by rand() limit " . $row['kshara'] . " ";
            $this->db->query($query);
            echo $row['current_date'] . '-' . $row['kshara'] . '-- added<br/>';
        }
    }

    function other_proc() {
        echo "OTHER PROC";
        $query = "select * from vhms_main.data_reference_table";
        $result_set = $this->db->query($query)->result_array();
        //pma($result_set);
        foreach ($result_set as $row) {
            $query = "INSERT INTO other_procedures_treatments (OpdNo, therapy_name, physician, treat_id, start_date, end_date) 
                select OpdNo,therapy_name, 'Dr Physician',a.ID,a.CameOn,DATE_ADD(a.CameOn, INTERVAL b.diff DAY)  end_date 
                from treatmentdata a,vhms_main.other_proc_reference b where a.diagnosis=b.diagnosis and a.CameOn='" . $row['current_date'] . "' and a.department='PRASOOTI_&_STRIROGA' order by rand() limit " . $row['other_proc'] . " ";
            $this->db->query($query);
            echo $row['current_date'] . '-' . $row['other_proc'] . '-- added<br/>';
        }
    }
}
