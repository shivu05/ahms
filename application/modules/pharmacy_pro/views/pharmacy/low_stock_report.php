<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-exclamation-triangle"></i>
            Low Stock Report
            <small>Medicines Below Reorder Level</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>">Reports</a></li>
            <li class="active">Low Stock Report</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicines Below Reorder Level</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" onclick="window.print()">
                                <i class="fa fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> 
                            <strong>Action Required:</strong> These medicines need to be restocked immediately.
                        </div>
                        
                        <?php if (isset($low_stock_medicines) && !empty($low_stock_medicines)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="lowStockTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Category</th>
                                            <th>Current Stock</th>
                                            <th>Reorder Level</th>
                                            <th>Shortage</th>
                                            <th>Last Purchase Date</th>
                                            <th>Preferred Supplier</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($low_stock_medicines as $medicine): ?>
                                            <tr class="<?php echo ($medicine->current_stock == 0) ? 'danger' : 'warning'; ?>">
                                                <td><?php echo $medicine->medicine_name; ?></td>
                                                <td><?php echo $medicine->category_name; ?></td>
                                                <td>
                                                    <span class="label label-<?php echo ($medicine->current_stock == 0) ? 'danger' : 'warning'; ?>">
                                                        <?php echo $medicine->current_stock; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $medicine->reorder_level; ?></td>
                                                <td><?php echo max(0, $medicine->reorder_level - $medicine->current_stock); ?></td>
                                                <td>
                                                    <?php 
                                                    echo isset($medicine->last_purchase_date) ? 
                                                         date('d-m-Y', strtotime($medicine->last_purchase_date)) : 
                                                         'Never';
                                                    ?>
                                                </td>
                                                <td><?php echo isset($medicine->supplier_name) ? $medicine->supplier_name : 'Not Set'; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary" onclick="createPO(<?php echo $medicine->id; ?>)">
                                                        <i class="fa fa-plus"></i> Create PO
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> 
                                Excellent! All medicines are above reorder levels, or please import the database schema to view stock data.
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
    $('#lowStockTable').DataTable({
        "order": [[2, "asc"]],
        "pageLength": 25
    });
});

function createPO(medicineId) {
    // Redirect to create purchase order with pre-selected medicine
    window.location.href = '<?php echo site_url("pharmacy_pro/pharmacy/create_purchase_order"); ?>?medicine_id=' + medicineId;
}
</script>
