<div class="row">
    <div class="col-md-4 col-lg-4 col-sm-12 col-md-offset-4 col-lg-offset-4">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-edit"></i> Change password</h3><a href="<?php echo base_url('login/home'); ?>" class="btn btn-primary btn-sm pull-right">Back to home</a>
            </div>
            <form method="POST" name="pass_change_form" id="pass_change_form"  autocomplete="off">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="current_password">Current password: <span class="error">*</span> </label>
                                <input type="password" class="form-control" id="current_password" placeholder="Current password" name="current_password" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_password">New password: <span class="error">*</span></label>
                                <input type="password" class="form-control strong_password" id="new_password" placeholder="New password" name="new_password" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirm password: <span class="error">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" placeholder="Confirm password" name="confirm_password" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-aqua">Password rules:</h4>
                            <ul>
                                <li>Password must be between 8 and 20 characters long.</li>
                                <li>Password must contain atleast one uppercase.</li>
                                <li>Password must contain atleast one lowercase.</li>
                                <li>Password must contain atleast one digit.</li>
                                <li>Password must contain special characters from @#$%&.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-danger btn-sm pull-right">Reset</button>
                    <button type="button" class="btn btn-primary btn-sm pull-right" style="margin-right: 10px;" name="update_btn" id="update_btn"><i class="fa fa-refresh icon_chng"></i> Update password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("strong_password", function (value, element) {
            let password = value;
            if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
                return false;
            }
            return true;
        }, function (value, element) {
            let password = $(element).val();
            if (!(/^(.{8,20}$)/.test(password))) {
                return 'Password must be between 8 and 20 characters long.';
            } else if (!(/^(?=.*[A-Z])/.test(password))) {
                return 'Password must contain atleast one uppercase.';
            } else if (!(/^(?=.*[a-z])/.test(password))) {
                return 'Password must contain atleast one lowercase.';
            } else if (!(/^(?=.*[0-9])/.test(password))) {
                return 'Password must contain atleast one digit.';
            } else if (!(/^(?=.*[@#$%&])/.test(password))) {
                return "Password must contain special characters from @#$%&.";
            }
            return false;
        });

        $('#pass_change_form').validate({
            rules: {
                current_password: {
                    required: true,
                    remote: {
                        url: '<?= base_url() ?>' + "check-password",
                        type: "post"
                    }
                },
                new_password: {
                    required: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                current_password: {
                    required: "Current password is required",
                    remote: "Wrong current password entered"
                },
                new_password: {
                    required: "New password is required"
                },
                confirm_password: {
                    required: "Confirm password is required",
                    equalTo: "Password does not match"
                }
            }
        });
        $('#pass_change_form #update_btn').on('click', function () {

            if ($('#pass_change_form').valid()) {
                $('.icon_chng').addClass('fa-spin');
                var form_data = $('#pass_change_form').serializeArray();
                $.ajax({
                    url: '<?= base_url() ?>' + "update-password",
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        BootstrapDialog.show({
                            type: BootstrapDialog.TYPE_SUCCESS,
                            title: 'Change password',
                            message: response.msg,
                            cssClass: 'login-dialog',
                            buttons: [{
                                    label: response.label,
                                    cssClass: response.class,
                                    action: function (dialog) {
                                        dialog.close();
                                        if (response.status == true) {
                                            window.location = '<?= base_url() ?>' + 'logout';
                                        }
                                    }
                                }]
                        });
                    }
                });

            } else {
                $('.icon_chng').removeClass('fa-spin');
                alert('in valid');
            }
        });
    });
</script>