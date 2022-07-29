<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Patient master:</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">OPD</label>
                        <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Unique ID</label>
                        <input class="form-control" type="text" placeholder="Enter ID" name="sid" id="sid">
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
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
                    <table class="table table-hover table-bordered dataTable display nowrap" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="patient_modal" tabindex="-1" role="dialog" aria-labelledby="patient_modal_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="patient_modal_label">Edit Patient, OPD: <span id="opd_number"></span></h5>
            </div>
            <div class="modal-body" id="patient_modal_body">
                <div class="callout callout-warning">
                    <p>Note: If the patient information is updated it will be reflected globally for all the visits which can not be reverted back.</p>
                </div>
                <?php
                $attributes = array(
                    'id' => 'patient_edit_form',
                    'name' => 'patient_edit_form'
                );
                echo form_open('', $attributes);
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First name:</label>
                            <input type="text" name="FirstName" id="FirstName" class="form-control required"/>
                            <input type="hidden" name="OpdNo" id="OpdNo" class="form-control required" value=""/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last name:</label>
                            <input type="text" name="LastName" id="LastName" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Age:</label>
                            <input type="text" name="Age" id="Age" class="form-control required digits-only"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gender:</label>
                            <select name="gender" id="gender" class="form-control required">
                                <option value="">Choose gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Occupation:</label>
                            <input type="text" name="occupation" id="occupation" class="form-control required"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" name="city" id="city" class="form-control required"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address:</label>
                            <textarea name="address" id="address" class="form-control required"></textarea>
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
    .unique_id{
        min-width: 230px !important;
        max-width: 230px !important;
    }
    .opd_no{
        min-width: 80px !important;
        max-width: 80px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });
        $('#search_form #export').on('click', '#export_to_xls', function (e) {
            e.preventDefault();
            $('.loading-box').css('display', 'block');
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/patient/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: {search_form: form_data},
                success: function (data) {
                    $('.loading-box').css('display', 'none');
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        var columns = [
            {
                title: "Unique ID",
                class: "unique_id",
                data: function (item) {
                    var unique_id = item.sid;
                    if (unique_id == 'null') {
                        unique_id = '--';
                    }
                    return  '<small style="color:blue">' + unique_id + '</small>';
                }
            },
            {
                title: "C.OPD",
                class: "opd_no",
                data: function (item) {
                    return  item.OpdNo;
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
                class: "opd_no",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                class: "opd_no",
                data: function (item) {
                    return item.gender;
                }
            },
            {
                title: "Address",
                data: function (item) {
                    return item.address;
                }
            },
            {
                title: "City",
                data: function (item) {
                    return item.city;
                }
            },
            {
                title: "Occupation",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Action",
                data: function (item) {
                    return '<i class="fa fa-edit edit_patient" style="cursor:pointer;" title="Edit patient information"></i>'
                            + '&nbsp;&nbsp;&nbsp;&nbsp;<i title="Take appointment" class="fa fa-calendar-plus-o take_appointment" style="cursor:pointer"></i>';
                }
            }
        ];
        var patient_table = $('#patient_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [4]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No patients found</div>",
                sEmptyTable: "<div class='no_records'>No patients found</div>",
                sProcessing: "<div class='no_records'>Loading</div>",
            },
            'searching': false,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': base_url + 'patient/patient/get_patients_list',
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
            sScrollX: true,
            ordering: false
        });

        $('#patient_table tbody').on('click', '.take_appointment', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            get_patient_info_by_opd(data);
        });
        var validator = $('#patient_edit_form').validate();
        $('#patient_table tbody').on('click', '.edit_patient', function () {
            validator.resetForm();
            var data = patient_table.row($(this).closest('tr')).data();
            $('#patient_modal_label #opd_number').html(data.OpdNo);
            $('#patient_modal_body #OpdNo').val(data.OpdNo);
            $('#patient_modal_body #FirstName').val(data.FirstName);
            $('#patient_modal_body #LastName').val(data.LastName);
            $('#patient_modal_body #Age').val(data.Age);
            $('#patient_modal_body #gender').val(data.gender);
            $('#patient_modal_body #address').val(data.address);
            $('#patient_modal_body #city').val(data.city);
            $('#patient_modal_body #occupation').val(data.occupation);
            $('#patient_modal').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#patient_modal .modal-footer').on('click', '#btn-update', function () {
            var form_data = $('#patient_edit_form').serializeArray();
            if ($('#patient_edit_form').valid()) {
                $.ajax({
                    url: base_url + 'patient/update_patient_personal_information',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#patient_modal').modal('hide');
                        if (res.status === 'ok') {
                            $.notify({
                                title: "Patient:",
                                message: res.msg,
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#search_form #search').trigger('click');
                        } else {
                            $.notify({
                                title: "Patient:",
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

        $('#default_modal_box').on('change', '#department', function () {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'master/Department/get_sub_departments',
                type: 'POST',
                data: {dept_id: dept_id},
                dataType: 'json',
                success: function (response) {
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
                            option += '<option value="' + response.doctors[i].user_name + '">' + response.doctors[i].user_name + '</option>'
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
            $("#default_modal_box .modal-footer #btn-ok").attr("disabled", true);
            if ($('#send_patient_for_followup').valid()) {
                var form_data = $('#send_patient_for_followup').serializeArray();
                $.ajax({
                    url: base_url + "patient/patient/followup",
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (res) {
                        $('#default_modal_box').modal('hide');
                        $.notify({
                            title: "Appointment:",
                            message: "Appointment added successfully",
                            icon: 'fa fa-check',
                        }, {
                            type: "success",
                        });
                        $("#default_modal_box .modal-footer #btn-ok").attr("disabled", false);
                    }
                });
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
                    table += "<tr><td><label>Date:</label></td><td><input type='text' name='date' id='date' onkeydown='return false;' class='form-control date_picker required'/><input type='hidden' name='opd' id='opd' value='" + data.OpdNo + "'/></td></td>";
                }
                table += "</table>";
                table += "</form>";
                $('#default_modal_box #default_modal_label').html('Confirm Appointment');
                $('#default_modal_box #default_modal_body').html(table);
                $('#default_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
                $('#date').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
                $('#send_patient_for_followup').validate();
            },
            error: function () {},
        });
    }

</script>