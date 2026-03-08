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

    // Method to get unique procedures with day counts
    public function get_unique_procedures()
    {
        // Calculate the total number of days for each procedure type
        // from_opd: Sum of days for procedures from OPD patients (no IpNo)
        // from_ipd: Sum of days for procedures from IPD patients (has IpNo)
        // total: Total sum of days (from_opd + from_ipd)
        // For each procedure: DATEDIFF(proc_end_date, date) + 1 gives the number of days
        // If proc_end_date is NULL or empty, treat it as 1 day
        // Filter out empty procedures and use DISTINCT to avoid duplicate counting
        $sql = "
            SELECT
                m.id AS `S.No.`,
                m.proc_name AS `procedure_name`,
                COALESCE(SUM(CASE 
                    WHEN (UPPER(TRIM(pp.treatment)) = UPPER(TRIM(m.proc_name)) AND (i.IpNo IS NULL OR i.IpNo = '')) THEN 
                        CASE
                            WHEN pp.proc_end_date IS NULL OR pp.proc_end_date = '' THEN 1
                            ELSE (DATEDIFF(pp.proc_end_date, pp.`date`) + 1)
                        END
                    ELSE 0 
                END), 0) AS `from_opd`,
                COALESCE(SUM(CASE 
                    WHEN (UPPER(TRIM(pp.treatment)) = UPPER(TRIM(m.proc_name)) AND (i.IpNo IS NOT NULL AND i.IpNo != '')) THEN 
                        CASE
                            WHEN pp.proc_end_date IS NULL OR pp.proc_end_date = '' THEN 1
                            ELSE (DATEDIFF(pp.proc_end_date, pp.`date`) + 1)
                        END
                    ELSE 0 
                END), 0) AS `from_ipd`,
                COALESCE(SUM(CASE 
                    WHEN UPPER(TRIM(pp.treatment)) = UPPER(TRIM(m.proc_name)) THEN
                        CASE
                            WHEN pp.proc_end_date IS NULL OR pp.proc_end_date = '' THEN 1
                            ELSE (DATEDIFF(pp.proc_end_date, pp.`date`) + 1)
                        END
                    ELSE 0 
                END), 0) AS `total`
            FROM master_panchakarma_procedures m
            LEFT JOIN panchaprocedure pp ON UPPER(TRIM(pp.treatment)) = UPPER(TRIM(m.proc_name)) 
                AND pp.treatment IS NOT NULL 
                AND TRIM(pp.treatment) != ''
                AND pp.procedure IS NOT NULL 
                AND TRIM(pp.procedure) != ''
            LEFT JOIN inpatientdetails i ON i.treatId = pp.treatid
            GROUP BY m.id, m.proc_name
            ORDER BY m.id
        ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}