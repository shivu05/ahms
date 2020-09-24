<style>
    .modal-body{
        margin: 0 10px 0 10px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Doctors duty chart:</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('master/doctors/export_duty_chart_pdf'); ?>">
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Day:</label>
                        <select class="form-control required" name="day" id="day">
                            <option value="">Choose day</option>
                            <?php
                            if (!empty($week_days)) {
                                foreach ($week_days as $day) {
                                    echo '<option value="' . $day['week_day'] . '">' . $day['week_day'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Name</label>
                        <input class="form-control" type="text" placeholder="Enter name" name="name" id="name">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Choose</option>
                            <?php
                            if (!empty($roles)) {
                                foreach ($roles as $role) {
                                    echo '<option value="' . $role['role_id'] . '">' . $role['role_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-6 align-self-end">
                        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                        <div class="btn-group" role="group" id="export">
                            <button class="btn btn-info btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-secondary btn-sm" type="button" id="add"><i class="fa fa-fw fa-lg fa-plus-circle"></i> Add doctor's duty</button>
                    </div>
                </form>
                <div id="user_details">
                    <table class="table table-bordered table-hover table-striped dataTable" id="users_table" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal fade" id="add_duty_modal_box" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Doctors duty</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" name="add_duty_form" id="add_duty_form" action="<?php echo base_url('add-purchase-type'); ?>">
                    <div class="form-group">
                        <label class="control-label">Department:</label>
                        <select class="form-control required required" name="user_department" id="user_department">
                            <option value="">Choose department</option>
                            <?php
                            if (!empty($department_list)) {
                                foreach ($department_list as $dept) {
                                    echo '<option value="' . $dept['dept_unique_code'] . '">' . $dept['department'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Doctor name:</label>
                        <select class="form-control chosen required" name="doctor_name" id="doctor_name">
                            <option value="">Choose doctor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Duty day:</label>
                        <select class="form-control required" name="week_day" id="week_day">
                            <option value="">Choose day</option>
                            <?php
                            if (!empty($week_days)) {
                                foreach ($week_days as $day) {
                                    echo '<option value="' . $day['week_id'] . '">' . $day['week_day'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" name="add_btn" id="add_btn">Save duty</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal modal fade" id="edit_duty_modal_box" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Doctors duty</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" name="edit_duty_form" id="edit_duty_form">
                    <div class="form-group">
                        <label class="control-label">Department:</label>
                        <select class="form-control required required" name="user_dept" id="user_dept" required="required">
                            <option value="">Choose department</option>
                            <?php
                            if (!empty($department_list)) {
                                foreach ($department_list as $dept) {
                                    echo '<option value="' . $dept['dept_unique_code'] . '">' . $dept['department'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Doctor name:</label>
                        <select class="form-control chosen required" name="doctor_name" id="doctor_name" required="required">
                            <option value="">Choose doctor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Duty day:</label>
                        <select class="form-control required" name="week_day" id="week_day" required="required">
                            <option value="">Choose day</option>
                            <?php
                            if (!empty($week_days)) {
                                foreach ($week_days as $day) {
                                    echo '<option value="' . $day['week_id'] . '">' . $day['week_day'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <input type="hidden" name="edit_duty_id" id="edit_duty_id" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" name="edit_btn" id="edit_btn">Update duty</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style type="text/css">
    .status{
        width: 50px !important;
        text-align: center;
    }
    .delete{
        text-align: center !important;
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {

        $('#search_form').on('click', '#search', function () {
            user_table.clear();
            user_table.draw();
        });

        $('#search_form #export').on('click', '#export_to_pdf', function () {
            $('#search_form').submit();
        });

        $('#search_form #export').on('click', '#export_to_xls', function () {
            $('.loading-box').css('display', 'block');
            var form_fields = $('#search_form').serialize();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('export-duty-doctors-xls') ?>",
                data: form_fields,
                dataType: 'json'
            }).done(function (data) {
                download(data.file, data.file_name, 'application/octet-stream');
                $('.loading-box').css('display', 'none');
            });
        });


        $('#search_form').on('click', '#add', function () {
            $('#add_duty_modal_box').modal('show');
        });

        $('#add_duty_form').on('change', '#user_department', function () {
            var dept = $('#user_department').val();
            $.ajax({
                url: base_url + 'master/doctors/get_doctors_by_dept',
                type: 'POST',
                dataType: 'json',
                data: {'dept': dept},
                success: function (response) {
                    console.log(response);
                    if (response.data.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.data, function (i) {
                            option += '<option value="' + response.data[i].ID + '">' + response.data[i].user_name + '</option>';
                        });
                        $('#doctor_name').html(option);
                    }
                }
            });
        });
        var edit_doc_id = '';
        $('#edit_duty_modal_box').on('change', '#user_dept', function () {
            var dept = $('#user_dept').val();
            $.ajax({
                url: base_url + 'master/doctors/get_doctors_by_dept',
                type: 'POST',
                dataType: 'json',
                data: {'dept': dept},
                success: function (response) {
                    console.log(response);
                    if (response.data.length > 0) {
                        var option = '<option value="">Choose doctor</option>';
                        $.each(response.data, function (i) {
                            var selected = '';
                            if (edit_doc_id == response.data[i].ID) {
                                selected = 'selected="selected"';
                            }
                            option += '<option value="' + response.data[i].ID + '" ' + selected + '>' + response.data[i].user_name + '</option>';
                        });
                        $('#edit_duty_modal_box #doctor_name').html(option);
                    }
                }
            });
        });

        var validator = $('#add_duty_form').validate();
        $('#add_duty_modal_box').on('click', '#add_btn', function () {
            if ($('#add_duty_form').valid()) {
                var form_data = $('#add_duty_form').serializeArray();
                $.ajax({
                    url: base_url + 'master/doctors/save_doctors_duty',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (response) {
                        validator.resetForm();
                        $('#add_duty_form').trigger("reset");
                        if (response == 'exists') {
                            $('#add_duty_modal_box').modal('hide');
                            $.notify({
                                title: "Doctors duty:",
                                message: "Doctor already exists for same day",
                                icon: 'fa fa-times',
                            }, {
                                type: "danger",
                            });
                        } else {
                            $('#add_duty_modal_box').modal('hide');
                            $.notify({
                                title: "Doctors duty:",
                                message: "Added successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            user_table.clear();
                            user_table.draw();
                        }
                    }
                });
            } else {
                $.notify({
                    title: "Doctors duty:",
                    message: "Failed to add please try again.",
                    icon: 'fa fa-times'
                }, {
                    type: "danger",
                    placement: {
                        from: "bottom"
                    },
                });
            }

        });

        var columns = [
            {
                title: "Name",
                class: "name",
                data: function (item) {
                    return item.user_name;
                }
            },
            {
                title: "Department",
                class: "email",
                data: function (item) {
                    return item.user_department;
                }
            },
            {
                title: "Duty day",
                class: "mobile",
                data: function (item) {
                    return item.week_day;
                }
            },
            {
                title: "Last updated",
                class: "mobile",
                data: function (item) {
                    return item.added_date;
                }
            },
            {
                title: "Action",
                class: "delete",
                data: function (item) {
                    console.log(item);
                    return '<i style="margin-right:15px !important;" data-week_id="' + item.week_id + '" data-dept="' + item.user_department + '" data-name="' + item.user_name + '" data-doc_id="' + item.doc_id + '" class="fa fa-pencil text-primary edit_duty" data-id="' + item.id + '" aria-hidden="true"></i>' +
                            '<i class="fa fa-trash-o error delete_duty"  data-id="' + item.id + '" aria-hidden="true"></i>';
                }
            }
        ];


        var user_table = $('#users_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [2]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No duty doctors found</div>",
                sEmptyTable: "<div class='no_records'>No duty doctors found</div>",
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
                'url': base_url + 'master/doctors/get_doctors_list',
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
        var duty_id = '';
        $('#users_table tbody').on('click', '.delete_duty', function () {
            duty_id = $(this).data("id");
            $('#confirmation_modal_box .modal-title').html('Delete confirmation');
            $('#confirmation_modal_box .modal-body').html('Are you sure you want to delete this record?');
            $('#confirmation_modal_box').modal('show');
        });

        $('#users_table tbody').on('click', '.edit_duty', function () {
            duty_id = $(this).data("id");
            $('#edit_duty_modal_box #user_dept').val($(this).data("dept")).trigger('change');
            $('#edit_duty_modal_box #edit_duty_id').val(duty_id);
            edit_doc_id = $(this).data("doc_id");
            $('#edit_duty_modal_box #week_day').val($(this).data("week_id")).trigger('change');
            $('#edit_duty_modal_box').modal('show');
        });

        $('#confirmation_modal_box').on('click', '#btn-ok', function () {
            $.ajax({
                url: base_url + 'master/doctors/delete_doctors_duty',
                type: 'POST',
                dataType: 'json',
                data: {duty_id: duty_id},
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        $('#confirmation_modal_box').modal('hide');
                        $.notify({
                            title: "Deleted:",
                            message: "Doctor data deleted successfully",
                            icon: 'fa fa-check',
                        }, {
                            type: "success",
                        });
                    } else {
                        $('#confirmation_modal_box').modal('hide');
                        $.notify({
                            title: "Doctors duty:",
                            message: "Failed to delete data please try again",
                            icon: 'fa fa-times',
                        }, {
                            type: "danger",
                        });

                    }
                    user_table.clear();
                    user_table.draw();
                }
            });
        });
        $('#edit_duty_modal_box').on('click', '#edit_btn', function () {
            var form_data = $('#edit_duty_modal_box #edit_duty_form').serializeArray();
            $.ajax({
                url: base_url + 'master/doctors/edit_doctors_duty',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        $('#edit_duty_modal_box').modal('hide');
                        $.notify({
                            title: "Updated:",
                            message: "Doctor duty data updated successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                    } else {
                        $('#edit_duty_modal_box').modal('hide');
                        $.notify({
                            title: "Doctors duty:",
                            message: "Failed to update data please try again",
                            icon: 'fa fa-times'
                        }, {
                            type: "danger"
                        });
                    }
                    user_table.clear();
                    user_table.draw();
                }
            });
        });
    });
</script>