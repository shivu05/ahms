<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Patient reference</h3>
                <div class="pull-right">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('show-ref/add') ?>"><i class="fa fa-plus-square"></i> Add record</a>
                    <a class="btn btn-default btn-sm" href="<?= base_url('show-ref/template') ?>"><i class="fa fa-download"></i> Download CSV template</a>
                </div>
            </div>
            <div class="box-body">
                <?php if ($this->session->flashdata('reference_success')): ?>
                    <div class="alert alert-success"><?= html_escape($this->session->flashdata('reference_success')) ?></div>
                <?php endif; ?>

                <?php $reference_errors = $this->session->flashdata('reference_errors'); ?>
                <?php if (!empty($reference_errors)): ?>
                    <div class="alert alert-danger">
                        <strong>Import / save issues:</strong>
                        <ul class="mb-0">
                            <?php foreach ($reference_errors as $error): ?>
                                <li><?= html_escape($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php $reference_warnings = $this->session->flashdata('reference_warnings'); ?>
                <?php if (!empty($reference_warnings)): ?>
                    <div class="alert alert-warning">
                        <strong>Auto-clean warnings:</strong>
                        <ul class="mb-0">
                            <?php foreach ($reference_warnings as $warning): ?>
                                <li><?= html_escape($warning) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php $reference_summary = $this->session->flashdata('reference_import_summary'); ?>
                <?php if (!empty($reference_summary)): ?>
                    <div class="alert alert-info">
                        <strong>Last import summary:</strong>
                        Processed <?= (int) $reference_summary['processed_count'] ?> row(s),
                        inserted <?= (int) $reference_summary['inserted_count'] ?>,
                        skipped blank rows <?= (int) $reference_summary['skipped_count'] ?>,
                        warnings <?= (int) $reference_summary['warning_count'] ?>,
                        errors <?= (int) $reference_summary['error_count'] ?>.
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('show-ref/import') ?>" class="form-inline import-form" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label for="reference_csv">CSV import:</label>
                        <input accept=".csv" class="form-control" id="reference_csv" name="reference_csv" required type="file">
                    </div>
                    <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-upload"></i> Import CSV</button>
                </form>

                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="patient_modal_box" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update patient details</h4>
            </div>
            <div class="modal-body">
                <form action="" id="patient_form" method="POST" name="patient_form">
                    <input name="ID" id="ID" type="hidden">
                    <?php
                    $values = array();
                    $errors = array();
                    $department_list = isset($department_list) ? $department_list : array();
                    include APPPATH . 'modules/auto/views/auto/_reference_form_fields.php';
                    ?>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" data-dismiss="modal" type="button"><i class="fa fa-remove"></i> Close</button>
                <button class="btn btn-primary btn-sm" id="btn-update" type="button"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="identity_modal_box" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Aadhaar / ABHA details</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th style="width:35%">Patient</th>
                        <td id="identity_patient_name">-</td>
                    </tr>
                    <tr>
                        <th>Aadhaar Number</th>
                        <td id="identity_aadhaar_number">Not available</td>
                    </tr>
                    <tr>
                        <th>ABHA ID</th>
                        <td id="identity_abha_id">Not available</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-sm" data-dismiss="modal" type="button"><i class="fa fa-remove"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .age {
        min-width: 60px !important;
        max-width: 60px !important;
    }
    .ipd_no {
        width: 140px !important;
        min-width: 140px !important;
        max-width: 140px !important;
    }
    .long_text {
        width: 220px !important;
        min-width: 220px !important;
        max-width: 220px !important;
    }
    .action {
        text-align: center;
        vertical-align: middle;
        width: 80px;
    }
    .import-form {
        margin-bottom: 15px;
    }
</style>

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

        var columns = [
            {
                title: "Action",
                class: 'action',
                data: function (item) {
                    var identityClass = (item.aadhaar_number || item.abha_id) ? 'text-info view-identity' : 'text-muted';
                    var identityTitle = (item.aadhaar_number || item.abha_id) ? 'View Aadhaar / ABHA' : 'Aadhaar / ABHA not available';
                    return '<i title="Edit record" class="fa fa-pencil-square text-primary hand_cursor edit-row"></i> | '
                        + '<i title="' + identityTitle + '" class="fa fa-id-card ' + identityClass + ' hand_cursor"></i> | '
                        + '<i title="Delete record" class="fa fa-trash text-danger hand_cursor delete-row"></i>';
                }
            },
            {
                title: "Name",
                class: "ipd_no",
                data: function (item) {
                    return [item.FirstName, item.LastName].join(' ').trim();
                }
            },
            {
                title: "Age",
                class: "age",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                data: function (item) {
                    return item.gender;
                }
            },
            {
                title: "Occupation",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Place",
                class: "ipd_no",
                data: function (item) {
                    return [item.address, item.city].join(', ').replace(/^,\s*/, '');
                }
            },
            {
                title: "Diagnosis",
                class: "ipd_no",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Department",
                class: "ipd_no",
                data: function (item) {
                    return item.department_display + '<br/><small class="text-primary">' + item.sub_dept + '</small>';
                }
            },
            {
                title: "Procedures",
                data: function (item) {
                    return item.procedures;
                }
            },
            {
                title: "Treatment",
                class: "long_text",
                data: function (item) {
                    return item.Trtment;
                }
            },
            {
                title: "Medicines",
                class: "long_text",
                data: function (item) {
                    return item.medicines;
                }
            }
        ];

        var patient_table = $('#patient_table').DataTable({
            columns: columns,
            bDestroy: true,
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>"
            },
            searching: true,
            paging: true,
            pageLength: 25,
            lengthChange: true,
            aLengthMenu: [10, 25, 50, 100],
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: base_url + 'get_data',
                type: 'POST',
                dataType: 'json'
            },
            info: true,
            sScrollX: true
        });

        $('#patient_form').validate({
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
                            return $('#patient_form #department').val() === 'SHALAKYA_TANTRA';
                        }
                    }
                }
            }
        });

        $('#patient_form').on('change', '#department', function () {
            syncSubDepartmentState($('#patient_form'));
        });

        $('#patient_table tbody').on('click', '.edit-row', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#patient_form #ID').val(data.ID);
            $('#patient_form #FirstName').val(data.FirstName);
            $('#patient_form #MidName').val(data.MidName || '');
            $('#patient_form #LastName').val(data.LastName || '');
            $('#patient_form #Age').val(data.Age);
            $('#patient_form #gender').val(data.gender);
            $('#patient_form #occupation').val(data.occupation);
            $('#patient_form #address').val(data.address);
            $('#patient_form #city').val(data.city);
            $('#patient_form #Mobileno').val(data.Mobileno || '');
            $('#patient_form #diagnosis').val(data.diagnosis);
            $('#patient_form #complaints').val(data.complaints || '');
            $('#patient_form #department').val(data.department || '');
            $('#patient_form #procedures').val(data.procedures || '');
            $('#patient_form #Trtment').val(data.Trtment);
            $('#patient_form #medicines').val(data.medicines);
            $('#patient_form #aadhaar_number').val(data.aadhaar_number || '');
            $('#patient_form #abha_id').val(data.abha_id || '');
            $('#patient_form #notes').val(data.notes || '');
            syncSubDepartmentState($('#patient_form'));
            $('#patient_form #sub_dept').val(data.sub_dept || '');
            $('#patient_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#patient_table tbody').on('click', '.view-identity', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#identity_patient_name').text([data.FirstName, data.LastName].join(' ').trim());
            $('#identity_aadhaar_number').text(data.aadhaar_number || 'Not available');
            $('#identity_abha_id').text(data.abha_id || 'Not available');
            $('#identity_modal_box').modal('show');
        });

        $('#patient_modal_box').on('click', '#btn-update', function () {
            if (!$('#patient_form').valid()) {
                return;
            }

            $.ajax({
                url: base_url + 'save-data',
                type: 'POST',
                dataType: 'json',
                data: $('#patient_form').serialize(),
                success: function (response) {
                    if (!response.status) {
                        $.notify({title: "Patient records:", message: response.msg, icon: 'fa fa-warning'}, {type: "danger"});
                        return;
                    }

                    $('#patient_modal_box').modal('hide');
                    $.notify({title: "Patient records:", message: response.msg, icon: 'fa fa-check'}, {type: "success"});
                    if (response.warnings && response.warnings.length) {
                        $.notify({title: "Auto-clean:", message: response.warnings.join('<br/>'), icon: 'fa fa-info-circle'}, {type: "warning"});
                    }
                    patient_table.ajax.reload(null, false);
                },
                error: function () {
                    $.notify({title: "Patient records:", message: 'Failed to update the record.', icon: 'fa fa-warning'}, {type: "danger"});
                }
            });
        });

        $('#patient_table tbody').on('click', '.delete-row', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            if (!confirm('Delete reference record for "' + ([data.FirstName, data.LastName].join(' ').trim()) + '"?')) {
                return;
            }

            $.ajax({
                url: base_url + 'delete-ref-data',
                type: 'POST',
                dataType: 'json',
                data: {id: data.ID},
                success: function (response) {
                    $.notify({title: "Patient records:", message: response.msg, icon: response.status ? 'fa fa-check' : 'fa fa-warning'}, {type: response.status ? "success" : "danger"});
                    if (response.status) {
                        patient_table.ajax.reload(null, false);
                    }
                },
                error: function () {
                    $.notify({title: "Patient records:", message: 'Failed to delete the record.', icon: 'fa fa-warning'}, {type: "danger"});
                }
            });
        });
    });
</script>
