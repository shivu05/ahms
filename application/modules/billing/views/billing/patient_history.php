<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Patient Billing History</h1>
        </div>
    </div>
</div>

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

<!-- Patient Search -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Search Patient</h3>
    </div>
    <div class="panel-body">
        <form method="GET" class="form-inline">
            <div class="form-group" style="width: 300px;">
                <label class="sr-only">Patient</label>
                <select name="patient_id" class="form-control select2" style="width: 100%;" required>
                    <option value="">-- Select Patient --</option>
                    <?php if (isset($all_patients) && !empty($all_patients)): ?>
                        <?php foreach ($all_patients as $p): ?>
                            <option value="<?php echo $p['id']; ?>" 
                                <?php echo (isset($patient_id) && $patient_id == $p['id']) ? 'selected' : ''; ?>>
                                <?php echo $p['name'] . ' (' . $p['patient_id'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Search
            </button>
        </form>
    </div>
</div>

<?php if (isset($patient) && !empty($patient)): ?>

<!-- Patient Information -->
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Patient Information</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Patient ID:</strong><br>
                <?php echo $patient['patient_id'] ?? 'N/A'; ?>
            </div>
            <div class="col-md-3">
                <strong>Name:</strong><br>
                <?php echo $patient['name'] ?? 'N/A'; ?>
            </div>
            <div class="col-md-3">
                <strong>Phone:</strong><br>
                <?php echo $patient['phone'] ?? 'N/A'; ?>
            </div>
            <div class="col-md-3">
                <strong>Email:</strong><br>
                <?php echo $patient['email'] ?? 'N/A'; ?>
            </div>
        </div>
    </div>
</div>

<!-- Billing Summary -->
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Total Invoices</h3>
            </div>
            <div class="panel-body text-center">
                <h2><?php echo $summary['total_invoices'] ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Total Billed</h3>
            </div>
            <div class="panel-body text-center">
                <h2>₹<?php echo number_format($summary['total_amount'] ?? 0, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Total Paid</h3>
            </div>
            <div class="panel-body text-center">
                <h2>₹<?php echo number_format($summary['paid_amount'] ?? 0, 2); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Outstanding</h3>
            </div>
            <div class="panel-body text-center">
                <h2>₹<?php echo number_format($summary['balance_amount'] ?? 0, 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Invoices List -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Invoice History</h3>
    </div>
    <div class="panel-body">
        <?php if (isset($invoices) && !empty($invoices)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="invoices-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Services</th>
                            <th class="text-right">Total Amount</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($invoices as $invoice): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <a href="<?php echo base_url('billing/view_invoice/' . $invoice['id']); ?>">
                                        <?php echo $invoice['invoice_number']; ?>
                                    </a>
                                </td>
                                <td><?php echo date('d-M-Y', strtotime($invoice['invoice_date'])); ?></td>
                                <td>
                                    <small>
                                        <?php 
                                        if (isset($invoice['services'])) {
                                            echo implode(', ', array_slice($invoice['services'], 0, 3));
                                            if (count($invoice['services']) > 3) {
                                                echo '... +' . (count($invoice['services']) - 3) . ' more';
                                            }
                                        }
                                        ?>
                                    </small>
                                </td>
                                <td class="text-right">₹<?php echo number_format($invoice['total_amount'], 2); ?></td>
                                <td class="text-right">₹<?php echo number_format($invoice['paid_amount'], 2); ?></td>
                                <td class="text-right">
                                    <strong class="text-<?php echo ($invoice['balance_amount'] > 0) ? 'danger' : 'success'; ?>">
                                        ₹<?php echo number_format($invoice['balance_amount'], 2); ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="label label-<?php 
                                        echo ($invoice['payment_status'] == 'PAID') ? 'success' : 
                                             (($invoice['payment_status'] == 'PARTIAL') ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo $invoice['payment_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('billing/view_invoice/' . $invoice['id']); ?>" 
                                       class="btn btn-xs btn-info" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php if ($invoice['balance_amount'] > 0): ?>
                                        <a href="<?php echo base_url('billing/payment/' . $invoice['id']); ?>" 
                                           class="btn btn-xs btn-success" title="Record Payment">
                                            <i class="fa fa-money"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="success">
                            <th colspan="4" class="text-right">Total:</th>
                            <th class="text-right">₹<?php echo number_format($summary['total_amount'] ?? 0, 2); ?></th>
                            <th class="text-right">₹<?php echo number_format($summary['paid_amount'] ?? 0, 2); ?></th>
                            <th class="text-right">₹<?php echo number_format($summary['balance_amount'] ?? 0, 2); ?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No invoices found for this patient.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payment History -->
<?php if (isset($payments) && !empty($payments)): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Payment History</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="payments-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Invoice Number</th>
                            <th>Payment Method</th>
                            <th>Reference</th>
                            <th class="text-right">Amount</th>
                            <th>Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-M-Y', strtotime($payment['payment_date'])); ?></td>
                                <td>
                                    <a href="<?php echo base_url('billing/view_invoice/' . $payment['invoice_id']); ?>">
                                        <?php echo $payment['invoice_number'] ?? 'N/A'; ?>
                                    </a>
                                </td>
                                <td><?php echo $payment['payment_method']; ?></td>
                                <td><?php echo $payment['reference_number'] ?? '-'; ?></td>
                                <td class="text-right">₹<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo $payment['received_by'] ?? 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="success">
                            <th colspan="5" class="text-right">Total Payments:</th>
                            <th class="text-right">₹<?php echo number_format($summary['paid_amount'] ?? 0, 2); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php endif; ?>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "-- Select Patient --",
        allowClear: true
    });
    
    // Initialize DataTables if available
    if ($.fn.DataTable) {
        $('#invoices-table, #payments-table').DataTable({
            "order": [[1, "desc"]],
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    }
});
</script>
