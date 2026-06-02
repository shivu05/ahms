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
                <h3 class="panel-title"><i class="fa fa-building"></i> New Insurance Company</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('billing/insurance/companies'); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Companies
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal">
            <input type="hidden" name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>" value="<?php echo html_escape($this->security->get_csrf_hash()); ?>">

            <div class="form-group">
                <label class="col-sm-3 control-label">Company Code <span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <input type="text" name="company_code" class="form-control" maxlength="50" value="<?php echo html_escape(set_value('company_code')); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Company Name <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="company_name" class="form-control" maxlength="255" value="<?php echo html_escape(set_value('company_name')); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Contact Person</label>
                <div class="col-sm-6">
                    <input type="text" name="contact_person" class="form-control" maxlength="100" value="<?php echo html_escape(set_value('contact_person')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-4">
                    <input type="email" name="email" class="form-control" maxlength="100" value="<?php echo html_escape(set_value('email')); ?>">
                </div>
                <label class="col-sm-1 control-label">Phone</label>
                <div class="col-sm-3">
                    <input type="text" name="phone" class="form-control" maxlength="20" value="<?php echo html_escape(set_value('phone')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Address</label>
                <div class="col-sm-8">
                    <textarea name="address" class="form-control" rows="2"><?php echo html_escape(set_value('address')); ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-3">
                    <input type="text" name="city" class="form-control" maxlength="100" value="<?php echo html_escape(set_value('city')); ?>">
                </div>
                <label class="col-sm-1 control-label">State</label>
                <div class="col-sm-3">
                    <input type="text" name="state" class="form-control" maxlength="100" value="<?php echo html_escape(set_value('state')); ?>">
                </div>
                <label class="col-sm-1 control-label">PIN</label>
                <div class="col-sm-1">
                    <input type="text" name="pincode" class="form-control" maxlength="10" value="<?php echo html_escape(set_value('pincode')); ?>">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="col-sm-3 control-label">Claim Email</label>
                <div class="col-sm-4">
                    <input type="email" name="claim_contact_email" class="form-control" maxlength="100" value="<?php echo html_escape(set_value('claim_contact_email')); ?>">
                </div>
                <label class="col-sm-1 control-label">Claim Phone</label>
                <div class="col-sm-3">
                    <input type="text" name="claim_contact_phone" class="form-control" maxlength="20" value="<?php echo html_escape(set_value('claim_contact_phone')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Website</label>
                <div class="col-sm-6">
                    <input type="text" name="website" class="form-control" maxlength="255" value="<?php echo html_escape(set_value('website')); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save Company
                    </button>
                    <a href="<?php echo site_url('billing/insurance/companies'); ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
