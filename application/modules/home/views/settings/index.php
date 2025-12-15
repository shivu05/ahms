<?php $is_admin = $this->rbac->is_admin(); ?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Configuration settings:</h3>
        </div>
        <div class="box-body">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php if ($is_admin): ?>
                        <li class="active"><a href="#user-settings" data-toggle="tab" aria-expanded="true">Settings</a></li>
                    <?php endif; ?>
                    <li class=""><a class="" href="#profile-settings" data-toggle="tab" aria-expanded="false">Profile</a></li>
                </ul>
                <div class="tab-content">
                    <?php if ($is_admin) { ?>
                        <div class="tab-pane <?= ($is_admin) ? 'active' : '' ?>" id="user-settings">
                            <div class="tile user-settings">
                                <h4 class="line-head">Settings</h4>
                                <?php
                                $attributes = array(
                                    'class' => 'form-horizontal',
                                    'name' => 'config_settings_form',
                                    'id' => 'config_settings_form',
                                    'enctype' => "multipart/form-data"
                                );
                                echo form_open_multipart('home/Settings/save_app_settings', $attributes);
                                ?>
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <label>College name:</label>
                                        <textarea class="form-control required" name="college_name" id="college_name"><?php echo trim($settings['college_name']); ?></textarea>
                                    </div>
                                    <div class="col-md-8 mb-4">
                                        <label>College code:</label>
                                        <input class="form-control required" name="college_id" id="college_id" type="text" value="<?php echo trim($settings['college_id']); ?>">
                                        <small class="form-text text-danger" id="college_idHelp">Please do not update College ID once set as it will be used for UHID Generation</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <label>Place:</label>
                                        <input class="form-control required" name="place" id="place" type="text" value="<?php echo trim($settings['place']); ?>">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-8 mb-4">
                                        <label>Printing style:</label>
                                        <select class="form-control" name="printing_style" id="printing_style">
                                            <option value="L" <?php echo ($settings['printing_style'] == 'L') ? 'selected="selected"' : ''; ?>>L</option>
                                            <option value="P" <?php echo ($settings['printing_style'] == 'P') ? 'selected="selected"' : ''; ?>>P</option>
                                        </select>
                                        <small class="form-text text-muted" id="emailHelp">P - Portrait, L - Landscape</small>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-8 mb-4">
                                        <label>Email</label>
                                        <input class="form-control" type="text" name="config_email" id="config_email" value="<?php echo trim($settings['admin_email']); ?>">
                                        <small class="form-text text-muted" id="emailHelp">Notifications will be sent to this email id</small>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="exampleInputFile">College logo:</label><br />
                                        <!-- Allow only images; show size hint -->
                                        <input type="file" name="college_logo" class="span4" id="college_logo" accept="image/png, image/jpeg" />
                                        <input type="hidden" name="logo_name" id="logo_name" value="<?php echo $settings['logo']; ?>" />
                                        <small class="form-text text-muted">Allowed types: .jpg, .png — Max size: 2MB</small>
                                    </div>
                                    <div class="col-md-4 mb-4 text-center">
                                        <img id="logo_preview" src="<?php echo base_url('assets/' . $settings['logo']); ?>" width="90" height="90" alt="College logo" style="object-fit:contain;border:1px solid #ddd;padding:4px;background:#fff;" />
                                        <?php /*echo '<img src="data:image/png;base64,'.base64_encode($settings['logo_img']).'" width="90px" height="90px"/>'; */ ?>
                                    </div>
                                </div>
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="tab-pane <?= (!$is_admin) ? 'active' : '' ?>" id="profile-settings">
                        <div class="tile profile-settings">
                            <h4 class="line-head">User profile</h4>
                            <div class="row">
                                <div class="col-md-8 mb-4">
                                    <label>Name:</label>
                                    <input class="form-control required" name="place" id="place" type="text" value="<?php echo trim($user_profile[0]['user_name']); ?>">
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-8 mb-4">
                                    <label>Email:</label>
                                    <input class="form-control required" name="place" id="place" type="text" value="<?php echo trim($user_profile[0]['user_email']); ?>" readonly="readonly">
                                    <small class="form-text text-muted" id="emailHelp">Email can not be edited</small>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-8 mb-4">
                                    <label>Country</label>
                                    <input class="form-control" type="text" name="country" id="country" value="<?php echo trim($user_profile[0]['user_country']); ?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var hash = window.location.hash;
        if ('#profile-settings' === hash) {
            $('.user-settings').removeClass('active show');
            $('.profile-settings').addClass('active show');
            $('#user-settings').removeClass('active');
            $('#profile-settings').addClass('active');
            $('.profile-settings > .active').closest('li').find('a').trigger('click');
            $('.nav-item > .active').next('li').find('a').trigger('click');
        } else {
            $('.profile-settings').removeClass('active show');
            $('.user-settings').addClass('active show');
            $('#user-settings').addClass('active');
            $('#profile-settings').removeClass('active');
            $('.nav-item > .active').next('li').find('a').trigger('click');
        }

        // logo preview
        $('#college_logo').on('change', function (e) {
            var file = this.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('File size exceeds 2MB. Please choose a smaller image.');
                $(this).val('');
                return;
            }
            var reader = new FileReader();
            reader.onload = function (ev) {
                $('#logo_preview').attr('src', ev.target.result);
            };
            reader.readAsDataURL(file);
        });

        // lock college_id if edit_flag is 0
        var editFlag = '<?php echo $settings['edit_flag']; ?>';
        if (editFlag === '0') {
            $('#college_id').prop('readonly', true).addClass('form-control-plaintext');
            $('#college_idHelp').text('College ID is locked and cannot be changed.');
        }

    });
</script>