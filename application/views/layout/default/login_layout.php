<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AHMS | <?= $this->layout->title ?></title>
        <?php
        echo $this->scripts_include->includeCss();
        echo $this->scripts_include->preJs();
        ?>
    </head>
    <body>
        <section class="material-half-bg">
            <div class="cover"></div>
        </section>
        <section class="login-content">
            <div class="logo">
                <h4 style='font-family: Palatino, "Palatino Linotype", "Palatino LT STD"'><?php echo $app_settings['college_name']; ?></h4>
            </div>
            <div class="login-box">
                <form class="login-form" action="<?php echo base_url('login/validate'); ?>" method="post">
                    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
                    <p class="text-danger"><?php
                        if ($this->session->flashdata('noty_msg') != '') {
                            echo $this->session->flashdata('noty_msg');
                        }
                        ?></p>
                    <div class="form-group">
                        <label class="control-label">USERNAME</label>
                        <input class="form-control" type="text" placeholder="Email" autofocus name="loginname" id="loginname" autocomplete="off">
                        <div class="form-control-feedback text-danger"><?php echo form_error('loginname'); ?></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">PASSWORD</label>
                        <input class="form-control" type="password" placeholder="Password" name="password" id="password">
                        <div class="form-control-feedback text-danger"><?php echo form_error('password'); ?></div>
                    </div>
                    <div class="form-group">
                        <div class="utility">
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox"><span class="label-text">Stay Signed in</span>
                                </label>
                            </div>
                            <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
                        </div>
                    </div>
                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                    </div>
                </form>
                <form class="forget-form" action="<?php echo base_url('Login/validate'); ?>">
                    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
                    <div class="form-group">
                        <label class="control-label">EMAIL</label>
                        <input class="form-control" type="text" placeholder="Email">
                    </div>
                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
                    </div>
                    <div class="form-group mt-3">
                        <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
                    </div>
                </form>

            </div>
            <p>Powered by <a href="http://www.ayushsoftwares.com/" target="_blank">Ayush Softwares</a> <br/>Write us @: <a href="mailto:info@ayushsoftwares.com">info@ayushsoftwares.com</a></p>
        </section>
        <?= $this->scripts_include->includeJs(); ?>
        <script type="text/javascript">
            // Login Page Flipbox control
            $('.login-content [data-toggle="flip"]').click(function () {
                $('.login-box').toggleClass('flipped');
                return false;
            });
        </script>
    </body>
</html>