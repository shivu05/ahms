<?php if (!empty($setup_required)): ?>
    <div class="alert alert-warning">
        <strong>Setup Required:</strong> <?php echo html_escape($message ?? 'Insurance company setup is pending.'); ?>
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
                <h3 class="panel-title"><i class="fa fa-building"></i> Insurance Companies</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/insurance/add_company'); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> New Company
                </a>
                <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-shield"></i> Policies
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Claim Contact</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($companies)): ?>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td><?php echo html_escape($company['company_code']); ?></td>
                                <td><strong><?php echo html_escape($company['company_name']); ?></strong></td>
                                <td><?php echo html_escape($company['contact_person'] ?? 'N/A'); ?></td>
                                <td><?php echo html_escape($company['email'] ?? 'N/A'); ?></td>
                                <td><?php echo html_escape($company['phone'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo html_escape($company['claim_contact_email'] ?? 'N/A'); ?><br>
                                    <small><?php echo html_escape($company['claim_contact_phone'] ?? ''); ?></small>
                                </td>
                                <td>
                                    <span class="label label-<?php echo !empty($company['is_active']) ? 'success' : 'default'; ?>">
                                        <?php echo !empty($company['is_active']) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No insurance companies found.</td>
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
            order: [[1, 'asc']]
        });
    }
});
</script>

<?php endif; ?>
