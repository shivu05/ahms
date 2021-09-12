<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list-ul"></i> Diet register:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <hr/>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="diet_modal_box" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="edit_modal_label">Update Diet details</h5>
            </div>
            <div class="modal-body" id="edit_modal_body">
                <form name="edit_form" id="edit_form" method="POST">
                    <div class="form-group">
                        <label for="morning">Morning:</label>
                        <input class="form-control required" id="morning" name="morning" type="text" aria-describedby="morningHelp" placeholder="Enter Morning diet">
                        <small class="form-text text-muted" id="morningHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="after_noon">After noon:</label>
                        <input type="hidden" name="id" id="id" />
                        <input class="form-control required" id="after_noon" name="after_noon" type="text" aria-describedby="after_noonHelp" placeholder="Enter Afternoon diet">
                        <small class="form-text text-muted" id="after_noonHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="evening">Evening:</label>
                        <input class="form-control required" id="evening" name="evening" type="text" aria-describedby="eveningHelp" placeholder="Enter evening diet">
                        <small class="form-text text-muted" id="eveningHelp"></small>
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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#search_form').on('click', '#search', function () {
            show_patients();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        var columns = [
            {
                title: "Sl. No",
                class: "ipd_no",
                data: function (item) {
                    return item.serial_number;
                }
            },
            {
                title: "IPD",
                class: "opd_no",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FName;
                }
            },
            {
                title: "Bed",
                data: function (item) {
                    return item.BedNo;
                }
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "DOA",
                data: function (item) {
                    return item.DoAdmission;
                }
            },
            {
                title: "DOD",
                data: function (item) {
                    return item.DoDischarge;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Morning <i data-toggle='tooltip' data-placement='left' title='Bread/Biscuit/Tea' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return item.morning;
                }
            },
            {
                title: "Afternoon <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return item.after_noon;
                }
            },
            {
                title: "Night <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return item.evening;
                }
            }

        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    return "<center>" + "<i class='fa fa-edit hand_cursor edit' data-id='" + item.ID + "'></i>" + "</center>";
                }
            });
        }
        var patient_table = '';
        function show_patients() {
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
                    'url': base_url + 'reports/Test/get_diet_register_data',
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
            $('#patient_table tbody').on('click', '.edit', function () {
                var data = patient_table.row($(this).closest('tr')).data();
                $('#diet_modal_box #edit_form #id').val(data.ID);
                $('#diet_modal_box #edit_form #morning').val(data.morning);
                $('#diet_modal_box #edit_form #after_noon').val(data.after_noon);
                $('#diet_modal_box #edit_form #evening').val(data.evening);
                $('#diet_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
            });
            $('#edit_form').validate({
                messages: {
                    morning: {required: 'Morning diet is empty'},
                    after_noon: {required: 'After noon diet is empty'},
                    evening: {required: 'Evening diet is empty'}
                }
            });


        }
        $('#diet_modal_box').on('click', '#btn-update', function () {
            if ($('#edit_form').valid()) {
                var form_data = $('#edit_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_diet',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#diet_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Diet:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Diet:",
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


    });
</script>