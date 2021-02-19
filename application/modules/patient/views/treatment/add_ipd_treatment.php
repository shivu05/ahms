<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="box box-primary" style="border-top: 5px solid #009688;">
            <div class="box-body">
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
        <form name="add_treatment_form" id="add_treatment_form" action="<?php echo base_url('patient/treatment/ipd_save'); ?>" method="POST">
            <!--hiddden fields-->

            <input type="hidden" name="ipd_no" id="ipd_no" value="<?php echo $ipd; ?>"/>
            <input type="hidden" name="opd_no" id="opd_no" value="<?php echo $patient_details['OpdNo']; ?>"/>
            <input type="hidden" name="department" id="department" value="<?php echo $patient_details['department']; ?>"/>
            <div class="box box-primary">
                <div class="box-body">
                    <h6>Department: <span class="text-warning"> <?php echo ucfirst(strtolower(str_replace('_', ' ', $patient_details['department']))); ?></span>
                        <span class="pull-right">Admission date: <span class="text-warning"> <?php echo $patient_details['DoAdmission']; ?></span> 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-info btn-sm" style="color:white;"><i class="fa fa-backward"></i> Back</a>
                        </span>
                    </h6>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <input type="checkbox" name="add_prescription" id="add_prescription" style="margin-top: 7px;" />
                            <label>Add prescription: <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label>Complaints: <span class="text-danger">*</span></label>
                            <textarea class="form-control prescription_inputs" name="complaints" id="complaints"></textarea>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Diagnosis: <span class="text-danger">*</span></label>
                            <textarea class="form-control prescription_inputs" name="diagnosis" id="diagnosis"></textarea>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label>Treatment: <span class="text-danger">*</span></label>
                            <textarea class="form-control prescription_inputs" name="treatment" id="treatment"></textarea>
                        </div>
                        <!--<div class="col-md-4 col-sm-12">
                            <label>Panchakarma procedures: <span class="text-danger">*</span></label>
                            <textarea class="form-control prescription_inputs" name="panch_procedures" id="panch_procedures"></textarea>
                        </div>
                        <div class="col-md-2 col-sm12">
                            <label>Days</label>
                            <input type="text" name="panch_days" id="panch_days" placeholder="Days" class="form-control prescription_inputs" value="7"/>
                        </div>-->
                        <div class="col-md-6 col-sm-12">
                            <label>Notes: <span class="text-danger">*</span></label>
                            <textarea class="form-control prescription_inputs" name="notes" id="notes"></textarea>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">

                        <div class="col-md-3 col-sm-12">
                            <label>Doctor: </label>
                            <input type="text" name="doctor_name" id="doctor_name" value="<?php echo $patient_details['Doctor'] ?>" class="form-control prescription_inputs"/>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Date: </label>
                            <input type="text" name="attened_date" id="attened_date" class="form-control date_picker prescription_inputs" value="<?php echo date('Y-m-d') ?>" />
                        </div>
                    </div><!-- end of trearment div-->
                    <!-- Tests -->
                    <hr/>
                    <div class="row" style="margin-bottom: 2rem;">
                        <div class="col-md-12">
                            <h3>Procedures:</h3>
                            <div class="bs-component">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#birth">Birth</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ecg">ECG</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#usg">USG</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#xray">X-Ray</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kshara">Ksharasutra</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#surgery">Surgery</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lab">Laboratory</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade" id="birth">
                                        <div id="Birth">
                                            <h5 style="margin-top: 2%;"><input type="checkbox" name="birth_check" id="birth_check"/> Birth Register</h5>
                                            <div class="control-group col-4">		
                                                <label class="control-label" for="delivery">Details of delivery:</label>
                                                <div class="controls">
                                                    <input id="delivery" type="text" name="delivery" placeholder="Delivery details" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">
                                                <label class="control-label" for="birthdate">Birth Date/Time Of Baby:</label>
                                                <div class="controls">
                                                    <input id="birthdate" type="text" name="birthdate" class="form-control birth_input date_picker" placeholder="Birth date" autocomplete="off">
                                                    <br>
                                                    <input id="birthtime" placeholder="Time __:__" type="text" name="birthtime" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">									
                                                <label class="control-label" for="weight">Baby weight:</label>
                                                <div class="controls">
                                                    <input id="weight" type="text" name="weight" class="form-control birth_input" placeholder="bdaby weight" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="weight">Baby Gender:</label>
                                                <div class="controls">
                                                    <input id="babygender" type="text" name="babygender" class="form-control birth_input" placeholder="Gender" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="babyblood">Baby Blood Group:</label>
                                                <div class="controls">
                                                    <input id="babyblood" type="text" name="babyblood" class="form-control birth_input" placeholder="Baby blood group" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="fathername">Father Name:</label>
                                                <div class="controls">
                                                    <input id="fathername" type="text" name="fathername" placeholder="Father name" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="motherblood">Mother Blood Group:</label>
                                                <div class="controls">
                                                    <input id="motherblood" type="text" name="motherblood" placeholder="Mother blood group" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="surgeonname">Name Of Surgeon:</label>
                                                <div class="controls">
                                                    <input id="surgeonname" value="" type="text" name="surgeonname" placeholder="surgeon name" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
                                                <label class="control-label" for="anaesthetic">Name Of Anaesthetic:</label>
                                                <div class="controls">
                                                    <input id="anaesthetic" type="text" name="anaesthetic" placeholder="Anaesthetic" class="form-control birth_input" autocomplete="off">
                                                    <p class="help-block"></p>
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group col-4">											
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
                                                    <input id="usgdate" type="text" name="usgdate" class="form-control date_picker usg_inputs" placeholder="Enter USG referred Date" autocomplete="off">
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
                                                    <input id="treatment" value="" type="text" name="ksharasurgeonname" class="form-control kshara_inputs" placeholder="Enter Surgeon" autocomplete="off">
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
                                                    <input id="treatment" type="text" name="testname" class="form-control lab_inputs" placeholder="Enter Test Name" autocomplete="off">
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
    var procedure_div_ids = ['prescription_inputs', 'birth_input', 'ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs'];

    $(document).ready(function () {

        $.each(procedure_div_ids, function (i) {
            $('.' + procedure_div_ids[i]).attr('disabled', 'disabled');
        });

        $('#add_prescription').click(function () {
            if ($(this).is(":checked")) {
                $('.prescription_inputs').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.prescription_inputs').attr('disabled', 'disabled');
            }
        });

        $('#birth_check').click(function () {
            if ($(this).is(":checked")) {
                $('.birth_input').removeAttr('disabled');
            } else if ($(this).is(":not(:checked)")) {
                $('.birth_input').attr('disabled', 'disabled');
            }
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