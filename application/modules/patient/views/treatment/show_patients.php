<style type="text/css">
    .opd_no {
        cursor: pointer;
        width: 60px !important;
    }

    .type {
        text-align: center;
    }

    .text_center {
        text-align: center;
    }

    .select2-container {
        width: 100% !important;
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
<?php
$physiotherapy_markup = "";
if (!empty($physic_list)) {
    foreach ($physic_list as $row) {
        $physiotherapy_markup .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
    }
}

$other_proc_markup = "";
if (!empty($other_proc_list)) {
    foreach ($other_proc_list as $row) {
        $other_proc_markup .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pencil"></i> OPD Treatment</h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/treatment/export'); ?>">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="control-label sr-only">OPD</label>
                            <input class="form-control" type="text" placeholder="OPD" name="OpdNo" id="OpdNo" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label sr-only">Name</label>
                            <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label sr-only">Date</label>
                            <input class="form-control date_picker" type="text" placeholder="Enter date" name="date" id="date" autocomplete="off">
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label class="control-label  sr-only">Department:</label>
                            <select name="department" id="department" class="form-control select2" data-placeholder="Department">
                                <option value=""></option>
                                <?php
                                if (!empty($dept_list)) {
                                    foreach ($dept_list as $dept) {
                                        echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check" style="padding-top: 5% !important">
                                <input class="form-check-input" type="checkbox" value="1" checked="checked" name="all_patients" id="all_patients">
                                <label class="form-check-label" for="all_patients">
                                    Display all patients
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <div class="">
                                <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                                <div class="btn-group" role="group" id="export">
                                    <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a class="dropdown-item" href="#" id="export_cs">Case sheet</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div id="patient_details" style="margin-top:1%">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="treatment_edit_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="treatment_edit_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="treatment_edit_modal_title">Update patient information: <span id="OPD_NUM" class="text-warning"></span> </h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <h5 class="text-primary" id=""></h5>
                <form method="POST" name="treatment_edit_form" id="treatment_edit_form">
                    <div class="control-group">
                        <label class="control-label" for="pat_name">Name:</label>
                        <div class="controls">
                            <input type="text" class="form-control required" id="pat_name" name="pat_name" placeholder="Name" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_age">Age:</label>
                        <div class="controls">
                            <input type="number" class="form-control required" id="pat_age" name="pat_age" placeholder="Age" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Gender:</label>
                        <div class="controls">
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="Male">Male</label>
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="Female">Female</label>
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="others">Others</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_diagnosis">Diagnosis:</label>
                        <div class="controls">
                            <input type="text" class="form-control required" id="pat_diagnosis" name="pat_diagnosis" placeholder="Diagnisis" />
                            <input type="hidden" id="treat_id" name="treat_id" />
                            <input type="hidden" id="opd" name="opd" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_treatment">Treatment:</label>
                        <span class="error">Note: Please separate each medicine with <b>,</b>(comma) </span>
                        <div class="controls">
                            <textarea name="pat_treatment" class="form-control required" id="pat_treatment" rows="3" style="width: 100%" placeholder="Teatment should be seperated by comma"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_procedure">Procedures:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_procedure" name="pat_procedure" placeholder="Procedure" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="AddedBy">Doctor:</label>
                        <div class="controls">
                            <select class="form-control select2 required" id="AddedBy" name="AddedBy">
                                <option value="">Choose doctor</option>
                            </select>
                            <!--<input type="text" class="form-control required" id="AddedBy" name="AddedBy" placeholder="Docotr"/>-->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-update">Save changes</button>
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
<!-- Large modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="treatment_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Update treatment for <span style="color:#5A55A3" id="treatment_modal_opdno"></span></h4>
            </div>
            <div class="modal-body" style="padding:2px !important;">
                <div class="row row-no-gutters">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-container">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">
                            <div class="list-group">
                                <a href="#" class="list-group-item active text-center">
                                    PHYSIOTHERAPY
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    OTHERPROCEDURES
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    KRIYAKALPA
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    X-RAY
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    USG
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    ECG
                                </a>
                                <a href="#" class="list-group-item text-center">
                                    Swarnaprashana
                                </a>
                                <a href="#" class="list-group-item text-center">
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
                            <!-- flight section -->
                            <div class="bhoechie-tab-content active">
                                <!-- PHYSIOTHERAPY FORM - STARTS -->
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        PHYSIOTHERAPY
                                    </h5>
                                    <form class="form-horizontal" id="physiotherapy_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="physiotherapy_check" id="physiotherapy_check" />
                                                    Refer for PHYSIOTHERAPY
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="physic_name" class="col-md-4 col-sm-4 control-label pull-left required">Physiotherapy name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select id="physic_name" name="physic_name" class="form-control chosen-select physic_inputs" required="required">
                                                    <option value="">Choose</option>
                                                    <?= $physiotherapy_markup ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="start_date" class="col-md-4 col-sm-4 control-label required">Start date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="physic_date" type="text" name="start_date" class="form-control date_picker physic_inputs required" placeholder="Enter Start date" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="start_date" class="col-md-4 col-sm-4 control-label required">End date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="physic_date" type="text" name="end_date" class="form-control date_picker physic_inputs required" placeholder="Enter End date" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="physic_doc" class="col-md-4 col-sm-4 control-label required">Physician name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="physic_doc" type="text" name="physic_doc" class="form-control physic_inputs required" placeholder="Enter Doctor name" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md physic_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md physic_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- PHYSIOTHERAPY FORM - ENDS -->
                            </div>
                            <!-- train section -->
                            <div class="bhoechie-tab-content">
                                <!-- OTHERPROCEDURES FORM STARTS -->
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        OTHERPROCEDURES
                                    </h5>
                                    <form class="form-horizontal" id="otherprocedure_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="other_proc_check" id="other_proc_check" />
                                                    Refer for OTHERPROCEDURE
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="other_proc_name required" class="col-md-4 col-sm-4 control-label pull-left required">Procedure name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select id="other_proc_name" type="text" name="other_proc_name" class="chosen-select form-control othr_proc_inputs" required="required">
                                                    <option value="">Choose</option>
                                                    <?= $other_proc_markup ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="other_start_date required" class="col-md-4 col-sm-4 control-label required">Start date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="other_start_date" type="text" name="other_start_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter Start date" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="other_end_date required" class="col-md-4 col-sm-4 control-label required">End date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="other_end_date" type="text" name="other_end_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter End date" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="oth_proc_doc required" class="col-md-4 col-sm-4 control-label required">Physician name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="oth_proc_doc" type="text" name="oth_proc_doc" class="form-control othr_proc_inputs" placeholder="Enter Doctor name" autocomplete="off" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md othr_proc_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md othr_proc_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- OTHERPROCEDURE ENDS HERE -->
                            </div>

                            <!-- hotel search -->
                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        KRIYAKALPA
                                    </h5>
                                    <form class="form-horizontal" id="kriyakalpa_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="kriyakalpa_check" id="kriyakalpa_check" />
                                                    Refer for Kriyakalpa
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="kriya_procedures required" class="col-md-4 col-sm-4 control-label required">Procedures:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea id="kriya_procedures" required type="text" name="kriya_procedures" class="form-control kriya_inputs" placeholder="Enter Procedures"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="kriya_start_date required" class="col-md-4 col-sm-4 control-label required">Date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="kriya_start_date" required type="text" name="kriya_start_date" class="form-control date_picker kriya_inputs" placeholder="Enter Date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md kriya_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md kriya_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        X-RAY
                                    </h5>
                                    <form class="form-horizontal" id="xray_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="xray_check" id="xray_check" />
                                                    Refer for X-Ray
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="partxray required" class="col-md-4 col-sm-4 control-label required">Part of X-Ray:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="partxray" type="text" name="partxray" class="form-control xray_inputs" placeholder="Enter X-Ray Part" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="xraydocname required" class="col-md-4 col-sm-4 control-label required">Referred Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="xraydocname" value="" type="text" name="xraydocname" class="form-control xray_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="xraydate required" class="col-md-4 col-sm-4 control-label required">Referred date:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="xraydate" type="text" name="xraydate" class="form-control date_picker xray_inputs" placeholder="Enter referred date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md xray_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md xray_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bhoechie-tab-content">
                                USG
                                <h4>Coming soon</h4>
                            </div>
                            <div class="bhoechie-tab-content">
                                ECG
                                <h4>Coming soon</h4>
                            </div>
                            <div class="bhoechie-tab-content">
                                <div class="row">
                                    <h5 class="text-capitalize headline" style="padding-left: 15px !important;margin-top: 3px !important;font-size: larger;font-weight: bold;">
                                        Swarnaprashana
                                    </h5>
                                    <form class="form-horizontal" id="swarnaprashana_form" name="swarnaprashana_form">
                                        <div class="form-group col-sm-10 col-md-10">
                                            <div class="checkbox">
                                                <label class="" style="padding-left:46px !important;">
                                                    <input type="checkbox" name="swarnaprashana_check" id="swarnaprashana_check" />
                                                    Refer for Swarnaprashana
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="date_month required" class="col-md-4 col-sm-4 control-label required">Date and Month of Swarnaprashana:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="date_month" type="text" name="date_month" class="form-control swarnaprashana_inputs date_picker required" placeholder="Select date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="dpse_time required" class="col-md-4 col-sm-4 control-label required">Time:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="dpse_time" type="time" name="dose_time" class="form-control swarnaprashana_inputs required" placeholder="Enter referred date" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-10 col-md-10">
                                            <label for="consultant required" class="col-md-4 col-sm-4 control-label required">Referred Doctor Name:</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input id="consultant" value="" type="text" name="consultant" class="form-control swarnaprashana_inputs required" placeholder="Enter Doctor Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-5 col-md-10" style="padding-left: 5% !important;">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-md swarnaprashana_inputs"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="reset" id="reset" class="btn btn-danger btn-md swarnaprashana_inputs"><i class="fa fa-refresh"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="bhoechie-tab-content">
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
                                        Jaloukavacharana
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
                                                <textarea id="procedure_details" value="" type="text" name="procedure_details" class="form-control jaloukavacharana_inputs required" placeholder="Enter Procedure Details" autocomplete="off"></textarea>
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
                                        Siravyadana
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
                                                <textarea id="procedure_details" value="" type="text" name="procedure_details" class="form-control siravyadana_inputs required" placeholder="Enter Procedure Details" autocomplete="off"></textarea>
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

        /*$('#swarnaprashana_form #date_month').datepicker({
         format: 'MM yyyy',
         viewMode: "months",
         minViewMode: "months",
         autoclose: true
         });*/


        $.each(procedure_div_ids, function(i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
            $('.' + procedure_div_ids[i]).prop('disabled', true).trigger("chosen:updated");
        });

        $('#physiotherapy_check').change(function() {
            if ($(this).is(":checked")) {
                $('.physic_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.physic_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.physic_inputs').attr('disabled', 'disabled');
                $('.physic_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#treatment_modal #physiotherapy_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #physiotherapy_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            $.ajax({
                url: base_url + 'store-physiotherapy',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "PHYSIOTHERAPY",
                        message: response.message,
                        icon: 'fa fa-check'
                    }, {
                        element: '#physiotherapy_form',
                        type: response.success
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #physiotherapy_form #reset').trigger('click');
                        $('#treatment_modal #physiotherapy_form #physiotherapy_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#other_proc_check').change(function() {
            if ($(this).is(":checked")) {
                $('.othr_proc_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.othr_proc_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.othr_proc_inputs').attr('disabled', 'disabled');
                $('.othr_proc_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#treatment_modal #otherprocedure_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #otherprocedure_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            $.ajax({
                url: base_url + 'store-other-procedures',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "OTHER-PROCEDURES",
                        message: response.message,
                        icon: 'fa fa-check'
                    }, {
                        element: '#otherprocedure_form',
                        type: response.success
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #otherprocedure_form #reset').trigger('click');
                        $('#treatment_modal #otherprocedure_form #other_proc_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#kriyakalpa_check').change(function() {
            if ($(this).is(":checked")) {
                $('.kriya_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.kriya_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kriya_inputs').attr('disabled', 'disabled');
                $('.kriya_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#treatment_modal #kriyakalpa_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #kriyakalpa_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            $.ajax({
                url: base_url + 'store-kriyakalpa',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "KRIYAKALPA",
                        message: response.message,
                        icon: 'fa fa-check'
                    }, {
                        element: '#kriyakalpa_form',
                        type: response.success
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #kriyakalpa_form #reset').trigger('click');
                        $('#treatment_modal #kriyakalpa_form #kriyakalpa_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#xray_check').change(function() {
            if ($(this).is(":checked")) {
                $('.xray_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#xraydocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.xray_inputs').attr('disabled', 'disabled');
            }
        });

        $('#treatment_modal #xray_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #xray_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            $.ajax({
                url: base_url + 'store-xray',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "X-RAY",
                        message: response.message,
                        icon: 'fa fa-check'
                    }, {
                        element: '#xray_form',
                        type: response.success
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #xray_form #reset').trigger('click');
                        $('#treatment_modal #xray_form #xray_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $('#swarnaprashana_check').change(function() {
            if ($(this).is(":checked")) {
                $('.swarnaprashana_inputs').removeAttr('disabled');
                //copy_input_text('#doctor_name', '#xraydocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.swarnaprashana_inputs').attr('disabled', 'disabled');
            }
        });
        $('#treatment_modal #swarnaprashana_form').validate();
        $('#treatment_modal #swarnaprashana_form').submit(function(e) {
            e.preventDefault();
            var form_data = $('#treatment_modal #swarnaprashana_form').serializeArray();
            form_data.push({
                name: 'opd',
                value: $('#ajaxopd').val()
            });
            form_data.push({
                name: 'tid',
                value: $('#ajaxtid').val()
            });
            $.ajax({
                url: base_url + 'store-swarnaprashana',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(response) {
                    $.notify({
                        title: "SWARNAPRASHANA",
                        message: response.message,
                        icon: 'fa ' + response.icon
                    }, {
                        element: '#swarnaprashana_form',
                        type: response.type
                    });
                    if (response.status == 'OK') {
                        $('#treatment_modal #swarnaprashana_form #reset').trigger('click');
                        $('#treatment_modal #swarnaprashana_form #swarnaprashana_check').attr('checked', false).trigger('change');
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

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
                value: null
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
                url: base_url + 'reports/Opd/export_patients_list',
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

        $('#search_form #export').on('click', '#export_cs', function(e) {
            e.preventDefault();
            $('<input>').attr({
                type: 'hidden',
                id: 'exp_type',
                name: 'exp_type',
                value: btoa('cs')
            }).appendTo('#search_form');
            $('#search_form').submit();
        });
        var columns = [{
                title: "OPD",
                class: "opd_no",
                data: function(item) {
                    if (item.attndedon) {
                        return '<span class="badge badge-primary" disabled="disabled">' + item.OpdNo + '</span>';
                    } else {
                        return '<span class="badge badge-danger">' + item.OpdNo + '</span>';
                    }
                }
            },
            {
                title: "Name",
                data: function(item) {
                    return item.FirstName + ' ' + item.LastName;
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
                    return item.gender;
                }
            },
            //            {
            //                title: "Address",
            //                data: function (item) {
            //                    return item.address;
            //                }
            //            },
            {
                title: "City",
                data: function(item) {
                    var patient_address = item.city;
                    if (item.address) {
                        patient_address = item.address + ', ' + item.city;
                    }
                    return patient_address;
                }
            },
            {
                title: "Occupation",
                data: function(item) {
                    return item.occupation;
                }
            },
            {
                title: "Diagnosis",
                data: function(item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Department",
                data: function(item) {
                    return item.department;
                }
            },
            {
                title: "Came on",
                data: function(item) {
                    return item.CameOn;
                }
            },
            {
                title: "Type",
                class: 'type',
                data: function(item) {
                    if (item.PatType == 'Old Patient') {
                        return '<span style="color:orange">O</span>';
                    } else if (item.PatType == 'New Patient') {
                        return '<span style="color:green">N</span>';
                    } else {
                        return '';
                    }
                }
            },
            {
                title: "C.Sheet",
                class: "text_center",
                data: function(item) {
                    return '<i class="fa fa-download hand_cursor text-primary download_case_sheet" data-opd="' + item.OpdNo + '" data-treat_id="' + item.ID + '"></i>';
                }
            },
            {
                title: "Action",
                data: function(item) {
                    return '<i class="fa fa-edit  hand_cursor edit_treatment_data" data-opd="' + item.OpdNo + '" data-treat_id="' + item.ID + '"></i>' +
                        ' | <i class="fa fa-plus hand_cursor add_treatment_details" style="color:#5A55A3" data-tid="' + item.ID + '" data-opd="' + item.OpdNo + '" data-name="' + item.FirstName + '" id="add_treatment_details"></i>';
                }
            },
            {
                title: "Status",
                class: "text_center",
                data: function(item) {
                    if (item.attndedon === '' || item.attndedon === null) {
                        return '<i class="fa fa-clock-o hand_cursor text-warning text-center" data-toggle="tooltip" data-placement="left" title="Treatment Pending" aria-hidden="true"></i>';
                    } else {
                        return '<i class="fa fa-check-circle hand_cursor text-success text-center" data-toggle="tooltip" data-placement="left" title="Treatment Completed" aria-hidden="true"></i>';
                    }
                }
            }
        ];
        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            "fnInitComplete": function(oSettings, json) {
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            },
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-treat_id', data.ID);
                $(row).attr('data-opd_id', data.OpdNo);
            },
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
            'ordering': false,
            'ajax': {
                'url': base_url + 'patient/treatment/get_patients_for_treatment',
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
            "scrollX": true
        });
        $('#patient_table tbody').on('click', 'tr td.opd_no', function() {
            var data = patient_table.row(this).data();
            window.location.href = base_url + 'add-opd-treatment/' + data.OpdNo + '/' + data.ID;
        });
        $('#patient_table tbody').on('click', '.download_case_sheet', function() {
            var opd = $(this).data('opd');
            var treat_id = $(this).data('treat_id');
            window.location.href = base_url + 'patient/Treatment/print_case_sheet/' + opd + '/' + treat_id;
        });
        $('#patient_table tbody').on('click', '.add_treatment_details', function() {
            var opd = $(this).data('opd');
            var name = $(this).data('name');
            var tid = $(this).data('tid');
            $('#treatment_modal #ajaxopd').val(opd);
            $('#treatment_modal #ajaxtid').val(tid);
            $('#treatment_modal').modal({
                keyboard: false,
                backdrop: 'static'
            }, 'show');
            $('#treatment_modal #treatment_modal_opdno').html('[' + opd + ' - ' + name + ']');
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
                            option += '<option value"' + response.doctors[i].doctorname + '">' + response.doctors[i].doctorname + '</option>'
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
            alert();
            var form_data = $('#send_patient_for_followup').serializeArray();
            $.ajax({
                url: base_url + "reports/Opd/followup",
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                }
            });
        });
        $('#treatment_edit_form').validate();
        $('#patient_table tbody').on('click', '.edit_treatment_data', function() {
            $('#treatment_edit_form .control-group').removeClass('error');
            var opd = $(this).data('opd');
            var treat_id = $(this).data('treat_id');

            $.ajax({
                url: base_url + 'patient/treatment/fetch_treatment_data',
                type: 'POST',
                data: {
                    'opd': opd,
                    'treat_id': treat_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#treatment_edit_form #pat_name').val(response.data.FirstName);
                    $('#treatment_edit_form #pat_age').val(response.data.Age);
                    $("input[name=pat_gender][value='" + response.data.gender + "']").prop("checked", true);
                    $('#treatment_edit_form #pat_treatment').val(response.data.Trtment);
                    $('#treatment_edit_form #treat_id').val(response.data.ID);
                    $('#treatment_edit_form #opd').val(response.data.OpdNo);
                    $('#treatment_edit_form #pat_diagnosis').val(response.data.diagnosis);
                    $('#treatment_edit_form #pat_procedure').val(response.data.procedures);
                    $('#treatment_edit_form #AddedBy').val(response.data.AddedBy);
                    var option = '';
                    $.each(response.doctors_list, function(i) {
                        var is_selected = '';
                        if (response.data.AddedBy == response.doctors_list[i].user_name) {
                            is_selected = 'selected="selected"';
                        }
                        option += '<option ' + is_selected + ' value="' + response.doctors_list[i].user_name + '">' + response.doctors_list[i].user_name + '</option>';
                    });
                    $('#treatment_edit_form #AddedBy').html(option);
                }
            });
            $('#treatment_edit_modal #OPD_NUM').html("Center OPD: " + opd);
            $('#treatment_edit_modal').modal('show');

        });

        $('#treatment_edit_modal').on('click', '#btn-update', function() {
            var form_data = $('#treatment_edit_modal #treatment_edit_form').serializeArray();
            if ($('#treatment_edit_form').valid()) {
                $.ajax({
                    url: base_url + 'patient/treatment/update_treatment_details',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function(response) {
                        $('#treatment_edit_modal').modal('hide');
                        if (response.status == true) {
                            $.notify({
                                title: "Treatment:",
                                message: "Updated successfully",
                                icon: 'fa fa-check',
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Treatment:",
                                message: "Failed to update",
                                icon: 'fa fa-check',
                            }, {
                                type: "danger"
                            });
                        }
                        $('#search_form #search').trigger('click');
                    }
                });
            } else {
                alert('Patient data can not be empty');
            }
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