<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-title">Pending Patient's X-Ray list:</div>
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
<div class="modal fade" id="xray_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="default_modal_label">Update X-Ray details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="xray_form" id="xray_form" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Film size:</label>
                        <input type="hidden" name="xray_id" id="xray_id" />
                        <input class="form-control" id="film_size" name="film_size" type="text" aria-describedby="film_sizeHelp" placeholder="Enter film size">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Date:</label>
                        <input class="form-control date_picker" id="xray_date" name="xray_date" type="text" aria-describedby="xray_dateHelp" placeholder="Enter X-Ray date">
                        <small class="form-text text-muted" id="xray_dateHelp"></small>
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
        $('#film_size').keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });
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
                title: "Part",
                data: function (item) {
                    return item.partOfXray;
                }
            },
            {
                title: "Part of X-Ray",
                data: function (item) {
                    return item.partOfXray;
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
                    return item.refDate;
                }
            },
            {
                title: "Film",
                data: function (item) {
                    return item.filmSize;
                }
            },
            {
                title: "X-Ray Date",
                data: function (item) {
                    return item.xrayDate;
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
                    return '<i class="fa fa-pencil-square-o text-primary pointer edit_xray" data-id="' + item.ID + '" aria-hidden="true"></i>';
                }
            }

        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [8]}
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
                'url': base_url + 'patient/Xray/get_pending_xray_list',
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

        $('#patient_table tbody').on('click', '.edit_xray', function () {
            var id = $(this).data('id');
            $('#xray_modal_box #xray_form #xray_id').val(id);
            $('#xray_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');

        });
        $('#xray_modal_box .modal-footer').on('click', '#btn-ok', function () {
            var form_data = $('#xray_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/xray/save_xray_data',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#xray_modal_box').modal('hide');
                    if (res) {
                        $.notify({
                            title: "X-Ray:",
                            message: "Details added successfully",
                            icon: 'fa fa-check',
                        }, {
                            type: "success",
                        });
                    } else {
                        $.notify({
                            title: "X-Ray:",
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
</script>`
