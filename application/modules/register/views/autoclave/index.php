<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-photo"></i> Autoclave register:
                </h3><button class='btn btn-primary btn-sm pull-right' data-backdrop="static" data-keyboard="false"  id="add-autoclave"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('autoclave_register') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="autoclave_add_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="xray_modal_label">Add autoclave</h5>
            </div>
            <form action="" name="autoclave_form" id="autoclave_form" method="POST">
                <div class="modal-body" id="autoclave_modal_body">
                    <div class="form-group">
                        <label for="filmpartOfXray_size">DrumNo:</label>
                        <input class="form-control required" id="DrumNo" name="DrumNo" type="text" aria-describedby="DrumNoHelp" placeholder="Enter DrumNo">
                        <small class="form-text text-muted" id="partOfXrayHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Drum Start Time:</label>
                        <input class="form-control required date_time_picker" id="DrumStartTime" name="DrumStartTime" type="text" aria-describedby="DrumStartTimeHelp" placeholder="Enter DrumStartTime">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Drum End Time:</label>
                        <input class="form-control required date_time_picker" id="DrumEndTime" name="DrumEndTime" type="text" aria-describedby="DrumEndTimeHelp" placeholder="Enter Drum End Time">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Name of the Superviser:</label>
                        <input class="form-control required" id="SupervisorName" name="SupervisorName" type="text" aria-describedby="SupervisorNameHelp" placeholder="Enter Supervisor Name">
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
                    </div>
                    <div class="form-group">
                        <label for="filmSize">Remarks:</label>
                        <textarea class="form-control" id="Remarks" name="Remarks" type="text" aria-describedby="RemarksHelp" placeholder="Enter Remarks"></textarea>
                        <small class="form-text text-muted" id="film_sizeHelp"></small>
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
            format: 'YYYY-MM-DD HH:mm:ss',
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

        $('#add-autoclave').on('click', function () {
            $('#autoclave_add_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#autoclave_add_modal_box #autoclave_form').validate();
        $('#autoclave_add_modal_box #autoclave_form #btn-add').on('click', function () {
            if ($('#autoclave_add_modal_box #autoclave_form').valid()) {
                var form_data = $('#autoclave_form').serializeArray();
                $.ajax({
                    url: base_url + 'store-autoclave-indfo',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#autoclave_add_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Autoclave:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Autoclave:",
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
                    title: "Autoclave:",
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
                title: "Drum No",
                class: "ipd_no",
                data: function (item) {
                    return item.DrumNo;
                }
            },
            {
                title: "Start Date",
                class: "ipd_no",
                data: function (item) {
                    return item.DrumStartTime;
                }
            },
            {
                title: "End Date",
                class: "ipd_no",
                data: function (item) {
                    return item.DrumEndTime;
                }
            },
            {
                title: "Supervisor Name",
                class: "ipd_no",
                data: function (item) {
                    return item.SupervisorName;
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
                'url': base_url + 'fetch-autoclave-list',
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