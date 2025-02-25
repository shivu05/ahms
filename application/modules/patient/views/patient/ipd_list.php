<style type="text/css">
    .ipd_no,
    .opd_no {
        text-align: right;
        cursor: pointer;
    }


    /*  bhoechie tab */
    div.bhoechie-tab-container {
        z-index: 10;
        border: 1px solid #ddd;
        background-color: #ffffff;
        padding: 0 !important;
        border-radius: 4px;
    }

    div.bhoechie-tab-menu {
        padding-right: 0;
        padding-left: 0;
        padding-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group {
        margin-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group>a {
        margin-bottom: 0;
    }

    div.bhoechie-tab-menu div.list-group>a .glyphicon,
    div.bhoechie-tab-menu div.list-group>a .fa {
        color: #5A55A3;
    }

    div.bhoechie-tab-menu div.list-group>a:first-child {
        border-top-right-radius: 0;
        -moz-border-top-right-radius: 0;
    }

    div.bhoechie-tab-menu div.list-group>a:last-child {
        border-bottom-right-radius: 0;
        -moz-border-bottom-right-radius: 0;
    }

    div.bhoechie-tab-menu div.list-group>a.active,
    div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
    div.bhoechie-tab-menu div.list-group>a.active .fa {
        background-color: #5A55A3;
        background-image: #5A55A3;
        color: #ffffff;
    }

    div.bhoechie-tab-menu div.list-group>a.active:after {
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        margin-top: -13px;
        border-left: 0;
        border-bottom: 13px solid transparent;
        border-top: 13px solid transparent;
        border-left: 10px solid #5A55A3;
    }

    div.bhoechie-tab-content {
        background-color: #ffffff;
        /* border: 1px solid #eeeeee; */
        padding-left: 20px;
        padding-top: 10px;
    }

    div.bhoechie-tab div.bhoechie-tab-content:not(.active) {
        display: none;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> IPD patients:</h3>
            </div>
            <div class="box-body">
                <?php
                if (!empty($this->session->flashdata('noty_msg'))) {
                ?>
                    <div class="bs-component">
                        <div class="alert alert-dismissible alert-success">
                            <button class="close" type="button" data-dismiss="alert">×</button>
                            <p>
                                <?php echo $this->session->flashdata('noty_msg'); ?>
                            </p>
                        </div>
                    </div>
                <?php
                }
                ?>
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">OPD</label>
                        <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                    <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="discharge_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="default_modal_label">Discharge patient</h4>
            </div>
            <div class="modal-body" id="default_modal_body">
                <form class="form-horizontal" id="discharge_form">
                    <input type="hidden" name="dis_admit_data" id="dis_admit_data" class="" autocomplete="off">
                    <input type="hidden" name="dis_treat_id" id="dis_treat_id" class="" autocomplete="off">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>IPD:</td>
                                <td><input type="text" name="dis_ipd" id="dis_ipd" class="form-control" readonly="readonly" autocomplete="off">
                                    <input type="hidden" name="dis_doa" id="dis_doa" />
                                </td>
                            </tr>
                            <tr>
                                <td>Discharge Date:<span class="err_msg">*</span> </td>
                                <td><input type="text" name="dod" id="dod" class="form-control todate required date_picker" required="required" placeholder="Discharge date" required="required" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>Discharge Time:<span class="err_msg">*</span> </td>
                                <td><input type="time" name="discharge_time" id="discharge_time" class="form-control todate required" required="required" placeholder="Discharge time" required="required" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>Notes:</td>
                                <td><textarea name="notes" id="notes" class="form-control" placeholder="Notes"></textarea></td>
                            </tr>
                            <tr>
                                <td>No.Of Days:</td>
                                <td><input name="days" id="days" class="form-control calculated" placeholder="Total days" readonly="readonly" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>Discharged By:<span class="err_msg">*</span> </td>
                                <td><input name="treated" id="treated" class="form-control" placeholder="Doctor Name" autocomplete="off"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Discharge</button>
            </div>
        </div>
    </div>
</div>
<?php
$bed_select = '';
if (!empty($wards)) {
    foreach ($wards as $ward) {
        $bed_select .= '<optgroup label="' . $ward['department'] . '"></optgroup>';
        $beds = explode(',', $ward['beds']);
        $bedstatus = explode(',', $ward['bedstatus']);
        //asort($beds);
        if (!empty($bedstatus)) {
            $i = 0;
            //asort($beds);
            foreach ($bedstatus as $bed) {
                //$is_disabled = ()
                $bed_stat = explode('#', $bed);
                $is_disabled = ($bed_stat[1] == 'not available') ? 'disabled="disabled" style="color:red;"' : '';
                $bed_select .= '<option value="' . $bed_stat[0] . '" ' . $is_disabled . '>' . $bed_stat[0] . '</option>';
            }
        }
    }
}
?>
<div class="modal fade" id="patient_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="default_modal_label">IPD [<span id="ipd_no_span"></span>] Patient information</h5>
            </div>
            <div class="modal-body" id="default_modal_body">
                <form name="patient_form" id="patient_form" method="POST">
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input class="form-control" id="last_name" name="last_name" type="text" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input class="form-control required" id="age" name="age" type="text" placeholder="Age">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <input class="form-control required" id="gender" name="gender" type="text" placeholder="Gender">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="DoAdmission">Date of Admission:</label>
                                <input class="form-control required date_picker ipd_dates" id="DoAdmission" name="DoAdmission" type="text" placeholder="Date of Admisison">
                                <input class="form-control required ipd_dates" id="admit_time" name="admit_time" type="time" placeholder="Time of Admisison">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="DoDischarge">Date of Discharge</label>
                                <input class="form-control date_picker ipd_dates" id="DoDischarge" name="DoDischarge" type="text" placeholder="Date of Discharge">
                                <input class="form-control ipd_dates" id="discharge_time" name="discharge_time" type="time" placeholder="Time of Discharge">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="NofDays">Days</label>
                                <input class="form-control" id="NofDays" name="NofDays" type="text">
                                <input class="form-control" id="cur_bed_no" name="cur_bed_no" type="hidden" />
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="bed_no">Bed No</label>
                                <select class="form-control" name="bed_no" id="bed_no">
                                    <option value="">Select bed</option>
                                    <?php echo $bed_select; ?>
                                </select>
                                <input class="form-control" id="selected_bed_no" name="selected_bed_no" type="hidden" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pat_assigned_doctor">Assigned doctor :</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_assigned_doctor" name="pat_assigned_doctor" placeholder="Doctor" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pat_diagnosis">Diagnosis:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_diagnosis" name="pat_diagnosis" placeholder="Diagnisis" />
                            <input type="hidden" id="ipd" name="ipd" />
                            <input type="hidden" id="opd" name="opd" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pat_treatment">Treatment:</label>
                        <span class="error">Note: Please separate each medicine with <b>,</b>(comma) </span>
                        <div class="controls">
                            <textarea name="pat_treatment" class="form-control" id="pat_treatment" rows="3" style="width: 100%" placeholder="Teatment should be seperated by comma"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_procedure">Procedures:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_procedure" name="pat_procedure" placeholder="Procedure" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>
<style>
    #treatment_modal .form-horizontal .form-group .control-label {
        padding-top: 7px;
        margin-bottom: 0;
        padding-left: 28px !important;
        text-align: left;
    }

    .required:after {
        content: " *";
        color: red;
    }
</style>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="treatment_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Add treatment for <span style="color:#5A55A3" id="treatment_modal_opdno"></span></h4>
            </div>
            <div class="modal-body" style="padding:2px !important;">
                <div class="row row-no-gutters">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-container">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">
                            <div class="list-group">
                                <a href="#" class="list-group-item text-center active">
                                    Agnikarma
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    Cupping
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    Jaloukavacharana
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    Siravyadana
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    Wound dressing
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
                            <input type="hidden" name="ajaxopd" id="ajaxopd" />
                            <input type="hidden" name="ajaxtid" id="ajaxtid" />
                            <input type="hidden" name="ajaxipd" id="ajaxipd" />
                            <!-- flight section -->
                            <div class="bhoechie-tab-content active">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        AGNIKARMA
                                    </h5>
                                    <form class="form-horizontal" id="agnikarma_form" name="agnikarma_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="agnikarma_check" id="agnikarma_check" />
                                                    Refer for Agnikarma
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="ref_date required" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="ref_date" type="text" required="required" name="ref_date" class="form-control agnikarma_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_name required" class="col-md-4 col-sm-4 control-label required">Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="doctor_name" value="" required="required" type="text" name="doctor_name" class="form-control agnikarma_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="treatment_notes" class="col-md-4 col-sm-4 control-label">Medical Notes: (Optional)</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="treatment_notes" value="" type="text" name="treatment_notes" class="form-control agnikarma_inputs" placeholder="Enter Medical notes" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md agnikarma_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md agnikarma_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        Cupping Register
                                    </h5>
                                    <form class="form-horizontal" id="cupping_form" name="cupping_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="cupping_check" id="cupping_check" />
                                                    Refer for Cupping
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="ref_date" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="ref_date" type="text" required="required" name="ref_date" class="form-control cupping_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_name" class="col-md-4 col-sm-4 control-label required">Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="doctor_name" value="" required="required" type="text" name="doctor_name" class="form-control cupping_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="type_of_cupping" class="col-md-4 col-sm-4 control-label required">Type of Cupping:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select id="type_of_cupping" name="type_of_cupping" class="form-control cupping_inputs required">
                                                    <option value="">Choose</option>
                                                    <option value="Dry Cupping">Dry Cupping</option>
                                                    <option value="Wet Cupping">Wet Cupping</option>
                                                    <option value="Fire Cupping">Fire Cupping</option>
                                                    <option value="Oil Cupping">Oil Cupping</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="site_of_application" class="col-md-4 col-sm-4 control-label required">Site of Application:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="site_of_application" value="" required="required" type="text" name="site_of_application" class="form-control cupping_inputs required" placeholder="Enter Site of Application" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="no_of_cups_used" class="col-md-4 col-sm-4 control-label required">Number of Cups Used:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="no_of_cups_used" value="" required="required" type="number" name="no_of_cups_used" class="form-control cupping_inputs required" placeholder="Enter Number of Cups Used" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="treatment_notes" class="col-md-4 col-sm-4 control-label">Medical Notes: (Optional)</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="treatment_notes" value="" type="text" name="treatment_notes" class="form-control cupping_inputs" placeholder="Enter Medical notes" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md cupping_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md cupping_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        Jaloukavacharana Register
                                    </h5>
                                    <form class="form-horizontal" id="jaloukavacharana_form" name="jaloukavacharana_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="jaloukavacharana_check" id="jaloukavacharana_check" />
                                                    Refer for Jaloukavacharana
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="ref_date" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="ref_date" type="text" required="required" name="ref_date" class="form-control jaloukavacharana_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_name" class="col-md-4 col-sm-4 control-label required">Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="doctor_name" value="" required="required" type="text" name="doctor_name" class="form-control jaloukavacharana_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="procedure_details" class="col-md-4 col-sm-4 control-label required">Procedure Details:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="procedure_details" value="" required="required" type="text" name="procedure_details" class="form-control jaloukavacharana_inputs required" placeholder="Enter Procedure Details" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_remarks" class="col-md-4 col-sm-4 control-label">Doctor Remarks:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="doctor_remarks" value="" type="text" name="doctor_remarks" class="form-control jaloukavacharana_inputs" placeholder="Enter Doctor Remarks" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md jaloukavacharana_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md jaloukavacharana_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        Siravyadana Register
                                    </h5>
                                    <form class="form-horizontal" id="siravyadana_form" name="siravyadana_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="siravyadana_check" id="siravyadana_check" />
                                                    Refer for Siravyadana
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="ref_date" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="ref_date" type="text" required="required" name="ref_date" class="form-control siravyadana_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_name" class="col-md-4 col-sm-4 control-label required">Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="doctor_name" value="" required="required" type="text" name="doctor_name" class="form-control siravyadana_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="procedure_details" class="col-md-4 col-sm-4 control-label required">Procedure Details:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="procedure_details" value="" required="required" type="text" name="procedure_details" class="form-control siravyadana_inputs required" placeholder="Enter Procedure Details" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_remarks" class="col-md-4 col-sm-4 control-label">Doctor Remarks:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="doctor_remarks" value="" type="text" name="doctor_remarks" class="form-control siravyadana_inputs" placeholder="Enter Doctor Remarks" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md siravyadana_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md siravyadana_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        Wound Dressing
                                    </h5>
                                    <form class="form-horizontal" id="wound_dressing_form" name="wound_dressing_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="wound_dressing_check" id="wound_dressing_check" />
                                                    Refer for Wound Dressing
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="ref_date" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="ref_date" type="text" required="required" name="ref_date" class="form-control wound_dressing_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="wound_location" class="col-md-4 col-sm-4 control-label required">Wound Location:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="wound_location" value="" required="required" type="text" name="wound_location" class="form-control wound_dressing_inputs required" placeholder="Enter Wound Location" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="wound_type" class="col-md-4 col-sm-4 control-label required">Wound Type:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="wound_type" value="" required="required" type="text" name="wound_type" class="form-control wound_dressing_inputs required" placeholder="Enter Wound Type" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="dressing_material" class="col-md-4 col-sm-4 control-label required">Dressing Material:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="dressing_material" value="" type="text" name="dressing_material" class="form-control wound_dressing_inputs required" placeholder="Enter Dressing Material" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_name" class="col-md-4 col-sm-4 control-label required">Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="doctor_name" value="" required="required" type="text" name="doctor_name" class="form-control wound_dressing_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="next_dressing_date" class="col-md-4 col-sm-4 control-label">Next Dressing Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="next_dressing_date" value="" type="text" name="next_dressing_date" class="form-control wound_dressing_inputs date_picker" placeholder="Select Next Dressing Date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="doctor_remarks" class="col-md-4 col-sm-4 control-label">Doctor Remarks:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="doctor_remarks" value="" type="text" name="doctor_remarks" class="form-control wound_dressing_inputs" placeholder="Enter Doctor Remarks" autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md wound_dressing_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md wound_dressing_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var procedure_div_ids = ['prescription_inputs', 'birth_input', 'ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs', 'physic_inputs', 'pancha_input', 'othr_proc_inputs',
        'kriya_inputs', 'swarnaprashana_inputs', 'agnikarma_inputs', 'cupping_inputs', 'jaloukavacharana_inputs', 'siravyadana_inputs', 'wound_dressing_inputs'
    ];

    $(document).ready(function() {

        $.each(procedure_div_ids, function(i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
            $('.' + procedure_div_ids[i]).prop('disabled', true).trigger("chosen:updated");
        });

        $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
        });

        $('#search_form').on('click', '#search', function() {
            patient_table.clear();
            patient_table.draw();
        });
        $('#search_form #export').on('click', '#export_to_xls', function(e) {
            e.preventDefault();
            //$('#search_form').submit();
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/patient/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: {
                    search_form: form_data
                },
                success: function(data) {
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });

        $('#search_form #export').on('click', '#export_to_pdf', function() {
            $('#search_form').submit();
        });
        var columns = [{
                title: "IPD",
                class: "ipd_no",
                data: function(item) {
                    return '<span class="badge badge-danger" data-ipd_no=' + item.IpNo + '>' + item.IpNo + '</span>';
                }
            },
            {
                title: "OPD",
                class: "opd_no",
                data: function(item) {
                    return item.OpdNo;
                }
            },
            {
                title: "Name",
                data: function(item) {
                    return item.FName;
                }
            },
            {
                title: "Age",
                data: function(item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                data: function(item) {
                    return item.Gender;
                }
            },
            {
                title: "Department",
                data: function(item) {
                    return item.department;
                }
            },
            {
                title: "Diagnosis",
                data: function(item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Ward",
                data: function(item) {
                    return item.WardNo;
                }
            },
            {
                title: "Bed",
                data: function(item) {
                    return item.BedNo;
                }
            },
            {
                title: "Assigned doctor",
                data: function(item) {
                    return item.Doctor;
                }
            },
            {
                title: "DOA",
                data: function(item) {
                    return item.DoAdmission + ' ' + item.admit_time;
                }
            },
            {
                title: "DOD",
                data: function(item) {
                    if (item.status === "stillin") {
                        return item.DoDischarge;
                    } else {
                        return item.DoDischarge + ' ' + item.discharge_time;
                    }
                }
            },
            {
                title: "Days",
                data: function(item) {
                    return item.NofDays;
                }
            },
            {
                title: "Discharge",
                data: function(item) {
                    if (item.status == 'stillin') {
                        return '<button class="btn btn-danger btn-sm discharge" data-doctor_name="' + item.Doctor + '" data-doa="' + item.DoAdmission + '" data-ipd_id=' + item.IpNo + '>Discharge</button>';
                    } else {
                        return '<button class="btn btn-primary btn-sm disabled" disabled="disabled" >Discharged</button>';
                    }
                }
            },
            {
                title: "Action",
                data: function(item) {
                    /*if (item.status == 'stillin') {
                     return "<i class='fa fa-download text-disabled' style='pointer-events: none;'></i>";
                     } else {
                     return '<i title="Download case sheet for IPD :' + item.IpNo + '" data-toggle="tooltip" data-placement="left"' +
                     ' class="fa fa-download hand_cursor text-primary download_case_sheet" data-ipd="' + item.IpNo + '"></i>';
                     
                     }*/
                    return '<i title="Download case sheet for IPD :' + item.IpNo + '" data-toggle="tooltip" data-placement="left"' +
                        ' class="fa fa-download hand_cursor text-primary download_case_sheet" data-ipd="' + item.IpNo + '"></i>' +
                        ' | <i class="fa fa-edit text-primary edit_patient" style="cursor:pointer;" data-opd="' + item.OpdNo + '" data-ipd="' + item.IpNo + '"></i>' +
                        ' | <i class="fa fa-plus hand_cursor add_treatment_details" style="color:#5A55A3" data-ipd="' + item.IpNo + '" data-tid="' + item.treatId + '" data-opd="' + item.OpdNo + '" data-name="' + item.FName + '" id="add_treatment_details"></i>';

                }
            }
        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [{
                className: "",
                "targets": [4]
            }],
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
            },
            'searching': true,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': base_url + 'patient/patient/get_ipd_patients_list',
                'type': 'POST',
                'dataType': 'json',
                'data': function(d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_form').serializeArray()
                    });
                }
            },
            order: [
                [0, 'desc']
            ],
            info: true,
            scrollX: true,
            ordering: false,

        });

        patient_table.on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#patient_table tbody').on('click', '.ipd_no', function() {
            var ipd_id = $(this).find('span').data('ipd_no');
            window.location = base_url + 'patient/Treatment/add_ipd_treatment/' + ipd_id + '';
        });

        $('#patient_table tbody').on('click', '.add_treatment_details', function() {
            var opd = $(this).data('opd');
            var ipd = $(this).data('ipd');
            var name = $(this).data('name');
            var tid = $(this).data('tid');
            $('#treatment_modal #ajaxopd').val(opd);
            $('#treatment_modal #ajaxipd').val(ipd);
            $('#treatment_modal #ajaxtid').val(tid);
            $('#treatment_modal').modal({
                keyboard: false,
                backdrop: 'static'
            }, 'show');
            $('#treatment_modal #treatment_modal_opdno').html('[C.OPD: ' + opd + ' | C.IPD: ' + ipd + ' - ' + name + ']');
        });

        $('#patient_table tbody').on('click', '.discharge', function() {
            $('#discharge_form').find("input[type=text],input[name=days], textarea").val("")
            var ipd_id = $(this).data('ipd_id');
            var doa = $(this).data('doa');
            $('#discharge_modal_box #dis_ipd').val(ipd_id);
            $('#discharge_modal_box #dis_doa').val(doa);
            $('#discharge_modal_box #treated').val($(this).data('doctor_name'));


            $('#discharge_modal_box').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
            var date = new Date(doa);
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                minDate: date.getDate()
            });
        });

        $('#discharge_form').validate();
        $('#discharge_modal_box').on('click', '#btn-ok', function() {
            if ($('#discharge_form').valid()) {
                var form_data = $('#discharge_form').serializeArray();
                $.ajax({
                    url: base_url + 'patient/patient/discharge_patient',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function(res) {
                        if (res == 1) {
                            patient_table.draw();
                            $('#discharge_modal_box').modal('hide');
                            $.notify({
                                title: "Discharge Complete : ",
                                message: "Patient discharged successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                        }
                    }
                });
            }
        });

        $('#discharge_form').on('change', '#dod', function() {
            var doa = $('#dis_doa').val();
            var dod = $('#dod').val();

            $.ajax({
                url: base_url + 'patient/patient/date_difference/',
                type: 'POST',
                dataType: 'json',
                data: {
                    doa: doa,
                    dod: dod
                },
                success: function(res) {
                    if (!isNaN(res)) {
                        $('#days').val(res);
                    }
                }
            });
        });

        $('#patient_form').on('change', '.ipd_dates', function() {
            var doa = $('#DoAdmission').val();
            var dod = $('#DoDischarge').val();
            if (doa && dod) {
                $.ajax({
                    url: base_url + 'patient/patient/date_difference/',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        doa: doa,
                        dod: dod
                    },
                    success: function(res) {
                        if (!isNaN(res)) {
                            $('#NofDays').val(res);
                        }
                    }
                });
            }
        });

        $('#default_modal_box').on('change', '#department', function() {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'master/Department/get_sub_departments',
                type: 'POST',
                data: {
                    dept_id: dept_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.sub_departments.length > 0) {
                        var option = '<option value="">Choose sub department';
                        $.each(response.sub_departments, function(i) {
                            option += '<option value"' + response.sub_departments[i].sub_dept_id + '">' + response.sub_departments[i].sub_dept_name + '</option>'
                        });
                        $('#sub_branch').html(option);
                        $('#sub_branch').removeAttr('disabled');
                    } else {
                        $('#sub_branch').attr('disabled', 'disabled');
                    }
                    if (response.doctors.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.doctors, function(i) {
                            option += '<option value="' + response.doctors[i].doctorname + '">' + response.doctors[i].doctorname + '</option>'
                        });
                        $('#doctor_name').html(option);
                        $('#doctor_name').removeAttr('disabled');
                    } else {
                        $('#doctor_name').attr('disabled', 'disabled');
                    }

                }
            });
        });


        $('#default_modal_box .modal-footer').on('click', '#btn-ok', function() {
            var form_data = $('#send_patient_for_followup').serializeArray();
            $.ajax({
                url: base_url + "patient/patient/followup",
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                }
            });
        });

        $('#patient_table tbody').on('click', '.download_case_sheet', function() {
            var ipd = $(this).data('ipd');
            window.open(base_url + 'patient/print_ipd_case_sheet/' + ipd, '_blank');
            //window.location.href = base_url + 'patient/print_ipd_case_sheet/' + ipd;
        });

        $('#patient_table tbody').on('click', '.edit_patient', function() {
            var opd_id = $(this).data('opd');
            var ipd_id = $(this).data('ipd');
            $('#patient_modal_box #ipd_no_span').html(ipd_id).css('color', 'brown');
            $.ajax({
                url: base_url + "common_methods/get_ipd_patient_details",
                type: 'POST',
                data: {
                    opd: opd_id,
                    ipd: ipd_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#patient_form #first_name').val(response.data.FirstName);
                        $('#patient_form #last_name').val(response.data.LastName);
                        $('#patient_form #age').val(response.data.Age);
                        $('#patient_form #gender').val(response.data.gender);
                        $('#patient_form #opd').val(response.data.OpdNo);
                        $('#patient_form #ipd').val(response.data.IpNo);
                        $('#patient_form #DoAdmission').val(response.data.DoAdmission);
                        if (response.data.admit_time != "00:00") {
                            $('#patient_form #admit_time').val(response.data.admit_time);
                        } else {
                            $('#patient_form #admit_time').val("");
                        }
                        $('#patient_form #DoDischarge').val(response.data.DoDischarge);
                        if (response.data.discharge_time != "00:00") {
                            $('#patient_form #discharge_time').val(response.data.discharge_time);
                        } else {
                            $('#patient_form #discharge_time').val("");
                        }

                        $('#patient_form #NofDays').val(response.data.NofDays);
                        $('#patient_form #pat_assigned_doctor').val(response.data.Doctor);
                        $('#patient_form #pat_diagnosis').val(response.data.diagnosis);
                        $('#patient_form #pat_treatment').val(response.data.Trtment);
                        $('#patient_form #bed_no').val(response.data.BedNo);
                        $('#patient_form #cur_bed_no').val(response.data.BedNo);
                        $('#patient_form #selected_bed_no').val(response.data.BedNo);
                        $('#patient_form #pat_procedure').val(response.data.procedures);
                    }
                }
            });
            $('#patient_modal_box').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });

        $('#patient_form #bed_no').on('change', function() {
            var selected_bed = $(this).val();
            $('#patient_form #selected_bed_no').val(selected_bed);
        });

        $('#patient_form').validate();
        $('#patient_modal_box').on('click', '#btn-ok', function() {

            if ($('#patient_form').valid()) {
                var form_data = $('#patient_form').serializeArray();

                $.ajax({
                    url: base_url + "common_methods/update_ipd_details",
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        $('.loading-box').show();
                    },
                    success: function(response) {
                        $('.loading-box').hide();
                        if (response.status) {
                            $('#patient_modal_box').modal('hide');
                            $.notify({
                                title: "Patient information:",
                                message: "Successfully updated patient info",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Patient information:",
                                message: "Failed to update data please try again",
                                icon: 'fa fa-cross'
                            }, {
                                z_index: 2000,
                                type: "danger"
                            });
                        }
                        patient_table.clear();
                        patient_table.draw();
                    }
                });
            } else {
                $.notify({
                    title: "Patient information:",
                    message: "Failed to update data please try again",
                    icon: 'fa fa-cross'
                }, {
                    z_index: 2000,
                    type: "danger"
                });
            }
        });

        //code for handling pop up for treatment details

        $('#agnikarma_check').change(function() {
            if ($(this).is(":checked")) {
                $('.agnikarma_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.agnikarma_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #agnikarma_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #agnikarma_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            form_data.push({
                name: 'ipd',
                value: $('#ajaxipd').val()
            });
            $.ajax({
                url: base_url + 'store-agnikarma',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "AGNIKARMA",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#agnikarma_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #agnikarma_form #reset').trigger('click');
                        $('#treatment_modal #agnikarma_form #agnikarma_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#cupping_check').change(function() {
            if ($(this).is(":checked")) {
                $('.cupping_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.cupping_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #cupping_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #cupping_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            form_data.push({
                name: 'ipd',
                value: $('#ajaxipd').val()
            });
            $.ajax({
                url: base_url + 'store-cupping',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "CUPPING",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#cupping_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #cupping_form #reset').trigger('click');
                        $('#treatment_modal #cupping_form #cupping_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#jaloukavacharana_check').change(function() {
            if ($(this).is(":checked")) {
                $('.jaloukavacharana_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.jaloukavacharana_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #jaloukavacharana_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #jaloukavacharana_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            form_data.push({
                name: 'ipd',
                value: $('#ajaxipd').val()
            });
            $.ajax({
                url: base_url + 'store-jaloukavacharana',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "JALOUKAVACHARANA",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#jaloukavacharana_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #jaloukavacharana_form #reset').trigger('click');
                        $('#treatment_modal #jaloukavacharana_form #jaloukavacharana_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#siravyadana_check').change(function() {
            if ($(this).is(":checked")) {
                $('.siravyadana_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.siravyadana_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #siravyadana_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #siravyadana_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            form_data.push({
                name: 'ipd',
                value: $('#ajaxipd').val()
            });
            $.ajax({
                url: base_url + 'store-siravyadana',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "SIRAVYADANA",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#siravyadana_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #siravyadana_form #reset').trigger('click');
                        $('#treatment_modal #siravyadana_form #siravyadana_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#wound_dressing_check').change(function() {
            if ($(this).is(":checked")) {
                $('.wound_dressing_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.wound_dressing_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #wound_dressing_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #wound_dressing_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            form_data.push({
                name: 'ipd',
                value: $('#ajaxipd').val()
            });
            $.ajax({
                url: base_url + 'store-wound-dressing',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "WOUND DRESSING",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#wound_dressing_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #wound_dressing_form #reset').trigger('click');
                        $('#treatment_modal #wound_dressing_form #wound_dressing_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    });

    function get_patient_info_by_opd(data) {
        $.ajax({
            type: "POST",
            url: base_url + 'master/Department/get_department_list',
            data: {},
            dataType: 'json',
            success: function(response) {
                var table = "<form name='send_patient_for_followup' id='send_patient_for_followup' method='POST'>";
                table += "<table class='table'>";
                if (response.length > 0) {
                    var dept_option = '';
                    var i = 0;
                    dept_option += "<select name='department' id='department' class='form-control required'>";
                    dept_option += "<option value=''>Select Department</option>";
                    $.each(response, function() {
                        dept_option += "<option value='" + response[i]['dept_unique_code'] + "'>" + response[i]['department'] + "</option>";
                        i++;
                    });
                    var sub_dept = "<select class='form-control required' name='sub_branch' id='sub_branch' disabled='disabled'><option value=''>Choose sub Branch</option><option value='Netra-Roga Vibhaga'>Netra-Roga Vibhaga</option><option value='karna-Nasa-Mukha & Danta Vibhaga'>Karna-Nasa-Mukha & Danta Vibhaga</option></select>";
                    var doc_name = "<div id='doc_info_dept'><select class='form-control' name='doctor_name' id='doctor_name' disabled='true'><option value=''>Choose Doctor</option></select></div>";
                    table += "<tr>";
                    table += "<td colspan=2><b>Name: </b>" + data.FirstName + " " + data.LastName + "</td>";
                    table += "</tr>";
                    table += "<tr><td><b>Age: </b>" + data.Age + "</td>";
                    table += "<td><b>Sex: </b>" + data.gender + "</td></tr>";
                    table += "<tr><td colspan='2'><b>Address: </b> " + data.address + ", " + data.city + "</td></tr>";
                    table += "<tr><td><b>Department:</b></td><td>" + dept_option + "</td></td>";
                    table += "<tr><td><b>Sub Department:</b></td><td>" + sub_dept + "</td></td>";
                    table += "<tr><td><b>Doctor:</b></td><td>" + doc_name + "</td></td>";
                    table += "<tr><td><label>Date:</label></td><td><input type='text' name='date' id='date' class='form-control date_picker required'/><input type='hidden' name='opd' id='opd' value='" + data.OpdNo + "'/></td></td>";
                }
                table += "</table>";
                table += "</form>";
                $('#default_modal_box #default_modal_label').html('Send patient for OPD');
                $('#default_modal_box #default_modal_body').html(table);
                $('#default_modal_box').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
                $('#date').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
            },
            error: function() {},
        });
    }
</script>