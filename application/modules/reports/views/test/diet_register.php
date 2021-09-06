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
            <div id="patient_statistics" class="col-md-12"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="diet_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="xray_modal_label">Update Diet details</h5>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="xray_form" id="xray_form" method="POST">
                    <div class="form-group">
                        <label for="filmpartOfXray_size">Morning:</label>
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
                    return 'Yes';
                }
            },
            {
                title: "Afternoon <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return 'Yes';
                }
            },
            {
                title: "Night <i data-toggle='tooltip' data-placement='left' title='Chapathi Rice' class='fa fa-info-circle'></i>",
                data: function (item) {
                    return 'Yes';
                }
            }

        ];
//        if (is_admin == '1') {
//            columns.push({
//                title: 'Action | <input type="checkbox" name="check_all" class="check_all" id="check_all" onclick="toggle(this)"/>',
//                data: function (item) {
//                    return "<center><input type='checkbox' name='check_del[]' class='check_xray' id='checkbx" + item.ID + "' value='" + item.ID + "'/>" +
//                            "&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-edit hand_cursor edit_xray' data-id='" + item.ID + "'></i>" + "</center>";
//                }
//            });
//        }

        function show_patients() {
            var patient_table = $('#patient_table').DataTable({
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
        }
    });
</script>