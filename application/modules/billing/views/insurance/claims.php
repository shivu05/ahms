<?php
if (!function_exists('billing_claim_status_class')) {
    function billing_claim_status_class($status)
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

<?php if (!empty($setup_required)): ?>
    <div class="alert alert-warning">
        <strong>Setup Required:</strong> <?php echo html_escape($message ?? 'Insurance claim setup is pending.'); ?>
    </div>
<?php else: ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo html_escape($this->session->flashdata('success')); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo html_escape($this->session->flashdata('error')); ?>
    </div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title"><i class="fa fa-file-text"></i> Insurance Claims</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-shield"></i> Policies
                </a>
                <a href="<?php echo site_url('billing'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Billing
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="get" action="<?php echo site_url('billing/insurance/claims'); ?>" class="form-inline">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo html_escape($status); ?>" <?php echo $status_filter === $status ? 'selected' : ''; ?>>
                            <?php echo html_escape(str_replace('_', ' ', $status)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Apply</button>
            <a href="<?php echo site_url('billing/insurance/claims'); ?>" class="btn btn-default">Reset</a>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Claims (<?php echo count($claims); ?>)</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>Claim #</th>
                        <th>Date</th>
                        <th>Invoice #</th>
                        <th>Policy #</th>
                        <th>Company</th>
                        <th class="text-right">Invoice Amount</th>
                        <th class="text-right">Claimed</th>
                        <th class="text-right">Approved</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($claims)): ?>
                        <?php foreach ($claims as $claim): ?>
                            <tr>
                                <td><strong><?php echo html_escape($claim['claim_number']); ?></strong></td>
                                <td><?php echo !empty($claim['claim_date']) ? date('d-m-Y', strtotime($claim['claim_date'])) : 'N/A'; ?></td>
                                <td><?php echo html_escape($claim['invoice_number'] ?? 'N/A'); ?></td>
                                <td><?php echo html_escape($claim['policy_number'] ?? 'N/A'); ?></td>
                                <td><?php echo html_escape($claim['company_name'] ?? 'N/A'); ?></td>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['invoice_amount'] ?? 0), 2); ?></td>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['claimed_amount'] ?? 0), 2); ?></td>
                                <td class="text-right">INR <?php echo number_format((float) ($claim['approved_amount'] ?? 0), 2); ?></td>
                                <td>
                                    <span class="label label-<?php echo billing_claim_status_class($claim['claim_status']); ?>">
                                        <?php echo html_escape(str_replace('_', ' ', $claim['claim_status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('billing/insurance/view_claim/' . (int) $claim['claim_id']); ?>" class="btn btn-xs btn-primary">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No claims found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            pageLength: 25,
            order: [[1, 'desc']],
            columnDefs: [{ orderable: false, targets: -1 }]
        });
    }
});
</script>

<?php endif; ?>
