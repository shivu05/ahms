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
        <style id="antiClickjack">body{display:none !important;}</style>
        <script type="text/javascript">
            if (self === top) {
                var antiClickjack = document.getElementById("antiClickjack");
                antiClickjack.parentNode.removeChild(antiClickjack);
            } else {
                top.location = self.location;
            }
        </script>
    </head>
    <body class="hold-transition login-page" style="background-image: url('<?php echo base_url('/assets/ayush_bg.png') ?>'); background-repeat: no-repeat, repeat;">
        <div class="login-box">
            <div class="login-logo">
                <img src="<?= base_url('assets/your_logo.png') ?>" class="logo-mini" style="background-color: white;" height="120px" width="120px" />
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form class="login-form" action="<?php echo base_url('login/validate'); ?>" method="post">
                    <p class="text-danger"><?php
                        if ($this->session->flashdata('noty_msg') != '') {
                            echo $this->session->flashdata('noty_msg');
                        }
                        ?></p>
                    <div class="form-group has-feedback">
                        <input class="form-control" type="text" placeholder="Email" autofocus name="loginname" id="loginname" autocomplete="off">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input class="form-control" type="password" placeholder="Password" name="password" id="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <?= $this->scripts_include->includeJs(); ?>
    </body>
</html>