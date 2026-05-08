<?php

class M_auto extends CI_Model
{

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
    private $addedby, $agadaDoc;
    private $countkayachik, $countshalakya, $countshallya, $counttantra, $countSwastha, $countpanchakar, $countbalachik, $countemergency;
    private $tobeentkayachik, $tobeentshalakya, $tobeentshallya, $tobeenttantra, $toBeEntSwastha, $tobeentpanchakar, $tobeentbalachik, $tobeentemergency;
    private $kayachikper, $shalakyaper, $shallyaper, $tantraper, $swasthaPer, $panchakarper, $balachikper, $emergencyper, $shalakyaSubBranch, $agadatantraper;
    private $_dept_percentage_arr, $_entered_records_arr, $_total_records_to_be_entered, $_treatment_data;
    private $_department_data = array(), $_index = 0, $_pancha_count = 5;
    private $_college_id = '';
    private $allPatients, $femalePatients, $malePatients, $femaleAdultPatients, $maleAdultPatients, $prasootiPatients, $childPatients, $childMalePatients, $childFemalePatients;
    private $_pool_indexes, $_doctor_cache, $_patientdata_identity_columns_available;

    function __construct()
    {
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

        $config = $this->db->get('config')->row(); // assuming only one row exists
        $this->_college_id = $config->college_id ?? 'VHMS';

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
        $this->agadaDoc = "";

        //new code update
        $this->_dept_percentage_arr = $this->_entered_records_arr = array();
        $this->_total_records_to_be_entered = 0;
        $this->allPatients = $this->femalePatients = $this->malePatients = $this->femaleAdultPatients = array();
        $this->maleAdultPatients = $this->prasootiPatients = $this->childPatients = array();
        $this->childMalePatients = $this->childFemalePatients = $this->_pool_indexes = $this->_doctor_cache = array();
        $this->_patientdata_identity_columns_available = null;

        $this->addedby = "";
    }

    function auto_master($target, $cdate, $newpatient, $pancha_count)
    {
        shuffle($this->firstname);

        $query = "SELECT sum(dept_count) as dept_count,A.department,dept.percentage FROM ( 
                    SELECT count(*) as dept_count,department from treatmentdata WHERE 
                        CameOn='$cdate' group by department 
                    UNION ALL 
                    SELECT 0 dept_count,dept_unique_code department FROM deptper dd
                  ) A 
                  JOIN deptper dept ON dept.dept_unique_code=A.department and dept.dept_unique_code <> 'SPECIALIZED'
                  GROUP BY dept_unique_code";
        //and dept.dept_unique_code <> 'AGADATANTRA'
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

    private function insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept)
    {
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
        //        if (strtolower($dept) == strtolower('Panchakarma')) {
        //            $this->InsertPanchaProcedure($last_id, $treatid, $cdate, $labdisease, $docname);
        //        }
        /* if (strtolower($dept) == strtolower('Panchakarma')) {
          $j = $this->db->get('panchaprocedure')->num_rows();
          if ($j <= $this->_pancha_count) {
          //$this->InsertPanchaProcedure($last_id, $treatid, $cdate, $labdisease, $docname, $docname);
          }
          } */
    }

    function calculate_new_old_patient_count()
    {
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

    function insertextradata($diff, $cdate, $target, $newpatient, $pancha_count)
    {

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
        $this->agadatantraper = $this->_entered_records_arr['agadatantra']['per'];

        $tobeentkayachik = $this->calculate_department_entry_count($this->kayachikper, $target, $this->_entered_records_arr['kayachikitsa']['count']);
        $tobeentshalakya = $this->calculate_department_entry_count($this->shalakyaper, $target, $this->_entered_records_arr['shalakya_tantra']['count']);
        $tobeentshallya = $this->calculate_department_entry_count($this->shallyaper, $target, $this->_entered_records_arr['shalya_tantra']['count']);
        $tobeenttantra = $this->calculate_department_entry_count($this->tantraper, $target, $this->_entered_records_arr['prasooti_&_striroga']['count']);
        $toBeEntSwastha = $this->calculate_department_entry_count($this->swasthaPer, $target, $this->_entered_records_arr['swasthavritta']['count']);
        $tobeentpanchakar = $this->calculate_department_entry_count($this->panchakarper, $target, $this->_entered_records_arr['panchakarma']['count']);
        $tobeentbalachik = $this->calculate_department_entry_count($this->balachikper, $target, $this->_entered_records_arr['balaroga']['count']);
        $tobeentemergency = $this->calculate_department_entry_count($this->emergencyper, $target, $this->_entered_records_arr['aatyayikachikitsa']['count']);
        $tobeenteragada = $this->calculate_department_entry_count($this->agadatantraper, $target, $this->_entered_records_arr['agadatantra']['count']);

        $this->_entered_records_arr['kayachikitsa']['total'] = $tobeentkayachik;
        $this->_entered_records_arr['shalakya_tantra']['total'] = $tobeentshalakya;
        $this->_entered_records_arr['shalya_tantra']['total'] = $tobeentshallya;
        $this->_entered_records_arr['prasooti_&_striroga']['total'] = $tobeenttantra;
        $this->_entered_records_arr['swasthavritta']['total'] = $toBeEntSwastha;
        $this->_entered_records_arr['panchakarma']['total'] = $tobeentpanchakar;
        $this->_entered_records_arr['balaroga']['total'] = $tobeentbalachik;
        $this->_entered_records_arr['aatyayikachikitsa']['total'] = $tobeentemergency;
        $this->_entered_records_arr['agadatantra']['total'] = $tobeenteragada;

        $totalentry = ($tobeentkayachik + $tobeentshalakya + $tobeentshallya + $tobeenttantra + $toBeEntSwastha + $tobeentpanchakar + $tobeentbalachik + $tobeentemergency + $tobeenteragada);

        $this->_total_records_to_be_entered = $totalentry;
        $this->calculate_new_old_patient_count();

        $query = $this->db->query("SELECT * FROM oldtable ORDER BY RAND()");
        $insert_data = $query->result();
        $hasIdentityColumns = $this->has_oldtable_identity_columns();
        foreach ($insert_data as $row) {
            $dept = $row->department;
            $isFemale = $this->_is_female_gender($row->gender);
            $isBalaroga = ($dept === 'BALAROGA');
            $isPrasooti = ($dept === 'PRASOOTI_&_STRIROGA');
            $record = array(
                'id' => $row->ID,
                'department' => $dept,
                'first_name' => $row->FirstName,
                'mid_name' => $row->MidName,
                'last_name' => $row->LastName,
                'age' => $row->Age,
                'gender' => $row->gender,
                'occupation' => $row->occupation,
                'address' => $row->address,
                'city' => $row->city,
                'aadhaar_number' => $hasIdentityColumns ? ($row->aadhaar_number ?? null) : null,
                'abha_id' => $hasIdentityColumns ? ($row->abha_id ?? null) : null,
                'aadhaar_masked' => $hasIdentityColumns ? ($row->aadhaar_masked ?? null) : null
            );

            $this->allPatients[] = $record;
            if ($isFemale) {
                $this->femalePatients[] = $record;
                if ($isPrasooti) {
                    $this->prasootiPatients[] = $record;
                }
                if (!$isBalaroga) {
                    $this->femaleAdultPatients[] = $record;
                }
            } else {
                $this->malePatients[] = $record;
                if (!$isBalaroga) {
                    $this->maleAdultPatients[] = $record;
                }
            }
            if ($isBalaroga) {
                $this->childPatients[] = $record;
                if ($isFemale) {
                    $this->childFemalePatients[] = $record;
                } else {
                    $this->childMalePatients[] = $record;
                }
            }

            $this->_treatment_data[$row->department][] = array(
                'diagnosis' => $row->diagnosis,
                'complaints' => $row->complaints,
                'treatment' => $row->Trtment,
                'procedure' => $row->procedures,
                'medicines' => $row->medicines,
                'sub_dept' => $row->sub_dept,
                'gender' => $row->gender,
                'age' => $row->Age,
                "blood_pressure" => $row->blood_pressure,
                "pulse_rate" => $row->pulse_rate,
                "respiratory_rate" => $row->respiratory_rate,
                "body_temperature" => $row->body_temperature,
                "spo2" => $row->spo2,
                "weight" => $row->weight
            );
        } // end of old for
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

            if (strtolower($k[1]) == strtolower("AGADATANTRA")) {
                $this->enter_agadatantra_patient_details($k, $cdate);
            }
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('noty_msg', @$target . " Records added successfully");
    }

    private function _get_diagnosis_by_gender($dg_arr = array(), $gender = '')
    {
        if (empty($dg_arr)) {
            return array();
        }

        if ($this->_is_female_gender($gender)) {
            $new_arr = array_values(array_filter($dg_arr, function ($ar) {
                return ($ar['gender'] == 'Female');
            }));
        } else {
            $new_arr = array_values(array_filter($dg_arr, function ($ar) {
                return ($ar['gender'] == 'Male');
            }));
        }

        if (empty($new_arr)) {
            return $dg_arr[array_rand($dg_arr)];
        }

        return $new_arr[array_rand($new_arr)];
    }

    private function _get_treatment_template($dept_name, $gender = '')
    {
        $dept_arr = $this->_treatment_data[$dept_name] ?? array();
        if (!empty($dept_arr)) {
            $this->_index = $this->_check_list_index($dept_arr, $this->_index);
            $treatment_details = $this->_get_diagnosis_by_gender($dept_arr, $gender);
            if (!empty($treatment_details)) {
                return $treatment_details;
            }
        }

        return $this->_get_treatment_template_from_history($dept_name, $gender);
    }

    private function _get_treatment_template_from_history($dept_name, $gender = '')
    {
        $this->db->select('t.diagnosis, t.complaints, t.Trtment AS treatment, t.procedures AS procedure, t.medicines, t.sub_department AS sub_dept, p.gender, p.Age AS age, pv.blood_pressure, pv.pulse_rate, pv.respiratory_rate, pv.body_temperature, pv.spo2, pv.weight', false);
        $this->db->from('treatmentdata t');
        $this->db->join('patientdata p', 'p.OpdNo = t.OpdNo', 'left');
        $this->db->join('patient_vitals pv', 'pv.opd_no = t.OpdNo AND pv.date = t.CameOn', 'left');
        $this->db->where('t.department', $dept_name);
        if ($gender !== '') {
            $this->db->where('UPPER(TRIM(p.gender))', strtoupper(trim($gender)));
        }
        $this->db->order_by('RAND()', '', false);
        $this->db->limit(1);
        $row = $this->db->get()->row_array();

        if (!empty($row)) {
            return $row;
        }

        if ($gender !== '') {
            $this->db->select('t.diagnosis, t.complaints, t.Trtment AS treatment, t.procedures AS procedure, t.medicines, t.sub_department AS sub_dept, p.gender, p.Age AS age, pv.blood_pressure, pv.pulse_rate, pv.respiratory_rate, pv.body_temperature, pv.spo2, pv.weight', false);
            $this->db->from('treatmentdata t');
            $this->db->join('patientdata p', 'p.OpdNo = t.OpdNo', 'left');
            $this->db->join('patient_vitals pv', 'pv.opd_no = t.OpdNo AND pv.date = t.CameOn', 'left');
            $this->db->where('t.department', $dept_name);
            $this->db->order_by('RAND()', '', false);
            $this->db->limit(1);
            return $this->db->get()->row_array() ?: array();
        }

        return array();
    }

    public function add_to_pharmacy($treat_id)
    {
        $this->db->where('ID', $treat_id);
        $this->db->where('department <>', 'SWASTHAVRITTA');
        $treatment_data = $this->db->get('treatmentdata')->row_array();
        if (!empty($treatment_data)) {
            $digits = 4;
            $four_digit_random_number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            $string_to_explode = $treatment_data['medicines'] ?? '';
            $products = explode(',', $string_to_explode);
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
        } // end if
    }

    private function _check_occupation($age = 0, $occupation = '')
    {
        $where = $child = '';
        if ($age > 30) {
            $where = " AND occupation !='STUDENT'";
        }

        if ($age > 15) {
            $child = " AND occupation !='CHILD'";
        }
        $result_set = $this->db->query("SELECT occupation FROM master_occupation WHERE 1=1 $where " . " $child order by rand() limit 1")->row_array();
        return $result_set['occupation'];
    }

    private function _get_random_data($index = 0, $array = null)
    {
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

    private function _check_valid_index($index, $dept = '')
    {
        if (
            $index >= count($this->femfirstname) ||
            ($index >= count($this->femLastName)) ||
            ($index >= count($this->femoccp)) || ($index >= count($this->age)) ||
            ($dept == 'BALAROGA' && $index >= count($this->childage)) ||
            ($dept == 'PRASOOTI_&_STRIROGA' && $index >= count($this->Prasootiage))
        ) {
            $index = 0;
            $this->shuffle();
            return $index;
        }
        return $index;
    }

    private function _check_list_index($dept_arr, $index)
    {
        if (empty($dept_arr)) {
            return 0;
        }

        if (!isset($dept_arr[$index]) || trim((string) ($dept_arr[$index]['diagnosis'] ?? '')) === "") {
            $index = 0;
            shuffle($dept_arr);
            return $index;
        }
        return $index;
    }

    private function _is_female_gender($gender = '')
    {
        $sex = strtoupper(trim($gender));
        return ($sex == 'FEMALE') ? true : false;
    }

    private function _insert_vitals($opd_no, $date, $treatment_data = array())
    {
        $vitals_data = array(
            'opd_no' => $opd_no,
            'blood_pressure' => $treatment_data['blood_pressure'],
            'date' => $date,
            'pulse_rate' => $treatment_data['pulse_rate'],
            'respiratory_rate' => $treatment_data['respiratory_rate'],
            'body_temperature' => $treatment_data['body_temperature'],
            'spo2' => $treatment_data['spo2'],
            'weight' => $treatment_data['weight'],
        );

        $this->db->insert('patient_vitals', $vitals_data);
    }

    private function add_old_patient_data($dept = NULL, $date = NULL)
    {
        $query = "SELECT * from treatmentdata WHERE InOrOutPat='FollowUp' AND department='$dept' AND NOT (CameOn = '" . $date . "') ORDER BY RAND() LIMIT 1 ";
        $result = $this->db->query($query)->row_array();
        $treating_doctor = $this->get_day_doctor($date, $dept);
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
                "sub_department" => $result['sub_department'],
                "AddedBy" => $treating_doctor,
                "patType" => "Old Patient",
                "medicines" => $result['medicines']
            );
            $this->db->insert('treatmentdata', $insert_data);
            $treatid = $this->db->insert_id();

            $query_opd = "INSERT INTO patient_vitals 
            (opd_no, blood_pressure, date, pulse_rate, respiratory_rate, body_temperature, spo2, weight)
            SELECT opd_no,blood_pressure,'$date',pulse_rate,respiratory_rate,body_temperature,spo2,weight 
            FROM patient_vitals 
            WHERE opd_no = '" . $result['OpdNo'] . "' AND date = '" . $result['CameOn'] . "' 
            UNION ALL 
            SELECT '" . $result['OpdNo'] . "',NULL,'$date',NULL,NULL,NULL,NULL,NULL 
            FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM patient_vitals WHERE opd_no = '" . $result['OpdNo'] . "' 
            AND date = '" . $result['CameOn'] . "' )";
            $this->db->query($query_opd);

            $this->add_to_pharmacy($treatid);

            $last_id = $result['OpdNo'];
            $labdisease = $result['diagnosis'];
            $docname = $treating_doctor;
            //insert_lexu
            $this->insert_lexu($last_id, $treatid, $date, $labdisease, $docname, $dept);
            if ($dept == 'SHALAKYA_TANTRA') {
                $shalakya_insert_data = array(
                    'OpdNo' => $result['OpdNo'],
                    'IpNo' => NULL,
                    'treat_id' => $treatid,
                    'kriya_procedures' => $result['procedures'],
                    'kriya_date' => $date
                );
                $this->add_to_kriyaklpa($shalakya_insert_data);
            }
        }
    }

    function add_to_kriyaklpa($insert_data)
    {
        return $this->db->insert('kriyakalpa', $insert_data);
    }

    function insert_patient_with_uhid($date)
    {
        $hospital_code = $this->_college_id; // your code
        $today = $date;
        $today_short = date('ymd', strtotime($date));//date('ymd');

        // Step 1: Get or insert sequence
        $query = $this->db->get_where('uhid_sequence', [
            'seq_date' => $today,
            'hospital_code' => $hospital_code
        ]);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $daily_seq = $row->daily_seq + 1;
            $this->db->update('uhid_sequence', ['daily_seq' => $daily_seq], ['id' => $row->id]);
        } else {
            $daily_seq = 1;
            $this->db->insert('uhid_sequence', [
                'seq_date' => $today,
                'hospital_code' => $hospital_code,
                'daily_seq' => $daily_seq
            ]);
        }

        // Step 2: Generate UHID
        $uhid = 'VH-' . $hospital_code . '-' . $today_short . '-' . str_pad($daily_seq, 4, '0', STR_PAD_LEFT);

        // Step 3: Add UHID to patient data
        //$patient_data['UHID'] = $uhid;
        //$this->db->insert('patientdata', $patient_data);

        return $uhid;
    }


    function enter_prasooti_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'PRASOOTI_&_STRIROGA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->getDeptNoForNewPatient($dept_name);
            $patient = $this->_get_new_patient_record($dept_name, 'Female');
            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $treatment_details = $this->_get_treatment_template($dept_name, 'Female');
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => $patient['mid_name'],
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => "Female",
                    "occupation" => $patient['occupation'],
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "patType" => "New Patient",
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_balaroga_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'BALAROGA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);
            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => 'School',
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_kayachikitsa_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'KAYACHIKITSA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);
            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $occ = $patient['occupation'];
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_shalakya_tantra_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SHALAKYA_TANTRA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];

                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sub_dept" => $treatment_details['sub_dept'],
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "sub_department" => $treatment_details['sub_dept'],
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);

                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_shalya_tantra_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SHALYA_TANTRA';

        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);

                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "patType" => "New Patient",
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_swasthavritta_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'SWASTHAVRITTA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient('SWASTHAVRITTA');
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();
                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        } else {
            $this->add_old_patient_data($dept_name, $cdate);
            $this->_index++;
        }
    }

    function enter_panchakarma_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'PANCHAKARMA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function enter_aatyayikachikitsa_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'AATYAYIKACHIKITSA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

                $this->add_to_pharmacy($treatid);

                $labdisease = trim($diagnosis);

                $this->insert_lexu($last_id, $treatid, $cdate, $labdisease, $docname, $dept_name);
                $this->_index++;
            }
        }
    }

    function enter_agadatantra_patient_details($arr, $cdate)
    {
        $addemailid = $this->session->userdata('user_name');
        $dept_name = 'AGADATANTRA';
        if (strtolower($arr[0]) != strtolower('old')) {
            $getDeptNum = $this->m_auto->getDeptNoForNewPatient($dept_name);
            $preferredGender = $this->gender[$this->_index] ?? '';
            $patient = $this->_get_new_patient_record($dept_name, $preferredGender);

            if (($getDeptNum != "" || strlen($getDeptNum) > 0) && !empty($patient)) {
                $sex = $patient['gender'];
                $occ = $patient['occupation'];
                $treatment_details = $this->_get_treatment_template($dept_name, $sex);
                if (empty($treatment_details)) {
                    return;
                }
                $deptNum = $getDeptNum + 1;
                $uid = $this->uuid->v5('AnSh');
                $data = array(
                    "deptOpdNo" => $deptNum,
                    "FirstName" => $patient['first_name'],
                    "MidName" => '',
                    "LastName" => $patient['last_name'],
                    "Age" => $patient['age'],
                    "gender" => $sex,
                    "occupation" => $occ,
                    "address" => $patient['address'],
                    "city" => $patient['city'],
                    "AddedBy" => $addemailid,
                    "entrydate" => $cdate,
                    "dept" => $dept_name,
                    "sid" => $uid,
                    'UHID' => $this->insert_patient_with_uhid($cdate)
                );
                $data = $this->_append_patient_identity_fields($data, $patient);
                $this->db->insert('patientdata', $data);
                $last_id = $this->db->insert_id();

                $diagnosis = $treatment_details['diagnosis'];
                $docname = $this->_get_day_doctor_cached($cdate, $dept_name);
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
                    "medicines" => $treatment_details['medicines']
                );
                $this->db->insert('treatmentdata', $treatment_arr);
                $treatid = $this->db->insert_id();

                $this->db->where('OpdNo', $last_id);
                $this->db->update('patientdata', array('Age' => $treatment_details['age']));

                $this->_insert_vitals($last_id, $cdate, $treatment_details);

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

    function shuffle()
    {
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
        shuffle($this->allPatients);
        shuffle($this->femalePatients);
        shuffle($this->malePatients);
        shuffle($this->femaleAdultPatients);
        shuffle($this->maleAdultPatients);
        shuffle($this->prasootiPatients);
        shuffle($this->childPatients);
        shuffle($this->childMalePatients);
        shuffle($this->childFemalePatients);
    }

    private function _get_new_patient_record($dept_name, $preferredGender = '')
    {
        if ($dept_name === 'PRASOOTI_&_STRIROGA') {
            return $this->_get_patient_record_from_pool('prasootiPatients');
        }

        if ($dept_name === 'BALAROGA') {
            if ($this->_is_female_gender($preferredGender)) {
                return $this->_get_patient_record_from_pool('childFemalePatients', array('childPatients'));
            }

            return $this->_get_patient_record_from_pool('childMalePatients', array('childPatients'));
        }

        if ($this->_is_female_gender($preferredGender)) {
            return $this->_get_patient_record_from_pool('femaleAdultPatients', array('femalePatients', 'allPatients'));
        }

        return $this->_get_patient_record_from_pool('maleAdultPatients', array('malePatients', 'allPatients'));
    }

    private function _get_patient_record_from_pool($primary_pool, $fallback_pools = array())
    {
        $pool_names = array_merge(array($primary_pool), $fallback_pools);
        foreach ($pool_names as $pool_name) {
            if (empty($this->$pool_name)) {
                continue;
            }

            if (!isset($this->_pool_indexes[$pool_name])) {
                $this->_pool_indexes[$pool_name] = 0;
            }

            if ($this->_pool_indexes[$pool_name] >= count($this->$pool_name)) {
                $this->_pool_indexes[$pool_name] = 0;
                shuffle($this->$pool_name);
            }

            $record = $this->{$pool_name}[$this->_pool_indexes[$pool_name]];
            $this->_pool_indexes[$pool_name]++;
            return $record;
        }

        return null;
    }

    private function _append_patient_identity_fields($data, $patient = array())
    {
        if (!$this->has_patientdata_identity_columns()) {
            return $data;
        }

        $aadhaar_number = trim((string) ($patient['aadhaar_number'] ?? ''));
        $abha_id = trim((string) ($patient['abha_id'] ?? ''));
        $aadhaar_masked = trim((string) ($patient['aadhaar_masked'] ?? ''));

        if ($aadhaar_number !== '' && $this->db->where('aadhaar_number', $aadhaar_number)->count_all_results('patientdata') == 0) {
            $data['aadhaar_number'] = $aadhaar_number;
            $data['abha_id'] = ($abha_id !== '') ? $abha_id : null;
            $data['aadhaar_masked'] = ($aadhaar_masked !== '') ? $aadhaar_masked : null;
            return $data;
        }

        $data['aadhaar_number'] = null;
        $data['abha_id'] = null;
        $data['aadhaar_masked'] = null;
        return $data;
    }

    private function _get_day_doctor_cached($date, $dept)
    {
        $cache_key = $date . '||' . $dept;
        if (!array_key_exists($cache_key, $this->_doctor_cache)) {
            $this->_doctor_cache[$cache_key] = $this->get_day_doctor($date, $dept);
        }

        return $this->_doctor_cache[$cache_key];
    }

    function calculate_department_entry_count($deptper, $targetforentry, $entereddata)
    {
        $count = round(((($deptper * $targetforentry) / 100) - $entereddata));
        return ($count > 0) ? $count : 0;
    }

    function getDeptNoForNewPatient($deptString)
    {
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

    function InsertLabRegistry($opdno, $treatid, $camedate, $labdisease, $docname)
    {
        return true;
        if ($this->db->table_exists('lab_reference')) {
            $check_data = "select treatID from lab_reference where upper(trim(labdisease))='" . strtoupper(trim($labdisease)) . "' ORDER BY RAND() LIMIT 1";
            $trement_details = $this->db->query($check_data)->row_array();
            if (!empty($trement_details)) {
                $insert_query = "INSERT INTO labregistery (OpdNo,refDocName,lab_test_cat,lab_test_type,testName,testDate,treatID,testrange,testvalue,labdisease,tested_date) 
            select '$opdno','$docname',lab_test_cat,lab_test_type,testName,'$camedate','$treatid',testrange,testvalue,'$labdisease','$camedate' from lab_reference where treatID='" . $trement_details['treatID'] . "'";
                return $this->db->query($insert_query);
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function InsertXrayRegistry($opdno, $treatid, $camedate, $labdisease, $docname)
    {
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

    function InsertUSGRegistry($opdno, $treatid, $camedate, $labdisease, $docname)
    {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "usgDate" => $camedate,
            "refDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('usgregistery', $treatment_arr2);
    }

    function InsertECGRegistry($opdno, $treatid, $camedate, $labdisease, $docname)
    {
        $treatment_arr2 = array(
            "OpdNo" => $opdno,
            "refDocName" => $docname,
            "ecgDate" => $camedate,
            "refDate" => $camedate,
            "treatId" => $treatid
        );
        $this->db->insert('ecgregistery', $treatment_arr2);
    }

    function InsertPanchaProcedure($opdno, $treatid, $camedate, $labdisease, $docname)
    {
        if ($this->db->table_exists('reference_panchakarma')) {
            $check_data = "select treatid,no_of_days from reference_panchakarma where upper(trim(disease))='UNMADA' ORDER BY RAND() LIMIT 1";
            $trement_details = $this->db->query($check_data)->row_array();
            if (!empty($trement_details)) {
                $insert_query = "INSERT INTO panchaprocedure (opdno,disease,`treatment`,`procedure`,`date`,docname,treatid,proc_end_date)
            select '$opdno','$labdisease',`treatment`,`procedure`,'$camedate','$docname','$treatid',DATE_ADD('$camedate', INTERVAL " . $trement_details['no_of_days'] . " DAY) from reference_panchakarma where treatid=" . $trement_details['treatid'] . "";
                return $this->db->query($insert_query);
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function get_day_doctor($date = "", $dept = "")
    {
        $query = "SELECT u.ID,user_name FROM users u 
            JOIN doctorsduty d ON u.ID=d.doc_id JOIN week_days wd ON d.day=wd.week_id 
            WHERE UPPER(u.user_department)=UPPER(replace('$dept',' ','_')) AND wd.week_day=DAYNAME(STR_TO_DATE('$date','%Y-%m-%d')) ORDER BY RAND() LIMIT 1;";
        $result = $this->db->query($query)->row_array();
        return $result['user_name'];
    }

    function get_data($conditions, $export_flag = false)
    {
        $return = array();
        $start = 0;
        $length = 25;
        if (!$export_flag) {
            $start = (isset($conditions['start']) && is_numeric($conditions['start'])) ? (int) $conditions['start'] : 0;
            $length = (isset($conditions['length']) && is_numeric($conditions['length'])) ? (int) $conditions['length'] : 25;
            unset($conditions['start'], $conditions['length'], $conditions['order']);
        }

        unset($conditions['start_date'], $conditions['end_date']);

        $this->db->from('oldtable');
        $this->_apply_reference_filters($conditions);
        $return['found_rows'] = $this->db->count_all_results();

        $this->db->select('ID, FirstName, LastName, Age, gender, occupation, address, city, Mobileno, diagnosis, complaints, department, REPLACE(department, "_", " ") AS department_display, procedures, Trtment, notes, AddedBy, entrydate, medicines, sub_dept', false);
        if ($this->has_oldtable_identity_columns()) {
            $this->db->select('aadhaar_number, abha_id, aadhaar_masked');
        }
        $this->db->from('oldtable');
        $this->_apply_reference_filters($conditions);
        $this->db->order_by('ID', 'ASC');
        if (!$export_flag) {
            $this->db->limit($length, $start);
        }

        $return['data'] = $this->db->get()->result_array();
        $return['total_rows'] = $this->db->count_all('oldtable');
        return $return;
    }

    private function _apply_reference_filters($conditions)
    {
        foreach ($conditions as $col => $val) {
            $val = trim((string) $val);
            if ($val === '') {
                continue;
            }

            switch ($col) {
                case 'keyword':
                    $this->db->group_start()
                        ->like('FirstName', $val)
                        ->or_like('LastName', $val)
                        ->or_like('gender', $val)
                        ->or_like('city', $val)
                        ->or_like('diagnosis', $val)
                        ->or_like('department', str_replace(' ', '_', strtoupper($val)))
                        ->or_like('sub_dept', $val)
                        ->or_like('Trtment', $val)
                        ->or_like('medicines', $val)
                        ->group_end();
                    break;
                case 'gender':
                case 'department':
                case 'sub_dept':
                    $this->db->where($col, $val);
                    break;
            }
        }
    }

    function get_reference_by_id($id)
    {
        return $this->db->where('ID', (int) $id)->get('oldtable')->row_array();
    }

    function create_patient_data($post_values)
    {
        $is_inserted = $this->db->insert('oldtable', $post_values);
        return $is_inserted ? $this->db->insert_id() : false;
    }

    function insert_reference_batch($rows)
    {
        if (empty($rows)) {
            return 0;
        }

        return $this->db->insert_batch('oldtable', $rows);
    }

    function update_patient_data($id, $post_values)
    {
        $this->db->where('ID', (int) $id);
        return $this->db->update('oldtable', $post_values);
    }

    function delete_patient_data($id)
    {
        return $this->db->where('ID', (int) $id)->delete('oldtable');
    }

    function has_oldtable_identity_columns()
    {
        return $this->db->field_exists('aadhaar_number', 'oldtable')
            && $this->db->field_exists('abha_id', 'oldtable')
            && $this->db->field_exists('aadhaar_masked', 'oldtable');
    }

    function has_patientdata_identity_columns()
    {
        if ($this->_patientdata_identity_columns_available === null) {
            $this->_patientdata_identity_columns_available =
                $this->db->field_exists('aadhaar_number', 'patientdata')
                && $this->db->field_exists('abha_id', 'patientdata')
                && $this->db->field_exists('aadhaar_masked', 'patientdata');
        }

        return $this->_patientdata_identity_columns_available;
    }

    function get_missing_oldtable_identity_columns()
    {
        $missing_columns = array();
        foreach (array('aadhaar_number', 'abha_id', 'aadhaar_masked') as $column) {
            if (!$this->db->field_exists($column, 'oldtable')) {
                $missing_columns[] = $column;
            }
        }
        return $missing_columns;
    }
}
