<?php

/**
 * Description of Updatedata
 *
 * @author shv
 */
class Updatedata extends SHV_Controller {

    private $_doctors_tab;

    public function __construct() {
        parent::__construct();
        $this->_doctors_tab = 'doctors_list';
    }

    function doctors() {
        $this->layout->title = "Doctors list";
        $data = array();
        if (!$this->db->table_exists($this->_doctors_tab)) {
            echo 'Data doesnot exists please contact administrator';
        }
        
        $this->layout->data = $data;
        $this->layout->render();
    }

    private function _check_doctors_tbl() {
        $this->load->dbforge();

        $fields = array(
            'ID' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'dept' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE,
            ),
            'doctor_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->_doctors_tab, TRUE, $fields);
    }

    private function _insert_departments() {
        $insert_data = array(
            array('dept' => 'KAYACHIKITSA'),
            array('dept' => 'BALAROGA'),
            array('dept' => 'PANCHAKARMA'),
            array('dept' => 'SHALAKYA_TANTRA'),
            array('dept' => 'SHALYA_TANTRA'),
            array('dept' => 'PRASOOTI_&_STRIROGA'),
            array('dept' => 'SWASTHAVRITTA'),
            array('dept' => 'AATYAYIKACHIKITSA')
        );
        $this->db->insert_batch($this->_doctors_tab, $insert_data);
    }
}
