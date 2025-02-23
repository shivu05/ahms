<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pulse"></i> Jaloukavacharana:</h3>
                <!--<a class="btn btn-warning btn-sm pull-right" href="<?php echo base_url('other-procedure-statistics'); ?>">Statistics</a>
                <a class="btn btn-info btn-sm pull-right" style="margin-right:10px;" target="_blank" href="<?php echo base_url('reports/Test/export_otherprocedures_full'); ?>">Export all data</a>-->
            </div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('jaloukavacharana') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
                <div id="patient_statistics" class="col-md-12 jaloukavacharana_table_grid"></div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .sl_no {
        width: 40px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function() {
            show_patients();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function() {
            $('#search_form').submit();
        });

        var columns = [{
                title: "Sl. No",
                class: "sl_no",
                data: function(item) {
                    return item.serial_number;
                }
            },
            {
                title: "OPD",
                class: "sl_no",
                data: function(item) {
                    return item.opd_no;
                }
            },
            {
                title: "IPD",
                data: function(item) {
                    return item.ipd_no;
                }
            },
            {
                title: "Name",
                data: function(item) {
                    return item.FirstName;
                }
            },
            {
                title: "Age",
                data: function(item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                data: function(item) {
                    return item.gender;
                }
            },
            {
                title: "Ref Date",
                data: function(item) {
                    return item.ref_date;
                }
            },
            {
                title: "Doctor Name",
                data: function(item) {
                    return item.doctor_name;
                }
            },
            {
                title: "Procedure Details",
                data: function(item) {
                    return item.procedure_details;
                }
            },
            {
                title: "Doctor Remarks",
                data: function(item) {
                    return item.doctor_remarks;
                }
            },
            {
                title: "Diagnosis",
                data: function(item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Last Updated",
                data: function(item) {
                    return item.last_updated;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function(item) {
                    return "<center><i class='fa fa-edit edit_jaloukavacharana' style='cursor:pointer;' title='Edit information'></i>&nbsp;&nbsp;&nbsp;&nbsp;" +
                        "<input type='checkbox' name='check_del[]' class='check_box' id='checkbx" + item.id + "' value='" + item.id + "'/>" +
                        "</center>";
                }
            });
        }
        var patient_table;

        function show_patients() {
            patient_table = $('#patient_table').DataTable({
                'columns': columns,
                'columnDefs': [{
                    className: "",
                    "targets": [4]
                }],
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
                    'url': base_url + 'fetch-jaloukavacharana',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function(d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    },
                    drawCallback: function(response) {}
                },
                order: [
                    [0, 'desc']
                ],
                info: true,
                sScrollX: true
            });
            $('#patient_table tbody').on('click', '.edit_jaloukavacharana', function() {
                var data = patient_table.row($(this).closest('tr')).data();
                $('#jaloukavacharana_modal #jaloukavacharana_edit_form #id').val(data.id);
                $('#jaloukavacharana_modal #jaloukavacharana_edit_form #procedure_details').val(data.procedure_details);
                $('#jaloukavacharana_modal #jaloukavacharana_edit_form #ref_date').val(data.ref_date);
                $('#jaloukavacharana_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            });

            $('#jaloukavacharana_edit_form').validate({
                messages: {
                    procedure_details: {
                        required: 'Procedure details are empty'
                    },
                    ref_date: {
                        required: 'Date is empty'
                    }
                }
            });
        }

        $('#jaloukavacharana_modal').on('click', '#btn-update', function() {
            if ($('#jaloukavacharana_edit_form').valid()) {
                var form_data = $('#jaloukavacharana_edit_form').serializeArray();
                $.ajax({
                    url: base_url + 'reports/Test/update_jaloukavacharana',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function(res) {
                        $('#jaloukavacharana_modal').modal('hide');
                        if (res.status == 'ok') {
                            $.notify({
                                title: "Jaloukavacharana:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Jaloukavacharana:",
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

        $('#search_form').on('click', '#btn_delete', function() {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: 'Delete records',
                message: 'Are you sure want to delete records?',
                buttons: [{
                    label: 'Yes',
                    cssClass: 'btn-primary btn-sm',
                    action: function(dialogItself) {
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
                                success: function(res) {
                                    alert('Deleted successfully');
                                    $('#search_form search').trigger('click');
                                    patient_table.clear();
                                    patient_table.draw();
                                },
                                error: function(err) {
                                    console.log(err)
                                }
                            });
                        }
                        dialogItself.close();
                    }
                }, {
                    label: 'No',
                    cssClass: 'btn-danger btn-sm',
                    action: function(dialogItself) {
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