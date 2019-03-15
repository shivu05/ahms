<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-title">Patient's Lab list:</div>
            <div class="tile-body">
                <?php //echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="test_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="default_modal_label">Update Lab details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ecg_modal_body">
                <form action="" name="test_form" id="test_form" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Test value:</label>
                        <input class="form-control" id="test_value" name="test_value" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test value">
                        <small class="form-text text-muted" id="test_dateHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Test range:</label>
                        <input class="form-control" id="test_range" name="test_range" type="text" aria-describedby="test_dateHelp" placeholder="Enter Test range">
                        <small class="form-text text-muted" id="test_dateHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Lab Date:</label>
                        <input type="hidden" name="test_id" id="test_id" />
                        <input class="form-control date_picker" id="test_date" name="test_date" type="text" aria-describedby="test_dateHelp" placeholder="Enter Lab date">
                        <small class="form-text text-muted" id="test_dateHelp"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        //show_patients();
        $('#search_form').on('click', '#search', function () {
            // show_patients();
        });


        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

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
                title: "Name",
                data: function (item) {
                    return item.name;
                }
            },
            {
                title: "Ref. doctor",
                data: function (item) {
                    return item.refDocName;
                }
            },
            {
                title: "Ref. date",
                data: function (item) {
                    return item.testDate;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Test name",
                data: function (item) {
                    return item.testName;
                }
            },
            {
                title: "Test value",
                data: function (item) {
                    return item.testvalue;
                }
            },
            {
                title: "Test range",
                data: function (item) {
                    return item.testrange;
                }
            },
            {
                title: "Test date",
                data: function (item) {
                    return item.tested_date;
                }
            },
            {
                title: "Action",
                data: function (item) {
                    if (item.testvalue == '' || item.testvalue == null) {
                        return '<center><i class="fa fa-pencil-square-o text-primary text-center pointer edit_lab" data-id="' + item.ID + '" aria-hidden="true"></i></center>';
                    } else {
                        return '<center><i class="fa fa-pencil-square-o text-primary text-center fa-disabled" data-id="' + item.ID + '" aria-hidden="true"></i></center>';
                    }
                }
            }

        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [7]}
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
            'ajax': {
                'url': base_url + 'patient/lab/get_pending_lab_list',
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        //"search_form": $('#search_form').serializeArray()
                    });
                },
                drawCallback: function (response) {}
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });

        $('#patient_table tbody').on('click', '.edit_lab', function () {
            var id = $(this).data('id');
            $('#test_modal_box #test_form #test_id').val(id);
            $('#test_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');

        });
        $('#test_modal_box .modal-footer').on('click', '#btn-ok', function () {
            var form_data = $('#test_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/lab/save_lab_data',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#test_modal_box').modal('hide');
                    if (res) {
                        $.notify({
                            title: "Lab:",
                            message: "Details added successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                    } else {
                        $.notify({
                            title: "Lab:",
                            message: "Failed to update data. Try again",
                            icon: 'fa fa-check'
                        }, {
                            type: "danger"
                        });
                    }
                    patient_table.clear();
                    patient_table.draw();
                }
            });
        });
    });
</script>