<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-clock-o"></i>
            Expiry Report
            <small>Medicines Expiring Soon</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>">Reports</a></li>
            <li class="active">Expiry Report</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicines Expiring in Next 90 Days</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> 
                            <strong>Important:</strong> Please review medicines expiring soon and take necessary action.
                        </div>
                        
                        <?php if (isset($expiring_medicines) && !empty($expiring_medicines)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="expiryTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Batch Number</th>
                                            <th>Expiry Date</th>
                                            <th>Days to Expire</th>
                                            <th>Current Stock</th>
                                            <th>Value at Risk</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($expiring_medicines as $medicine): ?>
                                            <tr class="<?php echo (strtotime($medicine->expiry_date) <= strtotime('+30 days')) ? 'danger' : 'warning'; ?>">
                                                <td><?php echo $medicine->medicine_name; ?></td>
                                                <td><?php echo $medicine->batch_number; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($medicine->expiry_date)); ?></td>
                                                <td>
                                                    <?php 
                                                    $days = ceil((strtotime($medicine->expiry_date) - time()) / (60*60*24));
                                                    echo $days;
                                                    ?>
                                                </td>
                                                <td><?php echo $medicine->current_stock; ?></td>
                                                <td>₹<?php echo number_format($medicine->current_stock * $medicine->mrp, 2); ?></td>
                                                <td>
                                                    <?php if ($days <= 30): ?>
                                                        <span class="label label-danger">Critical</span>
                                                    <?php elseif ($days <= 60): ?>
                                                        <span class="label label-warning">Warning</span>
                                                    <?php else: ?>
                                                        <span class="label label-info">Watch</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> 
                                Great! No medicines are expiring in the next 90 days, or please import the database schema to view expiry data.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#expiryTable').DataTable({
        "order": [[2, "asc"]],
        "pageLength": 25
    });
});
</script>
