<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Users list:</h3></div>
            <div class="box-body">
                <form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo base_url('patient/patient/export_patients_list_pdf'); ?>">
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Email:</label>
                        <input class="form-control form-control-sm" type="text" placeholder="Enter Email" name="email" id="email" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Name</label>
                        <input class="form-control form-control-sm" type="text" placeholder="Enter name" name="name" id="name">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label sr-only">Role</label>
                        <select name="role" id="role" class="form-control form-control-sm">
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
                            <button type="button" class="btn btn-info btn-sm"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                                <li><a class="dropdown-item" href="#" id="export_to_xls">.xls</a></li>
                            </ul>
                        </div>
                        <a href="<?php echo base_url('add-user'); ?>" class="btn btn-primary btn-sm" type="button" id="add"><i class="fa fa-fw fa-lg fa-plus-circle"></i> Add user</a>
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
<style type="text/css">
    .status{
        width: 50px !important;
        text-align: center;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_form').on('click', '#search', function () {
            user_table.clear();
            user_table.draw();
        });
        var columns = [
            {
                title: "Sl.No",
                class: "name",
                data: function (item) {
                    return item.serial_number;
                }
            }, {
                title: "Name",
                class: "name",
                data: function (item) {
                    return item.user_name;
                }
            },
            {
                title: "Email",
                class: "email",
                data: function (item) {
                    return item.user_email;
                }
            },
            {
                title: "Mobile",
                class: "mobile",
                data: function (item) {
                    return item.user_mobile;
                }
            },
            {
                title: "Type",
                class: "mobile",
                data: function (item) {
                    return item.role_name;
                }
            },
            {
                title: "Department",
                class: "department",
                data: function (item) {
                    return item.user_department.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                        return letter.toUpperCase();
                    });
                    ;
                }
            },
            {
                title: "Status",
                class: "status",
                data: function (item) {
                    if (item.active == 1) {
                        return '<i class="fa fa-check-square text-success" style="font-size:20px" aria-hidden="true"></i>';
                    } else {
                        return '<i class="fa fa-check-square" aria-hidden="true"></i>';
                    }
                }
            },
            {
                title: "Action",
                class: "status",
                data: function (item) {
                    return '<i class="fa fa-pencil-square-o text-primary" style="font-size:16px" aria-hidden="true"></i>';
                }
            }
        ];


        var user_table = $('#users_table').DataTable({
            'columns': columns,
            'columnDefs': [
                {className: "", "targets": [2]}
            ],
            language: {
                sZeroRecords: "<div class='no_records'>No users found</div>",
                sEmptyTable: "<div class='no_records'>No users found</div>",
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
                'url': base_url + 'master/users/get_users_list',
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
    });
</script>