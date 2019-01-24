<?php

class m_auto extends CI_Model {

    private $addemailid;
    private $countOld;
    private $CountNew;
    private $comparestr;
    private $totalentry;
    private $patPercent;
    private $patTypeList;
    public $firstname = array();
    private $midname;
    private $lastname;
    private $age;
    private $gender;
    private $occupation;
    private $address;
    private $city;
    private $department;
    /* Conditional check or constraints added */
    private $femfirstname;
    private $femLastName;
    private $femoccp;
    private $Prasootiage;
    private $childage;
    private $childoccup;
    /*  Department Lists of KAYACHIKITSA********************************************************* */
    private $kayadiagnosis;
    private $kayacomplaints;
    private $kayaprocedure;
    private $kayaTreatment;
    private $kayaDoc;
    private $kayamed;
    /* Department Lists of Shalakya */
    private $shalkayadiagnosis;
    private $shalkayacomplaints;
    private $shalkayaprocedure;
    private $shalkayaTreatment;
    private $shalkayaDoc;
    private $shalkayamed;
    /* Department Lists of Shallya */
    private $shalyadiagnosis;
    private $shalyacomplaints;
    private $shalyaprocedure;
    private $shalyaTreatment;
    private $shalyaDoc;
    private $shalyamed;
    /* Department Lists of tantra */
    private $tantradiagnosis;
    private $tantracomplaints;
    private $tantraprocedure;
    private $tantraTreatment;
    private $tantraDoc;
    private $tantramed;
    /* Department Lists of StringUtility.dept_SwasthaStr */
    private $swasthaDiagnosis;
    private $swasthaComplaints;
    private $swasthaProcedure;
    private $swasthaTreatment;
    private $swasthaDoc;
    private $swasthamed;
    /* Department Lists of panchakarma */
    private $pkdiagnosis;
    private $pkcomplaints;
    private $pkprocedure;
    private $pkTreatment;
    private $pkDoc;
    private $pkmed;
    /* Department Lists of StringUtility.dept_BalaStr */
    private $bcdiagnosis;
    private $bccomplaints;
    private $bcprocedure;
    private $bcTreatment;
    private $bcDoc;
    private $bcmed;
    /* Department Lists of Emergency */
    private $akdiagnosis;
    private $akcomplaints;
    private $akprocedure;
    private $akTreatment;
    private $akDoc;
    private $akmed;
    private $addedby;
    private $countkayachik;
    private $countshalakya;
    private $countshallya;
    private $counttantra;
    private $countSwastha;
    private $countpanchakar;
    private $countbalachik;
    private $countemergency;
    private $tobeentkayachik;
    private $tobeentshalakya;
    private $tobeentshallya;
    private $tobeenttantra;
    private $toBeEntSwastha;
    private $tobeentpanchakar;
    private $tobeentbalachik;
    private $tobeentemergency;
    private $kayachikper;
    private $shalakyaper;
    private $shallyaper;
    private $tantraper;
    private $swasthaPer;
    private $panchakarper;
    private $balachikper;
    private $emergencyper;
    private $shalakyaSubBranch;
    private $selected_date;
    private $_dept_percentage_arr;
    private $_entered_records_arr;
    private $_total_records_to_be_entered;

    function __construct() {
        parent::__construct();
        $this->addemailid = "";
        $this->countOld = 0;
        $this->CountNew = 0;
        $this->comparestr = "Female";
        $this->totalentry = 0;
        $this->patPercent = "";
        $this->patTypeList = array();
        /* $this->firstname = array(); */
        $this->midname = array();
        $this->lastname = array();
        $this->age = array();
        $this->gender = array();
        $this->occupation = array();
        $this->address = array();
        $this->city = array();
        $this->department = array();
        /* Conditional check or constraints added */
        $this->femfirstname = array();
        $this->femLastName = array();
        $this->femoccp = array();
        $this->Prasootiage = array();
        $this->childage = array();
        $this->childoccup = array();

        /* Department Lists of KAYACHIKITSA******************************************************** */
        $this->kayadiagnosis = array();
        $this->kayacomplaints = array();
        $this->kayaprocedure = array();
        $this->kayaTreatment = array();
        $this->kayaDoc = array();
        $this->kayamed = array();

        /* Department Lists of Shalakya */
        $this->shalkayadiagnosis = array();
        $this->shalkayacomplaints = array();
        $this->shalkayaprocedure = array();
        $this->shalkayaTreatment = array();
        $this->shalkayaDoc = array();
        $this->shalkayamed = array();
        $this->shalakyaSubBranch = array();


        $this->shalyadiagnosis = array();
        $this->shalyacomplaints = array();
        $this->shalyaprocedure = array();
        $this->shalyaTreatment = array();
        $this->shalyaDoc = array();
        $this->shalyamed = array();


        $this->tantradiagnosis = array();
        $this->tantracomplaints = array();
        $this->tantraprocedure = array();
        $this->tantraTreatment = array();
        $this->tantraDoc = array();
        $this->tantramed = array();


        $this->swasthaDiagnosis = array();
        $this->swasthaComplaints = array();
        $this->swasthaProcedure = array();
        $this->swasthaTreatment = array();
        $this->swasthaDoc = array();
        $this->swasthamed = array();


        $this->pkdiagnosis = array();
        $this->pkcomplaints = array();
        $this->pkprocedure = array();
        $this->pkTreatment = array();
        $this->pkDoc = array();
        $this->pkmed = array();


        $this->bcdiagnosis = array();
        $this->bccomplaints = array();
        $this->bcprocedure = array();
        $this->bcTreatment = array();
        $this->bcDoc = array();
        $this->bcmed = array();


        $this->akdiagnosis = array();
        $this->akcomplaints = array();
        $this->akprocedure = array();
        $this->akTreatment = array();
        $this->akDoc = array();
        $this->akmed = array();

        $this->addedby = "";

        $this->countkayachik = 0;
        $this->countshalakya = 0;
        $this->countshallya = 0;
        $this->counttantra = 0;
        $this->countSwastha = 0;
        $this->countpanchakar = 0;
        $this->countbalachik = 0;
        $this->countemergency = 0;
        $this->tobeentkayachik = 0;
        $this->tobeentshalakya = 0;
        $this->tobeentshallya = 0;
        $this->tobeenttantra = 0;
        $this->toBeEntSwastha = 0;
        $this->tobeentpanchakar = 0;
        $this->tobeentbalachik = 0;
        $this->tobeentemergency = 0;
        $this->kayachikper = 0;
        $this->shalakyaper = 0;
        $this->shallyaper = 0;
        $this->tantraper = 0;
        $this->swasthaPer = 0;
        $this->panchakarper = 0;
        $this->balachikper = 0;
        $this->emergencyper = 0;
        //new code update
        $this->_dept_percentage_arr = array();
        $this->_entered_records_arr = array();
        $this->_total_records_to_be_entered = 0;
    }

    function auto_master($target, $cdate, $newpatient) {
        $fnamess = shuffle($this->firstname);
        $comparedate = $this->db->get_where('treatmentdata', array('CameOn' => $cdate));
        $a = $comparedate->result();
        $query = "SELECT sum(dept_count) as dept_count,A.department,dept.percentage FROM ( 
                    SELECT count(*) as dept_count,department from treatmentdata WHERE 
                        CameOn='$cdate' group by department 
                    UNION ALL 
                    SELECT 0 dept_count,department FROM deptper d
                  ) A 
                  JOIN deptper dept ON dept.department=A.department
                  GROUP BY department";
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
                $this->insertextradata($diff, $cdate, $target, $newpatient);
            } else {
                $this->session->set_flashdata('noty_msg', $count . " records are entered and Target is reached");
            }
        }
    }

    private function insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept) {

        if ($labdisease == "AMAVATA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "SANDHIVATA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "MADHUMEHA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "VATARAKTA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ARDITA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ARSHA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ATISARA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "BHAGANDARA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "GARBHINI") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "HYPERTENSION") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "JWARA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "KAMALA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "MUTRAKRUCHRA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PAKSHAGHATA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PANDU") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PRAVAHIKA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "RAJAYAXMA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "RAKTAPRADARA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "STHOULYA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "SWETAPRADARA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "TAMAKA SWASA") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "VANDHYATWA FEMALE") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "VANDHYATWA MALE") {
            $this->InsertLabRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "KASA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "SWASA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PRATISHYAYA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PEENASA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "NASAGATA RAKTAPITTA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "NASAPAKA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "MANYASTAMBH") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ASHMARI") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "KAPHAJA KASA") {
            $this->InsertXrayRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ABDOMINAL PAIN") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "4 MONTHS PREGNANCY ANC") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "6 MONTHS PREGNENCY ANC") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ANARTAVA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "5 MONTHS GARBHINI WITH PANDU") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "4 MONTHS PREGNANCY ANC") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "6 MONTHS PREGNENCY ANC") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "8 1/2 MONTHS PREGNANCY FREQUENT PAIN IN LUMBER REGION") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "18 YRS GIRL WITH DYSMENORRHEA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "8 1/2 MONTHS PREGNANCY WITH MILD PET") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "8 MONTHS PREGNANCY WITH SHOTHA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "5 MONTHS AMENORRHEA WITH POLYHYDROMNIOS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "M.2- 2 YRS PRIMARY INFERTILITY") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "6 MONTHS AMENORRHEA WITH IUGR") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "9 MONTHS PREGNANCY WITH PAIN IN ABDOMEN") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "5 MONTHS AMENORRHEA WITH POLYHYDROMNIOS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "6 MONTHS AMENORRHEA WITH IUGR") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "6 MONTHS AMENORRHEA WITH ITCHING") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "8 1/2 MONTHS PREGNANCY") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "2 MONTHS AMENORRHEA WITH VOMITING") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "HYDROCELE") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "APPENDICITIS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "CHOLECYSTITIS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ASCITIS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "CHOLECYSTITIS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "HERNIA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "AMC") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "ANARTAVA(AMENORRHOEA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "STANA SHOTHA(MASTITIS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "IUGR") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "STANARBUDA") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "UDAR SHOOL") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "OLIGOHYDROMNIOS") {
            $this->InsertUSGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        } else if ($labdisease == "PAKSHAGHATA" || $labdisease == "ARDITA" || $labdisease == "V.RAKTACHAP" || $labdisease == "HYPERTENSION" || $labdisease == "AMLAPITTA") {
            $this->InsertECGRegistry($last_id, $treatid, $cdate, $labdisease, $docname);
        }

        if (strtolower($dept) == strtolower('Panchakarma')) {
            $this->InsertPanchaProcedure($last_id, $treatid, $cdate, $labdisease, $docname, $docname);
        }
    }

    private $_department_data = array();

    function calculate_new_old_patient_count() {
        $type_arr = array('new', 'old');
        foreach ($this->_entered_records_arr as $dept => $vals) {
            $new_count = 0;
            if ($dept == 'aatyayikachikitsa') {
                $new_count = ($this->_entered_records_arr[$dept]['total']);
            } else {
                $new_count = ($this->_entered_records_arr[$dept]['total'] / 100) * $this->patPercent;
            }
            $this->_entered_records_arr[$dept]['new'] = round($new_count);
            $this->_entered_records_arr[$dept]['old'] = round($this->_entered_records_arr[$dept]['total'] - $new_count);
            if (($this->_entered_records_arr[$dept]['new'] + $this->_entered_records_arr[$dept]['old']) > $this->_entered_records_arr[$dept]['total']) {
                $i = rand(0, 1);
                $diff = ($this->_entered_records_arr[$dept]['new'] + $this->_entered_records_arr[$dept]['old']) - $this->_entered_records_arr[$dept]['total'];
                $this->_entered_records_arr[$dept][$type_arr[$i]] = $this->_entered_records_arr[$dept][$type_arr[$i]] - $diff;
            }

            for ($i = 0; $i < $this->_entered_records_arr[$dept]['new']; $i++) {
                $this->_department_data[] = 'new_' . $dept;
            }

            for ($i = 0; $i < $this->_entered_records_arr[$dept]['old']; $i++) {
                $this->_department_data[] = 'old_' . $dept;
            }
            shuffle($this->_department_data);
        }
    }

    private $_index = 0;

    function insertextradata($diff, $cdate, $target, $newpatient) {

        $addemailid = $this->session->userdata('user_email');
        $this->patPercent = $newpatient;
        $querydeptperc = $this->db->get('deptper');
        $a = $querydeptperc->result();
        $this->panchakarper = $this->_entered_records_arr['panchakarma']['per'];
        $this->balachikper = $this->_entered_records_arr['balaroga']['per'];
        $this->shalakyaper = $this->_entered_records_arr['shalakya tantra']['per'];
        $this->kayachikper = $this->_entered_records_arr['kayachikitsa']['per'];
        $this->tantraper = $this->_entered_records_arr['prasooti & striroga']['per'];
        $this->swasthaPer = $this->_entered_records_arr['swasthavritta']['per'];
        $this->shallyaper = $this->_entered_records_arr['shalya tantra']['per'];
        $this->emergencyper = $this->_entered_records_arr['aatyayikachikitsa']['per'];

        $tobeentkayachik = $this->CalculateForEntry($this->kayachikper, $target, $this->_entered_records_arr['kayachikitsa']['count']);
        $tobeentshalakya = $this->CalculateForEntry($this->shalakyaper, $target, $this->_entered_records_arr['shalakya tantra']['count']);
        $tobeentshallya = $this->CalculateForEntry($this->shallyaper, $target, $this->_entered_records_arr['shalya tantra']['count']);
        $tobeenttantra = $this->CalculateForEntry($this->tantraper, $target, $this->_entered_records_arr['prasooti & striroga']['count']);
        $toBeEntSwastha = $this->CalculateForEntry($this->swasthaPer, $target, $this->_entered_records_arr['swasthavritta']['count']);
        $tobeentpanchakar = $this->CalculateForEntry($this->panchakarper, $target, $this->_entered_records_arr['panchakarma']['count']);
        $tobeentbalachik = $this->CalculateForEntry($this->balachikper, $target, $this->_entered_records_arr['balaroga']['count']);
        $tobeentemergency = $this->CalculateForEntry($this->emergencyper, $target, $this->_entered_records_arr['aatyayikachikitsa']['count']);

        $this->_entered_records_arr['kayachikitsa']['total'] = $tobeentkayachik;
        $this->_entered_records_arr['shalakya tantra']['total'] = $tobeentshalakya;
        $this->_entered_records_arr['shalya tantra']['total'] = $tobeentshallya;
        $this->_entered_records_arr['prasooti & striroga']['total'] = $tobeenttantra;
        $this->_entered_records_arr['swasthavritta']['total'] = $toBeEntSwastha;
        $this->_entered_records_arr['panchakarma']['total'] = $tobeentpanchakar;
        $this->_entered_records_arr['balaroga']['total'] = $tobeentbalachik;
        $this->_entered_records_arr['aatyayikachikitsa']['total'] = $tobeentemergency;

        $totalentry = ($tobeentkayachik + $tobeentshalakya + $tobeentshallya + $tobeenttantra + $toBeEntSwastha + $tobeentpanchakar + $tobeentbalachik + $tobeentemergency);

        $this->_total_records_to_be_entered = $totalentry;
        $this->calculate_new_old_patient_count();

        $query = $this->db->query("SELECT * from oldtable ORDER BY RAND()");
        $insert_data = $query->result();

        foreach ($insert_data as $row) {
            array_push($this->department, $row->department);

            if ($row->gender == $this->comparestr) {
                array_push($this->femfirstname, $row->FirstName);
                array_push($this->femLastName, $row->LastName);

                if ($row->department == 'Prasooti & Striroga') {
                    array_push($this->Prasootiage, $row->Age);
                }

                if ($row->department != 'Balaroga') {
                    array_push($this->femoccp, $row->occupation);
                }
            } else {
                array_push($this->firstname, $row->FirstName);
                array_push($this->lastname, $row->LastName);
                if ($row->department != "Balaroga") {
                    array_push($this->age, $row->Age);
                    array_push($this->occupation, $row->occupation);
                }
            }

            if ($row->department == "Balaroga") {
                array_push($this->childage, $row->Age);
                array_push($this->childoccup, $row->occupation);
            }
            array_push($this->midname, $row->MidName);
            array_push($this->gender, $row->gender);
            array_push($this->address, $row->address);
            array_push($this->city, $row->city);

            if ($row->department == "KayaChikitsa") {
                array_push($this->kayadiagnosis, $row->diagnosis);
                array_push($this->kayacomplaints, $row->complaints);
                array_push($this->kayaprocedure, $row->procedures);
                array_push($this->kayaTreatment, $row->Trtment);
                array_push($this->kayaDoc, $this->get_day_doctor($this->input->post('cdate'), "KayaChikitsa"));
                array_push($this->kayamed, $row->medicines);
            }

            if ($row->department == "Shalya Tantra") {
                array_push($this->shalyadiagnosis, $row->diagnosis);
                array_push($this->shalyacomplaints, $row->complaints);
                array_push($this->shalyaprocedure, $row->procedures);
                array_push($this->shalyaTreatment, $row->Trtment);
                array_push($this->shalyaDoc, $this->get_day_doctor($this->input->post('cdate'), "Shalya Tantra"));
                array_push($this->shalyamed, $row->medicines);
            }

            if ($row->department == "Shalakya Tantra") {
                array_push($this->shalkayadiagnosis, $row->diagnosis);
                array_push($this->shalkayacomplaints, $row->complaints);
                array_push($this->shalkayaprocedure, $row->procedures);
                array_push($this->shalkayaTreatment, $row->Trtment);
                array_push($this->shalkayaDoc, $this->get_day_doctor($this->input->post('cdate'), "Shalakya Tantra"));
                array_push($this->shalkayamed, $row->medicines);
                array_push($this->shalakyaSubBranch, $row->sub_dept);
            }

            if ($row->department == "Prasooti & Striroga") {
                array_push($this->tantradiagnosis, $row->diagnosis);
                array_push($this->tantracomplaints, $row->complaints);
                array_push($this->tantraprocedure, $row->procedures);
                array_push($this->tantraTreatment, $row->Trtment);
                array_push($this->tantraDoc, $this->get_day_doctor($this->input->post('cdate'), "Prasooti & Striroga"));
                array_push($this->tantramed, $row->medicines);
            }

            if ($row->department == "Swasthavritta") {
                array_push($this->swasthaDiagnosis, $row->diagnosis);
                array_push($this->swasthaComplaints, $row->complaints);
                array_push($this->swasthaProcedure, $row->procedures);
                array_push($this->swasthaTreatment, $row->Trtment);
                array_push($this->swasthaDoc, $this->get_day_doctor($this->input->post('cdate'), "Swasthavritta"));
                array_push($this->swasthamed, $row->medicines);
            }

            if ($row->department == "Panchakarma") {
                array_push($this->pkdiagnosis, $row->diagnosis);
                array_push($this->pkcomplaints, $row->complaints);
                array_push($this->pkprocedure, $row->procedures);
                array_push($this->pkTreatment, $row->Trtment);
                array_push($this->pkDoc, $this->get_day_doctor($this->input->post('cdate'), "Panchakarma"));
                array_push($this->pkmed, $row->medicines);
            }

            if ($row->department == "Balaroga") {
                array_push($this->bcdiagnosis, $row->diagnosis);
                array_push($this->bccomplaints, $row->complaints);
                array_push($this->bcprocedure, $row->procedures);
                array_push($this->bcTreatment, $row->Trtment);
                array_push($this->bcDoc, $this->get_day_doctor($this->input->post('cdate'), "Balaroga"));
                array_push($this->bcmed, $row->medicines);
            }

            if ($row->department == "Aatyayikachikitsa") {
                array_push($this->akdiagnosis, $row->diagnosis);
                array_push($this->akcomplaints, $row->complaints);
                array_push($this->akprocedure, $row->procedures);
                array_push($this->akTreatment, $row->Trtment);
                array_push($this->akDoc, $this->get_day_doctor($this->input->post('cdate'), "Aatyayikachikitsa"));
                array_push($this->akmed, $row->medicines);
            }
        }

        $this->shuffle();

        //main logic starts
        $depts = array_keys($this->_entered_records_arr);
        $this->db->trans_start();
        for ($entryloop = 0; $entryloop < $diff; $entryloop++) {
            $k = explode('_', $this->_department_data[$entryloop]);

            if (strtolower($k[1]) == strtolower("Prasooti & Striroga")) {
                $this->enter_prasooti_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Balaroga")) {
                $this->enter_balaroga_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("KayaChikitsa")) {
                $this->enter_kayachikitsa_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Shalakya Tantra")) {
                $this->enter_shalakya_tantra_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Shalya Tantra")) {
                $this->enter_shalya_tantra_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Swasthavritta")) {
                $this->enter_swasthavritta_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Panchakarma")) {
                $this->enter_panchakarma_patient_details($k, $cdate);
            }

            if (strtolower($k[1]) == strtolower("Aatyayikachikitsa")) {
                $this->enter_aatyayikachikitsa_patient_details($k, $cdate);
            }
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('noty_msg', "records added successfully");
    }

    function enter_prasooti_patient_details($arr, $cdate) {

        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->getDeptNoForNewPatient('Prasooti & Striroga');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }
            $prage = '';
            if ($this->_index >= sizeof($this->Prasootiage)) {
                $this->_index = 0;
                shuffle($this->Prasootiage);
                $prage = $this->Prasootiage[$this->_index];
            } else {
                $prage = $this->Prasootiage[$this->_index];
            }

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $this->femfirstname[$this->_index],
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $this->femLastName[$this->_index],
                    "Age" => $prage,
                    "gender" => "Female",
                    "occupation" => $this->femoccp[$this->_index],
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Prasooti & Striroga'
                );

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $this->tantraTreatment[$this->_index],
                    "OpdNo" => $last_id,
                    "diagnosis" => $this->tantradiagnosis[$this->_index],
                    "complaints" => $this->tantracomplaints[$this->_index],
                    "department" => 'Prasooti & Striroga',
                    "procedures" => $this->tantraprocedure[$this->_index],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $this->tantraDoc[$this->_index],
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $this->tantraDoc[$this->_index],
                    "patType" => "New Patient");
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $tantramedicine = explode(',', $this->tantramed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'batch' => 947,
                            'date' => $cdate,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }

                $labdisease = trim($this->tantradiagnosis[$this->_index]);
                $docname = $this->tantraDoc[$this->_index];

                //insert_lexu()
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Prasooti & Striroga');
                $this->_index++;
            }
        } else {
            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Prasooti & Striroga' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);
            foreach ($query->result() as $val) {
                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Prasooti & Striroga',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->AddedBy,
                    "patType" => "Old Patient");
                $this->db->insert('treatmentdata', $treatment_arr2);
                $last_id = $val->OpdNo;
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->AddedBy;
                //insert_lexu
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Prasooti & Striroga');
            }
            $this->_index++;
        }
    }

    function enter_balaroga_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->childage))) {
                $this->_index = 0;
            }
            $getDeptNum = $this->getDeptNoForNewPatient('Balaroga');
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->childage[$this->_index],
                    "gender" => $gen,
                    "occupation" => 'School',
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Balaroga'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $this->bcTreatment[$this->_index],
                    "OpdNo" => $last_id,
                    "diagnosis" => $this->bcdiagnosis[$this->_index],
                    "complaints" => $this->bccomplaints[$this->_index],
                    "department" => 'Balaroga',
                    "procedures" => $this->bcprocedure[$this->_index],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $this->bcDoc[$this->_index],
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $this->bcDoc[$this->_index],
                    "patType" => "New Patient");
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $this->bcmed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid,
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->bcdiagnosis[$this->_index];
                $docname = $this->bcDoc[$this->_index];

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Balaroga');

                $this->_index++;
            }
        } else {
            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Balaroga' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);
            foreach ($query->result() as $val) {
                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Balaroga',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->AddedBy,
                    "patType" => "Old Patient");
                $this->db->insert('treatmentdata', $treatment_arr2);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->AddedBy;

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Balaroga');
            }
            $this->_index++;
        }
    }

    function enter_kayachikitsa_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('KayaChikitsa');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->age)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'KayaChikitsa'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $this->kayaTreatment[$this->_index],
                    "OpdNo" => $last_id,
                    "diagnosis" => $this->kayadiagnosis[$this->_index],
                    "complaints" => $this->kayacomplaints[$this->_index],
                    "department" => 'KayaChikitsa',
                    "procedures" => $this->kayaprocedure[$this->_index],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $this->kayaDoc[$this->_index],
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $this->kayaDoc[$this->_index],
                    "patType" => "New Patient");
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $tantramedicine = explode(',', $this->kayamed[$this->_index]);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                $sizeoftest = sizeof($tantramedicine);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->kayadiagnosis[$this->_index];
                $docname = $this->kayaDoc[$this->_index];

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'KayaChikitsa');

                $this->_index++;
            }
        } else {
            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='KayaChikitsa' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);

            foreach ($query->result() as $val) {

                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'KayaChikitsa',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->attndedby,
                    "patType" => "Old Patient");

                $this->db->insert('treatmentdata', $treatment_arr2);

                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);

                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid,
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }

                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->attndedby;
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'KayaChikitsa');
            }
            $this->_index++;
        }
    }

    function enter_shalakya_tantra_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('Shalakya Tantra');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ( $this->_index >= sizeof($this->age)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Shalakya Tantra',
                    "sub_dept" => $this->shalakyaSubBranch[$this->_index]
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                if (sizeof($this->shalkayaTreatment) < $this->_index) {
                    $this->_index = 0;
                    $this->m_auto->shuffle();
                } else {

                    $treatment_arr = array(
                        "deptOpdNo" => $deptNum,
                        "Trtment" => $this->shalkayaTreatment[$this->_index],
                        "OpdNo" => $last_id,
                        "diagnosis" => $this->shalkayadiagnosis[$this->_index],
                        "complaints" => $this->shalkayacomplaints[$this->_index],
                        "department" => 'Shalakya Tantra',
                        "procedures" => $this->shalkayaprocedure[$this->_index],
                        "InOrOutPat" => "FollowUp",
                        "attndedby" => $this->shalkayaDoc[$this->_index],
                        "CameOn" => $cdate,
                        "attndedon" => $cdate,
                        "AddedBy" => $this->shalkayaDoc[$this->_index],
                        "patType" => "New Patient");
                    $this->db->insert('treatmentdata', $treatment_arr);
                }
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $this->shalkayamed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->shalkayadiagnosis[$this->_index];
                $docname = $this->shalkayaDoc[$this->_index];

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Shalakya Tantra');
                $this->_index++;
            }
        } else {

            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Shalakya Tantra' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";

            $query = $this->db->query($query);

            foreach ($query->result() as $val) {

                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Shalakya Tantra',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->attndedby,
                    "patType" => "Old Patient");
                $this->db->insert('treatmentdata', $treatment_arr2);
                $treatid = $this->db->insert_id();
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->attndedby;
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Shalakya Tantra');
            }//for

            $this->_index++;
        }
    }

    function enter_shalya_tantra_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('Shalya Tantra');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->age)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Shalya Tantra'
                );

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                if (sizeof($this->shalyaTreatment) < $this->_index) {
                    $this->_index = 0;
                    $this->m_auto->shuffle();
                } else {
                    $treatment_arr = array(
                        "deptOpdNo" => $deptNum,
                        "Trtment" => $this->shalyaTreatment[$this->_index],
                        "OpdNo" => $last_id,
                        "diagnosis" => $this->shalyadiagnosis[$this->_index],
                        "complaints" => $this->shalyacomplaints[$this->_index],
                        "department" => 'Shalya Tantra',
                        "procedures" => $this->shalyaprocedure[$this->_index],
                        "InOrOutPat" => "FollowUp",
                        "attndedby" => $this->shalyaDoc[$this->_index],
                        "CameOn" => $cdate,
                        "attndedon" => $cdate,
                        "AddedBy" => $this->shalyaDoc[$this->_index],
                        "patType" => "New Patient");
                    $this->db->insert('treatmentdata', $treatment_arr);
                }
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $this->shalyamed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid,
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->shalyadiagnosis[$this->_index];
                $docname = $this->shalyaDoc[$this->_index];

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Shalya Tantra');

                $this->_index++;
            }
        } else {

            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Shalya Tantra' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);
            foreach ($query->result() as $val) {
                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Shalya Tantra',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->attndedby,
                    "patType" => "Old Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr2);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->attndedby;

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Shalya Tantra');
            }

            $this->_index++;
        }
    }

    function enter_swasthavritta_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('Swasthavritta');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->age)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Swasthavritta'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                if (sizeof($this->swasthaTreatment) < $this->_index) {
                    $this->_index = 0;
                    $this->m_auto->shuffle();
                }

                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $this->swasthaTreatment[$this->_index],
                    "OpdNo" => $last_id,
                    "diagnosis" => $this->swasthaDiagnosis[$this->_index],
                    "complaints" => $this->swasthaComplaints[$this->_index],
                    "department" => 'Swasthavritta',
                    "procedures" => $this->swasthaProcedure[$this->_index],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $this->swasthaDoc[$this->_index],
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $this->swasthaDoc[$this->_index],
                    "patType" => "New Patient");
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $tantramedicine = explode(',', $this->swasthamed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                /* for ($i = 0; $i < $sizeoftest; $i++) {
                  $med_arr = array(
                  'opdno' => $last_id,
                  'billno' => $four_digit_random_number,
                  'date' => $cdate,
                  'batch' => 947,
                  'qty' => 1,
                  'product' => $tantramedicine[$i],
                  'treat_id' => $treatid,
                  );
                  $this->db->insert('sales_entry', $med_arr);
                  } */
                $labdisease = $this->swasthaDiagnosis[$this->_index];
                $docname = $this->swasthaDoc[$this->_index];
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Swasthavritta');
                $this->_index++;
            }
        } else {

            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Swasthavritta' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);
            foreach ($query->result() as $val) {
                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Swasthavritta',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->attndedby,
                    "patType" => "Old Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr2);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                /* for ($i = 0; $i < $sizeoftest; $i++) {
                  $med_arr = array(
                  'opdno' => $val->OpdNo,
                  'billno' => $four_digit_random_number,
                  'date' => $cdate,
                  'batch' => 947,
                  'qty' => 1,
                  'product' => $tantramedicine[$i],
                  'treat_id' => $treatid
                  );
                  $this->db->insert('sales_entry', $med_arr);
                  } */
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->attndedby;

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Swasthavritta');
            }

            $this->_index++;
        }
    }

    function enter_panchakarma_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {

            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('Panchakarma');
            if (($this->_index > sizeof($this->femfirstname)) || ($this->_index > sizeof($this->femLastName)) || ($this->_index > sizeof($this->age)) || ($this->_index > sizeof($this->femoccp))) {
                $this->_index = 0;
            }

            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }

                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Panchakarma'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                if (sizeof($this->pkTreatment) < $this->_index) {
                    $this->_index = 0;
                    $this->m_auto->shuffle();
                } else {
                    $treatment_arr = array(
                        "deptOpdNo" => $deptNum,
                        "Trtment" => $this->pkTreatment[$this->_index],
                        "OpdNo" => $last_id,
                        "diagnosis" => $this->pkdiagnosis[$this->_index],
                        "complaints" => $this->pkcomplaints[$this->_index],
                        "department" => 'Panchakarma',
                        "procedures" => $this->pkprocedure[$this->_index],
                        "InOrOutPat" => "FollowUp",
                        "attndedby" => $this->pkDoc[$this->_index],
                        "CameOn" => $cdate,
                        "attndedon" => $cdate,
                        "AddedBy" => $this->pkDoc[$this->_index],
                        "patType" => "New Patient"
                    );
                    $this->db->insert('treatmentdata', $treatment_arr);
                }
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $this->pkmed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->pkdiagnosis[$this->_index];
                $docname = $this->pkDoc[$this->_index];
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Panchakarma');
                $this->_index++;
            }
        } else {
            $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='Panchakarma' AND NOT (CameOn = '" . $cdate . "') ORDER BY RAND() LIMIT 1 ";
            $query = $this->db->query($query);
            foreach ($query->result() as $val) {
                $treatment_arr2 = array(
                    "deptOpdNo" => $val->deptOpdNo,
                    "Trtment" => $val->Trtment,
                    "OpdNo" => $val->OpdNo,
                    "diagnosis" => $val->diagnosis,
                    "complaints" => $val->complaints,
                    "department" => 'Panchakarma',
                    "procedures" => $val->procedures,
                    "InOrOutPat" => $val->InOrOutPat,
                    "attndedby" => $val->attndedby,
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $val->attndedby,
                    "patType" => "Old Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr2);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $val->Trtment);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $val->OpdNo,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $val->diagnosis;
                $last_id = $val->OpdNo;
                $docname = $val->attndedby;
                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Panchakarma');
            }

            $this->_index++;
        }
    }

    function enter_aatyayikachikitsa_patient_details($arr, $cdate) {
        $addemailid = $this->session->userdata('user_email');
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('Aatyayikachikitsa');
            if (($this->_index >= sizeof($this->femfirstname)) || ($this->_index >= sizeof($this->femLastName)) || ($this->_index >= sizeof($this->age)) || ($this->_index >= sizeof($this->femoccp))) {
                $this->_index = 0;
            }
            if ($getDeptNum != "" || strlen($getDeptNum) > 0) {
                $deptNum = $getDeptNum;
                $deptNum = $deptNum + 1;
                if ($this->gender[$this->_index] != "Female") {
                    $fname = $this->firstname[$this->_index];
                    $lname = $this->lastname[$this->_index];
                    $gen = "Male";
                    $occ = $this->occupation[$this->_index];
                } else {
                    $fname = $this->femfirstname[$this->_index];
                    $lname = $this->femLastName[$this->_index];
                    $gen = "Female";
                    $occ = $this->femoccp[$this->_index];
                }
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $fname,
                    "MidName" => $this->midname[$this->_index],
                    "LastName" => $lname,
                    "Age" => $this->age[$this->_index],
                    "gender" => $gen,
                    "occupation" => $occ,
                    "address" => $this->address[$this->_index],
                    "city" => $this->city[$this->_index],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => 'Aatyayikachikitsa'
                );
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                $treatment_arr = array(
                    "deptOpdNo" => $deptNum,
                    "Trtment" => $this->akTreatment[$this->_index],
                    "OpdNo" => $last_id,
                    "diagnosis" => $this->akdiagnosis[$this->_index],
                    "complaints" => $this->akcomplaints[$this->_index],
                    "department" => 'Aatyayikachikitsa',
                    "procedures" => $this->akprocedure[$this->_index],
                    "InOrOutPat" => "FollowUp",
                    "attndedby" => $this->akDoc[$this->_index],
                    "CameOn" => $cdate,
                    "attndedon" => $cdate,
                    "AddedBy" => $this->akDoc[$this->_index],
                    "patType" => "New Patient"
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();
                $tantramedicine = explode(',', $this->akmed[$this->_index]);
                $sizeoftest = sizeof($tantramedicine);
                $digits = 4;
                $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $sizeoftest; $i++) {
                    if (strlen($tantramedicine[$i]) > 0) {
                        $med_arr = array(
                            'opdno' => $last_id,
                            'billno' => $four_digit_random_number,
                            'date' => $cdate,
                            'batch' => 947,
                            'qty' => 1,
                            'product' => $tantramedicine[$i],
                            'treat_id' => $treatid
                        );
                        $this->db->insert('sales_entry', $med_arr);
                    }
                }
                $labdisease = $this->akdiagnosis[$this->_index];
                $docname = $this->akDoc[$this->_index];

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, 'Aatyayikachikitsa');
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

    function CalculateForEntry($deptper, $targetforentry, $entereddata) {
        $calresuslt = round(((($deptper * $targetforentry) / 100) - $entereddata));

        if ($calresuslt > 0) {
            return $calresuslt;
        } else {
            return 0;
        }
    }

    function getDeptNoForNewPatient($deptString) {
        $departmentalNo = "";
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
        $query = "SELECT * FROM ref_lab_reg_tab WHERE lab_disease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
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
            $this->db->insert('labregistery', $treatment_arr2);
        }
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function InsertXrayRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $query = "SELECT * FROM xray_ref WHERE diesease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
        foreach ($query->result() as $val) {
            $treatment_arr2 = array(
                "OpdNo" => $opdno,
                "refDocName" => $docname,
                "partOfXray" => $val->xraypart,
                "xrayDate" => $camedate,
                "treatID" => $treatid,
                "filmsize" => $val->filmsize
            );
            $this->db->insert('xrayregistery', $treatment_arr2);
        }
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function InsertUSGRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "usgDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('usgregistery', $treatment_arr2);
    }

    function InsertECGRegistry($opdno, $treatid, $camedate, $labdisease, $docname) {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "ecgDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('ecgregistery', $treatment_arr2);
    }

    function InsertPanchaProcedure($opdno, $treatid, $camedate, $labdisease, $docname) {
        $query = "SELECT * FROM panchakarma_ref WHERE disease='" . $labdisease . "' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($query);
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
            $this->db->insert('panchaprocedure', $treatment_arr2);
        }
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_day_doctor($date = "", $dept = "") {
        $result = $this->db->query("SELECT doctorname FROM doctors doc JOIN doctorsduty dd ON dd.doc_id=doc.id JOIN week_days wd ON dd.day=wd.week_id WHERE wd.week_day=DAYNAME(STR_TO_DATE('" . $date . "','%Y-%m-%d')) AND doc.doctortype='" . $dept . "' ORDER BY RAND() LIMIT 1")->row_array();
        return $result['doctorname'];
    }

}
