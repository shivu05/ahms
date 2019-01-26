<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="widget-small primary "><i class="icon fa fa-clock-o fa-3x"></i>
            <div class="info">
                <h4>Pending</h4>
                <p><b><?php echo $stats['PENDING']; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="widget-small info"><i class="icon fa fa-check fa-3x"></i>
            <div class="info">
                <h4>Completed</h4>
                <p><b><?php echo $stats['COMPLETED']; ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="widget-small warning"><i class="icon fa fa-plus-circle fa-3x"></i>
            <div class="info">
                <h4>Total</h4>
                <p><b><?php echo $stats['TOTAL']; ?></b></p>
            </div>
        </div>
    </div>
</div>