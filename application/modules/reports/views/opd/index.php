<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> OPD report:</h3>
            </div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('reports/Opd/export_to_pdf'); ?>">
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">From:</label>
                        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">To:</label>
                        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">Department:</label>
                        <select name="department" id="department" class="form-control" required="required">
                            <option value="">Select Department</option>
                            <?php
                            if ($is_admin) {
                                echo '<option value="1">Central OPD</option>';
                            }
                            if (!empty($dept_list)) {
                                foreach ($dept_list as $dept) {
                                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2 col-sm-12">
                        <label class="control-label sr-only">Sub department:</label>
                        <select name="sub_department" id="sub_department" class="form-control" required="required">
                            <option value="">Select Sub department</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12 align-self-end">
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
                                    <!--<li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>-->
                                </ul>
                            </div>
                        </div>
                        <?php
                        if ($is_admin) {
                        ?>
                            <a href="<?php echo base_url('full-year-report'); ?>" target="_blank"><button class="btn btn-primary btn-sm" type="button" id="full_report"><i class="fa fa-fw fa-lg fa-download"></i>Full OPD Report</button></a>
                        <?php
                        }
                        ?>
                    </div>
                </form>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
                <hr />
                <div id="patient_statistics" class="col-md-12 col-lg-12 col-sm-12"></div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .opd_no {
        width: 40px !important;
    }

    .name {
        width: 120px !important;
    }

    .diagnosis {
        width: 150px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sub_department').attr('disabled', true);
        $('#search_form').on('click', '#search', function() {
            show_patients();
        });
        $('#search_form').on('change', '#department', function() {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'patient/get_sub_department',
                data: {
                    'dept_id': dept_id
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.sub_dept.length > 0) {
                        var option = ''; //'<option value="">Choose sub department</option>';
                        option += '<option value="both">Both</option>';
                        $.each(response.sub_dept, function(i) {
                            option += '<option value="' + response.sub_dept[i].sub_dept_name + '">' + response.sub_dept[i].sub_dept_name + '</option>';
                        });
                        $('#sub_department').html(option);
                        $('#sub_department').attr('disabled', false);
                    } else {
                        var option = '<option value="">Choose sub department</option>';
                        $('#sub_department').html(option);
                        $('#sub_department').attr('disabled', true);
                    }

                    if (response.doctors.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.doctors, function(i) {
                            option += '<option value="' + response.doctors[i].user_name + '">' + response.doctors[i].user_name + '</option>';
                        });
                        $('#doctor').html(option);
                        $('#doctor').attr('disabled', false);
                    } else {
                        var option = '<option value="">Choose doctor</option>';
                        $('#doctor').html(option);
                        $('#doctor').attr('disabled', true);
                    }
                }

            });
        });
        $('#search_form #export').on('click', '#export_to_xls', function(e) {
            e.preventDefault();
            $('.loading-box').css('display', 'block');
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Opd/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function(data) {
                    $('.loading-box').css('display', 'none');
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });
        $('#patient_table tbody').on('click', 'tr', function() {
            alert()
        });
        $('#search_form #export').on('click', '#export_to_pdf', function() {
            $('#search_form').submit();
        });
        var columns = [{
                title: "Y.No",
                class: "opd_no",
                data: function(item) {
                    return item.sequence;
                }
            },
            {
                title: "M.No",
                class: "opd_no",
                data: function(item) {
                    return item.msd;
                }
            },
            {
                title: "C.OPD",
                class: "opd_no",
                data: function(item) {
                    return item.OpdNo;
                }
            },
            {
                title: "D.OPD",
                class: "opd_no",
                data: function(item) {
                    return item.deptOpdNo;
                }
            },
            {
                title: "Name",
                class: 'name',
                data: function(item) {
                    return item.name;
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
                title: "Place",
                data: function(item) {
                    return item.city;
                }
            },
            {
                title: "Diagnosis",
                class: 'diagnosis',
                data: function(item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Department",
                data: function(item) {
                    return item.department + '<br/> <small>' + item.sub_department + '</small>';
                }
            },
            {
                title: "Date",
                data: function(item) {
                    return item.CameOn;
                }
            }
        ];
        var patient_table;

        function show_patients() {
            var statistics = '';
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
                    'url': base_url + 'reports/Opd/get_patients_list',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': function(d) {
                        return $.extend({}, d, {
                            "search_form": $('#search_form').serializeArray()
                        });
                    },
                    drawCallback: function(response) {
                        statistics = response.statistics;

                    }
                },
                order: [
                    [0, 'desc']
                ],
                info: true,
                sScrollX: true
            });
            show_statistics();
        }

        $('#patient_table tbody').on('click', 'tr', function() {
            var data = patient_table.row(this).data();
            get_patient_info_by_opd(data);
        });

        $('#default_modal_box').on('change', '#department', function() {
            var dept_id = $('#department').val();
            $.ajax({
                url: base_url + 'master/Department/get_sub_departments',
                type: 'POST',
                data: {
                    dept_id: dept_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.sub_departments.length > 0) {
                        var option = '<option value="">Choose sub department';
                        $.each(response.sub_departments, function(i) {
                            option += '<option value"' + response.sub_departments[i].sub_dept_id + '">' + response.sub_departments[i].sub_dept_name + '</option>'
                        });
                        $('#sub_branch').html(option);
                        $('#sub_branch').removeAttr('disabled');
                    } else {
                        $('#sub_branch').attr('disabled', 'disabled');
                    }
                    if (response.doctors.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.doctors, function(i) {
                            option += '<option value="' + response.doctors[i].doctorname + '">' + response.doctors[i].doctorname + '</option>'
                        });
                        $('#doctor_name').html(option);
                        $('#doctor_name').removeAttr('disabled');
                    } else {
                        $('#doctor_name').attr('disabled', 'disabled');
                    }

                }
            });
        });
        $('#default_modal_box .modal-footer').on('click', '#btn-ok', function() {
            alert();
            var form_data = $('#send_patient_for_followup').serializeArray();
            $.ajax({
                url: base_url + "reports/Opd/followup",
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                }
            });
        });

        function show_statistics() {
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Opd/get_statistics',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function(response) {
                    stats = response.statistics;
                    var total = 0;
                    var male_total = 0;
                    var female_total = 0;
                    var table = "<h4>OPD STATISTICS:</h4><hr>";
                    table += "<table width='50%' class='table table-bordered dataTable' style='margin-left: auto;margin-right: auto;'>";
                    table += "<thead><tr><th width='30%'>Department</th><th><center>Old</center></th><th><center>New</center></th><th><center>Total</center></th><th><center>Male</center></th><th><center>Female</center></th>" +
                        "<th>Netra-Roga Vibhaga</th><th>karna-Nasa-Mukha & Danta Vibhaga</th></tr></thead>";
                    table += "<tbody>";
                    $.each(stats, function(i) {
                        table += "<tr>";
                        table += "<td>" + stats[i].department + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].OLD + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].NEW + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Total + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Male + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].Female + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].NRV + "</td>";
                        table += "<td style='text-align: right;'>" + stats[i].KNMDV + "</td>";
                        table += "</tr>";
                        total = parseInt(total) + parseInt(stats[i].Total);
                        male_total = parseInt(male_total) + parseInt(stats[i].Male);
                        female_total = parseInt(female_total) + parseInt(stats[i].Female);
                    });
                    table += "<tr><td colspan=3 align=right><b>Total No:</b></td><td style='text-align: right;' class='alert-info'><b>" + total + "</b></td><td style='text-align: right;'><b>" + male_total + "</b></td><td style='text-align: right;'><b>" + female_total + "</b></td><td></td><td></td></tr>";
                    table += "</tbody>";
                    table += "</table>";
                    //console.log(table);
                    //alert('Hi');
                    $('#patient_statistics').html(table);
                },
                error: function(err) {
                    console.log(err.responseText);
                }
            });

        }
    });


    function get_patient_info_by_opd(data) {
        $.ajax({
            type: "POST",
            url: base_url + 'master/Department/get_department_list',
            data: {},
            dataType: 'json',
            success: function(response) {
                var table = "<form name='send_patient_for_followup' id='send_patient_for_followup' method='POST'>";
                table += "<table class='table'>";
                if (response.length > 0) {
                    var dept_option = '';
                    var i = 0;
                    dept_option += "<select name='department' id='department' class='form-control required'>";
                    dept_option += "<option value=''>Select Department</option>";
                    $.each(response, function() {
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
                $('#default_modal_box').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
                $('#date').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
            },
            error: function() {},
        });
    }
</script>