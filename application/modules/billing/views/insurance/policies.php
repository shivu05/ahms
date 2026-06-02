<?php
if (!function_exists('billing_policy_status_class')) {
    function billing_policy_status_class($policy)
    {
        if (empty($policy['is_active'])) {
            return 'default';
        }

        if (!empty($policy['policy_end_date']) && strtotime($policy['policy_end_date']) < strtotime(date('Y-m-d'))) {
            return 'danger';
        }

        return 'success';
    }

    function billing_policy_status_label($policy)
    {
        if (empty($policy['is_active'])) {
            return 'Inactive';
        }

        if (!empty($policy['policy_end_date']) && strtotime($policy['policy_end_date']) < strtotime(date('Y-m-d'))) {
            return 'Expired';
        }

        return 'Active';
    }
}
?>

<?php if (!empty($setup_required)): ?>
    <div class="alert alert-warning">
        <strong>Setup Required:</strong> <?php echo html_escape($message ?? 'Insurance policy setup is pending.'); ?>
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
                <h3 class="panel-title"><i class="fa fa-shield"></i> Insurance Policies</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/insurance/companies'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-building"></i> Companies
                </a>
                <a href="<?php echo site_url('billing/insurance/edit_policy'); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> New Policy
                </a>
                <a href="<?php echo site_url('billing'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Billing
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="get" action="<?php echo site_url('billing/insurance/policies'); ?>" class="form-inline">
            <div class="form-group">
                <label for="company_id">Company</label>
                <select name="company_id" id="company_id" class="form-control">
                    <option value="">All Companies</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo (int) $company['company_id']; ?>" <?php echo (int) ($filters['company_id'] ?? 0) === (int) $company['company_id'] ? 'selected' : ''; ?>>
                            <?php echo html_escape($company['company_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" class="form-control" value="<?php echo html_escape($filters['search'] ?? ''); ?>" placeholder="Policy no or holder">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Apply</button>
            <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default">Reset</a>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Policies (<?php echo count($policies); ?>)</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>Policy #</th>
                        <th>Company</th>
                        <th>Holder</th>
                        <th>Type</th>
                        <th>Validity</th>
                        <th class="text-right">Coverage</th>
                        <th class="text-right">Co-pay</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($policies)): ?>
                        <?php foreach ($policies as $policy): ?>
                            <tr>
                                <td><strong><?php echo html_escape($policy['policy_number']); ?></strong></td>
                                <td><?php echo html_escape($policy['company_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo html_escape($policy['policy_holder_name'] ?: 'N/A'); ?>
                                    <?php if (!empty($policy['policy_holder_contact'])): ?>
                                        <br><small class="text-muted"><?php echo html_escape($policy['policy_holder_contact']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($policy['policy_type']); ?></td>
                                <td>
                                    <?php echo !empty($policy['policy_start_date']) ? date('d-m-Y', strtotime($policy['policy_start_date'])) : 'N/A'; ?>
                                    -
                                    <?php echo !empty($policy['policy_end_date']) ? date('d-m-Y', strtotime($policy['policy_end_date'])) : 'N/A'; ?>
                                </td>
                                <td class="text-right">INR <?php echo number_format((float) $policy['coverage_limit'], 2); ?></td>
                                <td class="text-right"><?php echo number_format((float) $policy['co_payment_percentage'], 2); ?>%</td>
                                <td>
                                    <span class="label label-<?php echo billing_policy_status_class($policy); ?>">
                                        <?php echo billing_policy_status_label($policy); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('billing/insurance/edit_policy/' . (int) $policy['policy_id']); ?>" class="btn btn-xs btn-primary">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No policies found.</td>
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
            order: [[0, 'asc']],
            columnDefs: [{ orderable: false, targets: -1 }]
        });
    }
});
</script>

<?php endif; ?>
