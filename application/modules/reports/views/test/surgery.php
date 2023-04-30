<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-hospital-o"></i> Surgery report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <input type="hidden" name="tab" id="tab" value="<?= base64_encode('surgeryregistery') ?>" />
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="surgery_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="surgery_modal_label">Update Surgery details</h5>
            </div>
            <div class="modal-body" id="surgery_modal_body">
                <form action="" name="surgery_form" id="surgery_form" method="POST">
                    <div class="form-group">
                        <label for="surgName">Surgeon:</label>
                        <input class="form-control required" id="surgName" name="surgName" type="text" 
                               aria-describedby="surgNameHelp" placeholder="Enter Surgeon">
                        <small class="form-text text-muted" id="surgNameHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="asssurgeon">Asst. Surgeon:</label>
                        <input class="form-control required" id="asssurgeon" name="asssurgeon" type="text" 
                               aria-describedby="asssurgeonHelp" placeholder="Enter Asst. Surgeon">
                        <small class="form-text text-muted" id="asssurgeonHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="anaesthetic">Anesthetist:</label>
                        <input type="hidden" name="ID" id="ID" />
                        <input class="form-control required" id="anaesthetic" name="anaesthetic" type="text" 
                               aria-describedby="anaestheticHelp" placeholder="Enter Anesthetist">
                        <small class="form-text text-muted" id="anaestheticHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="surgDate">Ref. Date:</label>
                        <input class="form-control required date_picker" id="surgDate" name="surgDate" type="text" 
                               aria-describedby="surgDateHelp" placeholder="Enter Ref. Date">
                        <small class="form-text text-muted" id="surgDateHelp"></small>
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
<style>
    .patient{
        width: 120px !important;
    }

    .sl_no{
        width: 40px !important;
        text-align: center;
    }
    .opd{
        width: 50px !important;
        text-align: center;
    }
    .place{
        width: 120px !important;
    }
    .department{
        width: 120px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
        var columns = [
            {
                title: "Sl. No",
                class: "sl_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "C.OPD",
                class: "opd",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "C.IPD",
                class: "opd",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "Patient",
                class: "patient",
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
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Surgery",
                data: function (item) {
                    return item.surgeryname;
                }
            }
            , {
                title: "Type",
                data: function (item) {
                    return item.surgType;
                }
            }
            , {
                title: "Surgeon",
                data: function (item) {
                    return item.surgName;
                }
            }
            , {
                title: "Asst. Surgeon",
                data: function (item) {
                    return item.asssurgeon;
                }
            }
            , {
                title: "Anesthetist",
                data: function (item) {
                    return item.anaesthetic;
                }
            },
            {
                title: "Ref. Date",
                data: function (item) {
                    return item.surgDate;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    return "<center><i class='fa fa-edit hand_cursor edit' data-id='" + item.ID + "'></i>" + " | " +
                            "<i class='fa fa-trash hand_cursor delete text-danger' data-id='" + item.id + "'></i>" + "</center>";
                }
            });
        }
        var patient_table;
        // function show_patients() {
        patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            "bDestroy": true,
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
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
                'url': base_url + 'reports/Test/get_surgery_report',
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
            sScrollX: "100%",
            sScrollXInner: "150%",
            "bScrollCollapse": true

        });

        $('#patient_table tbody').on('click', '.edit', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#surgery_modal_box #surgery_form #ID').val(data.ID);
            $('#surgery_modal_box #surgery_form #surgName').val(data.surgName);
            $('#surgery_modal_box #surgery_form #asssurgeon').val(data.asssurgeon);
            $('#surgery_modal_box #surgery_form #anaesthetic').val(data.anaesthetic);
            $('#surgery_modal_box #surgery_form #surgDate').val(data.surgDate);
            $('#surgery_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });


        // }

        $('#surgery_form').validate({
            messages: {
                surgName: {required: 'Surgeon is empty'},
                asssurgeon: {required: 'Asst. Surgeon is empty'},
                anaesthetic: {required: 'Anesthetist is empty'},
                surgDate: {required: 'Date is empty'}
            }
        });

        $('#surgery_modal_box').on('click', '#btn-update', function () {
            if ($('#surgery_form').valid()) {
                var form_data = $('#surgery_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_surgery',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#surgery_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Surgery:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Surgery:",
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