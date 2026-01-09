<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Invoices List - Filter & Search</h3>
    </div>
    <div class="panel-body">
        <form method="GET" class="form-inline">
            <div class="form-group">
                <label>Invoice Status:</label>
                <select name="invoice_status" class="form-control">
                    <option value="">-- All --</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status; ?>" 
                            <?php echo ($status == $this->input->get('invoice_status')) ? 'selected' : ''; ?>>
                            <?php echo $status; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Status:</label>
                <select name="payment_status" class="form-control">
                    <option value="">-- All --</option>
                    <option value="UNPAID" <?php echo ($this->input->get('payment_status') == 'UNPAID') ? 'selected' : ''; ?>>Unpaid</option>
                    <option value="PARTIAL" <?php echo ($this->input->get('payment_status') == 'PARTIAL') ? 'selected' : ''; ?>>Partial</option>
                    <option value="PAID" <?php echo ($this->input->get('payment_status') == 'PAID') ? 'selected' : ''; ?>>Paid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?php echo site_url('billing/invoices'); ?>" class="btn btn-default">Reset</a>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title">Invoices (<?php echo count($invoices); ?>)</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/create_invoice'); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> New Invoice
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered datatable">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invoices)): ?>
                        <?php foreach ($invoices as $inv): ?>
                            <tr>
                                <td><strong><?php echo $inv['invoice_number']; ?></strong></td>
                                <td><?php echo date('d-m-Y', strtotime($inv['invoice_date'])); ?></td>
                                <td><?php echo $inv['patient_name'] ?? 'N/A'; ?></td>
                                <td><span class="label label-info"><?php echo $inv['invoice_type']; ?></span></td>
                                <td class="text-right">₹<?php echo number_format($inv['total_amount'], 2); ?></td>
                                <td class="text-right">₹<?php echo number_format($inv['amount_paid'], 2); ?></td>
                                <td class="text-right">₹<?php echo number_format($inv['balance_due'], 2); ?></td>
                                <td><?php echo get_invoice_status_badge($inv['invoice_status']); ?></td>
                                <td><?php echo get_payment_status_badge($inv['payment_status']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo site_url('billing/view_invoice/' . $inv['invoice_id']); ?>" 
                                           class="btn btn-xs btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('billing/print_invoice/' . $inv['invoice_id']); ?>" 
                                           class="btn btn-xs btn-info" title="Print" target="_blank">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        <?php if ($inv['payment_status'] != 'PAID'): ?>
                                            <a href="<?php echo site_url('billing/payment/' . $inv['invoice_id']); ?>" 
                                               class="btn btn-xs btn-success" title="Payment">
                                                <i class="fa fa-money"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No invoices found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        "pageLength": 25,
        "order": [[1, "desc"]],
        "columnDefs": [
            {"orderable": false, "targets": -1}
        ]
    });
});
</script>
