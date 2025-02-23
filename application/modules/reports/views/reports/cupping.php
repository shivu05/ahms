<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-pulse"></i> Cupping:</h3>
                <!--<a class="btn btn-warning btn-sm pull-right" href="<?php echo base_url('other-procedure-statistics'); ?>">Statistics</a>
                <a class="btn btn-info btn-sm pull-right" style="margin-right:10px;" target="_blank" href="<?php echo base_url('reports/Test/export_otherprocedures_full'); ?>">Export all data</a>-->
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('cupping') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12 cupping_table_grid"></div>
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
                    return item.opd_no;
                }
            },
            {
                title: "IPD",
                data: function (item) {
                    return item.ipd_no;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FirstName;
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
                title: "Ref Date",
                data: function (item) {
                    return item.ref_date;
                }
            },
            {
                title: "Doctor Name",
                data: function (item) {
                    return item.doctor_name;
                }
            },
            {
                title: "Type of Cupping",
                data: function (item) {
                    return item.type_of_cupping;
                }
            },
            {
                title: "Site of Application",
                data: function (item) {
                    return item.site_of_application;
                }
            },
            {
                title: "Number of Cups Used",
                data: function (item) {
                    return item.no_of_cups_used;
                }
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Treatment Notes",
                data: function (item) {
                    return item.treatment_notes;
                }
            },
            {
                title: "Last Updates",
                data: function (item) {
                    return item.last_updates;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    return "<center><i class='fa fa-edit edit_cupping' style='cursor:pointer;' title='Edit information'></i>&nbsp;&nbsp;&nbsp;&nbsp;" +
                            "<input type='checkbox' name='check_del[]' class='check_box' id='checkbx" + item.id + "' value='" + item.id + "'/>"
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
                    'url': base_url + 'fetch-cupping',
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
            $('#patient_table tbody').on('click', '.edit_cupping', function () {
                var data = patient_table.row($(this).closest('tr')).data();
                $('#cupping_modal #cupping_edit_form #id').val(data.id);
                $('#cupping_modal #cupping_edit_form #cupping_procedures').val(data.treatment_notes);
                $('#cupping_modal #cupping_edit_form #cupping_date').val(data.ref_date);
                $('#cupping_modal').modal({backdrop: 'static', keyboard: false}, 'show');
            });

            $('#cupping_edit_form').validate({
                messages: {
                    cupping_procedures: {required: 'Procedure is empty'},
                    cupping_date: {required: 'Date is empty'}
                }
            });
        }

        $('#cupping_modal').on('click', '#btn-update', function () {
            if ($('#cupping_edit_form').valid()) {
                var form_data = $('#cupping_edit_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_cupping',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#cupping_modal').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Cupping:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Cupping:",
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
        $(".check_box").prop("checked", !clicked);
        clicked = !clicked;
    }
</script>