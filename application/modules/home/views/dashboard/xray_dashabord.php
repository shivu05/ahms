<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon fa fa-male fa-1x"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number"><?php echo $stats['PENDING']; ?></span>
            </div>
            <!--/.info-box-content -->
        </div>
        <!--/.info-box -->
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon fa fa-female"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completed</span>
                <span class="info-box-number"><?php echo $stats['COMPLETED']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon fa fa-user-plus"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo $stats['TOTAL']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>