<?php
$is_edit = !empty($policy['policy_id']);
$selected_type = $policy['policy_type'] ?? 'INDIVIDUAL';
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
                <h3 class="panel-title">
                    <i class="fa fa-shield"></i> <?php echo $is_edit ? 'Edit Insurance Policy' : 'New Insurance Policy'; ?>
                </h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Policies
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (empty($companies)): ?>
            <div class="alert alert-warning">
                Add at least one insurance company before creating policies.
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal">
            <input type="hidden" name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>" value="<?php echo html_escape($this->security->get_csrf_hash()); ?>">

            <div class="form-group">
                <label class="col-sm-3 control-label">Company <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <select name="company_id" class="form-control" required>
                        <option value="">Select company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?php echo (int) $company['company_id']; ?>" <?php echo (int) ($policy['company_id'] ?? 0) === (int) $company['company_id'] ? 'selected' : ''; ?>>
                                <?php echo html_escape($company['company_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Policy Number <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="policy_number" class="form-control" maxlength="100" value="<?php echo html_escape($policy['policy_number'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Policy Name</label>
                <div class="col-sm-6">
                    <input type="text" name="policy_name" class="form-control" maxlength="255" value="<?php echo html_escape($policy['policy_name'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Policy Type</label>
                <div class="col-sm-4">
                    <select name="policy_type" class="form-control">
                        <?php foreach ($policy_types as $type): ?>
                            <option value="<?php echo html_escape($type); ?>" <?php echo $selected_type === $type ? 'selected' : ''; ?>>
                                <?php echo html_escape(ucwords(strtolower($type))); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="col-sm-3 control-label">Policy Holder</label>
                <div class="col-sm-6">
                    <input type="text" name="policy_holder_name" class="form-control" maxlength="255" value="<?php echo html_escape($policy['policy_holder_name'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Holder Contact</label>
                <div class="col-sm-4">
                    <input type="text" name="policy_holder_contact" class="form-control" maxlength="20" value="<?php echo html_escape($policy['policy_holder_contact'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Valid From</label>
                <div class="col-sm-3">
                    <input type="date" name="policy_start_date" class="form-control" value="<?php echo html_escape($policy['policy_start_date'] ?? ''); ?>">
                </div>
                <label class="col-sm-1 control-label">To</label>
                <div class="col-sm-3">
                    <input type="date" name="policy_end_date" class="form-control" value="<?php echo html_escape($policy['policy_end_date'] ?? ''); ?>">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="col-sm-3 control-label">Coverage Limit <span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">INR</span>
                        <input type="number" name="coverage_limit" class="form-control" step="0.01" min="0" value="<?php echo html_escape($policy['coverage_limit'] ?? '0.00'); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Deductible</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <span class="input-group-addon">INR</span>
                        <input type="number" name="deductible_amount" class="form-control" step="0.01" min="0" value="<?php echo html_escape($policy['deductible_amount'] ?? '0.00'); ?>">
                    </div>
                </div>
                <label class="col-sm-2 control-label">Co-pay %</label>
                <div class="col-sm-2">
                    <input type="number" name="co_payment_percentage" class="form-control" step="0.01" min="0" max="100" value="<?php echo html_escape($policy['co_payment_percentage'] ?? '0.00'); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="requires_pre_approval" value="1" <?php echo !empty($policy['requires_pre_approval']) ? 'checked' : ''; ?>>
                            Requires pre-authorization
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_active" value="1" <?php echo !isset($policy['is_active']) || !empty($policy['is_active']) ? 'checked' : ''; ?>>
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary" <?php echo empty($companies) ? 'disabled' : ''; ?>>
                        <i class="fa fa-save"></i> Save Policy
                    </button>
                    <a href="<?php echo site_url('billing/insurance/policies'); ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
