<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">X-Ray report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('xrayregistery') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
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
                title: "Name",
                data: function (item) {
                    return item.FirstName + ' ' + item.LastName;
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
                title: "Place",
                data: function (item) {
                    return item.address;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            /*{
             title: "X-Ray No",
             data: function (item) {
             return item.xrayNo;
             }
             },*/
            {
                title: "Part of X-Ray",
                data: function (item) {
                    return item.partOfXray;
                }
            },
            {
                title: "Film size",
                data: function (item) {
                    return item.filmSize;
                }
            },
            {
                title: "Ref. Date",
                data: function (item) {
                    return item.refDate;
                }
            },
            {
                title: "X-Ray Date",
                data: function (item) {
                    return item.xrayDate;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: "Action",
                data: function (item) {
                    return "<center><input type='checkbox' name='check_del[]' class='check_xray' id='checkbx" + item.ID + "' value='" + item.ID + "'/>" +
                            "&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-edit hand_cursor edit_xray' data-id='" + item.ID + "'></i>" + "</center>";
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
            'ajax': {
                'url': base_url + 'reports/Test/get_xray_patients_list',
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


        $('#patient_table tbody').on('click', '.edit_xray', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#xray_modal_box #xray_form #ID').val(data.ID);
            $('#xray_modal_box #xray_form #partOfXray').val(data.partOfXray);
            $('#xray_modal_box #xray_form #filmSize').val(data.filmSize);
            $('#xray_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });
        $('#xray_form').validate({
            messages: {
                partOfXray: {required: 'Part of X-ray is empty'},
                filmSize: {required: 'Film size is empty'}
            }
        });

        $('#xray_modal_box').on('click', '#btn-update', function () {
            if ($('#xray_form').valid()) {
                var form_data = $('#xray_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_xray',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#xray_modal_box').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "X-Ray:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "X-Ray:",
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

        $('#search_form').on('click', '#btn_delete', function () {

            var $b = $('#test_form input[type=checkbox]');
            num = $b.filter(':checked').length;
            if (num == 0) {
                alert('Please select atleast one records');
            } else {
                var form_data = $('#test_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/delete_records',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (res) {
                        alert('Deleted successfully');
                        $('#search_form search').trigger('click');
                        patient_table.clear();
                        patient_table.draw();
                    },
                    error: function (err) {
                        console.log(err)
                    }
                })
            }
        });
    }
    );
</script>