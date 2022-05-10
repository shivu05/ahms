<style type="text/css">
    .opd_no{
        cursor: pointer;
        width:60px !important;
    }
    .type{
        text-align: center;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-archive"></i> Archived OPD List</h3>
                <a href="<?= base_url('archive/Opd'); ?>"><button class="btn btn-sm btn-primary pull-right"><i class="fa fa-backward"></i> Back</button></a>
            </div>
            <div class="box-body">
                <form class="form-horizontal" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('archive/treatment/export'); ?>">
                    <div class="row">
                        <div class=" col-md-2 col-sm-12">
                            <label class="control-label  sr-only">Archived Year:</label>
                            <select name="arch_year" id="arch_year" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php
                                if (!empty($arch_years)) {
                                    foreach ($arch_years as $row) {
                                        echo "<option value='" . base64_encode($row['db_name']) . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div class="col-md-1">
                            <label class="control-label sr-only">OPD</label>
                            <input class="form-control" type="text" placeholder="OPD" name="OpdNo" id="OpdNo" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label sr-only">Name</label>
                            <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                        </div>-->
                        <div class="col-md-2">
                            <label class="control-label sr-only">Date</label>
                            <input class="form-control date_picker" type="text" placeholder="Enter date" name="date" id="date" autocomplete="off">
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label class="control-label  sr-only">Department:</label>
                            <select name="department" id="department" class="form-control select2" data-placeholder="Department">
                                <option value=""></option>
                                <?php
                                if (!empty($dept_list)) {
                                    foreach ($dept_list as $dept) {
                                        echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check" style="padding-top: 5% !important">
                                <input class="form-check-input" type="checkbox" value="1" checked="checked" name="all_patients" id="all_patients">
                                <label class="form-check-label" for="all_patients">
                                    Display all patients
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <div class="">
                                <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                                <div class="btn-group" role="group" id="export">
                                    <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a class="dropdown-item" href="#" id="export_cs">Case sheet</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div id="patient_details" style="margin-top:1%">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="treatment_edit_modal" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="treatment_edit_modal_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="treatment_edit_modal_title">Update patient information: <span id="OPD_NUM" class="text-warning"></span> </h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <h5 class="text-primary" id=""></h5>
                <form method="POST" name="treatment_edit_form" id="treatment_edit_form">
                    <div class="control-group">
                        <label class="control-label" for="pat_name">Name:</label>
                        <div class="controls">
                            <input type="text" class="form-control required" id="pat_name" name="pat_name" placeholder="Name"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_age">Age:</label>
                        <div class="controls">
                            <input type="number" class="form-control required" id="pat_age" name="pat_age" placeholder="Age"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Gender:</label>
                        <div class="controls">
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="Male">Male</label>
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="Female">Female</label>
                            <label class="radio-inline"><input class="form-check-input required" type="radio" name="pat_gender" id="pat_gender" value="others">Others</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_diagnosis">Diagnosis:</label>
                        <div class="controls">
                            <input type="text" class="form-control required" id="pat_diagnosis" name="pat_diagnosis" placeholder="Diagnisis"/>
                            <input type="hidden" id="treat_id" name="treat_id" />
                            <input type="hidden" id="opd" name="opd" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_treatment">Treatment:</label>
                        <span class="error">Note: Please separate each medicine with <b>,</b>(comma) </span>
                        <div class="controls">
                            <textarea name="pat_treatment" class="form-control required" id="pat_treatment" rows="3" style="width: 100%" placeholder="Teatment should be seperated by comma"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_procedure">Procedures:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_procedure" name="pat_procedure" placeholder="Procedure"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="AddedBy">Doctor:</label>
                        <div class="controls">
                            <select class="form-control select2 required" id="AddedBy" name="AddedBy">
                                <option value="">Choose doctor</option>
                            </select>
                            <!--<input type="text" class="form-control required" id="AddedBy" name="AddedBy" placeholder="Docotr"/>-->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-update">Save changes</button>
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
        $('#search_form #export').on('click', '#export_to_xls', function (e) {
            e.preventDefault();
            //$('#search_form').submit();
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Opd/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: {search_form: form_data},
                success: function (data) {
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });

        $('#search_form #export').on('click', '#export_cs', function (e) {
            e.preventDefault();
            $('<input>').attr({
                type: 'hidden',
                id: 'exp_type',
                name: 'exp_type',
                value: btoa('cs')
            }).appendTo('#search_form');
            $('#search_form').submit();
        });
        var columns = [
            {
                title: "OPD",
                class: "opd_no",
                data: function (item) {
                    if (item.attndedon) {
                        return '<span class="badge badge-primary" disabled="disabled">' + item.OpdNo + '</span>';
                    } else {
                        return '<span class="badge badge-danger">' + item.OpdNo + '</span>';
                    }
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
//            {
//                title: "Address",
//                data: function (item) {
//                    return item.address;
//                }
//            },
            {
                title: "City",
                data: function (item) {
                    var patient_address = item.city;
                    if (item.address) {
                        patient_address = item.address + ', ' + item.city;
                    }
                    return patient_address;
                }
            },
            {
                title: "Occupation",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Department",
                data: function (item) {
                    return item.department;
                }
            },
            {
                title: "Came on",
                data: function (item) {
                    return item.CameOn;
                }
            },
            {
                title: "Type",
                class: 'type',
                data: function (item) {
                    if (item.PatType == 'Old Patient') {
                        return '<span style="color:orange">O</span>';
                    } else if (item.PatType == 'New Patient') {
                        return '<span style="color:green">N</span>';
                    } else {
                        return '';
                    }
                }
            },
            {
                title: "Case sheet",
                data: function (item) {
                    return '<i class="fa fa-download hand_cursor text-primary download_case_sheet" data-opd="' + item.OpdNo + '" data-treat_id="' + item.ID + '"></i>';
                }
            }
        ];
        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-treat_id', data.ID);
                $(row).attr('data-opd_id', data.OpdNo);
            },
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
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
                'url': base_url + 'archive/treatment/get_patients_for_treatment',
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_form').serializeArray()
                    });
                }
            },
            order: [[0, 'desc']],
            info: true,
            "scrollX": true
        });

        $('#patient_table tbody').on('click', '.download_case_sheet', function () {
            var opd = $(this).data('opd');
            var treat_id = $(this).data('treat_id');
            var date = $('#search_form #arch_year').val();
            window.location.href = base_url + 'archive/Treatment/print_case_sheet/' + opd + '/' + treat_id + '/' + date;
        });

        $('#default_modal_box').on('change', '#department', function () {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'master/Department/get_sub_departments',
                type: 'POST',
                data: {dept_id: dept_id},
                dataType: 'json',
                success: function (response) {
                    console.log(response)
                    if (response.sub_departments.length > 0) {
                        var option = '<option value="">Choose sub department';
                        $.each(response.sub_departments, function (i) {
                            option += '<option value"' + response.sub_departments[i].sub_dept_id + '">' + response.sub_departments[i].sub_dept_name + '</option>'
                        });
                        $('#sub_branch').html(option);
                        $('#sub_branch').removeAttr('disabled');
                    } else {
                        $('#sub_branch').attr('disabled', 'disabled');
                    }
                    if (response.doctors.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.doctors, function (i) {
                            option += '<option value"' + response.doctors[i].doctorname + '">' + response.doctors[i].doctorname + '</option>'
                        });
                        $('#doctor_name').html(option);
                        $('#doctor_name').removeAttr('disabled');
                    } else {
                        $('#doctor_name').attr('disabled', 'disabled');
                    }

                }
            });
        });
        $('#default_modal_box .modal-footer').on('click', '#btn-ok', function () {
            alert();
            var form_data = $('#send_patient_for_followup').serializeArray();
            $.ajax({
                url: base_url + "reports/Opd/followup",
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                }
            });
        });
        $('#treatment_edit_form').validate();
        $('#patient_table tbody').on('click', '.edit_treatment_data', function () {
            $('#treatment_edit_form .control-group').removeClass('error');
            var opd = $(this).data('opd');
            var treat_id = $(this).data('treat_id');

            $.ajax({
                url: base_url + 'patient/treatment/fetch_treatment_data',
                type: 'POST',
                data: {'opd': opd, 'treat_id': treat_id},
                dataType: 'json',
                success: function (response) {
                    $('#treatment_edit_form #pat_name').val(response.data.FirstName);
                    $('#treatment_edit_form #pat_age').val(response.data.Age);
                    $("input[name=pat_gender][value='" + response.data.gender + "']").prop("checked", true);
                    $('#treatment_edit_form #pat_treatment').val(response.data.Trtment);
                    $('#treatment_edit_form #treat_id').val(response.data.ID);
                    $('#treatment_edit_form #opd').val(response.data.OpdNo);
                    $('#treatment_edit_form #pat_diagnosis').val(response.data.diagnosis);
                    $('#treatment_edit_form #pat_procedure').val(response.data.procedures);
                    $('#treatment_edit_form #AddedBy').val(response.data.AddedBy);
                    var option = '';
                    $.each(response.doctors_list, function (i) {
                        var is_selected = '';
                        if (response.data.AddedBy == response.doctors_list[i].user_name) {
                            is_selected = 'selected="selected"';
                        }
                        option += '<option ' + is_selected + ' value="' + response.doctors_list[i].user_name + '">' + response.doctors_list[i].user_name + '</option>';
                    });
                    $('#treatment_edit_form #AddedBy').html(option);
                }
            });
            $('#treatment_edit_modal #OPD_NUM').html("Center OPD: " + opd);
            $('#treatment_edit_modal').modal('show');

        });

        $('#treatment_edit_modal').on('click', '#btn-update', function () {
            var form_data = $('#treatment_edit_modal #treatment_edit_form').serializeArray();
            if ($('#treatment_edit_form').valid()) {
                $.ajax({
                    url: base_url + 'patient/treatment/update_treatment_details',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        $('#treatment_edit_modal').modal('hide');
                        if (response.status == true) {
                            $.notify({
                                title: "Treatment:",
                                message: "Updated successfully",
                                icon: 'fa fa-check',
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Treatment:",
                                message: "Failed to update",
                                icon: 'fa fa-check',
                            }, {
                                type: "danger"
                            });
                        }
                        $('#search_form #search').trigger('click');
                    }
                });
            } else {
                alert('Patient data can not be empty');
            }
        });
    });
    function get_patient_info_by_opd(data) {
        $.ajax({
            type: "POST",
            url: base_url + 'master/Department/get_department_list',
            data: {},
            dataType: 'json',
            success: function (response) {
                var table = "<form name='send_patient_for_followup' id='send_patient_for_followup' method='POST'>";
                table += "<table class='table'>";
                if (response.length > 0) {
                    var dept_option = '';
                    var i = 0;
                    dept_option += "<select name='department' id='department' class='form-control required'>";
                    dept_option += "<option value=''>Select Department</option>";
                    $.each(response, function () {
                        dept_option += "<option value='" + response[i]['dept_unique_code'] + "'>" + response[i]['department'] + "</option>";
                        i++;
                    });
                    var sub_dept = "<select class='form-control required' name='sub_branch' id='sub_branch' disabled='disabled'><option value=''>Choose sub Branch</option><option value='Netra-Roga Vibhaga'>Netra-Roga Vibhaga</option><option value='karna-Nasa-Mukha & Danta Vibhaga'>Karna-Nasa-Mukha & Danta Vibhaga</option></select>";
                    var doc_name = "<div id='doc_info_dept'><select class='form-control' name='doctor_name' id='doctor_name' disabled='true'><option value=''>Choose Doctor</option></select></div>";
                    table += "<tr>";
                    table += "<td colspan=2><b>Name: </b>" + data.FirstName + " " + data.LastName + "</td>";
                    table += "</tr>";
                    table += "<tr><td><b>Age: </b>" + data.Age + "</td>";
                    table += "<td><b>Sex: </b>" + data.gender + "</td></tr>";
                    table += "<tr><td colspan='2'><b>Address: </b> " + data.address + ", " + data.city + "</td></tr>";
                    table += "<tr><td><b>Department:</b></td><td>" + dept_option + "</td></td>";
                    table += "<tr><td><b>Sub Department:</b></td><td>" + sub_dept + "</td></td>";
                    table += "<tr><td><b>Doctor:</b></td><td>" + doc_name + "</td></td>";
                    table += "<tr><td><label>Date:</label></td><td><input type='text' name='date' id='date' class='form-control date_picker required'/><input type='hidden' name='opd' id='opd' value='" + data.OpdNo + "'/></td></td>";
                }
                table += "</table>";
                table += "</form>";
                $('#default_modal_box #default_modal_label').html('Send patient for OPD');
                $('#default_modal_box #default_modal_body').html(table);
                $('#default_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
                $('#date').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
            },
            error: function () {},
        });
    }

</script>