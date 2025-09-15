<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panchaprocedure extends CI_Model
{

    private $table_name = 'panchaprocedure';

    public function __construct()
    {
        parent::__construct();
    }

    // Example method to fetch all records
    public function get_all_records()
    {
        $query = $this->db->get($this->table_name);
        return $query->result_array();
    }

    // Example method to insert a record
    public function insert_record($data)
    {
        return $this->db->insert($this->table_name, $data);
    }

    // Example method to update a record
    public function update_record($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    // Example method to delete a record
    public function delete_record($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    // Method to get unique procedures
    public function get_unique_procedures()
    {
        /*$this->db->select('treatment, `procedure`, COUNT(*) as procedure_count');
        $this->db->from($this->table_name);
        $this->db->where('`procedure` !=', '');
        $this->db->group_by(['treatment', '`procedure`']);
        $this->db->order_by('treatment, `procedure`');*/
        $query = "WITH pp_classified AS ( 
            SELECT pp.treatment, CASE WHEN i.IpNo IS NULL THEN 'OPD' ELSE 'IPD' END AS kind 
            FROM panchaprocedure pp 
            LEFT JOIN inpatientdetails i ON i.treatId = CAST(pp.treatid AS CHAR)
        )
        SELECT 
            m.id AS `S.No.`, 
            m.proc_name AS `procedure_name`,
            COALESCE(SUM(ppc.kind = 'OPD'), 0) AS `from_opd`, 
            COALESCE(SUM(ppc.kind = 'IPD'), 0) AS `from_ipd`,
            COALESCE(COUNT(ppc.kind), 0) AS `total`
        FROM master_panchakarma_procedures m 
        LEFT JOIN pp_classified ppc ON UPPER(ppc.treatment) = UPPER(m.proc_name) 
        GROUP BY m.id, m.proc_name 
        ORDER BY m.id";
        $query = $this->db->query($query);
        return $query->result_array();
    }
}