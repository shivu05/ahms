<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AHMS | <?= $this->layout->title ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?php
        echo $this->scripts_include->includeCss();
        echo $this->scripts_include->preJs();
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css'); ?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script type="text/javascript">
            var base_url = '<?= APP_BASE ?>';
        </script>
        <style type="text/css">
            .fa-disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            .chosen-container{
                width: 100% !important;
            }
        </style>
    </head>
    <body class="app sidebar-mini rtl sidenav-toggled">
        <?php
        if ($this->layout->headerFlag) :
            $this->load->view($this->layout->layoutsFolder . '/header');
        else :
            $this->load->view($this->layout->layoutsFolder . '/no_header');
        endif;
        ?>
        <?php $this->load->view($this->layout->layoutsFolder . '/popups'); ?>
        <!--<div class="container">
             Content Header (Page header) -->

        <!-- Main content -->
        <main class="app-content">
            <?php $this->layout->navTitleFlag = false;
            if ($this->layout->navTitleFlag): ?>   
                <div class="app-title">
                    <div>
                        <h1><i class="<?php echo $this->layout->navIcon; ?>"></i> <?php echo $this->layout->navTitle; ?></h1>
                        <p><?php echo $this->layout->navDescr; ?></p>
                    </div>
                    <ul class="app-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo $this->layout->navTitle; ?></a></li>
                    </ul>
                </div>
            <?php endif; ?>
<?= $content ?>
        </main>
        <!-- /.content -->
        <?php
        if ($this->layout->footerFlag) {
            $this->load->view($this->layout->layoutsFolder . '/footer');
        } else {
            $this->load->view($this->layout->layoutsFolder . '/no_footer');
        }
        ?>
<?= $this->scripts_include->includeJs(); ?>
        <script type="text/javascript">
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                daysOfWeekHighlighted: "0"
            });
            $('.date_picker').attr('autocomplete', 'off');
        </script>
    </body>
</html>