<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-photo"></i> Fumigation register:
                </h3><button class='btn btn-primary btn-sm pull-right' data-backdrop="static" data-keyboard="false"  id="add-fumigation"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('fumigation_register') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="fumigation_add_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="xray_modal_label">Add Fumigation</h5>
            </div>
            <form action="" name="fumigation_form" id="fumigation_form" method="POST">
                <div class="modal-body" id="fumigation_modal_body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filmpartOfXray_size">Fumigation method:</label>
                                <input class="form-control required" id="fumigation_mothod" name="fumigation_mothod" type="text" aria-describedby="fumigation_mothodHelp" placeholder="Enter Method">
                                <small class="form-text text-muted" id="partOfXrayHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">Chemical used:</label>
                                <input class="form-control required" id="chemical_used" name="chemical_used" type="text" aria-describedby="chemical_usedHelp" placeholder="Enter Chemicals used">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">Start Time:</label>
                                <input class="form-control required date_time_picker" id="start_time" name="start_time" type="text" aria-describedby="start_timeHelp" placeholder="Enter Start Time">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">Name of the Superviser:</label>
                                <input class="form-control required" id="superviser_name" name="superviser_name" type="text" aria-describedby="superviser_nameHelp" placeholder="Enter Supervisor Name">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filmSize">OT Number:</label>
                                <input class="form-control required" id="ot_number" name="ot_number" type="text" aria-describedby="ot_numberHelp" placeholder="Enter OT number">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">Neutralization:</label>
                                <input class="form-control required" id="neutralization" name="neutralization" type="text" aria-describedby="neutralizationHelp" placeholder="Enter Neutralization">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">End Time:</label>
                                <input class="form-control required date_time_picker" id="end_time" name="end_time" type="text" aria-describedby="end_timeHelp" placeholder="Enter End Time">
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                            <div class="form-group">
                                <label for="filmSize">Date:</label>
                                <input class="form-control required date_picker" id="f_date" name="f_date" type="text" aria-describedby="f_dateHelp" placeholder="Enter Date"/>
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="filmSize">Remarks:</label>
                                <textarea class="form-control required" id="remarks" name="remarks" type="text" aria-describedby="RemarksHelp" placeholder="Enter Remarks"></textarea>
                                <small class="form-text text-muted" id="film_sizeHelp"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                    <button type="button" class="btn btn-primary" id="btn-add"><i class="fa fa-save"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.date_time_picker').datetimepicker({
            showTodayButton: true,
            format: 'LT',
            sideBySide: true,
            showClose: true
        });
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#add-fumigation').on('click', function () {
            $('#fumigation_add_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#fumigation_add_modal_box #fumigation_form').validate();
        $('#fumigation_add_modal_box #fumigation_form #btn-add').on('click', function () {
            if ($('#fumigation_add_modal_box #fumigation_form').valid()) {
                var form_data = $('#fumigation_form').serializeArray();
                $.ajax({
                    url: base_url + 'store-fumigation-info',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#fumigation_add_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Fumigation:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Fumigation:",
                                message: res.msg,
                                icon: 'fa fa-cross'
                            }, {
                                type: "danger"
                            });
                        }
                    }
                });
            } else {
                $.notify({
                    title: "Fumigation:",
                    message: 'failed',
                    icon: 'fa fa-check'
                }, {
                    type: "error"
                });
            }
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
                title: "Method",
                class: "ipd_no",
                data: function (item) {
                    return item.fumigation_mothod;
                }
            },
            {
                title: "Chemicals used",
                class: "ipd_no",
                data: function (item) {
                    return item.chemical_used;
                }
            },
            {
                title: "Start time",
                class: "ipd_no",
                data: function (item) {
                    return item.start_time;
                }
            },
            {
                title: "End time",
                class: "ipd_no",
                data: function (item) {
                    return item.end_time;
                }
            },
            {
                title: "OT No",
                class: "ipd_no",
                data: function (item) {
                    return item.ot_number;
                }
            },
            {
                title: "Neutralization",
                class: "ipd_no",
                data: function (item) {
                    return item.neutralization;
                }
            },
            {
                title: "Superviser name",
                class: "ipd_no",
                data: function (item) {
                    return item.superviser_name;
                }
            },
            {
                title: "Date",
                class: "ipd_no",
                data: function (item) {
                    return item.f_date;
                }
            },
            {
                title: "Remarks",
                class: "ipd_no",
                data: function (item) {
                    return item.Remarks;
                }
            }
        ];

        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            "bDestroy": true,
            language: {
                sZeroRecords: "<div class='no_records'>No data found</div>",
                sEmptyTable: "<div class='no_records'>No data found</div>",
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
                'url': base_url + 'fetch-fumigation-list',
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
    });
</script>