<div class="row">
    <?php
    // pma($dept_list);
    ?>
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <h5>Add new patient:</h5>
            </div>
            <div class="row tile-body">
                <form class="form-horizontal col-md-12" name="patient_form" id="patient_form" action="<?php echo base_url('patient/save'); ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="inputFirstname">First name: </label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" aria-describedby="first_nameHelp" placeholder="Enter your firstname">
                                <small class="form-text text-muted" id="first_nameHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Age: </label>
                                <input class="form-control required" id="age" name="age" type="text" aria-describedby="ageHelp" placeholder="Enter your age">
                                <small class="form-text text-muted" id="ageeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Mobile: </label>
                                <input class="form-control required" id="mobile" name="mobile" type="text" aria-describedby="ageHelp" placeholder="Enter your mobile number">
                                <small class="form-text text-muted" id="ageeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Place: </label>
                                <input class="form-control required" id="place" name="place" type="text" aria-describedby="placeHelp" placeholder="Enter your place">
                                <small class="form-text text-muted" id="placeHelp"></small>
                            </div>
                            <div class="form-group" style="margin-top: 36px">
                                <label for="exampleSelect1">Department:</label>
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
                            <div class="form-group">
                                <label for="exampleSelect1">Doctor:</label>
                                <select class="form-control required" id="doctor" name="doctor" disabled="disabled">
                                    <option value="">Choose doctor</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="inputLastname">Last name: </label>
                                <input class="form-control required" id="last_name" name="last_name" type="text" aria-describedby="last_nameHelp" placeholder="Enter Last name">
                                <small class="form-text text-muted" id="last_nameHelp"></small>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Gender:</label><br/>
                                <div class="form-check-inline">
                                    <label class="form-check-label inline">
                                        <input class="form-check-input required" type="radio" name="gender" id="gender" value="Male">Male
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input required" type="radio" name="gender" id="gender" value="Female">Female
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input required" type="radio" name="gender" id="gender" value="others">Others
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 30px">
                                <label for="inputAge">Occupation: </label>
                                <input class="form-control required" id="occupation" name="occupation" type="text" aria-describedby="ageHelp" placeholder="Enter your occupation">
                                <small class="form-text text-muted" id="occupationHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="inputAddress">Address: </label>
                                <textarea class="form-control required" id="address" name="address" type="text" aria-describedby="addressHelp" placeholder="Enter your address"></textarea>
                                <small class="form-text text-muted" id="addressHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelect1">Sub department:</label>
                                <select class="form-control required" id="sub_department" name="sub_department" disabled="disabled">
                                    <option value="">Choose sub department</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputLastname">Consultation date: </label>
                                <input class="form-control date_picker required" id="consultation_date" name="consultation_date" type="text" aria-describedby="consultation_dateHelp" placeholder="Enter consultation date">
                                <small class="form-text text-muted" id="last_nameHelp"></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tile-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-6">
                        <button class="btn btn-primary" type="button" name="save_button" id="save_button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" type="reset" name="reset_button" id="reset_button"><i class="fa fa-fw fa-lg fa-refresh"></i>Reset</button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var validator = $('#patient_form').validate({

        });
        $('#save_button').on('click', function () {
            if ($('#patient_form').valid()) {
                $('#patient_form').submit();
            }
        });
        $('#reset_button').on('click', function () {
            $('#patient_form')[0].reset();
            validator.resetForm();
        });

        $('#patient_form').on('change', '#department', function () {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'patient/get_sub_department',
                data: {'dept_id': dept_id},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    console.log(response.sub_dept)
                    if (response.sub_dept.length > 0) {
                        var option = '<option value="">Choose sub department</option>';
                        $.each(response.sub_dept, function (i) {
                            option += '<option value="' + response.sub_dept[i].sub_dept_name + '">' + response.sub_dept[i].sub_dept_name + '</option>';
                        });
                        $('#sub_department').html(option);
                        $('#sub_department').attr('disabled', false);
                    } else {
                        var option = '<option value="">Choose sub department</option>';
                        $('#sub_department').html(option);
                        $('#sub_department').attr('disabled', true);
                    }

                    if (response.doctors.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.doctors, function (i) {
                            option += '<option value="' + response.doctors[i].user_name + '">' + response.doctors[i].user_name + '</option>';
                        });
                        $('#doctor').html(option);
                        $('#doctor').attr('disabled', false);
                    } else {
                        var option = '<option value="">Choose doctor</option>';
                        $('#doctor').html(option);
                        $('#doctor').attr('disabled', true);
                    }
                }

            });
        });
    });
</script>

