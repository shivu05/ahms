<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon fa fa-male fa-1x"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Male</span>
                <span class="info-box-number"><?php echo $gender_count[0]['males']; ?></span>
            </div>
            <!--/.info-box-content -->
        </div>
        <!--/.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon fa fa-female"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Female</span>
                <span class="info-box-number"><?php echo $gender_count[0]['females']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon fa fa-user-plus"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo $gender_count[0]['total']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<?php

use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\export\Exportable;

$koolarray = array();
if (!empty($dept_wise_data)) {
    foreach ($dept_wise_data as $pdata) {
        $koolarray[] = array(
            'Department' => ucfirst(strtolower(str_replace('_', ' ', $pdata['department']))),
            'OLD' => (int) $pdata['OLD'],
            'NEW' => (int) $pdata['NEW'],
            'Total' => (int) $pdata['Total'],
            'Male' => (int) $pdata['Male'],
            'Female' => (int) $pdata['Female'],
        );
    }
}
?>
<style>
    .float-right{
        text-align: right !important;
    }
</style>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Department wise patients</h3></div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                ColumnChart::create(array(
                    "dataSource" => $koolarray
                ));
                ?>
                <hr/>
                <?php
                Table::create(array(
                    "showFooter" => "bottom",
                    "dataSource" => $koolarray,
                    "columns" => array(
                        'Department' => array(),
                        'OLD' => array("footer" => "sum", 'cssClass' => 'float-right'),
                        'NEW' => array("footer" => "sum"),
                        'Total' => array("footer" => "sum"),
                        'Male' => array("footer" => "sum"),
                        'Female' => array("footer" => "sum"),
                    ),
                    "cssClass" => array(
                        "table" => "table table-hover table-bordered dataTable",
                        "tf" => "alert-info"
                    )
                ));
                ?>
            </div>
        </div>
    </div>
</div>