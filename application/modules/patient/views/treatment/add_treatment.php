<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="tile" style="border-top: 5px solid #009688;">
            <div class="tile-body">
                <div class="text-center">
                    <img class="img-responsive rounded" src="<?php echo base_url('assets/img/user_icon.png') ?>" width="100" height="100"/>
                </div>
                <h4 style="margin-top: 2%;" class="text-center"><?php echo $patient_details['FirstName'] . ' ' . $patient_details['LastName']; ?></h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">OPD: <span class="pull-right"><?php echo $patient_details['OpdNo']; ?></span></li>
                    <li class="list-group-item">Age: <span class="pull-right"><?php echo $patient_details['Age']; ?></span></li>
                    <li class="list-group-item">Gender: <span class="pull-right"><?php echo $patient_details['gender']; ?></span></li>
                    <li class="list-group-item">Place: <span class="pull-right"><?php echo $patient_details['city']; ?></span></li>
                    <li class="list-group-item">Blood group: <span class="pull-right"></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-12">
        <?php //pma($current_treatment, 1); ?>
        <form name="add_treatment_form" id="add_treatment_form" action="<?php echo base_url('patient/treatment/save'); ?>" method="POST">
            <!--hiddden fields-->
            <input type="hidden" name="treat_id" id="treat_id" value="<?php echo $treat_id; ?>"/>
            <input type="hidden" name="opd_no" id="opd_no" value="<?php echo $opd; ?>"/>
            <input type="hidden" name="department" id="department" value="<?php echo $current_treatment['department']; ?>"/>
            <div class="tile" style="border-top: 5px solid #009688;">
                <div class="tile-body">
                    <h6>Department: <span class="text-warning"> <?php echo ucfirst(strtolower(str_replace('_', ' ', $current_treatment['department']))); ?></span>
                        <span class="pull-right">Follow up date: <span class="text-warning"> <?php echo $current_treatment['CameOn']; ?></span> 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="btn btn-info btn-sm" style="color:white;" href="<?php echo base_url('patient/treatment/show_patients'); ?>"><i class="fa fa-backward"></i> Back</a>
                        </span>
                    </h6>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label>Complaints: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="complaints" id="complaints"></textarea>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Diagnosis: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="diagnosis" id="diagnosis"></textarea>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label>Treatment: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="treatment" id="treatment"></textarea>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Panchakarma procedures: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="panch_procedures" id="panch_procedures"></textarea>
                        </div>
                        <!--<div class="col-md-2 col-sm12">
                            <label>Days</label>
                            <input type="text" name="panch_days" id="panch_days" placeholder="Days" class="form-control" value="7"/>
                        </div>-->
                    </div>
                    <!--<hr/>
                    <div class="row">
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
                    </div>-->
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label>Notes: <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="notes" id="notes"></textarea>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Doctor: </label>
                            <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?= $current_treatment['AddedBy']; ?>" readonly="readonly"/>
                           <!-- <select class="form-control" name="doctor_name" id="doctor_name">
                                <option>Choose doctor</option>
                            <?php
                            if (!empty($doctors)) {
                                $selected_doc = $current_treatment['AddedBy'];
                                foreach ($doctors as $doctor) {
                                    $selected = (strtolower($selected_doc) == strtolower($doctor)) ? 'selected="selected"' : '';
                                    echo '<option value="' . $doctor['doctorname'] . '" ' . $selected . '>' . $doctor['doctorname'] . '</option>';
                                }
                            }
                            ?>
                            </select>-->
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Treated date: </label>
                            <input type="text" name="attened_date" id="attened_date" class="form-control date_picker" value="<?php echo date('Y-m-d') ?>" />
                        </div>
                    </div><!-- end of trearment div-->

                    <!-- Tests -->
                    <hr/>
                    <div class="row" style="margin-bottom: 2rem;">
                        <div class="col-12">
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
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade" id="birth">
                                        <p>Birth register</p>
                                    </div>
                                    <div class="tab-pane fade" id="ecg">
                                        <div id="ECG">
                                            <h5 style="margin-top: 2%;"><input type="checkbox" name="ecg_check" id="ecg_check"/> ECG Register</h5>
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
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharatype">Type of Ksharasutra:</label>
                                                <div class="controls">
                                                    <input id="ksharatype" type="text" name="ksharatype" class="form-control kshara_inputs" placeholder="Enter Type of ksharasutra" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharatype">Name of Ksharasutra:</label>
                                                <div class="controls">
                                                    <input id="ksharaname" type="text" name="ksharaname" class="form-control kshara_inputs" placeholder="Enter Name of ksharasutra" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharasurgeonname">Name Of Surgeon:</label>
                                                <div class="controls">
                                                    <input id="ksharasurgeonname" value="" type="text" name="ksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharasurgeonname">Name Of Asst. Surgeon:</label>
                                                <div class="controls">
                                                    <input id="assksharasurgeonname" type="text" name="assksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharaanaesthetist">Name Of Anaesthetist:</label>
                                                <div class="controls">
                                                    <input id="ksharaanaesthetist" type="text" name="ksharaanaesthetist" class="form-control kshara_inputs" placeholder="Enter Name " autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="ksharadate">Ksharasutra Date:</label>
                                                <div class="controls">
                                                    <input id="ksharadate" type="text" name="ksharadate" class="form-control date_picker kshara_inputs" placeholder="Enter Date" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="surgery">
                                        <div id="Surgery" style="margin-top: 2%;">
                                            <h4><input type="checkbox" name="surgery_check" id="surgery_check" /> Surgery Register</h4>
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="SurgeryDocname">Name of Surgeon:</label>
                                                <div class="controls">
                                                    <input id="SurgeryDocname" value="" type="text" name="SurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Surgeon Name" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="AssSurgeryDocname">Name of Assistant Surgeon:</label>
                                                <div class="controls">
                                                    <input id="AssSurgeryDocname" type="text" name="AssSurgeryDocname" class="form-control surgery_inputs" placeholder="Enter Assistant Surgeon Name" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="surganaesthetist">Name of anaesthetist:</label>
                                                <div class="controls">
                                                    <input id="surganaesthetist" type="text" name="surganaesthetist" class="form-control surgery_inputs" placeholder="Enter Anaesthetist" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="surgeryname">Name of surgery:</label>
                                                <div class="controls">
                                                    <input id="surgeryname" type="text" name="surgeryname" class="form-control surgery_inputs" placeholder="Enter Name" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="surgerytype">Type of surgery:</label>
                                                <div class="controls">
                                                    <input id="surgerytype" type="text" name="surgerytype" class="form-control surgery_inputs" placeholder="Enter Type" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="surgerydate">Date of surgery:</label>
                                                <div class="controls">
                                                    <input id="surgerydate" type="text" name="surgerydate" class="form-control date_picker surgery_inputs" placeholder="Enter Date" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lab">
                                        <div id="Lab" style="margin-top: 2%;">
                                            <h4><input type="checkbox" name="lab_check" id="lab_check" /> Laboratory Register</h4>
                                            <div class="control-group col-4">
                                                <label class="control-label" for="labdocname">Referred Doctor Name:</label>
                                                <div class="controls">
                                                    <input id="labdocname" value="" type="text" name="labdocname" class="form-control lab_inputs" placeholder="Enter Doctor Name" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="testname">Name of Test:</label>
                                                <div class="controls">
                                                    <input id="testname" type="text" name="testname" class="form-control lab_inputs" placeholder="Enter Test Name" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->	
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="testname">Referred Date:</label>
                                                <div class="controls">
                                                    <input id="labdate" type="text" name="testdate" class="form-control date_picker lab_inputs" placeholder="Enter Date" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end of tests-->
                    <div class="admin_div">
                        <div class="toggle-flip">
                            <label>
                                <input type="checkbox" name="admit" id="admit"><span class="flip-indecator" data-toggle-on="Admit" data-toggle-off="Follow up"></span>
                            </label>
                        </div>
                        <?php
                        $bed_select = '';
                        if (!empty($wards)) {
                            foreach ($wards as $ward) {
                                $bed_select .= '<optgroup label="' . $ward['department'] . '"></optgroup>';
                                $beds = explode(',', $ward['beds']);
                                if (!empty($beds)) {
                                    foreach ($beds as $bed) {
                                        $bed_select .= '<option value="' . $bed . '">' . $bed . '</option>';
                                    }
                                }
                            }
                        }
                        ?>
                        <div id="admit_form" style="display: none;margin-left: 2%;">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <select class="form-control" name="bed_no" id="bed_no">
                                        <option>Select bed</option>
                                        <?php echo $bed_select; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-4 col-sm-12">
                                    <label>Admission date: </label>
                                    <input type="text" name="admit_date" id="admit_date" class="form-control date_picker" placeholder="Admission date"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save</button>
                        <button class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
                    </div>
                </div>
            </div>

        </form>
    </div><!-- col-9 -->
</div>
<script type="text/javascript">
    $(function () {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
    var procedure_div_ids = ['ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs'];
    $(document).ready(function () {
        $.each(procedure_div_ids, function (i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
        });

        $('#ecg_check').click(function () {
            if ($(this).is(":checked")) {
                $('.ecg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.ecg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#usg_check').click(function () {
            if ($(this).is(":checked")) {
                $('.usg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#usgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.usg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#xray_check').click(function () {
            if ($(this).is(":checked")) {
                $('.xray_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#xraydocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.xray_inputs').attr('disabled', 'disabled');
            }
        });

        $('#kshara_check').click(function () {
            if ($(this).is(":checked")) {
                $('.kshara_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.kshara_inputs').attr('disabled', 'disabled');
            }
        });

        $('#surgery_check').click(function () {
            if ($(this).is(":checked")) {
                $('.surgery_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.surgery_inputs').attr('disabled', 'disabled');
            }
        });

        $('#lab_check').click(function () {
            if ($(this).is(":checked")) {
                $('.lab_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#labdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.lab_inputs').attr('disabled', 'disabled');
            }
        });
        $('#admit').click(function () {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });


    });

</script>