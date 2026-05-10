<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Birth report:</h3></div>
            <div class="box-body">
                <?php echo $top_form; ?>
                <hr/>
                <div id="patient_details">
                    <table class="table table-hover table-bordered dataTable" id="patient_table" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="birth_modal_box" tabindex="-1" role="dialog" aria-labelledby="birthModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="birth_modal_label">Update Birth details</h5>
            </div>
            <div class="modal-body" id="birth_modal_body">
                <form name="birth_edit_form" id="birth_edit_form" method="POST">
                    <input type="hidden" name="ID" id="ID" />
                    <div class="form-group">
                        <label for="deliveryDetail">Delivery details:</label>
                        <input class="form-control required" id="deliveryDetail" name="deliveryDetail" type="text" maxlength="255" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="babyBirthDate">Birth date:</label>
                        <input class="form-control date_picker required" id="babyBirthDate" name="babyBirthDate" type="text" autocomplete="off" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="birthtime">Birth time:</label>
                        <input class="form-control required" id="birthtime" name="birthtime" type="text" placeholder="HH:MM" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="babyWeight">Baby weight:</label>
                        <div class="input-group">
                            <input class="form-control required number" id="babyWeight" name="babyWeight" type="number" min="0.1" max="9.99" step="0.01" required="required" />
                            <span class="input-group-addon">KG</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="babygender">Baby gender:</label>
                        <select class="form-control required" id="babygender" name="babygender" required="required">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="others">Others</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deliverytype">Delivery type:</label>
                        <input class="form-control required" id="deliverytype" name="deliverytype" type="text" maxlength="100" required="required" />
                    </div>
                    <div class="form-group">
                        <label for="treatby">Doctor:</label>
                        <input class="form-control required" id="treatby" name="treatby" type="text" maxlength="100" required="required" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
                <button type="button" class="btn btn-primary" id="btn-update"><i class="fa fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="birth_delete_modal_box" tabindex="-1" role="dialog" aria-labelledby="birthDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="birth_delete_modal_label">Confirmation</h5>
            </div>
            <div class="modal-body" id="birth_delete_modal_body">
                <form name="birth_delete_form" id="birth_delete_form" method="POST">
                    <p>Are you sure you want to delete the birth record for OPD <strong id="birth_delete_opd"></strong>?</p>
                    <input type="hidden" name="ID" id="ID" value="" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
                <button type="button" class="btn btn-danger" id="btn-delete"><i class="fa fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
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
                class: "ipd_no",
                data: function (item) {
                    return item.serial_number;
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
                title: "IPD",
                class: "opd_no",
                data: function (item) {
                    return item.IpNo;
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
                title: "Diagnosis",
                data: function (item) {
                    return item.diagnosis;
                }
            },
            {
                title: "Delivery details",
                data: function (item) {
                    return item.deliveryDetail;
                }
            },
            {
                title: "Birth date",
                data: function (item) {
                    return item.babyBirthDate;
                }
            },
            {
                title: "Birth time",
                data: function (item) {
                    return item.birthtime;
                }
            },
            {
                title: "Baby weight",
                data: function (item) {
                    return item.babyWeight;
                }
            },
            {
                title: "Delivery type",
                data: function (item) {
                    return item.deliverytype;
                }
            },
            {
                title: "Doctor",
                data: function (item) {
                    return item.treatby;
                }
            }

        ];
        if (is_admin == '1') {
            columns.push({
                title: 'Action',
                data: function (item) {
                    return "<center>" +
                        "<i class='fa fa-edit hand_cursor edit' data-id='" + item.ID + "' title='Edit'></i> | " +
                        "<i class='fa fa-trash hand_cursor text-danger delete' data-id='" + item.ID + "' title='Delete'></i>" +
                        "</center>";
                }
            });
        }

        var patient_table = '';
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
                    'url': base_url + 'reports/Test/get_birth_register_data',
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

        $('#patient_table').on('click', 'tbody .edit', function () {
            var data = getRowData(this);
            if (!data) {
                $.notify({title: "Birth:", message: 'Unable to read selected row. Please refresh the report and try again.', icon: 'fa fa-remove'}, {type: "danger"});
                return;
            }
            $('#birth_modal_box #birth_edit_form #ID').val(data.ID);
            $('#birth_modal_box #birth_edit_form #deliveryDetail').val(data.deliveryDetail);
            $('#birth_modal_box #birth_edit_form #babyBirthDate').val(normalizeBirthDate(data.babyBirthDate));
            $('#birth_modal_box #birth_edit_form #birthtime').val(normalizeBirthTime(data.birthtime, data.babyBirthDate));
            $('#birth_modal_box #birth_edit_form #babyWeight').val(normalizeBabyWeight(data.babyWeight));
            $('#birth_modal_box #birth_edit_form #babygender').val(data.babygender);
            $('#birth_modal_box #birth_edit_form #deliverytype').val(data.deliverytype);
            $('#birth_modal_box #birth_edit_form #treatby').val(data.treatby);
            $('#birth_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
            $('#birth_modal_box #babyBirthDate').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });
        });

        $('#patient_table').on('click', 'tbody .delete', function () {
            var data = getRowData(this);
            if (!data) {
                $.notify({title: "Birth:", message: 'Unable to read selected row. Please refresh the report and try again.', icon: 'fa fa-remove'}, {type: "danger"});
                return;
            }
            $('#birth_delete_modal_box #birth_delete_form #ID').val(data.ID);
            $('#birth_delete_modal_box #birth_delete_opd').text(data.OpdNo);
            $('#birth_delete_modal_box').modal({backdrop: 'static', keyboard: false}, 'show');
        });

        if ($.fn.validate) {
            $.validator.addMethod('time24', function (value, element) {
                return this.optional(element) || /^([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/.test(value);
            }, 'Enter time in HH:MM format.');
            $('#birth_edit_form').validate({
                rules: {
                    birthtime: {
                        required: true,
                        time24: true
                    },
                    babyWeight: {
                        required: true,
                        number: true,
                        min: 0.1,
                        max: 9.99
                    }
                },
                messages: {
                    birthtime: 'Enter time in HH:MM format.'
                }
            });
        }

        $('#birth_modal_box').on('click', '#btn-update', function () {
            if (!validateBirthForm()) {
                return;
            }
            var form_data = $('#birth_edit_form').serializeArray();
            form_data.push({name: 'babyWeightUnit', value: 'KG'});
            $.ajax({
                url: base_url + 'reports/Test/update_birth',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#birth_modal_box').modal('hide');
                    if (res.status == 'ok') {
                        $.notify({title: "Birth:", message: res.msg, icon: 'fa fa-check'}, {type: "success"});
                        $('#search_form #search').trigger('click');
                    } else {
                        $.notify({title: "Birth:", message: res.msg, icon: 'fa fa-remove'}, {type: "danger"});
                    }
                }
            });
        });

        $('#birth_delete_modal_box').on('click', '#btn-delete', function () {
            var form_data = $('#birth_delete_form').serializeArray();
            $.ajax({
                url: base_url + 'reports/Test/delete_birth',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (res) {
                    $('#birth_delete_modal_box').modal('hide');
                    if (res.status == 'ok') {
                        $.notify({title: "Birth:", message: res.msg, icon: 'fa fa-check'}, {type: "success"});
                        $('#search_form #search').trigger('click');
                    } else {
                        $.notify({title: "Birth:", message: res.msg, icon: 'fa fa-remove'}, {type: "danger"});
                    }
                }
            });
        });

        function validateBirthForm() {
            if ($.fn.validate && !$('#birth_edit_form').valid()) {
                return false;
            }

            var time = $.trim($('#birth_edit_form #birthtime').val());
            var weight = parseFloat($('#birth_edit_form #babyWeight').val());
            var required_fields = ['deliveryDetail', 'babyBirthDate', 'birthtime', 'babyWeight', 'babygender', 'deliverytype', 'treatby'];

            for (var i = 0; i < required_fields.length; i++) {
                if ($.trim($('#birth_edit_form #' + required_fields[i]).val()) === '') {
                    $.notify({title: "Birth:", message: 'Please fill all required fields.', icon: 'fa fa-remove'}, {type: "danger"});
                    return false;
                }
            }

            if (!/^([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/.test(time)) {
                $.notify({title: "Birth:", message: 'Enter birth time in HH:MM format.', icon: 'fa fa-remove'}, {type: "danger"});
                return false;
            }

            if (isNaN(weight) || weight <= 0 || weight >= 10) {
                $.notify({title: "Birth:", message: 'Enter a valid baby weight below 10.', icon: 'fa fa-remove'}, {type: "danger"});
                return false;
            }

            return true;
        }

        function normalizeBirthDate(value) {
            var match = String(value || '').match(/\d{4}-\d{2}-\d{2}/);
            return match ? match[0] : value;
        }

        function normalizeBirthTime(timeValue, dateValue) {
            var explicitTime = String(timeValue || '').match(/([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/);
            if (explicitTime) {
                return explicitTime[0];
            }
            var embeddedTime = String(dateValue || '').match(/([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/);
            return embeddedTime ? embeddedTime[0] : '';
        }

        function normalizeBabyWeight(value) {
            var match = String(value || '').match(/\d+(\.\d+)?/);
            return match ? match[0] : value;
        }

        function getRowData(element) {
            var row = $(element).closest('tr');
            if (row.hasClass('child')) {
                row = row.prev();
            }
            return patient_table ? patient_table.row(row).data() : null;
        }
    });
</script>
