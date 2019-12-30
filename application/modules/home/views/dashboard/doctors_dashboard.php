<div class="row">
    <div class="col-md-4 col-lg-4">
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
    <div class="col-md-4 col-lg-4">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon fa fa-female"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Female</span>
                <span class="info-box-number"><?php echo $gender_count[0]['females']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon fa fa-user-plus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number"><?php echo $gender_count[0]['total']; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>