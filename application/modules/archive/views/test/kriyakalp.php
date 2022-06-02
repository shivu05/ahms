<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Kriyakalp report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('kriyakalpa') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <hr/>
            <div id="patient_statistics" class="col-md-12"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="kriyakalpa_modal" tabindex="-1" role="dialog" aria-labelledby="kriyakalpa_modal_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="kriyakalpa_modal_label">Edit information</h5>
            </div>
            <div class="modal-body" id="kriyakalpa_modal_body">
                <?php
                $attributes = array(
                    'id' => 'kriya_edit_form',
                    'name' => 'kriya_edit_form'
                );
                echo form_open('', $attributes);
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Procedures:</label>
                            <input type="text" name="kriya_procedures" id="kriya_procedures" class="form-control required"/>
                            <input type="hidden" name="id" id="id" class="form-control required" value=""/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date:</label>
                            <input type="text" name="kriya_date" id="kriya_date" class="form-control date_picker required"/>
                        </div>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .sl_no{
        width: 40px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            show_patients();
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
                title: "OPD",
                class: "sl_no",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "IPD",
                class: "sl_no",
                data: function (item) {
                    return item.IpNo;
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
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Treatment",
                data: function (item) {
                    return item.procedures;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Sub Dept",
                data: function (item) {
                    return item.sub_dept;
                }
            },
            {
                title: "Ref. Doctor",
                data: function (item) {
                    return item.attndedby;
                }
            },
            {
                title: "Date",
                data: function (item) {
                    return item.kriya_date;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    return "<center><i class='fa fa-edit edit_kriya' style='cursor:pointer;' title='Edit information'></i>&nbsp;&nbsp;&nbsp;&nbsp;" +
                            "<input type='checkbox' name='check_del[]' class='check_box' id='checkbx" + item.kid + "' value='" + item.kid + "'/>"
                            + "</center>";
                }
            });
        }
        var patient_table;
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
                    'url': base_url + 'reports/Test/get_kriyakalp_patients_list',
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
            $('#patient_table tbody').on('click', '.edit_kriya', function () {
                var data = patient_table.row($(this).closest('tr')).data();
                $('#kriyakalpa_modal #kriya_edit_form #id').val(data.kid);
                $('#kriyakalpa_modal #kriya_edit_form #kriya_procedures').val(data.procedures);
                $('#kriyakalpa_modal #kriya_edit_form #kriya_date').val(data.kriya_date);
                $('#kriyakalpa_modal').modal({backdrop: 'static', keyboard: false}, 'show');
            });

            $('#kriya_edit_form').validate({
                messages: {
                    kriya_procedures: {required: 'Procedure is empty'},
                    kriya_date: {required: 'Date is empty'}
                }
            });
        }

        $('#kriyakalpa_modal').on('click', '#btn-update', function () {
            if ($('#kriya_edit_form').valid()) {
                var form_data = $('#kriya_edit_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_kriyakalpa',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#kriyakalpa_modal').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Kriyakalpa:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Kriyakalpa:",
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
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: 'Delete records',
                message: 'Are you sure want to delete records?',
                buttons: [{
                        label: 'Yes',
                        cssClass: 'btn-primary btn-sm',
                        action: function (dialogItself) {
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
                                });
                            }
                            dialogItself.close();
                        }
                    }, {
                        label: 'No',
                        cssClass: 'btn-danger btn-sm',
                        action: function (dialogItself) {
                            dialogItself.close();
                        }
                    }]
            });
        });
    });
    var clicked = false;
    function toggle(source) {
        //console.log($(".skip_script:checked").length+'check');
        $(".check_box").prop("checked", !clicked);
        clicked = !clicked;
    }
</script>
