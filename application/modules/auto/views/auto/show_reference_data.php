<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Patient reference:</h3>
               <!-- <button class="pull-right btn btn-primary btn-sm" id="add_patient"><i class="fa fa-plus-square"></i> &nbsp;&nbsp;Add patient</button>-->
            </div>
            <div class="box-body">
                <div id="patient_details">
                    <form name="test_form" id="test_form" method="POST">
                        <input type="hidden" name="tab" id="tab" value="<?= base64_encode('oldtable') ?>" />
                        <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                    </form>
                </div>
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="patient_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="xray_modal_label">Update Patient details</h4>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="patient_form" id="patient_form" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FirstName">First Name:</label>
                                <input class="form-control required" id="FirstName" name="FirstName" type="text" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="LastName">Last Name:</label>
                                <input class="form-control" id="LastName" name="LastName" type="text" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Age">Age:</label>
                                <input type="hidden" name="ID" id="ID" />
                                <input class="form-control required" id="Age" name="Age" type="text" placeholder="Enter Age">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select class="form-control required" id="gender" name="gender">
                                    <option value=""></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="occupation">Occupation:</label>
                                <input class="form-control required" id="occupation" name="occupation" type="text" placeholder="Enter Occupation">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input class="form-control required" id="address" name="address" type="text" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input class="form-control required" id="city" name="city" type="text" placeholder="City">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis:</label>
                                <input class="form-control required" id="diagnosis" name="diagnosis" type="text" placeholder="Diagnosis">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="procedures">Procedures:</label>
                                <input class="form-control required" id="procedures" name="procedures" type="text" placeholder="Procedures">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Trtment">Treatment:</label>
                                <textarea class="form-control required" id="Trtment" name="Trtment" type="text" placeholder="Treatment"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medicines">Medicines:</label>
                                <textarea class="form-control required" id="medicines" name="medicines" type="text" placeholder="Medicines"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_patient_modal_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:75%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="xray_modal_label">Add Patient details</h4>
            </div>
            <div class="modal-body" id="xray_modal_body">
                <form action="" name="add_patient_form" id="add_patient_form" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FirstName">First Name:</label>
                                <input class="form-control required" id="FirstName" name="FirstName" type="text" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="LastName">Last Name:</label>
                                <input class="form-control" id="LastName" name="LastName" type="text" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Age">Age:</label>
                                <input class="form-control required" id="Age" name="Age" type="text" placeholder="Enter Age">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select class="form-control required" id="gender" name="gender">
                                    <option value=""></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="occupation">Occupation:</label>
                                <input class="form-control required" id="occupation" name="occupation" type="text" placeholder="Enter Occupation">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input class="form-control required" id="address" name="address" type="text" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input class="form-control required" id="city" name="city" type="text" placeholder="City">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis:</label>
                                <input class="form-control required" id="diagnosis" name="diagnosis" type="text" placeholder="Diagnosis">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="procedures">Procedures:</label>
                                <input class="form-control required" id="procedures" name="procedures" type="text" placeholder="Procedures">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Trtment">Treatment:</label>
                                <textarea class="form-control required" id="Trtment" name="Trtment" type="text" placeholder="Treatment"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medicines">Medicines:</label>
                                <textarea class="form-control required" id="medicines" name="medicines" type="text" placeholder="Medicines"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .age{
        min-width: 60px !important;
        max-width: 60px !important;
    }
    .ipd_no{
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
    }
    .long_text{
        width: 200px !important;
        min-width: 200px !important;
        max-width: 200px !important;
    }
    .action{
        text-align: center;
        vertical-align: middle;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var is_admin = '<?= $is_admin ?>';
        $('#search_form').on('click', '#search', function () {
            patient_table.clear();
            patient_table.draw();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });
        var columns = [
            {
                title: "Action",
                class: 'action',
                data: function (item) {
                    return '<i class="fa fa-pencil-square text-primary hand_cursor edit-row"></i> | <i class="fa fa-trash text-danger hand_cursor delete-row"></i>';
                }
            },
//            {
//                title: "#",
//                class: "",
//                data: function (item) {
//                    return item.serial_number;
//                }
//            },
            {
                title: "Name",
                class: "ipd_no",
                data: function (item) {
                    return item.FirstName + ' ' + item.LastName;
                }
            },
            {
                title: "Age",
                class: "",
                data: function (item) {
                    return item.Age;
                }
            },
            {
                title: "Gender",
                class: "",
                data: function (item) {
                    return item.gender;
                }
            },
            {
                title: "Occupation",
                class: "",
                data: function (item) {
                    return item.occupation;
                }
            },
            {
                title: "Place",
                class: "ipd_no",
                data: function (item) {
                    return item.address + ', ' + item.city;
                }
            },
            {
                title: "Diagnosis",
                class: "ipd_no",
                data: function (item) {
                    return item.diagnosis;
                }
            },
//            {
//                title: "Complaintes",
//                class: "ipd_no",
//                data: function (item) {
//                    return item.complaints;
//                }
//            },
            {
                title: "Department",
                class: "ipd_no",
                data: function (item) {
                    return item.department + '<br/><small class="text-primary">' + item.sub_dept + '</small>';
                }
            },
//            {
//                title: "Sub dept",
//                class: "ipd_no",
//                data: function (item) {
//                    return item.sub_dept;
//                }
//            },
            {
                title: "Procedures",
                class: "",
                data: function (item) {
                    return item.procedures;
                }
            },
            {
                title: "Treatment",
                class: "long_text",
                data: function (item) {
                    return item.Trtment;
                }
            },
            {
                title: "Medicines",
                class: "long_text",
                data: function (item) {
                    return item.medicines;
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
                sProcessing: "<div class='no_records'>Loading</div>"
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
                'url': base_url + 'get_data',
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
        $('#patient_table tbody').on('click', '.edit-row', function () {
            var data = patient_table.row($(this).closest('tr')).data();
            $('#patient_modal_box #patient_form #ID').val(data.ID);
            $('#patient_modal_box #patient_form #FirstName').val(data.FirstName);
            $('#patient_modal_box #patient_form #LastName').val(data.LastName);
            $('#patient_modal_box #patient_form #Age').val(data.Age);
            $('#patient_modal_box #patient_form #gender').val(data.gender);
            $('#patient_modal_box #patient_form #occupation').val(data.occupation);
            $('#patient_modal_box #patient_form #address').val(data.address);
            $('#patient_modal_box #patient_form #city').val(data.city);
            $('#patient_modal_box #patient_form #diagnosis').val(data.diagnosis);
            $('#patient_modal_box #patient_form #procedures').val(data.procedures);
            $('#patient_modal_box #patient_form #Trtment').val(data.Trtment);
            $('#patient_modal_box #patient_form #medicines').val(data.medicines);
            $('#patient_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        $('#patient_modal_box #patient_form').validate();
        $('#patient_modal_box').on('click', '#btn-update', function () {
            if ($('#patient_modal_box #patient_form').valid()) {
                var form_data = $('#patient_modal_box #patient_form').serializeArray();
                $.ajax({
                    url: base_url + 'save-data',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (response) {
                        $('#patient_modal_box').modal('hide');
                        $.notify({
                            title: "Patient records:",
                            message: response.msg,
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                        console.log(response);
                        patient_table.clear();
                        patient_table.draw();
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            } else {
                alert('invalid')
            }
        });

        $('#add_patient').on('click', function () {
            $('#add_patient_modal_box').modal('show');
        });
    });
</script>