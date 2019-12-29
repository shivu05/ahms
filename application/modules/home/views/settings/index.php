<?php $is_admin = $this->rbac->is_admin(); ?>
<div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php if ($is_admin): ?>
                <li class="active"><a href="#user-settings" data-toggle="tab" aria-expanded="true">Settings</a></li>
            <?php endif; ?>
            <li class="<?= (!$is_admin) ? 'active' : '' ?>"><a class="" href="#profile-settings" data-toggle="tab" aria-expanded="false">Profile</a></li>
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
                                <label for="exampleInputFile">College logo:</label><br/>
                                <input type="file" name="college_logo" class="span4" id="college_logo" value="abc.png"/>
                                <input type="hidden" name="logo_name" id="logo_name" value="<?php echo $settings['logo']; ?>"/>

                            </div>
                            <div class="col-md-4 mb-4">
                                <img src="<?php echo base_url('assets/' . $settings['logo']); ?>" width="90px" height="90px"/>
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
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;

        if ('#profile-settings' == hash) {
            $('.user-settings').removeClass('active show');
            $('.profile-settings').addClass('active show');
            $('#user-settings').removeClass('active');
            $('#profile-settings').addClass('active');
            $('.profile-settings > .active').closest('li').find('a').trigger('click');
        } else {
            $('.profile-settings').removeClass('active show');
            $('.user-settings').addClass('active show');
            $('#user-settings').addClass('active');
            $('#profile-settings').removeClass('active');
            $('.nav-item > .active').next('li').find('a').trigger('click');
        }

    });
</script>