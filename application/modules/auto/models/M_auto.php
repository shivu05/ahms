<?php

class M_auto extends CI_Model {

    private $addemailid, $countOld, $CountNew, $comparestr, $totalentry, $patPercent, $patTypeList;
    public $firstname = array(), $midname, $lastname, $age, $gender, $occupation, $address, $city, $department;
    /* Conditional check or constraints added */
    private $femfirstname, $femLastName, $femoccp, $Prasootiage, $childage, $childoccup;
    /*  Department Lists of KAYACHIKITSA********************************************************* */
    private $kayadiagnosis, $kayacomplaints, $kayaprocedure, $kayaTreatment, $kayaDoc, $kayamed;
    /* Department Lists of Shalakya */
    private $shalkayadiagnosis, $shalkayacomplaints, $shalkayaprocedure, $shalkayaTreatment, $shalkayaDoc, $shalkayamed;
    /* Department Lists of Shallya */
    private $shalyadiagnosis, $shalyacomplaints, $shalyaprocedure, $shalyaTreatment, $shalyaDoc, $shalyamed;
    /* Department Lists of tantra */
    private $tantradiagnosis, $tantracomplaints, $tantraprocedure, $tantraTreatment, $tantraDoc, $tantramed;
    /* Department Lists of StringUtility.dept_SwasthaStr */
    private $swasthaDiagnosis, $swasthaComplaints, $swasthaProcedure, $swasthaTreatment, $swasthaDoc, $swasthamed;
    /* Department Lists of panchakarma */
    private $pkdiagnosis, $pkcomplaints, $pkprocedure, $pkTreatment, $pkDoc, $pkmed;
    /* Department Lists of StringUtility.dept_BalaStr */
    private $bcdiagnosis, $bccomplaints, $bcprocedure, $bcTreatment, $bcDoc, $bcmed;
    /* Department Lists of Emergency */
    private $akdiagnosis, $akcomplaints, $akprocedure, $akTreatment, $akDoc, $akmed;
    private $addedby;
    private $countkayachik, $countshalakya, $countshallya, $counttantra, $countSwastha, $countpanchakar, $countbalachik, $countemergency;
    private $tobeentkayachik, $tobeentshalakya, $tobeentshallya, $tobeenttantra, $toBeEntSwastha, $tobeentpanchakar, $tobeentbalachik, $tobeentemergency;
    private $kayachikper, $shalakyaper, $shallyaper, $tantraper, $swasthaPer, $panchakarper, $balachikper, $emergencyper, $shalakyaSubBranch;
    private $_dept_percentage_arr, $_entered_records_arr, $_total_records_to_be_entered, $_treatment_data;
    private $_department_data = array(), $_index = 0, $_pancha_count = 5;

    function __construct() {
        parent::__construct();
        $this->addemailid = "";
        $this->countOld = $this->CountNew = 0;
        $this->comparestr = "Female";
        $this->totalentry = 0;
        $this->patPercent = "";
        $this->patTypeList = array();
        $this->_treatment_data = array();
        $dept_data = $this->db->select('dept_unique_code')->get('deptper')->result_array();
        $dept_data = array_column($dept_data, 'dept_unique_code');
        foreach ($dept_data as $row) {
            $this->_treatment_data[$row] = array();
        }

        /* $this->firstname = array(); */
        $this->midname = $this->lastname = $this->age = $this->gender = $this->occupation = $this->address = $this->city = array();
        $this->department = array();

        /* Conditional check or constraints added */
        $this->femfirstname = $this->femLastName = $this->femoccp = $this->Prasootiage = $this->childage = $this->childoccup = array();

        /* Department Lists of KAYACHIKITSA******************************************************** */
        $this->kayadiagnosis = $this->kayacomplaints = $this->kayaprocedure = $this->kayaTreatment = $this->kayamed = array();
        $this->kayaDoc = "";

        /* Department Lists of Shalakya */
        $this->shalkayadiagnosis = $this->shalkayacomplaints = $this->shalkayaprocedure = $this->shalkayaTreatment = $this->shalkayamed = $this->shalakyaSubBranch = array();
        $this->shalkayaDoc = "";

        $this->shalyadiagnosis = $this->shalyacomplaints = $this->shalyaprocedure = $this->shalyaTreatment = $this->shalyamed = array();
        $this->shalyaDoc = "";

        $this->tantradiagnosis = $this->tantracomplaints = $this->tantraprocedure = $this->tantraTreatment = $this->tantraDoc = $this->tantramed = array();

        $this->swasthaDiagnosis = $this->swasthaComplaints = $this->swasthaProcedure = $this->swasthaTreatment = $this->swasthaDoc = $this->swasthamed = array();

        $this->pkdiagnosis = $this->pkcomplaints = $this->pkprocedure = $this->pkTreatment = $this->pkDoc = $this->pkmed = array();

        $this->bcdiagnosis = $this->bccomplaints = $this->bcprocedure = $this->bcTreatment = $this->bcDoc = $this->bcmed = array();

        $this->akdiagnosis = $this->akcomplaints = $this->akprocedure = $this->akTreatment = $this->akmed = array();
        $this->akDoc = "";

        $this->countkayachik = $this->countshalakya = $this->countshallya = $this->counttantra = $this->countSwastha = $this->countpanchakar = 0;
        $this->countbalachik = $this->countemergency = $this->tobeentkayachik = $this->tobeentshalakya = $this->tobeentshallya = $this->tobeenttantra = 0;
        $this->toBeEntSwastha = $this->tobeentpanchakar = $this->tobeentbalachik = $this->tobeentemergency = $this->kayachikper = $this->shalakyaper = 0;
        $this->shallyaper = $this->tantraper = $this->swasthaPer = $this->panchakarper = $this->balachikper = $this->emergencyper = 0;

        //new code update
        $this->_dept_percentage_arr = $this->_entered_records_arr = array();
        $this->_total_records_to_be_entered = 0;

        $this->addedby = "";
    }

    function auto_master($target, $cdate, $newpatient, $pancha_count) {
        shuffle($this->firstname);

        $query = "SELECT sum(dept_count) as dept_count,A.department,dept.percentage FROM ( 
                    SELECT count(*) as dept_count,department from treatmentdata WHERE 
                        CameOn='$cdate' group by department 
                    UNION ALL 
                    SELECT 0 dept_count,dept_unique_code department FROM deptper dd
                  ) A 
                  JOIN deptper dept ON dept.dept_unique_code=A.department
                  GROUP BY dept_unique_code";
        $dept_counts = $this->db->query($query)->result_array();
        $count = 0;
        foreach ($dept_counts as $dc) {
            $dept_name = strtolower($dc['department']);
            $this->_entered_records_arr[$dept_name]['count'] = $dc['dept_count'];
            $this->_entered_records_arr[$dept_name]['per'] = $dc['percentage'];
            $count = $count + $dc['dept_count'];
        }

        $diff = $target - $count;
        if ($count < $target) {
            if ($diff > 0) {
                $this->insertextradata($diff, $cdate, $target, $newpatient, $pancha_count);
            } else {
                $this->session->set_flashdata('noty_msg', $count . " Records are entered and Target is reached");
            }
        } else {
            $this->session->set_flashdata('noty_msg', 'Already ' . $count . " Records present for " . $cdate);
        }
    }

    private function insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept) {
        if (strtolower(trim($labdisease)) == strtolower("AMAVATA")) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("SANDHIVATA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("MADHUMEHA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("VATARAKTA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ARDITA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ARSHA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ATISARA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("BHAGANDARA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("GARBHINI"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("HYPERTENSION"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("JWARA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("KAMALA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("MUTRAKRUCHRA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PAKSHAGHATA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PANDU"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PRAVAHIKA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("RAJAYAXMA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("RAKTAPRADARA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("STHOULYA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("SWETAPRADARA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("TAMAKA SWASA"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("VANDHYATWA FEMALE"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("VANDHYATWA MALE"))) {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("KASA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("SWASA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PRATISHYAYA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PEENASA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("NASAGATA RAKTAPITTA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("NASAPAKA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("MANYASTAMBH"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ASHMARI"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("KAPHAJA KASA"))) {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ABDOMINAL PAIN"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("4 MONTHS PREGNANCY ANC"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("6 MONTHS PREGNENCY ANC"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ANARTAVA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("5 MONTHS GARBHINI WITH PANDU"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("4 MONTHS PREGNANCY ANC"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("6 MONTHS PREGNENCY ANC"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("8 1/2 MONTHS PREGNANCY FREQUENT PAIN IN LUMBER REGION"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("18 YRS GIRL WITH DYSMENORRHEA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("8 1/2 MONTHS PREGNANCY WITH MILD PET"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("8 MONTHS PREGNANCY WITH SHOTHA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("5 MONTHS AMENORRHEA WITH POLYHYDROMNIOS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("M.2- 2 YRS PRIMARY INFERTILITY"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("6 MONTHS AMENORRHEA WITH IUGR"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("9 MONTHS PREGNANCY WITH PAIN IN ABDOMEN"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("5 MONTHS AMENORRHEA WITH POLYHYDROMNIOS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("6 MONTHS AMENORRHEA WITH IUGR"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("6 MONTHS AMENORRHEA WITH ITCHING"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("8 1/2 MONTHS PREGNANCY"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("2 MONTHS AMENORRHEA WITH VOMITING"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("HYDROCELE"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("APPENDICITIS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("CHOLECYSTITIS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ASCITIS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("CHOLECYSTITIS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("HERNIA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("AMC"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("ANARTAVA(AMENORRHOEA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("STANA SHOTHA(MASTITIS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("IUGR"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("STANARBUDA"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("UDAR SHOOL"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("OLIGOHYDROMNIOS"))) {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if (strtolower(trim($labdisease)) == strtolower(trim("PAKSHAGHATA")) || strtolower(trim($labdisease)) == strtolower(trim("ARDITA")) || strtolower(trim($labdisease)) == strtolower(trim("V.RAKTACHAP")) || strtolower(trim($labdisease)) == strtolower(trim("HYPERTENSION")) || strtolower(trim($labdisease)) == strtolower(trim("AMLAPITTA"))) {
            $this->InsertECGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        }

        /* if (strtolower($dept) == strtolower('Panchakarma')) {
          $j = $this->db->get('panchaprocedure')->num_rows();
          if ($j <= $this->_pancha_count) {
          //$this->InsertPanchaProcedure($last_id, $treatid, $cdate, $labdisease, $docname, $docname);
          }
          } */
    }

    function calculate_new_old_patient_count() {
        $type_arr = array('new', 'old');
        foreach ($this->_entered_records_arr as $dept => $vals) {
            $new_count = 0;
            if ($dept == 'aatyayikachikitsa') {
                $new_count = ($this->_entered_records_arr[$dept]['total']);
            } else {
                $new_count = ($this->_entered_records_arr[$dept]['total'] / 100) * $this->patPercent;
            }
            $this->_entered_records_arr[$dept]['new'] = $new_count;
            $this->_entered_records_arr[$dept]['old'] = $this->_entered_records_arr[$dept]['total'] - $new_count;
            if (($this->_entered_records_arr[$dept]['new'] + $this->_entered_records_arr[$dept]['old']) > $this->_entered_records_arr[$dept]['total']) {
                $i = rand(0, 1);
                $diff = ($this->_entered_records_arr[$dept]['new'] + $this->_entered_records_arr[$dept]['old']) - $this->_entered_records_arr[$dept]['total'];
                $this->_entered_records_arr[$dept][$type_arr[$i]] = $this->_entered_records_arr[$dept][$type_arr[$i]] - $diff;
            }
            //pma($this->_entered_records_arr[$dept]['new']);
            for ($i = 0; $i < $this->_entered_records_arr[$dept]['new']; $i++) {
                $this->_department_data[] = 'new||' . $dept;
            }

            for ($i = 0; $i < $this->_entered_records_arr[$dept]['old']; $i++) {
                $this->_department_data[] = 'old||' . $dept;
            }
            shuffle($this->_department_data);
        }
    }

    function insertextradata($diff, $cdate, $target, $newpatient, $pancha_count) {

        $addemailid = $this->session->userdata('user_name');
        $this->_index = 0;
        $this->patPercent = $newpatient;
        //$querydeptperc = $this->db->get('deptper');
        $this->_pancha_count = $pancha_count;
        //$a = $querydeptperc->result();
        $this->panchakarper = $this->_entered_records_arr['panchakarma']['per'];
        $this->balachikper = $this->_entered_records_arr['balaroga']['per'];
        $this->shalakyaper = $this->_entered_records_arr['shalakya_tantra']['per'];
        $this->kayachikper = $this->_entered_records_arr['kayachikitsa']['per'];
        $this->tantraper = $this->_entered_records_arr['prasooti_&_striroga']['per'];
        $this->swasthaPer = $this->_entered_records_arr['swasthavritta']['per'];
        $this->shallyaper = $this->_entered_records_arr['shalya_tantra']['per'];
        $this->emergencyper = $this->_entered_records_arr['aatyayikachikitsa']['per'];

        $tobeentkayachik = $this->calculate_department_entry_count($this->kayachikper, $target, $this->_entered_records_arr['kayachikitsa']['count']);
        $tobeentshalakya = $this->calculate_department_entry_count($this->shalakyaper, $target, $this->_entered_records_arr['shalakya_tantra']['count']);
        $tobeentshallya = $this->calculate_department_entry_count($this->shallyaper, $target, $this->_entered_records_arr['shalya_tantra']['count']);
        $tobeenttantra = $this->calculate_department_entry_count($this->tantraper, $target, $this->_entered_records_arr['prasooti_&_striroga']['count']);
        $toBeEntSwastha = $this->calculate_department_entry_count($this->swasthaPer, $target, $this->_entered_records_arr['swasthavritta']['count']);
        $tobeentpanchakar = $this->calculate_department_entry_count($this->panchakarper, $target, $this->_entered_records_arr['panchakarma']['count']);
        $tobeentbalachik = $this->calculate_department_entry_count($this->balachikper, $target, $this->_entered_records_arr['balaroga']['count']);
        $tobeentemergency = $this->calculate_department_entry_count($this->emergencyper, $target, $this->_entered_records_arr['aatyayikachikitsa']['count']);

        $this->_entered_records_arr['kayachikitsa']['total'] = $tobeentkayachik;
        $this->_entered_records_arr['shalakya_tantra']['total'] = $tobeentshalakya;
        $this->_entered_records_arr['shalya_tantra']['total'] = $tobeentshallya;
        $this->_entered_records_arr['prasooti_&_striroga']['total'] = $tobeenttantra;
        $this->_entered_records_arr['swasthavritta']['total'] = $toBeEntSwastha;
        $this->_entered_records_arr['panchakarma']['total'] = $tobeentpanchakar;
        $this->_entered_records_arr['balaroga']['total'] = $tobeentbalachik;
        $this->_entered_records_arr['aatyayikachikitsa']['total'] = $tobeentemergency;

        $totalentry = ($tobeentkayachik + $tobeentshalakya + $tobeentshallya + $tobeenttantra + $toBeEntSwastha + $tobeentpanchakar + $tobeentbalachik + $tobeentemergency);

        $this->_total_records_to_be_entered = $totalentry;
        $this->calculate_new_old_patient_count();

        $query = $this->db->query("SELECT * FROM oldtable ORDER BY RAND()");
        $insert_data = $query->result();
        foreach ($insert_data as $row) {
            array_push($this->department, $row->department);
            // pma($insert_data, 1);
            if ($this->_is_female_gender($row->gender)) {
                array_push($this->femfirstname, $row->FirstName);
                array_push($this->femLastName, $row->LastName);

                if ($row->department == 'PRASOOTI_&_STRIROGA') {
                    array_push($this->Prasootiage, $row->Age);
                }

                if ($row->department != 'BALAROGA') {
                    array_push($this->femoccp, $row->occupation);
                }
            } else {
                array_push($this->firstname, $row->FirstName);
                array_push($this->lastname, $row->LastName);
                if ($row->department != "BALAROGA") {
                    array_push($this->age, $row->Age);
                    array_push($this->occupation, $row->occupation);
                }
            }

            if ($row->department == "BALAROGA") {
                array_push($this->childage, $row->Age);
                array_push($this->childoccup, $row->occupation);
            }
            array_push($this->midname, $row->MidName);
            array_push($this->gender, $row->gender);
            array_push($this->address, $row->address);
            array_push($this->city, $row->city);

            if ($row->department == "KAYACHIKITSA") {
                $this->_treatment_data["KAYACHIKITSA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->kayaDoc = $this->get_day_doctor($this->input->post('cdate'), "KAYACHIKITSA");
                array_push($this->kayamed, $row->medicines);
            }

            if ($row->department == "SHALYA_TANTRA") {
                $this->_treatment_data["SHALYA_TANTRA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );

                $this->shalyaDoc = $this->get_day_doctor($this->input->post('cdate'), "SHALYA_TANTRA");
            }

            if ($row->department == "SHALAKYA_TANTRA") {
                $this->_treatment_data["SHALAKYA_TANTRA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->shalkayaDoc = $this->get_day_doctor($this->input->post('cdate'), "SHALAKYA_TANTRA");
            }

            if ($row->department == "PRASOOTI_&_STRIROGA") {
                $this->_treatment_data["PRASOOTI_&_STRIROGA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->tantraDoc = $this->get_day_doctor($this->input->post('cdate'), "PRASOOTI_&_STRIROGA");
            }

            if ($row->department == "SWASTHAVRITTA") {
                $this->_treatment_data["SWASTHAVRITTA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->swasthaDoc = $this->get_day_doctor($this->input->post('cdate'), "SWASTHAVRITTA");
            }

            if ($row->department == "PANCHAKARMA") {
                $this->_treatment_data["PANCHAKARMA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->pkDoc = $this->get_day_doctor($this->input->post('cdate'), "PANCHAKARMA");
            }

            if ($row->department == "BALAROGA") {
                $this->_treatment_data["BALAROGA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->bcDoc = $this->get_day_doctor($this->input->post('cdate'), "BALAROGA");
            }

            if ($row->department == "AATYAYIKACHIKITSA") {
                $this->_treatment_data["AATYAYIKACHIKITSA"][] = array(
                    'diagnosis' => $row->diagnosis,
                    'complaints' => $row->complaints,
                    'treatment' => $row->Trtment,
                    'procedure' => $row->procedures,
                    'medicines' => $row->medicines,
                    'sub_dept' => $row->sub_dept,
                    'gender' => $row->gender
                );
                $this->akDoc = $this->get_day_doctor($this->input->post('cdate'), "AATYAYIKACHIKITSA");
            }
        }// end of old for
        $this->shuffle();

        //main logic starts
        $this->db->trans_start();
        $i = 1;
        for ($entryloop = 0; $entryloop < $diff; $entryloop++) {
            $i++;
            if ($i > $totalentry) {
                break;
            }
            $k = explode('||', $this->_department_data[$entryloop]);

            if (strtolower($k[1]) == strtolower("PRASOOTI_&_STRIROGA")) {
                $this->enter_prasooti_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("BALAROGA")) {
                $this->enter_balaroga_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("KAYACHIKITSA")) {
                $this->enter_kayachikitsa_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("SHALAKYA_TANTRA")) {
                $this->enter_shalakya_tantra_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("SHALYA_TANTRA")) {
                $this->enter_shalya_tantra_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("SWASTHAVRITTA")) {
                $this->enter_swasthavritta_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("PANCHAKARMA")) {
                $this->enter_panchakarma_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("AATYAYIKACHIKITSA")) {
                $this->enter_aatyayikachikitsa_patient_details($k, $cdate);
            }
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('noty_msg', @$target . " Records added successfully");
    }

    private function _get_diagnosis_by_gender($dg_arr = array(), $gender = '') {
        if ($this->_is_female_gender($gender)) {
            $new_arr = array_filter($dg_arr, function($ar) {
                return ($ar['gender'] == 'Female');
            });
        } else {
            $new_arr = array_filter($dg_arr, function($ar) {
                return ($ar['gender'] == 'Male');
            });
        }

        return $dg_arr[array_rand($new_arr)];
    }

    public function add_to_pharmacy($treat_id) {
        $this->db->where('ID', $treat_id);
        $this->db->where('department <>', 'SWASTHAVRITTA');
        $treatment_data = $this->db->get('treatmentdata')->row_array();
        if (!empty($treatment_data)) {
            $digits = 4;
            $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $products = explode(',', $treatment_data['Trtment']);
            $n = count($products);
            for ($i = 0; $i < $n; $i++) {
                if (strlen(trim($products[$i])) > 0) {
                    $med_arr = array(
                        'opdno' => $treatment_data['OpdNo'],
                        'billno' => $four_digit_random_number,
                        'batch' => 947,
                        'date' => $treatment_data['CameOn'],
                        'qty' => 1,
                        'product' => trim($products[$i]),
                        'treat_id' => $treatment_data['ID']
                    );
                    $this->db->insert('sales_entry', $med_arr);
                }
            }
        }// end if
    }

    private function _get_random_data($index = 0, $array = null) {
        if ($array) {
            if ($index >= count($array)) {
                $index = 0;
                $this->shuffle($array);
                return $array[$index];
            } else {
                return $array[$index];
            }
        }
        return null;
    }

    private function _check_valid_index($index, $dept = '') {
        if ($index >= count($this->femfirstname) ||
                ($index >= count($this->femLastName)) ||
                ($index >= count($this->femoccp)) || ( $index >= count($this->age)) ||
                ($dept == 'BALAROGA' && $index >= count($this->childage)) ||
                ($dept == 'PRASOOTI_&_STRIROGA' && $index >= count($this->Prasootiage))) {
            $index = 0;
            $this->shuffle();
            return $index;
        }
        return $index;
    }

    private function _check_list_index($dept_arr, $index) {
        if ((count($dept_arr) < $index ) || trim($dept_arr[$index]['diagnosis']) == "") {
            $index = 0;
            shuffle($dept_arr);
            return $index;
        }
        return $index;
    }

    private function _is_female_gender($gender = '') {
        $sex = strtoupper(trim($gender));
        return ($sex == 'FEMALE') ? true : false;
    }

    private function add_old_patient_data($dept = NULL, $date = NULL) {
        $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='$dept' AND NOT (CameOn = '" . $date . "') ORDER BY RAND() LIMIT 1 ";
        $result = $this->db->query($query)->row_array();
        if (!empty($result)) {
            $insert_data = array(
                "deptOpdNo" => $result['deptOpdNo'],
                "Trtment" => $result['Trtment'],
                "OpdNo" => $result['OpdNo'],
                "diagnosis" => $result['diagnosis'],
                "complaints" => $result['complaints'],
                "department" => $dept,
                "procedures" => $result['procedures'],
                "InOrOutPat" => $result['InOrOutPat'],
                "attndedby" => $result['attndedby'],
                "CameOn" => $date,
                "attndedon" => $date,
                "AddedBy" => $result['AddedBy'],
                "patType" => "Old Patient"
            );
            $this->db->insert('treatmentdata', $insert_data);
            $treatid = $this->db->insert_id();

            $this->add_to_pharmacy($treatid);

            $last_id = $result['OpdNo'];
            $labdisease = $result['diagnosis'];
            $docname = $result['AddedBy'];
            //insert_lexu
            $this->insert_lexu($last_id, $treatid, $date, $labdisease, $docname, $dept);
            if ($dept == 'SHALAKYA_TANTRA') {
                $shalakya_insert_data = array(
                    'OpdNo' => $result['OpdNo'],
                    'IpNo' => NULL,
                    'treat_id' => $treatid
                );
                $this->add_to_kriyaklpa($shalakya_insert_data);
            }
        }
    }

    function add_to_kriyaklpa($insert_data) {
        return $this->db->insert('kriyakalpa', $insert_data);
    }

    function enter_prasooti_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'PRASOOTI_&_STRIROGA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->getDeptNoForNewPatient($dept_name);

            $this->_index = $this->_check_valid_index($this->_index, $dept_name);

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $this->femfirstname[$this->_index],
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $this->femLastName[$this->_index],
                    "Age" => $this->Prasootiage[$this->_index],
                    "gender" => "Female",
                    "occupation" => $this->femoccp[$this->_index],
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], 'Female');

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->tantraDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => 'PRASOOTI_&_STRIROGA',
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                //insert_lexu()
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'PRASOOTI_&_STRIROGA');
                $this->_index++;
            }
        } else {
            $this->add_old_patient_data('PRASOOTI_&_STRIROGA', $cdate);
            $this->_index++;
        }
    }

    function enter_balaroga_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'BALAROGA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->getDeptNoForNewPatient($dept_name);
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;
                $gender = $this->gender[$this->_index];
                $sex = "Male";
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $sex = "Female";
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->childage[$this->_index],
                    "gender" => $sex,
                    "occupation" => 'School',
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->bcDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient");
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();
                $this->add_to_pharmacy($treatid);
                $labdisease = trim($diagnosis);
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_kayachikitsa_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'KAYACHIKITSA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;

                $gender = $this->gender[$this->_index];
                $sex = $gender;
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    // $sex = "Female";
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->kayaDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);
                $labdisease = trim($diagnosis);
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);

                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_shalakya_tantra_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SHALAKYA_TANTRA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;

                $gender = $this->gender[$this->_index];
                $sex = "Male";
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    $sex = "Female";
                }

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sub_dept" => $treatment_details['sub_dept']
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();


                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->shalkayaDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient",
                    "sub_department" => $treatment_details['sub_dept']
                );
                $this->db->insert('treatmentdata', $treatment_arr);

                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;


                $insert_data = array(
                    'OpdNo' => $last_id,
                    'IpNo' => NULL,
                    'treat_id' => $treatid
                );
                $this->db->insert('kriyakalpa', $insert_data);
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_shalya_tantra_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SHALYA_TANTRA';

        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;

                $gender = $this->gender[$this->_index];
                $sex = "Male";
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    $sex = "Female";
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->_get_random_data($this->_index, $this->age),
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $docname = $this->shalyaDoc;
                $diagnosis = $treatment_details['diagnosis'];
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);

                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);

                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_swasthavritta_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SWASTHAVRITTA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('SWASTHAVRITTA');

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;
                $gender = $this->gender[$this->_index];
                $sex = "Male";
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    $sex = "Female";
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'SWASTHAVRITTA'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);
                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->swasthaDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => 'SWASTHAVRITTA',
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_panchakarma_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'PANCHAKARMA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;
                $gender = $this->gender[$this->_index];
                $sex = $gender;
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    //$sex = "Female";
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->pkDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);

                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_aatyayikachikitsa_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'AATYAYIKACHIKITSA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $this->_index = $this->_check_valid_index($this->_index, $dept_name);
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum + 1;
                $gender = $this->gender[$this->_index];
                $sex = 'Male';
                if (!$this->_is_female_gender($gender)) {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $occ = $this->femoccp[$this->_index];
                    $sex = "Female";
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => '',
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $this->_get_random_data($this->_index, $this->address),
                    "city" => $this->_get_random_data($this->_index, $this->city),
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $this->_index = $this->_check_list_index($this->_treatment_data[$dept_name], $this->_index);
                $treatment_details = $this->_get_diagnosis_by_gender($this->_treatment_data[$dept_name], $sex);

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->akDoc;
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $treatment_details['treatment'],
                    "OpdNo" => $last_id,
                    "diagnosis" => $diagnosis,
                    "complaints" => $treatment_details['complaints'],
                    "department" => $dept_name,
                    "procedures" => $treatment_details['procedure'],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $docname,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $docname,
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        }
    }

    function shuffle() {
        shuffle($this->firstname);
        shuffle($this->midname);
        shuffle($this->lastname);
        shuffle($this->age);
        shuffle($this->gender);
        shuffle($this->occupation);
        shuffle($this->femfirstname);
        shuffle($this->femLastName);
        shuffle($this->femoccp);
        shuffle($this->Prasootiage);
        shuffle($this->childage);
        shuffle($this->childoccup);
    }

    function calculate_department_entry_count($deptper, $targetforentry, $entereddata) {
        $count = round(((($deptper * $targetforentry) / 100) - $entereddata));
        return ($count > 0) ? $count : 0;
    }

    function getDeptNoForNewPatient($deptString) {
        try {
            $query = "SELECT deptOpdNo FROM treatmentdata WHERE LOWER(department)=LOWER('" . $deptString . "') ORDER BY deptOpdNo+0 DESC LIMIT 0,1";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    return $row->deptOpdNo;
                }
            } else {
                return 0;
            }
        } catch (Exception $e) {
            echo "Error in getDeptNoForNewPatient";
            return null;
        }
    }

    function InsertLabRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        return true;
        $query = "SELECT * FROM ref_lab_reg_tab WHERE lab_disease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
        $is_inserted = false;
        foreach ($query->result() as $val) {
            $treatment_arr2 = array(
                "OpdNo" => $opdno,
                "refDocName" => $docname,
                "testName" => $val->lab_test,
                "testDate" => $camedate,
                "treatID" => $treatid,
                "testrange" => $val->lab_ref_range,
                "labdisease" => $val->lab_disease,
                "testvalue" => $val->lab_test_val,
            );
            $is_inserted = $this->db->insert('labregistery', $treatment_arr2);
        }
        return $is_inserted;
    }

    function InsertXrayRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $query = "SELECT * FROM xray_ref WHERE diesease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
        $is_inserted = false;
        foreach ($query->result() as $val) {
            $treatment_arr2 = array(
                "OpdNo" => $opdno,
                "refDocName" => $docname,
                "partOfXray" => $val->xraypart,
                "xrayDate" => $camedate,
                "refDate" => $camedate,
                "treatID" => $treatid,
                "filmsize" => $val->filmsize
            );
            $is_inserted = $this->db->insert('xrayregistery', $treatment_arr2);
        }
        return $is_inserted;
    }

    function InsertUSGRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "usgDate" => $camedate,
            "refDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('usgregistery', $treatment_arr2);
    }

    function InsertECGRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "ecgDate" => $camedate,
            "refDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('ecgregistery', $treatment_arr2);
    }

    function InsertPanchaProcedure($opdno, $treatid, $camedate, $labdisease, $docname) {
        $query = "SELECT * FROM panchakarma_ref WHERE disease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
        $is_inserted = false;
        foreach ($query->result() as $val) {
            $treatment_arr2 = array(
                "opdno" => $opdno,
                "docname" => $docname,
                "disease" => $val->disease,
                "date" => $camedate,
                "treatid" => $treatid,
                "treatment" => $val->treatment,
                "procedure" => $val->procedure
            );
            $is_inserted = $this->db->insert('panchaprocedure', $treatment_arr2);
        }
        return $is_inserted;
    }

    function get_day_doctor($date = "", $dept = "") {
        $query = "SELECT u.ID,user_name FROM users u 
            JOIN doctorsduty d ON u.ID=d.doc_id JOIN week_days wd ON d.day=wd.week_id 
            WHERE UPPER(u.user_department)=UPPER(replace('$dept',' ','_')) AND wd.week_day=DAYNAME(STR_TO_DATE('$date','%Y-%m-%d')) ORDER BY RAND() LIMIT 1;";
        $result = $this->db->query($query)->row_array();
        return $result['user_name'];
    }

    function get_data($conditions, $export_flag = false) {
        $return = array();
        $columns = array('ID', 'FirstName', 'LastName', 'Age', 'gender', 'occupation', 'address',
            'city', 'Mobileno', 'diagnosis', 'complaints', 'department', 'procedures', 'Trtment', 'notes', 'AddedBy',
            'entrydate', 'medicines', 'sub_dept');

        $where_cond = " WHERE 1=1";

        $limit = '';
        if (!$export_flag) {
            $start = (isset($conditions['start'])) ? $conditions['start'] : 0;
            $length = (isset($conditions['length'])) ? $conditions['length'] : 25;
            $limit = ' LIMIT ' . $start . ',' . ($length);
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);
        foreach ($conditions as $col => $val) {
            $val = trim($val);
            if ($val !== '') {
                switch ($col):
                    case 'keyword':
                        $where_cond .= " AND (FirstName like '%$val%' OR gender like '%$val%' OR diagnosis like '%$val%'
                            OR department like '%$val%' OR Trtment like '%$val%')";
                        break;
                    default:
                        $where_cond .= " AND $col = '$val'";
                endswitch;
            }
        }

        $query = "SELECT @a:=@a+1 serial_number, " . join(',', $columns) . " FROM oldtable o,
        (SELECT @a:= 0) AS a  $where_cond ORDER BY o.ID ASC";
        $result = $this->db->query($query . ' ' . $limit);
        $return['data'] = $result->result_array();
        $return['found_rows'] = $this->db->query($query)->num_rows();
        $return['total_rows'] = $this->db->query('SELECT * FROM oldtable x')->num_rows();
        return $return;
    }

}
