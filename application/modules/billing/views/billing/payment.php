<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Record Payment</h1>
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

<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>

<?php if (isset($invoice) && !empty($invoice)): ?>

<div class="row">
    <!-- Invoice Summary -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Invoice Summary</h3>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr>
                        <th>Invoice Number:</th>
                        <td><?php echo $invoice['invoice_number']; ?></td>
                    </tr>
                    <tr>
                        <th>Patient:</th>
                        <td><?php echo $invoice['patient_name'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Invoice Date:</th>
                        <td><?php echo date('d-M-Y', strtotime($invoice['invoice_date'])); ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td class="text-success">
                            <strong>₹<?php echo number_format($invoice['total_amount'] ?? 0, 2); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Paid Amount:</th>
                        <td>₹<?php echo number_format($invoice['paid_amount'] ?? 0, 2); ?></td>
                    </tr>
                    <tr class="danger">
                        <th><strong>Balance Due:</strong></th>
                        <td><strong>₹<?php echo number_format($invoice['balance_amount'] ?? 0, 2); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Payment Status:</th>
                        <td>
                            <span class="label label-<?php 
                                echo ($invoice['payment_status'] == 'PAID') ? 'success' : 
                                     (($invoice['payment_status'] == 'PARTIAL') ? 'warning' : 'danger'); 
                            ?>">
                                <?php echo $invoice['payment_status']; ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <?php if ($invoice['balance_amount'] <= 0): ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> This invoice is fully paid.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Previous Payments -->
        <?php if (isset($payments) && !empty($payments)): ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment History</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo date('d-M-Y', strtotime($payment['payment_date'])); ?></td>
                                    <td><?php echo $payment['method_name'] ?? $payment['payment_method'] ?? 'N/A'; ?></td>
                                    <td class="text-right">₹<?php echo number_format((float) ($payment['payment_amount'] ?? $payment['amount'] ?? 0), 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment Form -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Record New Payment</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="<?php echo base_url('billing/payment/' . $invoice['id']); ?>">
                    
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice['id']; ?>">
                    
                    <div class="form-group">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Payment Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">₹</span>
                            <input type="number" name="payment_amount" class="form-control" 
                                   step="0.01" min="0.01" 
                                   max="<?php echo $invoice['balance_amount'] ?? 0; ?>"
                                   value="<?php echo set_value('payment_amount', $invoice['balance_amount'] ?? 0); ?>"
                                   required>
                        </div>
                        <small class="text-muted">
                            Maximum: ₹<?php echo number_format($invoice['balance_amount'] ?? 0, 2); ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method_id" class="form-control" required>
                            <option value="">-- Select Method --</option>
                            <?php if (!empty($payment_methods)): ?>
                                <?php foreach ($payment_methods as $method): ?>
                                    <option value="<?php echo $method['method_id']; ?>"
                                            data-requires-reference="<?php echo (int) ($method['requires_reference'] ?? 0); ?>"
                                            <?php echo set_select('payment_method_id', $method['method_id']); ?>>
                                        <?php echo $method['method_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reference Number</label>
                        <input type="text" name="reference_number" class="form-control" 
                               placeholder="Transaction ID / Cheque No / Reference">
                        <small class="text-muted">
                            Enter transaction ID, cheque number, or other reference
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Received By</label>
                        <input type="text" name="received_by" class="form-control" 
                               value="<?php echo $this->session->userdata('user_name') ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <hr>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" 
                                <?php echo ($invoice['balance_amount'] <= 0) ? 'disabled' : ''; ?>>
                            <i class="fa fa-save"></i> Record Payment
                        </button>
                        <a href="<?php echo base_url('billing/view_invoice/' . $invoice['id']); ?>" 
                           class="btn btn-default btn-block">
                            <i class="fa fa-arrow-left"></i> Back to Invoice
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-warning">
        <strong>Invoice not found!</strong> The requested invoice does not exist or has been deleted.
    </div>
<?php endif; ?>

<script>
$(document).ready(function() {
    // Update reference field requirement based on payment method
    $('select[name="payment_method_id"]').on('change', function() {
        var requiresReference = parseInt($(this).find(':selected').data('requires-reference'), 10) === 1;
        var refField = $('input[name="reference_number"]');
        
        if (requiresReference) {
            refField.attr('required', true);
            refField.closest('.form-group').find('label').html('Reference Number <span class="text-danger">*</span>');
        } else {
            refField.removeAttr('required');
            refField.closest('.form-group').find('label').html('Reference Number');
        }
    }).trigger('change');
});
</script>
