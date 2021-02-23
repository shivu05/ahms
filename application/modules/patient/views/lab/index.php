<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Patient's Lab list:</h3>
            </div>
            <div class="box-body">
                <?php //echo $top_form; ?>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="test_modal_box" tabindex="-1" role="dialog" aria-labelledby="default_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 75% !important">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="default_modal_label">Update Lab details of OPD: <span id="pat_opd" class="text-warning"></span> </h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="test_form" id="test_form" method="POST">
                    <div id="lab_data"></div>
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
                title: "Action",
                data: function (item) {
                    if (item.testvalue == '' || item.testvalue == null) {
                        return '<center><i class="fa fa-pencil-square-o text-primary text-center pointer edit_lab" ' +
                                ' data-opdno="' + item.OpdNo + '" data-treat_id="' + item.treatID + '" data-id="' + item.ID + '" aria-hidden="true"></i></center>';
                    } else {
                        return '<center><i class="fa fa-pencil-square-o text-primary text-center fa-disabled" data-id="' + item.ID + '" aria-hidden="true"></i></center>';
                    }
                }
            }
        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [6]}
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
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            $('#lab_data').html('');
            var id = $(this).data('id');
            var opdno = $(this).data('opdno');
            var treat_id = $(this).data('treat_id');
            $('#test_modal_box #test_form #test_id').val(id);
            $('#test_modal_box #test_form #treat_id').val(treat_id);
            var post_data = {
                'opdno': opdno,
                'treat_id': treat_id
            };
            $.ajax({
                url: base_url + 'patient/lab/get_lab_records',
                type: 'POST',
                dataType: 'html',
                data: post_data,
                success: function (res) {
                    var date = new Date();
                    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    $('#lab_data').html(res);
                    $('.date_picker').datepicker({
                        format: "yyyy-mm-dd",
                        autoclose: true,
                        todayHighlight: true,
                        daysOfWeekHighlighted: "0"
                    });
                    $('.date_picker').attr('autocomplete', 'off');
                    $('.date_picker').datepicker('setDate', today);
                },
                error: function (err) {
                    console.log(err);
                }
            });

            $('#test_modal_box #pat_opd').html(opdno);
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