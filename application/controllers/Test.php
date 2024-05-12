<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Test
 *
 * @author Abhilasha
 */
class Test extends SHV_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function uuid_gen() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $this->load->library('uuid');
//        echo $this->uuid->v3('AnSh');
//        echo '<br/>';
//        echo $this->uuid->v4();
//        echo '<br/>';
//        echo $this->uuid->v5('AnSh');
        //$this->db->query("ALTER TABLE patientdata CHANGE COLUMN `sid` `sid` VARCHAR(200) NULL DEFAULT NULL");
        $result = $this->db->get('patientdata')->result_array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $uid = $this->uuid->v5('AnSh');
                echo $uid . '-' . $row['OpdNo'] . '<br/>';
                $this->db->query("UPDATE patientdata SET sid='" . $uid . "' WHERE OpdNo='" . $row['OpdNo'] . "'");
                $uid = '';
            }
        }
    }

    function print_pdf() {
        $this->load->helper('mpdf');
        $config = $this->db->get('config');
        $config = $config->row_array();
        $header = '<table width="100%" style="border:1px red solid"><tr>
                    <td width="10%"><img src="' . base_url('assets/your_logo.png') . '" width="80" height="80" alt="logo"></td>
                    <td width="90%"><h2 align="center">' . $config["college_name"] . '</h2></td>
                  </tr></table>';
        $pat_table .= $header;
        $pat_table .= '<table class="table"  width="100%"><tr><td align="center" width="100%" style="font-size:14pt">Out Patient Card</td></tr></table><br/>';
        $pat_table .= "<table class='' width='100%' style='font-size:10pt'>";
        $pat_table .= "<tr>";
        $pat_table .= "<td  width='50%'><b>OPD NO:</b> 1</td>";
        $pat_table .= "<td width='50%'><b>DATE:</b> 08-03-2021</td>";
        $pat_table .= "</tr>";
        $pat_table .= "<tr>";
        $pat_table .= "<td width='50%'><b>Name:</b> SHATHAJ BANU</td>";
        $pat_table .= "<td width='50%'><b>AGE:</b> 24</td>";
        $pat_table .= "</tr>";
        $pat_table .= "<tr>";
        $pat_table .= "<td width='50%'><b>ADDRESS:</b>  AMARAPURA</td>";
        $pat_table .= "<td width='50%'><b>DEPARTMENT:</b> STREEROGA & PRASOOTI</td>";
        $pat_table .= "</tr>";
        $pat_table .= "<tr>";
        $pat_table .= "<td width='50%'><b>DOCTOR:</b>  Dr.Manjunatha D</td>";
        $pat_table .= "</tr>";
        $pat_table .= "</table><hr/>";

        $treat_table = "<table class='' width='100%' style='font-size:12pt'>";
        $treat_table .= "<tr>";
        $treat_table .= "<td width='50%'>Pradhana Vedana:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td width='50%'>Anubandhi Vedana:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td width='50%'>Poorva Vyadhi Vrittanta:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td width='50%'>Vaiyaktika Vrittanta:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>a) Appetite:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>b) Bowel:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>c) Micturation:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>d) Sleep:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>e) Diet: Veg/Mixed </td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>f) Habits:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>Menstrual History: </td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>Treatment History: </td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>General Examination: </td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>Pulse:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>BP:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>Resp Rate:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>Temp:</td>";
        $treat_table .= "<td style='font-size:10pt' width='50%'>Weight:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>Systemic Examination:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>Investigations:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "<tr>";
        $treat_table .= "<td style='font-size:12pt' width='50%'>Diagnosis:</td>";
        $treat_table .= "</tr>";
        $treat_table .= "</table><hr style='border-top: 2px dotted black'/>";
        $html = $pat_table . $treat_table . '</div>';
        generate_pdf($html, 'P', '', false, false);
        exit;
    }

    function update_docs() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        $doctors = $this->db->get('doctors_list')->result_array();
        if (!empty($doctors)) {
            foreach ($doctors as $row) {
                pma($row);
                $this->db->query("update patientdata set AddedBy='" . $row['doctor_name'] . "' where dept=''");
            }
        }
    }

    function exe_db() {
        $db_list = $this->db->get('vhms_main.db_list')->result_array();
        echo 'STARTED<br/>----------------------------------------------------------------------------------------------</br>';
        if (!empty($db_list)) {
            foreach ($db_list as $row) {
                echo 'for DB: ' . $row['db_name'] . '--!';
                /* $perm_params = array(
                  'perm_code' => 'BED_MANAGEMENT',
                  'perm_desc' => 'Bed management',
                  'perm_order' => 10,
                  'perm_label' => 10,
                  'perm_parent' => 2,
                  'perm_class' => '',
                  'perm_url' => 'manage-beds',
                  'perm_status' => 'Active',
                  'perm_icon' => 'fa fa-list',
                  'last_updated_id' => 1
                  );
                  $perm_master = $this->db->insert('perm_master', $perm_params);
                  $insert_id = $this->db->insert_id();
                  $role_params = array(
                  'role_id' => 1,
                  'perm_id' => $insert_id,
                  'status' => 'Active',
                  'last_updated_id' => 1,
                  'access_perm' => 2
                  );
                  $role_perm = $this->db->insert('role_perm', $role_params); */
                $alter = $this->db->query("CREATE TABLE `fumigation_register` (
                                            `id` INT NOT NULL AUTO_INCREMENT,
                                            `fumigation_mothod` VARCHAR(200) NULL,
                                            `chemical_used` VARCHAR(250) NULL,
                                            `start_time` VARCHAR(45) NULL,
                                            `end_time` VARCHAR(45) NULL,
                                            `ot_number` VARCHAR(45) NULL,
                                            `neutralization` VARCHAR(200) NULL,
                                            `superviser_name` VARCHAR(100) NULL,
                                            `remarks` VARCHAR(200) NULL,
                                            `f_date` DATE NULL,
                                            PRIMARY KEY (`id`))");
                if ($alter):
                    echo 'Executed on ' . $row['db_name'] . ' at ' . date('dd-mm-YYYY hh:mm:ss') . '</br>';
                else:
                    echo 'Failed on ' . $row['db_name'] . ' at ' . date('dd-mm-YYYY hh:mm:ss') . '</br>';
                endif;

                sleep(1);
            }
        }
        echo 'ENDED<br/>----------------------------------------------------------------------------------------------</br>';
        echo 'Total count is: ' . count($db_list);
        exit;
    }
}
