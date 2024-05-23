<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-pulse"></i> Other Procedures report:</h3>
                <a class="btn btn-warning btn-sm pull-right" href="<?php echo base_url('other-procedure-statistics'); ?>">Statistics</a>
                <a class="btn btn-info btn-sm pull-right" style="margin-right:10px;" target="_blank" href="<?php echo base_url('reports/Test/export_otherprocedures_full'); ?>">Export all data</a>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('other_procedures_treatments') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12 other_procedure_table_grid"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="xray_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="xray_modal_label">Update X-Ray details</h5>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="xray_form" id="xray_form" method="POST">
                    <div class="form-group">
                        <label for="filmpartOfXray_size">Part of X-ray:</label>
                        <input class="form-control required" id="partOfXray" name="partOfXray" type="text" aria-describedby="partOfXrayHelp" placeholder="Enter Part of X-Ray">
                        <small class="form-text text-muted" id="partOfXrayHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Film size:</label>
                        <input type="hidden" name="ID" id="ID" />
                        <input class="form-control required" id="filmSize" name="filmSize" type="text" aria-describedby="film_sizeHelp" placeholder="Enter film size">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="other_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="modal_label">Update information</h5>
            </div>
            <div class="modal-body" id="modal_body">
                <form action="" name="edit_form" id="edit_form" method="POST">
                    <div class="form-group">
                        <label for="physician">Physician:</label>
                        <input type="hidden" name="ID" id="ID" />
                        <input class="form-control required" id="physician" name="physician" type="text" aria-describedby="physicianHelp" placeholder="Enter Physician">
                        <small class="form-text text-muted" id="physicianHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="physician">Start date:</label>
                        <input class="form-control required date_picker" id="start_date" name="start_date" type="text" aria-describedby="start_dateHelp" placeholder="Enter Start date">
                    </div>
                    <div class="form-group">
                        <label for="physician">End date:</label>
                        <input class="form-control required date_picker" id="end_date" name="end_date" type="text" aria-describedby="start_dateHelp" placeholder="Enter Start date">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });

        //other_procedure_grid
        var columns = [
            {
                title: "#",
                class: "ipd_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "C.OPD",
                class: "opd_no",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "D.OPD",
                class: "opd_no",
                data: function (item) {
                    return item.deptOpdNo;
                }
            },
            {
                title: "C.iPD",
                class: "opd_no",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.name;
                }
            },
            {
                title: "Age",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                data: function (item) {
                    return item.gender;
                }
            }, {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            }, {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Procedure",
                data: function (item) {
                    return item.therapy_name;
                }
            },
            {
                title: "Physician",
                data: function (item) {
                    return item.physician;
                }
            },
            {
                title: "Date",
                data: function (item) {
                    return item.referred_date;
                }
            }

        ];

        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    console.log(item.id);
                    return "<center><i class='fa fa-edit hand_cursor edit' data-id='" + item.id + "'></i>" + " | " +
                            "<i class='fa fa-trash hand_cursor delete text-danger' data-id='" + item.id + "'></i>" + "</center>";
                }
            });
        }

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            "bDestroy": true,
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>"
            },
            'searching': true,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ordering': false,
            'ajax': {
                'url': base_url + 'reports/Test/fetch_oherprocedures_records',
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_form').serializeArray()
                    });
                },
                drawCallback: function (response) {}
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#patient_table tbody').on('click', '.edit', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#other_modal_box #edit_form #ID').val(data.id);
            $('#other_modal_box #edit_form #physician').val(data.physician);
            $('#other_modal_box #edit_form #start_date').val(data.start_date);
            $('#other_modal_box #edit_form #end_date').val(data.end_date);
            $('#other_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#edit_form').validate({
            messages: {
                physician: {required: 'Physician name is empty'},
                start_date: {required: 'Start date is empty'},
                end_date: {required: 'End date is empty'}
            }
        });

        $('#other_modal_box').on('click', '#btn-update', function () {
            if ($('#edit_form').valid()) {
                var form_data = $('#edit_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_otherprocedure',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#other_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Otherprocedure:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Otherprocedure:",
                                message: res.msg,
                                icon: 'fa fa-remove'
                            }, {
                                type: "danger"
                            });
                        }
                    }
                });
            }
        });

        $('#patient_table tbody').on('click', '.delete', function () {
            var id = $(this).data('id');
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: 'Delete confirmation',
                message: 'Are you sure want to delete the record?',
                buttons: [{
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        autospin: true,
                        action: function (dialog) {
                            var table_name = $('#tab').val();
                            var form_data = {
                                'tab': table_name,
                                'id': id
                            };
                            $.ajax({
                                url: base_url + 'common_methods/delete_records',
                                type: 'POST',
                                data: form_data,
                                dataType: 'json',
                                success: function (res) {
                                    dialog.setMessage(res.msg);
                                    $('#search_form search').trigger('click');
                                    patient_table.clear();
                                    patient_table.draw();
                                    dialog.enableButtons(false);
                                    setTimeout(function () {
                                        dialog.close();
                                    }, 3000);
                                },
                                error: function (err) {
                                    console.log(err);
                                    dialog.setMessage('Error in deleting record please refresh page and try again');
                                }
                            });
                        }
                    }, {
                        label: 'No',
                        cssClass: 'btn-danger',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }]
            });
        });
    });
</script>