<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Billing Dashboard</h1>
        </div>
    </div>
</div>

<?php if (isset($setup_required) && $setup_required): ?>
<div class="alert alert-warning">
    <strong>Setup Required:</strong> <?php echo $message ?? 'Billing module needs to be configured'; ?>
</div>
<?php else: ?>

<div class="row">
    <!-- Today's Statistics -->
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Today's Invoices</h3>
            </div>
            <div class="panel-body text-center">
                <h2><?php echo $today_invoices ?? 0; ?></h2>
                <p class="text-muted">Invoices Created</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Today's Collection</h3>
            </div>
            <div class="panel-body text-center">
                <h2>₹<?php echo number_format($today_collection ?? 0, 2); ?></h2>
                <p class="text-muted"><?php echo $today_payments ?? 0; ?> Payments</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Overdue Invoices</h3>
            </div>
            <div class="panel-body text-center">
                <h2><?php echo $overdue_invoices ?? 0; ?></h2>
                <p class="text-muted">Pending Payment</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Pending Claims</h3>
            </div>
            <div class="panel-body text-center">
                <h2><?php echo $pending_claims ?? 0; ?></h2>
                <p class="text-muted">Insurance Claims</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Month's Summary -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">This Month's Summary</h3>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr>
                        <td><strong>Total Invoices:</strong></td>
                        <td><?php echo $month_summary['total_invoices'] ?? 0; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Revenue:</strong></td>
                        <td>₹<?php echo number_format($month_summary['total_revenue'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Collected:</strong></td>
                        <td>₹<?php echo number_format($month_summary['total_collected'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pending Amount:</strong></td>
                        <td>₹<?php echo number_format($month_summary['total_pending'] ?? 0, 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Invoice Status -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Invoice Status</h3>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr>
                        <td><strong>Paid Invoices:</strong></td>
                        <td><?php echo $month_summary['paid_invoices'] ?? 0; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Unpaid Invoices:</strong></td>
                        <td><?php echo $month_summary['unpaid_invoices'] ?? 0; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Partially Paid:</strong></td>
                        <td><?php echo $month_summary['partial_invoices'] ?? 0; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Recent Invoices -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Recent Invoices</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_invoices)): ?>
                            <?php foreach ($recent_invoices as $inv): ?>
                                <tr>
                                    <td><?php echo $inv['invoice_number']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($inv['invoice_date'])); ?></td>
                                    <td><?php echo $inv['patient_name'] ?? 'N/A'; ?></td>
                                    <td>₹<?php echo number_format($inv['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="label label-<?php echo get_invoice_status_class($inv['payment_status']); ?>">
                                            <?php echo $inv['payment_status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('billing/view_invoice/' . $inv['invoice_id']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No invoices found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Quick Actions</h3>
            </div>
            <div class="panel-body">
                <a href="<?php echo site_url('billing/create_invoice'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Invoice
                </a>
                <a href="<?php echo site_url('billing/invoices'); ?>" class="btn btn-info">
                    <i class="fa fa-list"></i> View All Invoices
                </a>
                <a href="<?php echo site_url('billing/insurance/claims'); ?>" class="btn btn-warning">
                    <i class="fa fa-file"></i> Insurance Claims
                </a>
                <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default">
                    <i class="fa fa-shield"></i> Policies
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php
function get_invoice_status_class($status) {
    switch($status) {
        case 'PAID':
            return 'success';
        case 'UNPAID':
            return 'danger';
        case 'PARTIAL':
            return 'warning';
        default:
            return 'default';
    }
}
?>
