<?php
if (!function_exists('billing_claim_detail_status_class')) {
    function billing_claim_detail_status_class($status)
    {
        switch ($status) {
            case 'APPROVED':
            case 'PAID':
            case 'CLOSED':
                return 'success';
            case 'SUBMITTED':
            case 'ACKNOWLEDGED':
            case 'UNDER_PROCESS':
            case 'PARTIALLY_APPROVED':
                return 'warning';
            case 'REJECTED':
                return 'danger';
            default:
                return 'default';
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                Claim Details
                <div class="pull-right">
                    <a href="<?php echo site_url('billing/insurance/claims'); ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Claims
                    </a>
                </div>
            </h1>
        </div>
    </div>
</div>

<?php if (!empty($claim)): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Claim Information</strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Claim #:</strong> <?php echo html_escape($claim['claim_number']); ?></p>
                            <p><strong>Claim Date:</strong> <?php echo !empty($claim['claim_date']) ? date('d-M-Y', strtotime($claim['claim_date'])) : 'N/A'; ?></p>
                            <p><strong>Status:</strong>
                                <span class="label label-<?php echo billing_claim_detail_status_class($claim['claim_status']); ?>">
                                    <?php echo html_escape(str_replace('_', ' ', $claim['claim_status'])); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Invoice #:</strong> <?php echo html_escape($claim['invoice_number'] ?? 'N/A'); ?></p>
                            <p><strong>Policy #:</strong> <?php echo html_escape($claim['policy_number'] ?? 'N/A'); ?></p>
                            <p><strong>Company:</strong> <?php echo html_escape($claim['company_name'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading"><strong>Claim Amounts</strong></div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Invoice Amount</th>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['invoice_amount'] ?? 0), 2); ?></td>
                            </tr>
                            <tr>
                                <th>Claimed Amount</th>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['claimed_amount'] ?? 0), 2); ?></td>
                            </tr>
                            <tr>
                                <th>Deductible</th>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['deductible_amount'] ?? 0), 2); ?></td>
                            </tr>
                            <tr>
                                <th>Co-pay</th>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['co_payment_amount'] ?? 0), 2); ?></td>
                            </tr>
                            <tr class="success">
                                <th>Approved Amount</th>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['approved_amount'] ?? 0), 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (!empty($claim['documents'])): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Documents</strong></div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($claim['documents'] as $document): ?>
                                    <tr>
                                        <td><?php echo html_escape($document['document_type']); ?></td>
                                        <td><?php echo html_escape($document['document_name']); ?></td>
                                        <td><?php echo !empty($document['uploaded_date']) ? date('d-M-Y H:i', strtotime($document['uploaded_date'])) : 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Processing Notes</strong></div>
                <div class="panel-body">
                    <?php if (!empty($claim['rejection_reason'])): ?>
                        <p><strong>Rejection Reason:</strong></p>
                        <p><?php echo nl2br(html_escape($claim['rejection_reason'])); ?></p>
                    <?php elseif (!empty($claim['partial_rejection_reason'])): ?>
                        <p><strong>Partial Rejection Reason:</strong></p>
                        <p><?php echo nl2br(html_escape($claim['partial_rejection_reason'])); ?></p>
                    <?php elseif (!empty($claim['remarks'])): ?>
                        <p><?php echo nl2br(html_escape($claim['remarks'])); ?></p>
                    <?php else: ?>
                        <p class="text-muted">No processing notes recorded.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($claim['followups'])): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Follow-ups</strong></div>
                    <div class="panel-body">
                        <?php foreach ($claim['followups'] as $followup): ?>
                            <p>
                                <strong><?php echo !empty($followup['followup_date']) ? date('d-M-Y', strtotime($followup['followup_date'])) : 'N/A'; ?></strong><br>
                                <?php echo html_escape($followup['followup_type']); ?>:
                                <?php echo html_escape($followup['contacted_person'] ?? 'N/A'); ?><br>
                                <small><?php echo html_escape($followup['followup_notes'] ?? ''); ?></small>
                            </p>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Claim not found.</div>
<?php endif; ?>
