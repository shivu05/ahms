<?php
$invoice_id = (int) ($invoice['invoice_id'] ?? 0);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo html_escape($error); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo html_escape($error); ?></div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title"><i class="fa fa-file-text"></i> Create Insurance Claim</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/view_invoice/' . $invoice_id); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Invoice
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-5">
                <div class="well well-sm">
                    <p><strong>Invoice #:</strong> <?php echo html_escape($invoice['invoice_number'] ?? 'N/A'); ?></p>
                    <p><strong>Patient:</strong> <?php echo html_escape($invoice['patient_name'] ?? 'N/A'); ?></p>
                    <p><strong>Invoice Amount:</strong> INR <?php echo number_format((float) ($invoice['total_amount'] ?? 0), 2); ?></p>
                    <p><strong>Paid:</strong> INR <?php echo number_format((float) ($invoice['paid_amount'] ?? $invoice['amount_paid'] ?? 0), 2); ?></p>
                    <p><strong>Balance:</strong> INR <?php echo number_format((float) ($invoice['balance_amount'] ?? $invoice['balance_due'] ?? 0), 2); ?></p>
                </div>
            </div>
            <div class="col-md-7">
                <?php if (empty($policies)): ?>
                    <div class="alert alert-warning">
                        Create an active insurance policy before raising a claim.
                    </div>
                    <a href="<?php echo site_url('billing/insurance/edit_policy'); ?>" class="btn btn-primary">
                        <i class="fa fa-shield"></i> New Policy
                    </a>
                <?php else: ?>
                    <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal">
                        <input type="hidden" name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>" value="<?php echo html_escape($this->security->get_csrf_hash()); ?>">

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Policy <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="policy_id" class="form-control" required>
                                    <option value="">Select policy</option>
                                    <?php foreach ($policies as $policy): ?>
                                        <option value="<?php echo (int) $policy['policy_id']; ?>">
                                            <?php echo html_escape($policy['policy_number'] . ' - ' . ($policy['company_name'] ?? 'Company') . ' - ' . ($policy['policy_holder_name'] ?: 'Holder N/A')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            The system will calculate deductible, co-pay, claim amount, and coverage cap from the selected policy.
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Claim
                                </button>
                                <a href="<?php echo site_url('billing/view_invoice/' . $invoice_id); ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
