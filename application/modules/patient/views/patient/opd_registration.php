<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus"></i> OPD Registration:</h3>
                <a href="<?php echo base_url('patient-list'); ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-list"></i> Patient list</a>
            </div>
            <div class="box-body">
                <form class="form-horizontal" name="patient_form" id="patient_form" action="<?php echo base_url('patient/store_patient_info'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="first_name">First name: </label>
                                        <input class="form-control required" id="first_name" name="first_name" type="text" aria-describedby="first_nameHelp" placeholder="Enter your firstname">
                                        <small class="form-text text-muted" id="first_nameHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="age">Age: </label>
                                        <input class="form-control required" id="age" name="age" type="text" aria-describedby="ageHelp" placeholder="Enter your age">
                                        <small class="form-text text-muted" id="ageeHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="mobile">Mobile: </label>
                                        <input class="form-control" id="mobile" name="mobile" type="text" aria-describedby="ageHelp" placeholder="Enter your mobile number">
                                        <small class="form-text text-muted" id="ageeHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="place">Place: </label>
                                        <input class="form-control required" id="place" name="place" type="text" aria-describedby="placeHelp" placeholder="Enter your place">
                                        <small class="form-text text-muted" id="placeHelp"></small>
                                    </div>
                                </div>
                                <!--<div class="form-group" style="margin-top: 36px">
                                    <div class="col-md-10">
                                        <label for="department">Department:</label>
                                        <select class="form-control required" id="department" name="department">
                                            <option value="">Choose department</option>
                                            <?php
                                            if (!empty($dept_list)) {
                                                foreach ($dept_list as $dept) {
                                                    echo '<option value="' . $dept['dept_unique_code'] . '">' . $dept['department'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="doctor">Doctor:</label>
                                        <select class="form-control required" id="doctor" name="doctor" disabled="disabled">
                                            <option value="">Choose doctor</option>
                                        </select>
                                    </div>
                                </div>-->
                            </div>
                            <div class="col-md-6" style="padding-left: 15px !important;">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="last_name">Last name: </label>
                                        <input class="form-control" id="last_name" name="last_name" type="text" aria-describedby="last_nameHelp" placeholder="Enter Last name">
                                        <small class="form-text text-muted" id="last_nameHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label class="control-label">Gender:</label><br />
                                        <label class="radio-inline"><input class="form-check-input required" type="radio" name="gender" id="gender" value="Male">Male</label>
                                        <label class="radio-inline"><input class="form-check-input required" type="radio" name="gender" id="gender" value="Female">Female</label>
                                        <label class="radio-inline"><input class="form-check-input required" type="radio" name="gender" id="gender" value="others">Others</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="occupation">Occupation: </label>
                                        <input class="form-control required" id="occupation" name="occupation" type="text" aria-describedby="ageHelp" placeholder="Enter your occupation">
                                        <small class="form-text text-muted" id="occupationHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="address">Address: </label>
                                        <textarea class="form-control required" id="address" name="address" type="text" aria-describedby="addressHelp" placeholder="Enter your address"></textarea>
                                        <small class="form-text text-muted" id="addressHelp"></small>
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <div class="col-md-10">
                                        <label for="sub_department">Sub department:</label>
                                        <select class="form-control required" id="sub_department" name="sub_department" disabled="disabled">
                                            <option value="">Choose sub department</option>
                                        </select>
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="consultation_date">Consultation date: </label>
                                        <input class="form-control date_picker required" id="consultation_date" name="consultation_date" type="text" aria-describedby="consultation_dateHelp" placeholder="Enter consultation date" value="<?= date('Y-m-d') ?>">
                                        <small class="form-text text-muted" id="last_nameHelp"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-default pull-right" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                        <button class="btn btn-danger pull-right rm-1" type="reset" name="reset_button" id="reset_button"><i class="fa fa-fw fa-lg fa-refresh"></i>Reset</button>
                        <button class="btn btn-primary pull-right rm-1" type="button" name="save_button" id="save_button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="statusModalLabel">Status</h5>
            </div>
            <div class="modal-body">
                <!-- Status message will be dynamically inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var validator = $('#patient_form').validate({});
        $('#save_button').on('click', function() {
            if ($('#patient_form').valid()) {
                $.ajax({
                    url: $('#patient_form').attr('action'),
                    type: 'POST',
                    data: $('#patient_form').serialize(),
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 'success') {
                            $('#statusModal .modal-body').html('<p class="text-success">' + response.message + '</p> <br>' +
                                '<a target="_blank" href="<?php echo base_url("patient/generate_opd_card?opd_id="); ?>' + response.opd_no + '" class="btn btn-primary"><i class="fa fa-download"></i> Generate OPD card</a>');
                        } else {
                            $('#statusModal .modal-body').html('<p class="text-danger">' + response.message + '</p>');
                        }
                        $('#statusModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        $('#statusModal .modal-body').html('<p class="text-danger">An error occurred: ' + error + '</p>');
                        $('#statusModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');
                    }
                });
            }
        });
        $('#reset_button').on('click', function() {
            $('#patient_form')[0].reset();
            validator.resetForm();
        });
    });
</script>