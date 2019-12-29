<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Bed occupied report</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('reports/Ipd/export_bed_to_pdf'); ?>">
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">From:</label>
                        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">To:</label>
                        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label class="control-label sr-only">Department:</label>
                        <select name="department" id="department" class="form-control" required="required">
                            <option value="">Select Department</option>
                            <option value="1">Central OPD</option>
                            <?php
                            if (!empty($dept_list)) {
                                foreach ($dept_list as $dept) {
                                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12 align-self-end">
                        <button class="btn btn-primary" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                    <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="patient_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="default_modal_label">Patient information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="default_modal_body">
                <form name="patient_form" id="patient_form" method="POST">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input class="form-control required" id="first_name" name="first_name" type="text" aria-describedby="first_nameHelp" placeholder="Enter First name">
                        <input class="form-control required" id="opd" name="opd" type="hidden">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input class="form-control required" id="last_name" name="last_name" type="text" aria-describedby="last_nameHelp" placeholder="Enter Last name">
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input class="form-control required numbers-only" id="age" name="age" type="text" aria-describedby="last_nameHelp" placeholder="Enter Age">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control required" name="gender" id="gender">
                            <option value="">Choose gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
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
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        var columns = [
            {
                title: "IPD",
                class: "ipd_no",
                data: function (item) {
                    return item.IpNo;
                }
            },
            {
                title: "OPD",
                class: "opd_no",
                data: function (item) {
                    return item.OpdNo;
                }
            },
            {
                title: "Name",
                data: function (item) {
                    return item.FName;
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
                    return item.Gender;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "BedNo",
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
                title: "Days",
                data: function (item) {
                    return item.NofDays;
                }
            },
            {
                title: "Doctor",
                data: function (item) {
                    return item.Doctor;
                }
            },
            {
                title: "Action",
                class: 'action',
                data: function (item) {
                    return '<center><i class="fa fa-edit text-primary edit_patient" style="cursor:pointer;" data-opd="' + item.OpdNo + '" data-ipd="' + item.IpNo + '"></i></center>';
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
                'url': base_url + 'reports/Ipd/get_bed_patients_list',
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_form').serializeArray()
                    });
                },
                drawCallback: function (response) {
                    //statistics = response.statistics;

                }
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });

        $('#patient_form').validate();
        $('#patient_modal_box').on('click', '#btn-ok', function () {

            if ($('#patient_form').valid()) {
                var form_data = $('#patient_form').serializeArray();

                $.ajax({
                    url: base_url + "common_methods/update_patient_info",
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            $('#patient_modal_box').modal('hide');
                            $.notify({
                                title: "Patient information:",
                                message: "Successfully updated patient info",
                                icon: 'fa fa-check',
                            }, {
                                type: "success",
                            });
                        } else {
                            $.notify({
                                title: "Patient information:",
                                message: "Failed to update data please try again",
                                icon: 'fa fa-cross',
                            }, {
                                z_index: 2000,
                                type: "danger",
                            });
                        }
                        patient_table.clear();
                        patient_table.draw();
                    }
                });
            } else {
                $.notify({
                    title: "Patient information:",
                    message: "Failed to update data please try again",
                    icon: 'fa fa-cross',
                }, {
                    z_index: 2000,
                    type: "danger",
                });
            }
        });
        $('#patient_table tbody').on('click', '.action', function () {
            var opd_id = $(this).find('i').data('opd');
            var ipd_id = $(this).find('i').data('ipd');
            $.ajax({
                url: base_url + "common_methods/get_patient_details",
                type: 'POST',
                data: {opd: opd_id},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $('#patient_form #first_name').val(response.data.FirstName);
                        $('#patient_form #last_name').val(response.data.LastName);
                        $('#patient_form #age').val(response.data.Age);
                        $('#patient_form #gender').val(response.data.gender);
                        $('#patient_form #opd').val(response.data.OpdNo);
                    }
                }
            });
            $('#patient_modal_box').modal('show');
        });
    });
</script>