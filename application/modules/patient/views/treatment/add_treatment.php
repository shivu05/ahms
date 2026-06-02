<style type="text/css">
    .accordion h1,
    .accordion a {
        color: #007b5e;
    }

    .accordion .btn-link {
        font-weight: 400;
        color: #007b5e;
        background-color: transparent;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: bold;
        padding-left: 25px;
    }

    .accordion .card-body {
        border-top: 2px solid #007b5e;
        font-size: 12px !important;
    }

    .accordion .card-header .btn.collapsed .fa.main {
        display: none;
    }

    .accordion .card-header .btn .fa.main {
        background: #007b5e;
        padding: 10px 8px;
        color: #ffffff;
        width: 30px;
        height: 35px;
        position: absolute;
        left: -1px;
        top: 10px;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
        display: block;
    }

    .treatment-screen .box {
        border-radius: 6px;
    }

    .patient-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .patient-summary img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #d7dde3;
    }

    .patient-summary-name {
        min-width: 180px;
    }

    .patient-summary-name h3 {
        margin: 0 0 4px;
        font-size: 20px;
        line-height: 1.2;
    }

    .patient-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(120px, 1fr));
        gap: 10px 18px;
        flex: 1;
        min-width: 320px;
    }

    .summary-label,
    .visit-label {
        display: block;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .summary-value,
    .visit-value {
        font-weight: 600;
        color: #263238;
        word-break: break-word;
    }

    .section-title {
        margin: 0 0 12px;
        font-size: 16px;
        font-weight: 700;
        color: #263238;
    }

    .visit-history-panel .box-body {
        max-height: 430px;
        overflow: auto;
    }

    .visit-item {
        border-bottom: 1px solid #eef1f4;
        padding: 10px 0;
    }

    .visit-item:first-child {
        padding-top: 0;
    }

    .visit-item:last-child {
        border-bottom: 0;
    }

    .visit-diagnosis {
        margin: 4px 0;
        font-weight: 700;
        color: #374151;
    }

    .visit-treatment {
        color: #4b5563;
        font-size: 12px;
        line-height: 1.35;
    }

    .clinical-entry .form-control {
        min-height: 42px;
    }

    .template-toolbar {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 6px;
    }

    .template-toolbar select {
        flex: 1;
    }

    .action-bar {
        border-top: 1px solid #edf0f2;
        padding: 15px;
        text-align: right;
        background: #fbfcfd;
    }

    @media (max-width: 991px) {
        .patient-summary-grid {
            grid-template-columns: repeat(2, minmax(120px, 1fr));
        }
    }
</style>
<?php
$panchakarma_markup = "";
if (!empty($pancha_procedures)) {
    foreach ($pancha_procedures as $proc) {
        $panchakarma_markup .= "<option value='" . $proc['proc_name'] . "'>" . $proc['proc_name'] . "</option>";
    }
}
$lab_categories_markup = "";
if (!empty($lab_categories)) {
    foreach ($lab_categories as $cat) {
        $lab_categories_markup .= '<option value="' . $cat['lab_cat_id'] . '">' . $cat['lab_cat_name'] . '</option>';
    }
}

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
$curr_dept = (isset($current_treatment['department']) && $current_treatment['department'] != '') ? $current_treatment['department'] : '';
$selected_doc = '';
if (!empty($doctors)) {
    $latest_data = NULL;
    if (!empty($treatment_details)) {
        end($treatment_details);
        $latest_data = key($treatment_details);
    }
    $selected_doc = (isset($current_treatment['AddedBy']) && $current_treatment['AddedBy'] != "") ? $current_treatment['AddedBy'] : '';
    if ($selected_doc === '' && $latest_data !== NULL && isset($treatment_details[$latest_data]['AddedBy'])) {
        $selected_doc = $treatment_details[$latest_data]['AddedBy'];
    }
}
$current_visit_date = isset($current_treatment['CameOn']) ? $current_treatment['CameOn'] : '';
$last_visit_date = isset($visit_summary['last_visit_date']) ? $visit_summary['last_visit_date'] : '';
$total_visits = isset($visit_summary['total_visits']) ? $visit_summary['total_visits'] : 0;
?>
<div class="treatment-screen">
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="">
            <div class="">
                <form name="add_treatment_form" id="add_treatment_form" action="<?php echo base_url('patient/treatment/save'); ?>" method="POST">
                    <!--hiddden fields-->
                    <input type="hidden" name="treat_id" id="treat_id" value="<?php echo $treat_id; ?>" />
                    <input type="hidden" name="opd_no" id="opd_no" value="<?php echo $opd; ?>" />
                    <input type="hidden" name="department" id="department" value="<?php echo $curr_dept; ?>" />
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="clearfix" style="margin-bottom: 12px;">
                                <a class="btn btn-info btn-sm pull-right" style="color:white;" href="<?php echo base_url('patient/treatment/show_patients'); ?>"><i class="fa fa-backward"></i> Back</a>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title section-title">Patient Summary</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="patient-summary">
                                                <img class="img-responsive" src="<?php echo base_url('assets/img/user_icon.png') ?>" alt="Patient photo" />
                                                <div class="patient-summary-name">
                                                    <h3><?php echo html_escape(trim($patient_details['FirstName'] . ' ' . $patient_details['LastName'])); ?></h3>
                                                    <span class="label label-primary">OPD <?php echo html_escape($patient_details['OpdNo']); ?></span>
                                                </div>
                                                <div class="patient-summary-grid">
                                                    <div><span class="summary-label">Age</span><span class="summary-value"><?php echo html_escape($patient_details['Age']); ?></span></div>
                                                    <div><span class="summary-label">Gender</span><span class="summary-value"><?php echo html_escape($patient_details['gender']); ?></span></div>
                                                    <div><span class="summary-label">Place</span><span class="summary-value"><?php echo html_escape($patient_details['city']); ?></span></div>
                                                    <div><span class="summary-label">Department</span><span class="summary-value"><?php echo $curr_dept ? html_escape(ucfirst(strtolower(str_replace('_', ' ', $curr_dept)))) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Doctor</span><span class="summary-value"><?php echo $selected_doc ? html_escape($selected_doc) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Current Visit</span><span class="summary-value"><?php echo $current_visit_date ? html_escape($current_visit_date) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Last Visit</span><span class="summary-value"><?php echo $last_visit_date ? html_escape($last_visit_date) : '-'; ?></span></div>
                                                    <div><span class="summary-label">Total Visits</span><span class="summary-value"><?php echo html_escape($total_visits); ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-solid visit-history-panel">
                                        <div class="box-header with-border">
                                            <h3 class="box-title section-title">Recent Visits</h3>
                                            <div class="box-tools pull-right">
                                                <a class="btn btn-default btn-xs" href="<?php echo base_url('patient/treatment/show_patients'); ?>">View full history</a>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <?php if (!empty($recent_visits)): ?>
                                                <?php foreach ($recent_visits as $visit): ?>
                                                    <div class="visit-item">
                                                        <span class="visit-label"><?php echo html_escape($visit['attndedon'] ?: $visit['CameOn']); ?></span>
                                                        <div class="visit-diagnosis"><?php echo html_escape($visit['diagnosis'] ?: '-'); ?></div>
                                                        <div class="visit-treatment"><?php echo html_escape(strlen($visit['Trtment']) > 120 ? substr($visit['Trtment'], 0, 120) . '...' : $visit['Trtment']); ?></div>
                                                        <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
                                                            <?php echo html_escape($visit['attndedby'] ?: $visit['AddedBy']); ?> &middot;
                                                            <?php echo html_escape(ucfirst(strtolower(str_replace('_', ' ', $visit['department'])))); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted">No previous visits recorded.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-solid clinical-entry">
                                <div class="box-header with-border">
                                    <h3 class="box-title section-title">Clinical Entry</h3>
                                </div>
                                <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <input type="checkbox" name="add_prescription" id="add_prescription" style="margin-top: 7px;" />
                                    <label>Add prescription: <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label>Complaints: <span class="text-danger">*</span></label>
                                    <!--<select class="form-control select2 prescription_inputs" name="complaints[]" id="complaints" multiple="multiple" data-placeholder="Choose complaints">
                                        <option value="">choose complaints</option>
                                    <?php
                                    if (!empty($complaints)) {
                                        foreach ($complaints as $row) {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <textarea class="form-control prescription_inputs" onkeyup="this.value = this.value.toUpperCase();" name="complaints" id="complaints"></textarea>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label>Diagnosis: <span class="text-danger">*</span></label>
                                    <!--<select class="form-control select2 prescription_inputs" name="diagnosis[]" id="diagnosis" multiple="multiple" data-placeholder="Choose diagnosis">
                                        <option value="">choose diagnosis</option>
                                    <?php
                                    if (!empty($diagnosis)) {
                                        foreach ($diagnosis as $row) {
                                            echo '<option value="' . $row['diagnosis_name'] . '">' . $row['diagnosis_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <input type="hidden" name="diagnosis_master_id" id="diagnosis_master_id" value="" />
                                    <select class="form-control prescription_inputs" name="diagnosis" id="diagnosis" style="width:100%;"></select>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label>Treatment: <span class="text-danger">*</span></label>
                                    <div class="template-toolbar">
                                        <select class="form-control prescription_inputs" id="treatment_template" <?php echo empty($treatment_templates) ? 'disabled="disabled"' : ''; ?>>
                                            <option value="">Use Template</option>
                                            <?php if (!empty($treatment_templates)): ?>
                                                <?php foreach ($treatment_templates as $template): ?>
                                                    <option value="<?php echo html_escape($template['id']); ?>" data-treatment="<?php echo html_escape($template['treatment_text']); ?>">
                                                        <?php echo html_escape($template['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <button type="button" class="btn btn-default btn-sm prescription_inputs" id="apply_template" <?php echo empty($treatment_templates) ? 'disabled="disabled"' : ''; ?>>
                                            <i class="fa fa-magic"></i> Apply
                                        </button>
                                    </div>
                                    <!-- <select class="form-control select2 prescription_inputs" name="treatment[]" id="treatment" multiple="multiple" data-placeholder="Choose Medicines">
                                        <option value="">choose medicine</option>
                                    <?php
                                    if (!empty($medicines)) {
                                        foreach ($medicines as $row) {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    </select>-->
                                    <textarea class="form-control prescription_inputs" onkeyup="this.value = this.value.toUpperCase();" name="treatment" id="treatment"></textarea>
                                </div>
                                <!--<div class="col-md-6 col-sm-12">
                                    <label>Panchakarma procedures: <span class="text-danger">*</span></label>
                                    <textarea class="form-control prescription_inputs" name="panch_procedures" id="panch_procedures"></textarea>
                                </div-->
                                <div class="col-md-6 col-sm-12">
                                    <label>Notes:</label>
                                    <textarea class="form-control prescription_inputs" name="notes" id="notes"></textarea>
                                </div>
                                <!--<div class="col-md-2 col-sm12">
                                    <label>Days</label>
                                    <input type="text" name="panch_days" id="panch_days" placeholder="Days" class="form-control" value="7"/>
                                </div>-->
                            </div>
                            <hr />
                            <!-- <div class="row">
                                 <div class="col-md-4">
                                     <label>Medicine name: <span class="text-danger">*</span></label>
                                     <select name="medicine" id="medicine" class="form-control  required chosen-select">
                                         <option>Choose medicine</option>

                            <?php
                            if (!empty($medicines)) {
                                foreach ($medicines as $medicine) {
                                    echo '<option value="' . $medicine['id'] . '">' . $medicine['name'] . '</option>';
                                }
                            }
                            ?>
                                     </select>
                                 </div>
                                 <div class="col-md-4">
                                     <label>When to take: <span class="text-danger">*</span></label>
                                     <select name="med_freq" id="med_freq" class="form-control  required chosen-select">
                                         <option>Choose frequency</option>
                            <?php
                            if (!empty($med_freqs)) {
                                foreach ($med_freqs as $frq) {
                                    echo '<option value="' . $frq['med_freq'] . '">' . $frq['med_freq'] . '</option>';
                                }
                            }
                            ?>
                                     </select>
                                 </div>
                                 <div class="col-md-2">
                                     <label>Days: <span class="text-danger">*</span></label>
                                     <input type="text" name="days" id="days" class="form-control required numbers-only" placeholder="Days" />
                                 </div>
                                 <div class="col-md-2">
                                     <label>Before food: <span class="text-danger">*</span></label>
                                     <input type="checkbox" name="before_food" id="before_food" class="form-control required numbers-only" />
                                 </div>
                             </div>
                             <hr/>-->
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <label>Doctor: </label>
                                    <!--<input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?= $current_treatment['AddedBy']; ?>" readonly="readonly"/>-->
                                    <select class="form-control " name="doctor_name" id="doctor_name" readonly="readonly">
                                        <option value="">Choose doctor</option>
                                        <?php
                                        if (!empty($doctors)) {
                                            foreach ($doctors as $doctor) {
                                                $selected = (strtolower($selected_doc) == strtolower($doctor['user_name'])) ? 'selected="selected"' : '';
                                                echo '<option value="' . $doctor['user_name'] . '" ' . $selected . '>' . $doctor['user_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <label>Treated date: </label>
                                    <input type="text" name="attened_date" id="attened_date" class="form-control date_picker prescription_inputs" value="<?php echo date('Y-m-d') ?>" />
                                </div>
                            </div><!-- end of trearment div-->
                                </div>
                            </div>

                            <!-- Tests -->
                            <hr />
                            <div class="row" style="margin-bottom: 2rem;">
                                <div class="col-md-12">
                                    <h3>Procedures:</h3>
                                    <div class="bs-component">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a class="nav-link disabled" data-toggle="tab" href="#birth" title="disabled">Birth</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ecg">ECG</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#usg">USG</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#xray">X-Ray</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kshara">Ksharasutra</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#surgery">Surgery</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lab">Laboratory</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#panchakarma_treatment">Panchakarma</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#physiotherapy">Physiotherapy</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#othertherapy">Other Procedures</a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kriyakalpa">Kriyakalpa</a></li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade" id="birth">
                                                <div id="Birth">
                                                    <h5 style="margin-top: 2%;"><input type="checkbox" name="birth_check" id="birth_check" /> Birth Register</h5>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="delivery">Details of delivery:</label>
                                                            <div class="controls">
                                                                <textarea id="delivery" type="text" name="delivery" placeholder="Delivery details" class="form-control birth_input"></textarea>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="birthdate">Birth Date/Time Of Baby:</label>
                                                            <div class="controls">
                                                                <input id="birthdate" type="text" name="birthdate" class="form-control birth_input date_picker" placeholder="Birth date" autocomplete="off">
                                                                <br>
                                                                <input id="birthtime" placeholder="Time __:__" type="text" name="birthtime" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="weight">Baby weight:</label>
                                                            <div class="controls">
                                                                <input id="weight" type="text" name="weight" class="form-control birth_input" placeholder="Baby weight">
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="weight">Baby Gender:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" id="babygender" name="babygender">
                                                                    <option value="">Choose one</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                    <option value="others">others</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="babyblood">Baby Blood Group:</label>
                                                            <div class="controls">
                                                                <input id="babyblood" type="text" name="babyblood" class="form-control birth_input" placeholder="Baby blood group">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="motherblood">Mother Blood Group:</label>
                                                            <div class="controls">
                                                                <input id="motherblood" type="text" name="motherblood" placeholder="Mother blood group" class="form-control birth_input">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="fathername">Father Name:</label>
                                                            <div class="controls">
                                                                <input id="fathername" type="text" name="fathername" placeholder="Father name" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgeonname">Name Of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="surgeonname" value="" type="text" name="surgeonname" placeholder="surgeon name" class="form-control birth_input" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anaesthetic">Name Of Anesthetic:</label>
                                                            <div class="controls">
                                                                <input id="anaesthetic" type="text" name="anaesthetic" placeholder="Anesthetic" class="form-control birth_input" />
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anaesthetic">Delivery Type:</label>
                                                            <div class="controls">
                                                                <select class="form-control birth_input" name="deliverytype" id="deliverytype">
                                                                    <option value="normal">Normal</option>
                                                                    <option value="LSCS">LSCS</option>
                                                                    <option value="cesarean">cesarean</option>
                                                                    <option value="Forceps">Forceps</option>
                                                                    <option value="Vacuum">Vacuum</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="ecg">
                                                <div id="ECG">
                                                    <h5 style="margin-top: 2%;"><input type="checkbox" name="ecg_check" id="ecg_check" /> ECG Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="ecgdocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="ecgdocname" value="" type="text" name="ecgdocname" class="form-control ecg_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="ecgdate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="ecgdate" type="text" name="ecgdate" class="form-control date_picker ecg_inputs" placeholder="Enter ECG referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="usg">
                                                <div id="USG" style="display: block;margin-top: 2%;">
                                                    <h5><input type="checkbox" name="usg_check" id="usg_check" /> USG Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="usgdocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="usgdocname" value="" type="text" name="usgdocname" class="form-control usg_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="usgdate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="usgdate" type="text" name="usgdate" class="form-control date_picker usg_inputs" placeholder="Enter USG referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="xray">
                                                <div id="Xray" style="margin-top: 2%">
                                                    <h5><input type="checkbox" name="xray_check" id="xray_check" /> X-ray Register</h5>
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="xraydocname">Referred Doctor Name:</label>
                                                        <div class="controls">
                                                            <input id="xraydocname" value="" type="text" name="xraydocname" class="form-control xray_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="partxray">Part of X-Ray:</label>
                                                        <div class="controls">
                                                            <input id="partxray" type="text" name="partxray" class="form-control xray_inputs" placeholder="Enter X-Ray Part" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                    <!--<div class="control-group col-4">
                                                        <label class="control-label" for="filmsize">Film Size:</label>
                                                        <div class="controls">
                                                            <input id="filmsize" type="text" name="filmsize" class="form-control xray_inputs" placeholder="Enter Film Size" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    <!--</div> <!-- /control-group -->
                                                    <div class="control-group col-4">
                                                        <label class="control-label" for="xraydate">Referred date:</label>
                                                        <div class="controls">
                                                            <input id="xraydate" type="text" name="xraydate" class="form-control date_picker xray_inputs" placeholder="Enter referred date" autocomplete="off">
                                                            <p class="help-block"></p>
                                                        </div> <!-- /controls -->
                                                    </div> <!-- /control-group -->
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kshara">
                                                <div id="Kshara" style="margin-top: 2%">
                                                    <h4><input type="checkbox" name="kshara_check" id="kshara_check" /> Ksharasutra Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharatype">Type of Ksharasutra:</label>
                                                            <div class="controls">
                                                                <input id="ksharatype" type="text" name="ksharatype" class="form-control kshara_inputs" placeholder="Enter Type of ksharasutra" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharatype">Name of Ksharasutra:</label>
                                                            <div class="controls">
                                                                <input id="ksharaname" type="text" name="ksharaname" class="form-control kshara_inputs" placeholder="Enter Name of ksharasutra" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharasurgeonname">Name Of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="ksharasurgeonname" value="" type="text" name="ksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharasurgeonname">Name Of Asst. Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="assksharasurgeonname" type="text" name="assksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharaanaesthetist">Name Of Anesthetist:</label>
                                                            <div class="controls">
                                                                <input id="ksharaanaesthetist" type="text" name="ksharaanaesthetist" class="form-control kshara_inputs" placeholder="Enter Name " autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control kshara_inputs" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="ksharadate">Ksharasutra Date:</label>
                                                            <div class="controls">
                                                                <input id="ksharadate" type="text" name="ksharadate" class="form-control date_picker kshara_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="surgery">
                                                <div id="Surgery" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="surgery_check" id="surgery_check" /> Surgery Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="SurgeryDocname">Name of Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="SurgeryDocname" value="" type="text" name="SurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Surgeon Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="AssSurgeryDocname">Name of Assistant Surgeon:</label>
                                                            <div class="controls">
                                                                <input id="AssSurgeryDocname" type="text" name="AssSurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Assistant Surgeon Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surganaesthetist">Name of Anesthetist:</label>
                                                            <div class="controls">
                                                                <input id="surganaesthetist" type="text" name="surganaesthetist" class="form-control surgery_inputs" placeholder="Enter Anaesthetist" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="anesthesia_type">Type of Anesthesia:</label>
                                                            <div class="controls">
                                                                <select class="form-control surgery_inputs" name="anesthesia_type" id="anesthesia_type">
                                                                    <option value="">Choose one</option>
                                                                    <option value="LOCAL">LOCAL</option>
                                                                    <option value="SPINAL">SPINAL</option>
                                                                    <option value="GENERAL">GENERAL</option>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgeryname">Name of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgeryname" type="text" name="surgeryname" class="form-control surgery_inputs" placeholder="Enter Name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgerytype">Type of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgerytype" type="text" name="surgerytype" class="form-control surgery_inputs" placeholder="Enter Type" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="surgerydate">Date of surgery:</label>
                                                            <div class="controls">
                                                                <input id="surgerydate" type="text" name="surgerydate" class="form-control date_picker surgery_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="lab">
                                                <div id="Lab" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="lab_check" id="lab_check" /> Laboratory Register</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="labdocname">Referred Doctor Name:</label>
                                                            <div class="controls">
                                                                <input id="labdocname" required value="" type="text" name="labdocname" class="form-control lab_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                                <input type="hidden" name="tab_lab_row_count" id="tab_lab_row_count" value="1" />
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-6">
                                                            <label class="control-label" for="testname">Referred Date:</label>
                                                            <div class="controls">
                                                                <input id="labdate" type="text" required name="testdate" class="form-control date_picker lab_inputs required" placeholder="Enter Date" autocomplete="off">
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>

                                                    <div class="col-12" id="lab_cats">
                                                        <table class="table table-borderless" id="lab_cat_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <th>Test</th>
                                                                    <th>Investigations</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <select name="lab_category[]" id="lab_category" class="form-control lab_category chosen-select" data-placeholder="Select Category">
                                                                            <option value="">Choose Category</option>
                                                                            <?= $lab_categories_markup ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="lab_test[]" id="lab_test" class="form-control lab_test chosen-select" data-placeholder="Choose test"></select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="lab_investigations[]" id="lab_investigations" class="lab_investigations form-control chosen-select multiple" multiple data-placeholder="Choose investigation"></select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="2">&nbsp;</td>
                                                                    <td class="pull-right">
                                                                        <button type="button" name="add_lab_row" id="add_lab_row" class="btn btn-sm btn-primary">
                                                                            <i class="fa fa-plus"></i> Add more</button>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="panchakarma_treatment">
                                                <div id="panchakarma_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="panchakarma_check" id="panchakarma_check" /> Refer for Panchakarma</h4>
                                                    <input type="hidden" name="tab_row_count" id="tab_row_count" value="1" />
                                                    <table class="table table-borderless" id="panchakarma_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30% !important;">Procedure</th>
                                                                <th style="width: 30% !important;">Sub procedure</th>
                                                                <th style="width: 20% !important;">Start date</th>
                                                                <th style="width: 20% !important;">End date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control chosen-select pancha_procedure pancha_input" required name="pancha_procedure[]" id="pancha_procedure_1">
                                                                        <option value="">Choose procedure</option>
                                                                        <?= $panchakarma_markup ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control pancha_sub_procedure chosen-select pancha_input" required name="pancha_sub_procedure[]" id="pancha_sub_procedure">
                                                                        <option value="">Choose sub procedure</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control date_picker pancha_input required" required name="pancha_proc_start_date[]" id="pancha_proc_start_date" /></td>
                                                                <td><input type="text" class="form-control date_picker pancha_input required" required name="pancha_proc_end_date[]" id="pancha_proc_end_date" /></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3">&nbsp;</td>
                                                                <td class="pull-right">
                                                                    <button type="button" name="" id="add_row" class="btn btn-sm btn-primary">
                                                                        <i class="fa fa-plus"></i> Add more procedure</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="physiotherapy">
                                                <div id="physiotherapy_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="physiotherapy_check" id="physiotherapy_check" /> Refer for Physiotherapy</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="physic_name">Physiotherapy name:</label>
                                                            <div class="controls">
                                                                <select id="physic_name" value="" type="text" name="physic_name" class="chosen-select form-control physic_inputs">
                                                                    <option value="">Choose</option>
                                                                    <?= $physiotherapy_markup ?>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="start_date">Start date:</label>
                                                            <div class="controls">
                                                                <input id="physic_date" type="text" name="start_date" class="form-control date_picker physic_inputs" placeholder="Enter Start date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="end_date">End date:</label>
                                                            <div class="controls">
                                                                <input id="physic_date" type="text" name="end_date" class="form-control date_picker physic_inputs" placeholder="Enter End date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="physic_doc">Physician name:</label>
                                                            <div class="controls">
                                                                <input id="physic_doc" type="text" name="physic_doc" class="form-control physic_inputs" placeholder="Enter Doctor name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="othertherapy">
                                                <div id="physiotherapy_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="other_proc_check" id="other_proc_check" /> Refer for Other Procedures</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_proc_name">Procedure name:</label>
                                                            <div class="controls">
                                                                <select id="other_proc_name" value="" type="text" name="other_proc_name" class="chosen-select form-control othr_proc_inputs">
                                                                    <option value="">Choose</option>
                                                                    <?= $other_proc_markup ?>
                                                                </select>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_start_date">Start date:</label>
                                                            <div class="controls">
                                                                <input id="other_start_date" type="text" name="other_start_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter Start date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="other_end_date">End date:</label>
                                                            <div class="controls">
                                                                <input id="other_end_date" type="text" name="other_end_date" class="form-control date_picker othr_proc_inputs" placeholder="Enter End date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="oth_proc_doc">Physician name:</label>
                                                            <div class="controls">
                                                                <input id="oth_proc_doc" type="text" name="oth_proc_doc" class="form-control othr_proc_inputs" placeholder="Enter Doctor name" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kriyakalpa">
                                                <div id="kriyakalpa_div" style="margin-top: 2%;">
                                                    <h4><input type="checkbox" name="kriyakalpa_check" id="kriyakalpa_check" /> Refer for Kriyakalpa</h4>
                                                    <div class="row">
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="kriya_procedures">Procedures:</label>
                                                            <div class="controls">
                                                                <input id="kriya_procedures" type="text" name="kriya_procedures" class="form-control kriya_inputs" placeholder="Enter Procedures" />
                                                                <p class="help-block">Enter comma separated data</p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                        <div class="control-group col-md-3">
                                                            <label class="control-label" for="kriya_start_date">Date:</label>
                                                            <div class="controls">
                                                                <input id="kriya_start_date" type="text" name="kriya_start_date" class="form-control date_picker kriya_inputs" placeholder="Enter Date" autocomplete="off">
                                                                <p class="help-block"></p>
                                                            </div> <!-- /controls -->
                                                        </div> <!-- /control-group -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end of tests-->
                            <div class="admin_div">
                                <?php if (isset($ipd_status) && $ipd_status !== false): ?>
                                    <div class="alert alert-warning">
                                        Patient is already admitted and IPD: <?php echo $ipd_status; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="admit" id="admit"> Admit
                                    </label>
                                </div>
                                <?php
                                $bed_select = '';
                                if (!empty($wards)) {
                                    foreach ($wards as $ward) {
                                        $bed_select .= '<optgroup label="' . html_escape($ward['department']) . '">';
                                        $bedstatus = explode(',', $ward['bedstatus']);
                                        if (!empty($bedstatus)) {
                                            foreach ($bedstatus as $bed) {
                                                $bed_stat = array_pad(explode('#', $bed, 3), 3, '');
                                                $bed_no = trim($bed_stat[0]);
                                                if ($bed_no === '') {
                                                    continue;
                                                }

                                                $bed_status = strtolower(trim($bed_stat[1]));
                                                $bed_category = trim($bed_stat[2]);
                                                $bed_label = $bed_no;
                                                if ($bed_category !== '') {
                                                    $bed_label .= ' (' . ucwords(str_replace('_', ' ', $bed_category)) . ')';
                                                }

                                                $is_disabled = ($bed_status === 'not available') ? 'disabled="disabled" style="color:red;"' : '';
                                                $bed_select .= '<option value="' . html_escape($bed_no) . '" ' . $is_disabled . '>' . html_escape($bed_label) . '</option>';
                                            }
                                        }
                                        $bed_select .= '</optgroup>';
                                    }
                                }
                                ?>
                                <div id="admit_form" class="well well" style="display: none;margin-left: 2%;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <select class="form-control" name="bed_no" id="bed_no">
                                                <option value="">Select bed</option>
                                                <?php echo $bed_select; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="col-md-4 col-sm-12">
                                            <label>Admission date: </label>
                                            <input type="text" name="admit_date" id="admit_date" class="form-control date_picker" placeholder="Admission date" />
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label>Time: </label>
                                            <input type="time" name="admit_time" id="admit_time" class="form-control" placeholder="Time" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-bar">
                            <button class="btn btn-primary" type="submit" name="save_action" value="save"><i class="fa fa-save"></i> Save</button>
                            <!-- TODO Phase 2: wire Save & Print after save flow supports post-save redirect to print_case_sheet. -->
                            <button class="btn btn-default" type="button" disabled="disabled"><i class="fa fa-print"></i> Save &amp; Print</button>
                            <!-- TODO Phase 2: enable after WhatsApp integration is available. -->
                            <button class="btn btn-default" type="button" disabled="disabled"><i class="fa fa-whatsapp"></i> Save &amp; WhatsApp</button>
                            <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function() {
        $('.chosen-select').chosen({
            width: '100%'
        });
        $('.chosen-select-deselect').chosen({
            allow_single_deselect: true
        });
    });
    var procedure_div_ids = ['prescription_inputs', 'birth_input', 'ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs', 'physic_inputs', 'pancha_input', 'othr_proc_inputs', 'kriya_inputs'];
    var panchakarma_markup = "<?= $panchakarma_markup ?>";
    $(document).ready(function() {
        var diagnosisSearchUrl = '<?php echo base_url('patient/treatment/search_diagnosis'); ?>';
        var templateFetchUrl = '<?php echo base_url('patient/treatment/fetch_treatment_templates'); ?>';
        var currentDepartment = '<?php echo html_escape($curr_dept); ?>';

        $('#diagnosis').select2({
            ajax: {
                url: diagnosisSearchUrl,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term || ''
                    };
                },
                processResults: function(data) {
                    return data;
                }
            },
            tags: true,
            placeholder: 'Search or type diagnosis',
            minimumInputLength: 1,
            width: '100%',
            createTag: function(params) {
                var term = $.trim(params.term || '').toUpperCase();
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        }).on('select2:select', function(e) {
            var data = e.params.data || {};
            $('#diagnosis_master_id').val(data.diagnosis_id || '');
            refreshTreatmentTemplates(data.diagnosis_id || '');
        });

        function refreshTreatmentTemplates(diagnosisId) {
            $.ajax({
                url: templateFetchUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    department: currentDepartment,
                    diagnosis_id: diagnosisId
                },
                success: function(response) {
                    var list = response.status ? response.data : [];
                    var options = '<option value="">Use Template</option>';
                    $.each(list, function(i, item) {
                        options += '<option value="' + item.id + '" data-treatment="' + $('<div/>').text(item.treatment_text || '').html() + '">' + $('<div/>').text(item.name || '').html() + '</option>';
                    });
                    $('#treatment_template').html(options);
                    $('#treatment_template, #apply_template').prop('disabled', list.length === 0 || $('#add_prescription').is(':not(:checked)'));
                }
            });
        }

        $('#apply_template').on('click', function() {
            var templateText = $('#treatment_template option:selected').data('treatment') || '';
            var currentText = $('#treatment').val();
            if (!templateText) {
                return;
            }
            if (currentText && !confirm('Treatment already has text. Replace it with the selected template? Choose Cancel to append.')) {
                $('#treatment').val(currentText + "\n" + templateText.toUpperCase());
            } else {
                $('#treatment').val(templateText.toUpperCase());
            }
            $('#treatment').focus();
        });

        var ipd_status = '<?php echo  $ipd_status; ?>';
        if (ipd_status) {
            $('#admit').prop('disabled', true);
            $.notify({
                title: "Admission Status:",
                message: "Patient is already admitted",
                icon: 'fa fa-info-circle',
            }, {
                type: "warning",
            });
        }

        $('#admit').click(function() {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });

        $.each(procedure_div_ids, function(i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
            $('.' + procedure_div_ids[i]).prop('disabled', true).trigger("chosen:updated");
        });

        $('#add_prescription').click(function() {
            if ($(this).is(":checked")) {
                $('.prescription_inputs').removeAttr('disabled');
                $('#diagnosis').prop('disabled', false).trigger('change.select2');
                $('#treatment_template, #apply_template').prop('disabled', $('#treatment_template option').length <= 1);
            } else if ($(this).is(":not(:checked)")) {
                $('.prescription_inputs').attr('disabled', 'disabled');
                $('#diagnosis').prop('disabled', true).trigger('change.select2');
                $('#treatment_template, #apply_template').prop('disabled', true);
            }
        });

        $('#ecg_check').click(function() {
            if ($(this).is(":checked")) {
                $('.ecg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.ecg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#birth_check').click(function() {
            if ($(this).is(":checked")) {
                $('.birth_input').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.birth_input').attr('disabled', 'disabled');
            }
        });

        $('#usg_check').click(function() {
            if ($(this).is(":checked")) {
                $('.usg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#usgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.usg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#xray_check').click(function() {
            if ($(this).is(":checked")) {
                $('.xray_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#xraydocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.xray_inputs').attr('disabled', 'disabled');
            }
        });

        $('#kshara_check').click(function() {
            if ($(this).is(":checked")) {
                $('.kshara_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kshara_inputs').attr('disabled', 'disabled');
            }
        });

        $('#surgery_check').click(function() {
            if ($(this).is(":checked")) {
                $('.surgery_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.surgery_inputs').attr('disabled', 'disabled');
            }
        });

        $('#lab_check').click(function() {
            if ($(this).is(":checked")) {
                $('.lab_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#labdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.lab_inputs').attr('disabled', 'disabled');
            }
        });

        $('#panchakarma_check').click(function() {
            if ($(this).is(":checked")) {
                $('.pancha_input').prop('disabled', false).trigger("chosen:updated");
                $('.pancha_input').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.pancha_input').attr('disabled', 'disabled');
                $('.pancha_input').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#physiotherapy_check').click(function() {
            if ($(this).is(":checked")) {
                $('.physic_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.physic_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.physic_inputs').attr('disabled', 'disabled');
                $('.physic_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#other_proc_check').click(function() {
            if ($(this).is(":checked")) {
                $('.othr_proc_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.othr_proc_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.othr_proc_inputs').attr('disabled', 'disabled');
                $('.othr_proc_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#kriyakalpa_check').click(function() {
            if ($(this).is(":checked")) {
                $('.kriya_inputs').prop('disabled', false).trigger("chosen:updated");
                $('.kriya_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kriya_inputs').attr('disabled', 'disabled');
                $('.kriya_inputs').prop('disabled', true).trigger("chosen:updated");
            }
        });

        $('#copy_diagnosis').click(function() {
            if ($(this).is(":checked")) {
                if ($('#diagnosis').val().length <= 0) {
                    $.notify({
                        title: "Treatment:",
                        message: "Please enter diagnosis to copy it",
                        icon: 'fa fa-cross',
                    }, {
                        type: "danger",
                    });
                    $("#copy_diagnosis").prop("checked", false);
                } else {
                    copy_input_text('#diagnosis', '#pancha_disease');
                }
            } else if ($(this).is(":not(:checked)")) {
                $('#pancha_disease').val('');
            }
        });
        $('#admit').click(function() {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });
        $('#lab_cat_table').on('change', '.lab_category', function() {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_test_list',
                type: 'POST',
                data: {
                    category: $(this).val()
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose test</option>';
                        $.each(list, function(i, item) {
                            options += '<option value="' + item.lab_test_id + '">' + item.lab_test_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_test').html(options);
                        dom.closest('td').next('td').find('.lab_test').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function(error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#lab_cat_table').on('change', '.lab_test', function() {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_investigation_list',
                type: 'POST',
                data: {
                    tests: $(this).val()
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose Investigations</option>';
                        $.each(list, function(i, item) {
                            options += '<option value="' + item.lab_inv_id + '">' + item.lab_inv_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_investigations').html(options);
                        dom.closest('td').next('td').find('.lab_investigations').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function(error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#panchakarma_table').on('change', '.pancha_procedure', function() {
            var panchaprocedure = $(this).val();
            var dom = $(this);
            $.ajax({
                url: base_url + 'master/panchakarma/fetch_sub_procedures',
                data: {
                    'proc_name': panchaprocedure
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    var sub_proc_options = '<option value="">choose sub procedure</option>';
                    if (response.status) {
                        var sub_proc = response.data;
                        $.each(sub_proc, function(i) {
                            sub_proc_options += '<option value="' + sub_proc[i].sub_proc_name + '">' + sub_proc[i].sub_proc_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.pancha_sub_procedure').html(sub_proc_options);
                        dom.closest('td').next('td').find('.pancha_sub_procedure').trigger("chosen:updated");
                    }
                },
                error: function(xhr, errorType, exception) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#add_row').on('click', function() {
            var n = $('#tab_row_count').val();
            $('#tab_row_count').val(n + 1);
            var markup = '<tr>' + '<td>' +
                '<select class="form-control chosen-select pancha_procedure" name="pancha_procedure[]" id="pancha_procedure_' + (n + 1) + '">' +
                '<option value="">Choose procedure</option>' +
                panchakarma_markup +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control pancha_sub_procedure chosen-select" name="pancha_sub_procedure[]" id="pancha_sub_procedure">' +
                '<option value="">Choose sub procedure</option>' +
                '</select>' +
                '</td>' +
                '<td>' + '<input type="text" class="form-control date_picker" name = "pancha_proc_start_date[]" id = "pancha_proc_start_date" />' + '</td>' +
                '<td>' + '<input type="text" class="form-control date_picker" name = "pancha_proc_end_date[]" pancha_proc_end_date />' + '</td>' +
                '</tr>';
            $('#panchakarma_table').append(markup);
            $(".pancha_procedure").chosen({
                width: '100%'
            });
            $(".pancha_sub_procedure").chosen({
                width: '100%'
            });
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                daysOfWeekHighlighted: "0"
            });
            $('.date_picker').attr('autocomplete', 'off');
        });
        var lab_cat_markup = '<?= $lab_categories_markup ?>';

        $('#add_lab_row').on('click', function() {
            var n = $('#tab_lab_row_count').val();
            $('#tab_lab_row_count').val(n + 1);
            var markup = '<tr>' + '<td>' +
                '<select class="form-control chosen-select lab_category" name="lab_category[]" id="lab_category_' + (n + 1) + '">' +
                '<option value="">Choose Category</option>' +
                lab_cat_markup +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control lab_test chosen-select" name="lab_test[]" id="lab_test_' + (n + 1) + '">' +
                '<option value="">Choose Test</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select class="form-control lab_investigations chosen-select" name="lab_investigations[]" multiple id="lab_investigations_' + (n + 1) + '">' +
                '<option value="">Choose Investigations</option>' +
                '</select>' +
                '</td>' +
                '</tr>';
            $('#lab_cat_table').append(markup);
            $(".lab_category").chosen({
                width: '100%'
            });
            $(".lab_test").chosen({
                width: '100%'
            });
            $(".lab_investigations").chosen({
                width: '100%'
            });
        });
    });
</script>
