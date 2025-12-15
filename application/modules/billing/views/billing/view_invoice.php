<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                Invoice Details
                <div class="pull-right">
                    <a href="<?php echo base_url('billing/invoices'); ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Invoices
                    </a>
                    <button onclick="window.print();" class="btn btn-primary">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
            </h1>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (isset($invoice) && !empty($invoice)): ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="invoice-container">
            
            <!-- Invoice Header -->
            <div class="row">
                <div class="col-md-6">
                    <h3><?php echo $hospital_name ?? 'Hospital Name'; ?></h3>
                    <p>
                        <?php echo $hospital_address ?? 'Hospital Address'; ?><br>
                        Phone: <?php echo $hospital_phone ?? 'N/A'; ?><br>
                        Email: <?php echo $hospital_email ?? 'N/A'; ?>
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <h2>INVOICE</h2>
                    <p>
                        <strong>Invoice #:</strong> <?php echo $invoice['invoice_number']; ?><br>
                        <strong>Date:</strong> <?php echo date('d-M-Y', strtotime($invoice['invoice_date'])); ?><br>
                        <strong>Due Date:</strong> <?php echo date('d-M-Y', strtotime($invoice['due_date'])); ?>
                    </p>
                    <span class="label label-<?php 
                        echo ($invoice['payment_status'] == 'PAID') ? 'success' : 
                             (($invoice['payment_status'] == 'PARTIAL') ? 'warning' : 'danger'); 
                    ?>">
                        <?php echo $invoice['payment_status']; ?>
                    </span>
                </div>
            </div>

            <hr>

            <!-- Patient Information -->
            <div class="row">
                <div class="col-md-6">
                    <h4>Bill To:</h4>
                    <p>
                        <strong><?php echo $invoice['patient_name'] ?? 'N/A'; ?></strong><br>
                        Patient ID: <?php echo $invoice['patient_id'] ?? 'N/A'; ?><br>
                        <?php if (!empty($invoice['patient_phone'])): ?>
                            Phone: <?php echo $invoice['patient_phone']; ?><br>
                        <?php endif; ?>
                        <?php if (!empty($invoice['patient_email'])): ?>
                            Email: <?php echo $invoice['patient_email']; ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <?php if (!empty($invoice['insurance_company'])): ?>
                        <h4>Insurance Details:</h4>
                        <p>
                            <strong><?php echo $invoice['insurance_company']; ?></strong><br>
                            Policy #: <?php echo $invoice['policy_number'] ?? 'N/A'; ?><br>
                            Coverage: <?php echo $invoice['coverage_percentage'] ?? 0; ?>%
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <hr>

            <!-- Invoice Items -->
            <div class="row">
                <div class="col-md-12">
                    <h4>Services / Items</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service / Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Discount</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($invoice['items']) && !empty($invoice['items'])): ?>
                                <?php $i = 1; foreach ($invoice['items'] as $item): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <?php echo $item['service_name']; ?>
                                            <?php if (!empty($item['description'])): ?>
                                                <br><small class="text-muted"><?php echo $item['description']; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-right">₹<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td class="text-right">
                                            <?php 
                                            $discount = ($item['discount_percentage'] > 0) 
                                                ? $item['discount_percentage'] . '%' 
                                                : '₹' . number_format($item['discount_amount'], 2);
                                            echo $discount;
                                            ?>
                                        </td>
                                        <td class="text-right">₹<?php echo number_format($item['total_amount'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="row">
                <div class="col-md-6">
                    <?php if (!empty($invoice['notes'])): ?>
                        <h4>Notes:</h4>
                        <p><?php echo nl2br($invoice['notes']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Subtotal:</th>
                            <td class="text-right">₹<?php echo number_format($invoice['subtotal'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td class="text-right">- ₹<?php echo number_format($invoice['discount_amount'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Tax (<?php echo $invoice['tax_percentage'] ?? 0; ?>%):</th>
                            <td class="text-right">₹<?php echo number_format($invoice['tax_amount'] ?? 0, 2); ?></td>
                        </tr>
                        <?php if (!empty($invoice['insurance_claim_amount'])): ?>
                            <tr>
                                <th>Insurance Claim:</th>
                                <td class="text-right">- ₹<?php echo number_format($invoice['insurance_claim_amount'], 2); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr class="success">
                            <th><h4>Total Amount:</h4></th>
                            <td class="text-right"><h4>₹<?php echo number_format($invoice['total_amount'] ?? 0, 2); ?></h4></td>
                        </tr>
                        <tr>
                            <th>Paid Amount:</th>
                            <td class="text-right">₹<?php echo number_format($invoice['paid_amount'] ?? 0, 2); ?></td>
                        </tr>
                        <tr class="<?php echo ($invoice['balance_amount'] > 0) ? 'danger' : 'success'; ?>">
                            <th><h4>Balance Due:</h4></th>
                            <td class="text-right"><h4>₹<?php echo number_format($invoice['balance_amount'] ?? 0, 2); ?></h4></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Payment History -->
            <?php if (isset($payments) && !empty($payments)): ?>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Payment History</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Reference</th>
                                    <th class="text-right">Amount</th>
                                    <th>Received By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?php echo date('d-M-Y', strtotime($payment['payment_date'])); ?></td>
                                        <td><?php echo $payment['payment_method']; ?></td>
                                        <td><?php echo $payment['reference_number'] ?? 'N/A'; ?></td>
                                        <td class="text-right">₹<?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><?php echo $payment['received_by'] ?? 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <?php if ($invoice['balance_amount'] > 0): ?>
                <hr>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo base_url('billing/payment/' . $invoice['id']); ?>" 
                           class="btn btn-success btn-lg">
                            <i class="fa fa-money"></i> Record Payment
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-warning">
        <strong>Invoice not found!</strong> The requested invoice does not exist or has been deleted.
    </div>
<?php endif; ?>

<style>
@media print {
    .page-header .pull-right,
    .btn,
    .alert {
        display: none !important;
    }
}
</style>
