<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $this->layout->title ?> - AYUSH</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $this->scripts_include->preJs() ?>
        <?= $this->scripts_include->includeCss() ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php /* echo CI_VERSION; */ ?>
        <style type="text/css">
            .error{
                color:red;
            }
            .loading-box{
                padding-top: 15% !important;
                text-align: center !important;
                position: absolute;
                display: none;
                height: 100% ;
                width: 100% ;
                z-index: 999 !important;
                background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
            }
            .ui-autocomplete{
                font-size: 12px !important;
            }
            .no_pad{
                padding: 0px !important;
            } 
            .no_margin{
                margin: 0px !important;
            }
            .dataTable thead tr  {
                background-color: #777777;
                color: #FFFFFF;
                font-weight: bold;
            }
            body{
                font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
            }
        </style>
        <script type="text/javascript">
            jQuery.ui.autocomplete.prototype._resizeMenu = function () {
                var ul = this.menu.element;
                ul.outerWidth(this.element.outerWidth());
            };
        </script>

    </head>
    <body class="hold-transition skin-blue layout-top-nav">   
        <div class="wrapper" style="overflow-y:hidden !important;">
            <?php
            if ($this->layout->headerFlag) :
                $this->load->view($this->layout->layoutsFolder . '/header');
            else :
                $this->load->view($this->layout->layoutsFolder . '/no_header');
            endif;
            ?>
            <!-- Left side column. contains the logo and sidebar -->
            <?php //$this->load->view($this->layout->layoutsFolder . '/l_side_bar'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="container-fluid">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <?php
                        if ($this->layout->navTitleFlag) {
                            echo '<h1>' . $this->layout->navTitle . '</h1>';
                        }
                        if ($this->layout->breadcrumbsFlag) {
                            if (!$this->layout->navTitleFlag || $this->layout->navTitle == '') {
                                echo '<h1>&nbsp;</h1>';
                            }
                            echo $this->breadcrumbs->show();
                        }
                        ?>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <div class="loading-box" id="ajxloading">
                            <p class="h1"><i class="fa fa-refresh fa-spin text-lime"></i></p>
                            <p id="load_msg"><small>please wait...</small></p>
                        </div>
                        <?php
                        if (GLOBAL_NOTICE) {
                            echo '<div class="alert alert-warning alert-dismissible">
                               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <i class="icon fa fa-info"></i>' . GLOBAL_NOTICE . '</div>';
                        }
                        ?>
                        <?php $this->load->view($this->layout->layoutsFolder . '/popups'); ?>
                        <?= $content ?>
                        <!-- /.box -->
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.container -->
            </div>
            <!-- ./wrapper -->
            <!--/.content-wrapper -->
            <!--Footer -->
            <?php
            if ($this->layout->footerFlag) {
                $this->load->view($this->layout->layoutsFolder . '/footer');
            } else {
                $this->load->view($this->layout->layoutsFolder . '/no_footer');
            }
            ?> 
            <!-- Footer -->
            <!-- Control Sidebar -->
            <?php $this->load->view($this->layout->layoutsFolder . '/r_side_bar'); ?>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <!--<div class="control-sidebar-bg"></div>-->
        </div>        
        <!-- ./wrapper -->
        <?= $this->scripts_include->includeJs() ?>
        <script type="text/javascript">
            $.widget.bridge('uibutton', $.ui.button);
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2();
            });
            $('#ajxloading').on('ajaxStart', function () {
                $(this).show();
            }).on('ajaxStop', function () {
                $(this).hide();
            });

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
