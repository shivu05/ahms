<?php if (isset($setup_required) && $setup_required): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Pharmacy Module Setup Required!</h4>
                <p>The pharmacy database tables are not yet created. Please run the SQL schema file to set up the pharmacy module.</p>
                <p><strong>File Location:</strong> <code>application/modules/pharmacy_pro/sql/pharmacy_schema.sql</code></p>
                <?php if (isset($error_message)): ?>
                    <p><strong>Error Details:</strong> <?php echo $error_message; ?></p>
                <?php endif; ?>
                <hr>
                <p><strong>Setup Instructions:</strong></p>
                <ol>
                    <li>Import the pharmacy_schema.sql file into your database</li>
                    <li>Refresh this page</li>
                    <li>Start using the professional pharmacy management system</li>
                </ol>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Dashboard Cards -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $total_medicines; ?></h3>
                <p>Total Medicines</p>
            </div>
            <div class="icon">
                <i class="fa fa-medkit"></i>
            </div>
            <a href="<?php echo site_url('pharmacy_pro/pharmacy/medicines'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $low_stock_count; ?></h3>
                <p>Low Stock Items</p>
            </div>
            <div class="icon">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <a href="<?php echo site_url('pharmacy_pro/pharmacy/low_stock_report'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo $expired_count; ?></h3>
                <p>Expired Items</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
            </div>
            <a href="<?php echo site_url('pharmacy_pro/pharmacy/expiry_report'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>₹<?php echo number_format($todays_sales, 0); ?></h3>
                <p>Today's Sales</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a href="<?php echo site_url('pharmacy_pro/pharmacy/sales_report'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales Chart -->
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Monthly Sales Trend</h3>
            </div>
            <div class="box-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body">
                <a href="<?php echo site_url('pharmacy_pro/pharmacy/pos'); ?>" class="btn btn-primary btn-block btn-lg">
                    <i class="fa fa-shopping-cart"></i> Point of Sale
                </a>
                <br>
                <a href="<?php echo site_url('pharmacy_pro/pharmacy/medicines'); ?>" class="btn btn-info btn-block">
                    <i class="fa fa-plus"></i> Add Medicine
                </a>
                <a href="<?php echo site_url('pharmacy_pro/pharmacy/purchases'); ?>" class="btn btn-warning btn-block">
                    <i class="fa fa-truck"></i> Purchase Order
                </a>
                <a href="<?php echo site_url('pharmacy_pro/pharmacy/inventory'); ?>" class="btn btn-success btn-block">
                    <i class="fa fa-cubes"></i> Inventory
                </a>
                <a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>" class="btn btn-default btn-block">
                    <i class="fa fa-bar-chart"></i> Reports
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Sales -->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Sales</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Bill #</th>
                                <th>Patient</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_sales)): ?>
                                <?php foreach ($recent_sales as $sale): ?>
                                    <tr>
                                        <td><?php echo $sale['bill_number']; ?></td>
                                        <td><?php echo $sale['patient_name'] ?: $sale['patient_name']; ?></td>
                                        <td>₹<?php echo number_format($sale['total_amount'], 2); ?></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($sale['sale_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No recent sales found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Soon -->
    <div class="col-md-6">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Medicines Expiring Soon (30 days)</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Batch</th>
                                <th>Stock</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($expiring_soon)): ?>
                                <?php foreach ($expiring_soon as $medicine): ?>
                                    <tr>
                                        <td><?php echo $medicine['medicine_name']; ?></td>
                                        <td><?php echo $medicine['batch_number']; ?></td>
                                        <td><?php echo $medicine['current_stock']; ?></td>
                                        <td>
                                            <span class="label label-warning">
                                                <?php echo date('d-m-Y', strtotime($medicine['expiry_date'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No medicines expiring soon</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Sales (₹)',
                data: [12000, 15000, 18000, 22000, 19000, 25000, 28000, 32000, 29000, 35000, 38000, 42000],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
