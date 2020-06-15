<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon fa fa-clock-o fa-1x"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number"><?php echo $stats['PENDING']; ?></span>
            </div>
            <!--/.info-box-content -->
        </div>
        <!--/.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon fa fa-check fa-1x"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completed</span>
                <span class="info-box-number"><?php echo $stats['COMPLETED']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon fa fa-plus-circle fa-1x"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo $stats['TOTAL']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>