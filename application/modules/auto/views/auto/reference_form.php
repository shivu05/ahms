<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus-square"></i> Add reference record</h3>
                <div class="pull-right">
                    <a class="btn btn-default btn-sm" href="<?= base_url('show-ref') ?>"><i class="fa fa-list"></i> Back to list</a>
                    <a class="btn btn-default btn-sm" href="<?= base_url('show-ref/template') ?>"><i class="fa fa-download"></i> Download CSV template</a>
                </div>
            </div>
            <form action="<?= base_url('show-ref/store') ?>" id="reference_form" method="POST">
                <div class="box-body">
                    <?php if ($this->session->flashdata('reference_success')): ?>
                        <div class="alert alert-success"><?= html_escape($this->session->flashdata('reference_success')) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($form_warnings)): ?>
                        <div class="alert alert-warning">
                            <ul class="mb-0">
                                <?php foreach ($form_warnings as $warning): ?>
                                    <li><?= html_escape($warning) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php
                    $values = !empty($form_values) ? $form_values : array();
                    $errors = !empty($form_errors) ? $form_errors : array();
                    $department_list = isset($department_list) ? $department_list : array();
                    include APPPATH . 'modules/auto/views/auto/_reference_form_fields.php';
                    ?>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" id="save_reference" type="button"><i class="fa fa-save"></i> Save record</button>
                    <a class="btn btn-default" href="<?= base_url('show-ref') ?>">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        function syncSubDepartmentState($form) {
            var isShalakya = $form.find('#department').val() === 'SHALAKYA_TANTRA';
            var $subDept = $form.find('#sub_dept');
            $subDept.prop('disabled', !isShalakya);
            if (!isShalakya) {
                $subDept.val('');
            }
        }

        $('#reference_form').validate({
            rules: {
                Age: {
                    required: true,
                    digits: true
                },
                Mobileno: {
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                aadhaar_number: {
                    digits: true,
                    minlength: 12,
                    maxlength: 12
                },
                sub_dept: {
                    required: {
                        depends: function () {
                            return $('#reference_form #department').val() === 'SHALAKYA_TANTRA';
                        }
                    }
                }
            }
        });

        syncSubDepartmentState($('#reference_form'));
        $('#reference_form').on('change', '#department', function () {
            syncSubDepartmentState($('#reference_form'));
        });

        $('#save_reference').on('click', function () {
            if ($('#reference_form').valid()) {
                $('#reference_form').submit();
            }
        });
    });
</script>
