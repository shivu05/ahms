<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Add new user:</h3></div>
            <p><?php if ($this->session->flashdata('noty_msg') != '') echo $this->session->flashdata('noty_msg'); ?></p>
            <form class="form-horizontal" id="user_form" name="user_form" action="<?php echo base_url('master/users/save'); ?>" method="POST">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Name:<span class="form_astrisk">*</span> </label>
                            <input class="form-control required" id="name" name="name" type="text" aria-describedby="nameHelp" placeholder="Enter name" autocomplete="off">
                            <small class="form-text text-muted" id="nameHelp">Full name of user</small>
                        </div>    
                        <div class="col-md-3">
                            <label for="email">Email: <span class="form_astrisk">*</span></label>
                            <input class="form-control required" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" autocomplete="off">
                            <small class="form-text text-muted" id="emailHelp">Email will be your username</small>
                        </div>    
                        <div class="col-md-3">
                            <label for="mobile">Mobile: <span class="form_astrisk">*</span></label>
                            <input class="form-control required" id="mobile" name="mobile" type="text" aria-describedby="mobileHelp" placeholder="Enter mobile number" autocomplete="off">
                            <small class="form-text text-muted" id="mobileHelp">10-digit mobile number</small>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="user_pass">Password: <span class="form_astrisk">*</span></label>
                            <input class="form-control required" id="user_pass" name="user_pass" type="password" aria-describedby="user_passHelp" placeholder="Enter password" autocomplete="off">
                            <small class="form-text text-muted" id="user_passHelp"></small>
                        </div>    
                        <div class="col-md-3">
                            <label for="conf_pass">Confirm password: <span class="form_astrisk">*</span></label>
                            <input class="form-control required" id="conf_pass" name="conf_pass" type="password" aria-describedby="conf_passHelp" placeholder="Enter confirm password" autocomplete="off">
                            <small class="form-text text-muted" id="mobileHelp"></small>
                        </div>    
                        <div class="col-md-3">
                            <label for="user_role">Role: <span class="form_astrisk">*</span></label>
                            <select name="user_role" id="user_role" class="form-control required">
                                <option value="">Choose user role</option>
                                <?php
                                if (!empty($roles)) {
                                    foreach ($roles as $role) {
                                        echo '<option value="' . $role['role_id'] . '">' . $role['role_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>    
                        <div class="col-md-3">
                            <label for="user_dept">Department: <span class="form_astrisk">*</span></label>
                            <select name="user_dept" id="user_dept" class="form-control" disabled="disabled">
                                <option value="">Choose department</option>
                                <?php
                                if (!empty($department_list)) {
                                    foreach ($department_list as $dept) {
                                        echo '<option value="' . $dept['dept_unique_code'] . '">' . $dept['department'] . '</option>';
                                    }
                                }
                                ?>    
                            </select>
                            <small class="form-text text-muted" id="deptHelp"></small>
                        </div>    
                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-default pull-right" href="#" id="cancel_btn"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                    <button class="btn btn-primary pull-right" style="margin-right: 10px;" type="button" name="register_btn" id="register_btn"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#user_form').on('change', '#user_role', function () {
            var role = $('#user_role').val();
            if (role == 4) {
                $('#user_dept').attr('disabled', false);
            } else {
                $('#user_dept').attr('disabled', true);
            }
        });
        $('#user_form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: base_url + 'master/users/check_for_duplicate_email'
                },
                mobile: {
                    required: true,
                    maxlength: 10,
                    minlength: 10
                },
                user_pass: {
                    required: true,
                    minlength: 5
                },
                conf_pass: {
                    required: true,
                    minlength: 5,
                    equalTo: '#user_pass'
                }
            },
            messages: {
                email: {
                    remote: 'Email already exists',
                },
                conf_pass: {
                    equalTo: 'Passwords does not match'
                }
            }
        });

        $('#register_btn').on('click', function () {
            if ($('#user_form').valid()) {
                $('#user_form').submit();
            } else {
                $('#warning_modal_box .modal-title').html('Warning');
                $('#warning_modal_box .modal-body').html('Please check all the fields.');
                $('#warning_modal_box').modal('show');
            }
        });
    });
</script>