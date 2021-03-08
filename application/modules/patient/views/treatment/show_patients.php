<style type="text/css">
    .opd_no{
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-pencil"></i> OPD Treatment</h3></div>
            <div class="box-body">
                <form class="form-horizontal" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('reports/Opd/export_patients_list_pdf'); ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label sr-only">OPD</label>
                            <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label sr-only">Name</label>
                            <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label sr-only">Date</label>
                            <input class="form-control date_picker" type="text" placeholder="Enter date" name="date" id="date" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <div class="form-check" style="padding-top: 5% !important">
                                <input class="form-check-input" type="checkbox" value="1" name="all_patients" id="all_patients">
                                <label class="form-check-label" for="all_patients">
                                    Display all patients
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 align-self-end">
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
                    </div>
                </form>
                <div id="patient_details">
                    <p class="text-warning">Note: OPD number with green label indicated treatment is completed and OPD with red color indicated treatment is pending</p>
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
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
        $('#patient_table tbody').on('click', 'tr', function () {
            alert()
        });
        $('#search_form #export').on('click', '#export_to_pdf', function () {
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
                data: function (item) {
                    if (item.PatType == 'Old Patient') {
                        return 'OLD';
                    } else if (item.PatType == 'New Patient') {
                        return 'New';
                    } else {
                        return '';
                    }
                }
            },
            {
                title: "Case sheet",
                data: function (item) {
                    return '<i class="fa fa-download hand_cursor download_case_sheet" data-opd="' + item.OpdNo + '" data-treat_id="' + item.ID + '"></i>';
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
            'ajax': {
                'url': base_url + 'patient/treatment/get_patients_for_treatment',
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
            sScrollX: true
        });
        $('#patient_table tbody').on('click', 'tr td.opd_no', function () {
            var data = patient_table.row(this).data();
            window.location.href = base_url + 'add-opd-treatment/' + data.OpdNo + '/' + data.ID;
        });
        $('#patient_table tbody').on('click', '.download_case_sheet', function () {
            var opd = $(this).data('opd');
            var treat_id = $(this).data('treat_id');
            window.location.href = base_url + 'patient/Treatment/print_case_sheet/' + opd + '/' + treat_id;
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