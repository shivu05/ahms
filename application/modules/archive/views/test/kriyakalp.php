<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-archive"></i> Archived Kriyakalp report:</h3>
                <a class="btn btn-sm btn-success pull-right" href="<?php echo base_url('archive/TestReports') ?>"><i class="fa fa-backward"></i> Back to main</a>
            </div>
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
                    'url': base_url + 'archive/Test/get_kriyakalp_patients_list',
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
