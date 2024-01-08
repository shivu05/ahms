<?php

/**
 * Description of Swarnaprashana
 *
 * @author shiv
 */
class Swarnaprashana extends SHV_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_patient_age($opd = NULL, $columns = array()) {
        if (!empty($columns) && $opd) {
            return $this->db->select($columns)
                            ->where('OpdNo', $opd)
                            ->get('patientdata')->row_array();
        }
        return false;
    }
}
