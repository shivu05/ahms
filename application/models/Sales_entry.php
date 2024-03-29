<?php

/**
 * Description of Sales_entry
 *
 * @author Shivaraj Badiger
 */
class Sales_entry extends SHV_Model {

    public function __construct($table = '') {
        parent::__construct($table);
    }

    function getSalesPharmacy($from, $to, $department, $ipd = 0) {
        $this->db->query('update sales_entry se,inpatientdetails i set se.ipdno=i.IpNo where se.opdno=i.OpdNo');
        //$from = date("Y-m-d", strtotime($from));
        //$to = date("Y-m-d", strtotime($to));
        $this->db->query("set session group_concat_max_len = 5000");
        if ($ipd == 0) {
            $dept_condition = ($department == "1") ? "" : "AND p.dept LIKE '%" . $department . "%'";
            $query = "select t.OpdNo,s.treat_id,group_concat(s.product) as product,group_concat(s.batch) as batch, group_concat(s.qty) as qty,
                group_concat(s.id) as product_ids,ucfirst(REPLACE((t.department),'_',' ')) dept,t.deptOpdNo,t.PatType,CONCAT(p.FirstName,' ',p.LastName) as name,
                t.AddedBy,t.CameOn ,p.Age,p.gender
                from sales_entry s 
                JOIN treatmentdata t ON s.treat_id=t.ID 
                JOIN patientdata p ON t.OpdNo=p.OpdNo 
                WHERE s.date >= '" . $from . "' AND s.date <= '" . $to . "' AND ipdno is null $dept_condition
                group by s.treat_id ";
            $query = $this->db->query($query);
        } else {
            $this->db->select("count(*) as records,s.id as product_id,s.treat_id,p.OpdNO,p.deptOpdNo,p.DoAdmission,p.DoDischarge,p.FName,p.Age,p.Gender,p.department,p.Doctor,s.ipdno,group_concat(s.product) as product,group_concat(s.batch) as batch,group_concat(s.qty) as qty,group_concat(s.id) as product_ids");
            $this->db->from("inpatientdetails p");
            $this->db->join("sales_entry s", "s.ipdno=p.IpNo");
            $this->db->join("ipdtreatment t", "s.ipdno=t.ipdno and s.date=t.attndedon");
            $this->db->where("s.date >=", $from);
            $this->db->where("s.date <=", $to);
            $this->db->where('p.department !=', 'Swasthavritta');
            $this->db->where("s.ipdno IS NOT NULL");
            if ($department != "1") {
                $this->db->where("p.department", $department);
            }
            $this->db->group_by('s.treat_id');
            $query = $this->db->get();
        }

        //echo $this->db->last_query();
        //exit;
        if ($query->num_rows() > 0) {
            return $query->result(); //if data is true
        } else {
            return false; //if data is wrong
        }
    }

    function update_pharmacy_data($input_array = NULL) {
        if ($input_array) {
            $this->db->where('id', $input_array['id']);
            return $this->db->update('sales_entry', $input_array);
        }
        return false;
    }

    function remove_pharmacy_data($input_array = NULL) {
        if ($input_array) {
            $this->db->where('id', $input_array['id']);
            return $this->db->delete('sales_entry');
        }
        return false;
    }
}
