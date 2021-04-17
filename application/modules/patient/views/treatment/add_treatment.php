<style type="text/css">
    .accordion h1,
    .accordion a{
        color:#007b5e;
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

    .accordion .card-header .btn.collapsed .fa.main{
        display:none;
    }

    .accordion .card-header .btn .fa.main{
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
        display:block;
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
?>
<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="box box-primary" style="border-top: 5px solid #009688;">
            <div class="box-body">
                <div class="text-center" style="margin: auto;text-align: center;">
                    <img class="img-responsive rounded" style="text-align: center;margin: auto" src="<?php echo base_url('assets/img/user_icon.png') ?>" width="100" height="100"/>
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
        <div class="">
            <div class="">
                <form name="add_treatment_form" id="add_treatment_form" action="<?php echo base_url('patient/treatment/save'); ?>" method="POST">
                    <!--hiddden fields-->
                    <input type="hidden" name="treat_id" id="treat_id" value="<?php echo $treat_id; ?>"/>
                    <input type="hidden" name="opd_no" id="opd_no" value="<?php echo $opd; ?>"/>
                    <input type="hidden" name="department" id="department" value="<?php echo $current_treatment['department']; ?>"/>
                    <div class="box box-primary">
                        <div class="box-body">
                            <h6>Department: <span class="text-warning"> <?php echo ucfirst(strtolower(str_replace('_', ' ', $current_treatment['department']))); ?></span>
                                <span class="pull-right">Follow up date: <span class="text-warning"> <?php echo $current_treatment['CameOn']; ?></span> 
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a class="btn btn-info btn-sm" style="color:white;" href="<?php echo base_url('patient/treatment/show_patients'); ?>"><i class="fa fa-backward"></i> Back</a>
                                </span>
                            </h6>
                            <hr/>
                            <?php if (!empty($treatment_details)): ?>
                                <div class="box-group" id="accordion">
                                    <?php
                                    $i = 0;
                                    foreach ($treatment_details as $treatment) {
                                        $i++;
                                        ?>        
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i ?>" aria-expanded="true" class="">
                                                        <i class="fa fa-user-md main"></i><i class="fa fa-angle-double-right mr-3"></i>
                                                        Visited date: <?php echo $treatment['CameOn']; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?= $i ?>" class="collapse" aria-labelledby="heading<?= $i ?>" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 border-info">
                                                            <h6>Diagnosis:</h6>
                                                            <p><?php echo $treatment['diagnosis']; ?></p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6>Treatment:</h6>
                                                            <p><?php echo $treatment['Trtment']; ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h6>Department:</h6>
                                                            <p><?php echo $treatment['department']; ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h6>Procedure:</h6>
                                                            <p><?php echo $treatment['procedures']; ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h6>Doctor:</h6>
                                                            <p><?php echo $treatment['attndedby']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php endif; ?>
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
                                    <textarea class="form-control prescription_inputs"  onkeyup="this.value = this.value.toUpperCase();" name="diagnosis" id="diagnosis"></textarea>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label>Treatment: <span class="text-danger">*</span></label>
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
                                    <textarea class="form-control prescription_inputs"  onkeyup="this.value = this.value.toUpperCase();" name="treatment" id="treatment"></textarea>
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
                            <hr/>
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
                            <?php
                            end($treatment_details);
                            $latest_data = key($treatment_details);
                            ?> 
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <label>Doctor: </label>
                                    <!--<input type="text" class="form-control" name="doctor_name" id="doctor_name" value="<?= $current_treatment['AddedBy']; ?>" readonly="readonly"/>-->
                                    <select class="form-control " name="doctor_name" id="doctor_name" readonly="readonly">
                                        <option value="">Choose doctor</option>
                                        <?php
                                        if (!empty($doctors)) {
                                            $selected_doc = ($current_treatment['AddedBy'] == "") ? $treatment_details[$latest_data]['AddedBy'] : $current_treatment['AddedBy'];

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

                            <!-- Tests -->
                            <hr/>
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
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade" id="birth">
                                                <div id="Birth">
                                                    <h5 style="margin-top: 2%;"><input type="checkbox" name="birth_check" id="birth_check"/> Birth Register</h5>
                                                    <div class="row">
                                                        <div class="control-group col-md-6">		
                                                            <label class="control-label" for="delivery">Details of delivery:</label>
                                                            <div class="controls">
                                                                <textarea id="delivery" type="text" name="delivery" placeholder="Delivery details" class="form-control birth_input" ></textarea>
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
                                                                <input id="weight" type="text" name="weight" class="form-control birth_input" placeholder="Baby weight" >
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
                                            <div  class="tab-pane fade" id="panchakarma_treatment">
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
                                                                    <select class="form-control chosen-select pancha_procedure" name="pancha_procedure[]" id="pancha_procedure_1">
                                                                        <option value="">Choose procedure</option>
                                                                        <?= $panchakarma_markup ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control pancha_sub_procedure chosen-select" name="pancha_sub_procedure[]" id="pancha_sub_procedure">
                                                                        <option value="">Choose sub procedure</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control date_picker" name="pancha_proc_start_date[]" id="pancha_proc_start_date"/></td>
                                                                <td><input type="text" class="form-control date_picker" name="pancha_proc_end_date[]" pancha_proc_end_date/></td>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end of tests-->
                            <div class="admin_div">
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="admit" id="admit"> Admit
                                    </label>
                                </div>
                                <?php
                                $bed_select = '';
                                if (!empty($wards)) {
                                    foreach ($wards as $ward) {
                                        $bed_select .= '<optgroup label="' . $ward['department'] . '"></optgroup>';
                                        $beds = explode(',', $ward['beds']);
                                        asort($beds);
                                        if (!empty($beds)) {
                                            foreach ($beds as $bed) {
                                                $bed_select .= '<option value="' . $bed . '">' . $bed . '</option>';
                                            }
                                        }
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
                        <br/>
                    </div>

                </form>
            </div>
        </div>
    </div><!-- col-9 -->
</div>
<script type="text/javascript">
    $(function () {
        $('.chosen-select').chosen({width: '100%'});
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
    var procedure_div_ids = ['prescription_inputs', 'birth_input', 'ecg_inputs', 'usg_inputs', 'xray_inputs', 'kshara_inputs', 'surgery_inputs', 'lab_inputs'];
    var panchakarma_markup = "<?= $panchakarma_markup ?>";
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

        $('#ecg_check').click(function () {
            if ($(this).is(":checked")) {
                $('.ecg_inputs').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.ecg_inputs').attr('disabled', 'disabled');
            }
        });

        $('#birth_check').click(function () {
            if ($(this).is(":checked")) {
                $('.birth_input').removeAttr('disabled');
                copy_input_text('#doctor_name', '#ecgdocname');
            } else if ($(this).is(":not(:checked)")) {
                $('.birth_input').attr('disabled', 'disabled');
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
        $('#copy_diagnosis').click(function () {
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
        $('#admit').click(function () {
            if ($(this).is(":checked")) {
                $('#admit_form').show();
            } else if ($(this).is(":not(:checked)")) {
                $('#admit_form').hide();
            }
        });
        $('#lab_cat_table').on('change', '.lab_category', function () {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_test_list',
                type: 'POST',
                data: {category: $(this).val()},
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose test</option>';
                        $.each(list, function (i, item) {
                            options += '<option value="' + item.lab_test_id + '">' + item.lab_test_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_test').html(options);
                        dom.closest('td').next('td').find('.lab_test').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function (error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#lab_cat_table').on('change', '.lab_test', function () {
            var dom = $(this);
            $.ajax({
                url: base_url + 'common_methods/fetch_laboratory_investigation_list',
                type: 'POST',
                data: {tests: $(this).val()},
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        var list = res.data;
                        var options = '<option value="">Choose Investigations</option>';
                        $.each(list, function (i, item) {
                            options += '<option value="' + item.lab_inv_id + '">' + item.lab_inv_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.lab_investigations').html(options);
                        dom.closest('td').next('td').find('.lab_investigations').trigger("chosen:updated");
                    } else {
                        alert('something went wrong try again');
                    }
                },
                error: function (error) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#panchakarma_table').on('change', '.pancha_procedure', function () {
            var panchaprocedure = $(this).val();
            var dom = $(this);
            $.ajax({
                url: base_url + 'master/panchakarma/fetch_sub_procedures',
                data: {'proc_name': panchaprocedure},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    var sub_proc_options = '<option value="">choose sub procedure</option>';
                    if (response.status) {
                        var sub_proc = response.data;
                        $.each(sub_proc, function (i) {
                            sub_proc_options += '<option value="' + sub_proc[i].sub_proc_name + '">' + sub_proc[i].sub_proc_name + '</option>';
                        });
                        dom.closest('td').next('td').find('.pancha_sub_procedure').html(sub_proc_options);
                        dom.closest('td').next('td').find('.pancha_sub_procedure').trigger("chosen:updated");
                    }
                },
                error: function (xhr, errorType, exception) {
                    console.log('ERROR! ');
                    console.log(xhr.responseText);
                }
            });
        });

        $('#add_row').on('click', function () {
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
            $(".pancha_procedure").chosen({width: '100%'});
            $(".pancha_sub_procedure").chosen({width: '100%'});
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                daysOfWeekHighlighted: "0"
            });
            $('.date_picker').attr('autocomplete', 'off');
        });
        var lab_cat_markup = '<?= $lab_categories_markup ?>';

        $('#add_lab_row').on('click', function () {
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
                    '</select>'
                    + '</td>' +
                    '</tr>';
            $('#lab_cat_table').append(markup);
            $(".lab_category").chosen({width: '100%'});
            $(".lab_test").chosen({width: '100%'});
            $(".lab_investigations").chosen({width: '100%'});
        });
    });

</script>