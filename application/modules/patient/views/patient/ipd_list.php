<style type="text/css">
    .ipd_no,.opd_no{
        text-align: right;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-users"></i> IPD patients:</h3></div>
            <div class="box-body">
                <?php
                if (!empty($this->session->flashdata('noty_msg'))) {
                    ?>
                    <div class="bs-component">
                        <div class="alert alert-dismissible alert-success">
                            <button class="close" type="button" data-dismiss="alert">×</button>
                            <p>
                                <?php echo $this->session->flashdata('noty_msg'); ?>
                            </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">OPD</label>
                        <input class="form-control" type="text" placeholder="Enter OPD number" name="OpdNo" id="OpdNo" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="control-label sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
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
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="discharge_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="default_modal_label">Discharge patient</h4>
            </div>
            <div class="modal-body" id="default_modal_body">
                <form class="form-horizontal" id="discharge_form">
                    <input type="hidden" name="dis_admit_data" id="dis_admit_data" class="" autocomplete="off">
                    <input type="hidden" name="dis_treat_id" id="dis_treat_id" class="" autocomplete="off">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>IPD:</td>
                                <td><input type="text" name="dis_ipd" id="dis_ipd" class="form-control" readonly="readonly" autocomplete="off">
                                    <input type="hidden" name="dis_doa" id="dis_doa"/></td>
                            </tr>
                            <tr>
                                <td>Discharge Date:<span class="err_msg">*</span> </td>
                                <td><input type="text" name="dod" id="dod" class="form-control todate required date_picker" required="required" placeholder="Discharge date" required="required" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>Notes:</td>
                                <td><textarea name="notes" id="notes" class="form-control" placeholder="Notes"></textarea></td>
                            </tr>
                            <tr>
                                <td>No.Of Days:</td>
                                <td><input name="days" id="days" class="form-control calculated" placeholder="Total days" readonly="readonly" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>Discharged By:<span class="err_msg">*</span> </td>
                                <td><input name="treated" id="treated" class="form-control" placeholder="Doctor Name" autocomplete="off"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-ok">Discharge</button>
            </div>
        </div>
    </div>
</div>
<?php
$bed_select = '';
if (!empty($wards)) {
    foreach ($wards as $ward) {
        $bed_select .= '<optgroup label="' . $ward['department'] . '"></optgroup>';
        $beds = explode(',', $ward['beds']);
        $bedstatus = explode(',', $ward['bedstatus']);
        //asort($beds);
        if (!empty($bedstatus)) {
            $i = 0;
            //asort($beds);
            foreach ($bedstatus as $bed) {
                //$is_disabled = ()
                $bed_stat = explode('#', $bed);
                $is_disabled = ($bed_stat[1] == 'not available') ? 'disabled="disabled" style="color:red;"' : '';
                $bed_select .= '<option value="' . $bed_stat[0] . '" ' . $is_disabled . '>' . $bed_stat[0] . '</option>';
            }
        }
    }
}
?>
<div class="modal fade" id="patient_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="default_modal_label">IPD [<span id="ipd_no_span"></span>] Patient information</h5>
            </div>
            <div class="modal-body" id="default_modal_body">
                <form name="patient_form" id="patient_form" method="POST">
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input class="form-control required" id="first_name" name="first_name" type="text" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input class="form-control" id="last_name" name="last_name" type="text" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input class="form-control required" id="age" name="age" type="text" placeholder="Age">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <input class="form-control required" id="gender" name="gender" type="text" placeholder="Gender">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="DoAdmission">Date of Admission:</label>
                                <input class="form-control required date_picker ipd_dates" id="DoAdmission" name="DoAdmission" type="text" placeholder="Date of Admisison">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="DoDischarge">Date of Discharge</label>
                                <input class="form-control date_picker ipd_dates" id="DoDischarge" name="DoDischarge" type="text" placeholder="Date of Discharge">
                            </div>
                        </div>
                    </div>
                    <div claass="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="NofDays">Days</label>
                                <input class="form-control" id="NofDays" name="NofDays" type="text">
                                <input class="form-control" id="cur_bed_no" name="cur_bed_no" type="hidden" />
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="bed_no">Bed No</label>
                                <select class="form-control" name="bed_no" id="bed_no">
                                    <option value="">Select bed</option>
                                    <?php echo $bed_select; ?>
                                </select>
                                <input class="form-control" id="selected_bed_no" name="selected_bed_no" type="hidden" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pat_diagnosis">Diagnosis:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_diagnosis" name="pat_diagnosis" placeholder="Diagnisis"/>
                            <input type="hidden" id="ipd" name="ipd" />
                            <input type="hidden" id="opd" name="opd" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="pat_treatment">Treatment:</label>
                        <span class="error">Note: Please separate each medicine with <b>,</b>(comma) </span>
                        <div class="controls">
                            <textarea name="pat_treatment" class="form-control" id="pat_treatment" rows="3" style="width: 100%" placeholder="Teatment should be seperated by comma"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pat_procedure">Procedures:</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="pat_procedure" name="pat_procedure" placeholder="Procedure"/>
                        </div>
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
        $('#search_form #export').on('click', '#export_to_xls', function (e) {
            e.preventDefault();
            //$('#search_form').submit();
            var form_data = $('#search_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/patient/export_patients_list',
                type: 'POST',
                dataType: 'json',
                data: {search_form: form_data},
                success: function (data) {
                    download(data.file, data.file_name, 'application/octet-stream');
                }
            });
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
        var columns = [
            {
                title: "IPD",
                class: "ipd_no",
                data: function (item) {
                    return '<span class="badge badge-danger" data-ipd_no=' + item.IpNo + '>' + item.IpNo + '</span>';
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
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Ward",
                data: function (item) {
                    return item.WardNo;
                }
            },
            {
                title: "Bed",
                data: function (item) {
                    return item.BedNo;
                }
            },
            {
                title: "Assigned doctor",
                data: function (item) {
                    return item.Doctor;
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
                title: "Discharge",
                data: function (item) {
                    if (item.status == 'stillin') {
                        return '<button class="btn btn-danger btn-sm discharge" data-doctor_name="' + item.Doctor + '" data-doa="' + item.DoAdmission + '" data-ipd_id=' + item.IpNo + '>Discharge</button>';
                    } else {
                        return '<button class="btn btn-primary btn-sm disabled" disabled="disabled" >Discharged</button>';
                    }
                }
            },
            {
                title: "Action",
                data: function (item) {
                    /*if (item.status == 'stillin') {
                     return "<i class='fa fa-download text-disabled' style='pointer-events: none;'></i>";
                     } else {
                     return '<i title="Download case sheet for IPD :' + item.IpNo + '" data-toggle="tooltip" data-placement="left"' +
                     ' class="fa fa-download hand_cursor text-primary download_case_sheet" data-ipd="' + item.IpNo + '"></i>';
                     
                     }*/
                    return '<i title="Download case sheet for IPD :' + item.IpNo + '" data-toggle="tooltip" data-placement="left"' +
                            ' class="fa fa-download hand_cursor text-primary download_case_sheet" data-ipd="' + item.IpNo + '"></i>'
                            + ' | <i class="fa fa-edit text-primary edit_patient" style="cursor:pointer;" data-opd="' + item.OpdNo + '" data-ipd="' + item.IpNo + '"></i>';

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
            'searching': true,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': base_url + 'patient/patient/get_ipd_patients_list',
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
            scrollX: true,
            ordering: false,

        });

        patient_table.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#patient_table tbody').on('click', '.ipd_no', function () {
            var ipd_id = $(this).find('span').data('ipd_no');
            window.location = base_url + 'patient/Treatment/add_ipd_treatment/' + ipd_id + '';
        });

        $('#patient_table tbody').on('click', '.discharge', function () {
            $('#discharge_form').find("input[type=text],input[name=days], textarea").val("")
            var ipd_id = $(this).data('ipd_id');
            var doa = $(this).data('doa');
            $('#discharge_modal_box #dis_ipd').val(ipd_id);
            $('#discharge_modal_box #dis_doa').val(doa);
            $('#discharge_modal_box #treated').val($(this).data('doctor_name'));


            $('#discharge_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
            var date = new Date(doa);
            $('.date_picker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                minDate: date.getDate()
            });
        });

        $('#discharge_modal_box').on('click', '#btn-ok', function () {
            var form_data = $('#discharge_form').serializeArray();
            $.ajax({
                url: base_url + 'patient/patient/discharge_patient',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    console.log(res);
                    if (res == 1) {
                        patient_table.draw();
                        $('#discharge_modal_box').modal('hide');
                        $.notify({
                            title: "Discharge Complete : ",
                            message: "Patient discharged successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                    }
                }
            });
        });

        $('#discharge_form').on('change', '#dod', function () {
            var doa = $('#dis_doa').val();
            var dod = $('#dod').val();

            $.ajax({
                url: base_url + 'patient/patient/date_difference/',
                type: 'POST',
                dataType: 'json',
                data: {doa: doa, dod: dod},
                success: function (res) {
                    if (!isNaN(res)) {
                        $('#days').val(res);
                    }
                }
            });
        });

        $('#patient_form').on('change', '.ipd_dates', function () {
            var doa = $('#DoAdmission').val();
            var dod = $('#DoDischarge').val();
            if (doa && dod) {
                $.ajax({
                    url: base_url + 'patient/patient/date_difference/',
                    type: 'POST',
                    dataType: 'json',
                    data: {doa: doa, dod: dod},
                    success: function (res) {
                        if (!isNaN(res)) {
                            $('#NofDays').val(res);
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


        $('#default_modal_box .modal-footer').on('click', '#btn-ok', function () {
            var form_data = $('#send_patient_for_followup').serializeArray();
            $.ajax({
                url: base_url + "patient/patient/followup",
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                }
            });
        });

        $('#patient_table tbody').on('click', '.download_case_sheet', function () {
            var ipd = $(this).data('ipd');
            window.open(base_url + 'patient/print_ipd_case_sheet/' + ipd, '_blank');
            //window.location.href = base_url + 'patient/print_ipd_case_sheet/' + ipd;
        });

        $('#patient_table tbody').on('click', '.edit_patient', function () {
            var opd_id = $(this).data('opd');
            var ipd_id = $(this).data('ipd');
            $('#patient_modal_box #ipd_no_span').html(ipd_id).css('color', 'brown');
            $.ajax({
                url: base_url + "common_methods/get_ipd_patient_details",
                type: 'POST',
                data: {opd: opd_id, ipd: ipd_id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        $('#patient_form #first_name').val(response.data.FirstName);
                        $('#patient_form #last_name').val(response.data.LastName);
                        $('#patient_form #age').val(response.data.Age);
                        $('#patient_form #gender').val(response.data.gender);
                        $('#patient_form #opd').val(response.data.OpdNo);
                        $('#patient_form #ipd').val(response.data.IpNo);
                        $('#patient_form #DoAdmission').val(response.data.DoAdmission);
                        $('#patient_form #DoDischarge').val(response.data.DoDischarge);
                        $('#patient_form #NofDays').val(response.data.NofDays);
                        $('#patient_form #pat_diagnosis').val(response.data.diagnosis);
                        $('#patient_form #pat_treatment').val(response.data.Trtment);
                        $('#patient_form #bed_no').val(response.data.BedNo);
                        $('#patient_form #cur_bed_no').val(response.data.BedNo);
                        $('#patient_form #selected_bed_no').val(response.data.BedNo);
                        $('#patient_form #pat_procedure').val(response.data.procedures);
                    }
                }
            });
            $('#patient_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#patient_form #bed_no').on('change', function () {
            var selected_bed = $(this).val();
            $('#patient_form #selected_bed_no').val(selected_bed);
        });

        $('#patient_form').validate();
        $('#patient_modal_box').on('click', '#btn-ok', function () {

            if ($('#patient_form').valid()) {
                var form_data = $('#patient_form').serializeArray();

                $.ajax({
                    url: base_url + "common_methods/update_ipd_details",
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        $('.loading-box').show();
                    },
                    success: function (response) {
                        $('.loading-box').hide();
                        if (response.status) {
                            $('#patient_modal_box').modal('hide');
                            $.notify({
                                title: "Patient information:",
                                message: "Successfully updated patient info",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                        } else {
                            $.notify({
                                title: "Patient information:",
                                message: "Failed to update data please try again",
                                icon: 'fa fa-cross'
                            }, {
                                z_index: 2000,
                                type: "danger"
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
                    icon: 'fa fa-cross'
                }, {
                    z_index: 2000,
                    type: "danger"
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