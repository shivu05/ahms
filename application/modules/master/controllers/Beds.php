<?php

/**
 * Description of Beds
 *
 * @author shiva
 */
class Beds extends SHV_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->layout->title = "Bed Management";
    }

    public function index()
    {
        $this->scripts_include->includePlugins(array('datatables', 'js'));
        $this->scripts_include->includePlugins(array('datatables', 'css'));
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->get_beds_with_patient_details();
        $data['dept_list'] = $this->get_department_list('array');
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function bed_allocation()
    {
        $this->load->model('master/bed_details');
        $data['beds_list'] = $this->bed_details->all();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function update()
    {
        if ($this->input->is_ajax_request()) {
            $post_values = $this->input->post();
            $this->load->model('master/bed_details');
            $data = array(
                'department' => $post_values['department'],
                'bed_category' => $post_values['bed_category']
            );
            $is_updated = $this->bed_details->update($data, array('id' => $post_values['id']));
            if ($is_updated) {
                echo json_encode(array('msg' => 'Updated Successfully', 'status' => 'ok'));
            } else {
                echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
            }
        } else {
            echo json_encode(array('msg' => 'Failed to update', 'status' => 'nok'));
        }
    }

    public function export_pdf()
    {
        $this->load->helper('mpdf');
        $this->load->model('master/bed_details');

        ini_set("memory_limit", "-1");
        set_time_limit(0);

        $data['beds_list'] = $this->bed_details->get_beds_with_patient_details();
        $html = $this->load->view('master/beds/export_beds_pdf', $data, true);
        $title = array(
            'report_title' => 'BED OCCUPIED REPORT',
            'extradata' => '<div style="text-align:right;"><b>Report Date:</b> ' . date('d-m-Y') . '</div>'
        );

        generate_pdf($html, 'L', $title, 'bed_management_report_' . date('Ymd'), true, true, 'I');
        exit;
    }

    public function refresh_beds()
    {
        $query = "update bed_details 
        set OpdNo=null,bedstatus='Available',treatId=null 
        where id in (select id from bed_details where treatId not in (select treatId from inpatientdetails where status='stillin'))";
        $is_updated = $this->db->query($query);
        if ($is_updated) {
            echo "Bed allotments are refreshed as per IPD. Please close this tab";
        } else {
            echo "Error in refreshing please try again";
        }
    }
}
