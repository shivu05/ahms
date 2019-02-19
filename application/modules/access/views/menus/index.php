<style type="text/css">

    .perm_order{
        text-align: center;
    }
    .acess_perms{
        text-align: center;
    }
    .edit_btn{
        text-align: center;
        cursor: pointer;
    }
    .perm_status{
        text-align: center;
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    var columns = [
        {
            title: 'Description',
            class: 'perm_desc',
            target: 0,
            data: function (item) {
                return item.perm_desc;
            }
        }, {
            title: 'Code',
            class: 'perm_code',
            target: 1,
            data: function (item) {
                return item.perm_code;
            }
        }, {
            title: 'Parent menu',
            class: 'perm_parent',
            target: 2,
            data: function (item) {
                return item.parent_perm_code;
            }
        },
        {
            title: 'Order',
            class: 'perm_order',
            target: 3,
            data: function (item) {
                return item.perm_order;
            }
        }, {
            title: 'URL',
            class: 'perm_url',
            target: 4,
            data: function (item) {
                return item.perm_url;
            }
        }, {
            title: 'Status',
            class: 'perm_status',
            target: 5,
            data: function (item) {
                var icon = '<h4><i class="fa fa-remove text-danger" title="Inactive"></i></h4>';
                if (item.perm_status == 'Active') {
                    icon = '<h4><i class="fa fa-check text-success" title="Active"></i></h4>';
                }
                return icon;
            }
        },
        {
            title: 'Edit',
            class: 'edit_btn',
            target: 7,
            data: function (item) {
                return '<i class="btn btn-info fa fa-pencil btn_edit_menu" data-perm_id="' + item.perm_id + '"><i>';
            }
        },
        {
            title: 'Manage',
            class: 'acess_perms',
            target: 8,
            data: function (item) {
                return '<i class="btn btn-warning btn_manage_menu fa fa-cog" data-perm_name="' + item.perm_desc + '" data-perm_id="' + item.perm_id + '"></i>';
            }
        },
        {
            title: 'Last updated',
            class: 'last_updated_id',
            target: 6,
            data: function (item) {
                return item.last_updated_date;
            }
        }

    ];
</script>
<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body">
                <form id="search_menu_form" method="POST">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pwd" id="course_code_label">Parent menu:</label>
                        <div class="col-sm-9">
                            <select name="menu_p" id="menu_p" class="chosen-select form-control" style="width: 62%;">
                                <option value="">Select parent menu</option>
                                <?php echo $parent_menus; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 button_margin col-sm-offset-3">
                            <button type="button" class="btn  btn-success btn-block" id="show_eval_status" style="width: 94%;">Show menus</button>
                        </div>
                    </div>
                </form>
                <div id="patient_details">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right" id="add_new_menu">Add menu</button>
                    </div>
                    <table id="menu_table" class="table table-hover table-bordered dataTable" cellspacing="0" cellpadding="0" width="100%"></table>
                </div
            </div>
            <div id="patient_statistics" class="col-12"></div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="add_menu_msg"></div>
                <form id="add_menu_form" name="add_menu_form" method="POST" class="form-signin">
                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" id="is_child" name="is_child" value="1"><b> Is child?</b>
                        </label>
                    </div>
                    <div class="form-group" style="display: none;" id="parent_menu_dropdown">
                        <label for="parent_menu">Parent menu:</label>
                        <select name="parent_menu" id="parent_menu" class="chosen-select required form-control">
                            <option value="">Select parent menu</option>
                            <?php
                            if (!empty($menus_list->data)) {
                                foreach ($menus_list->data as $menu) {
                                    echo "<option value='" . $menu->perm_id . "'>" . $menu->perm_desc . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu_title">Menu title:</label>
                        <input type="text" class="form-control required" id="menu_title" name="menu_title" placeholder="Menu title">
                    </div>
                    <div class="form-group">
                        <label for="menu_code">Unique code:</label>
                        <input type="text" class="form-control required" id="menu_code" name="menu_code" placeholder="Menu code">
                    </div>
                    <div class="form-group">
                        <label for="menu_order">Order:</label>
                        <input type="text" class="form-control required" id="menu_order" name="menu_order" placeholder="Menu order">
                    </div>
                    <div class="form-group">
                        <label for="menu_url">URL:</label>
                        <input type="text" class="form-control required" id="menu_url" name="menu_url" placeholder="Menu url">
                    </div>
                    <div class="form-group">
                        <label class="radio-inline">
                            <input type="radio" class="required" name="menu_status" id="menu_status_act" value="Active" checked="checked"> Active
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="required" name="menu_status" id="menu_status_inact" value="Inactive"> Inactive
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_menu_item">Save</button>
                <button type="button" class="btn btn-primary" id="update_menu_item">Update</button>
                <button type="button" class="btn cancel_btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="accessPermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Access management</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="accessPermModalMSG"></div>
                <form name="accessPermForm" id="accessPermForm" method="POST">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="update_menu_item_perm">Update</button>
                <button type="button" class="btn btn-default" style="background: gray;color: white;" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var dt_table;
    var url = base_url + 'access/menus/get_sub_menus';
    //var url = base_url + 'api/menu_management/get_application_menus';

    $(document).ready(function () {
        dt_table = $('#menu_table').DataTable({
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
                'url': url,
                'type': 'POST',
                'dataType': 'json',
                'data': function (d) {
                    return $.extend({}, d, {
                        "search_form": $('#search_menu_form').serializeArray()
                    });
                },
                drawCallback: function (response) {}
            },
            order: [[0, 'desc']],
            info: true,
            sScrollX: true
        });
        $('#show_eval_status').on('click', function () {
            dt_table.draw();
        });

        $('#add_new_menu').on('click', function () {
            reset_form();
            $('#menu_code').removeAttr('readonly');
            $('#addMenuModal #save_menu_item').show();
            $('#addMenuModal #update_menu_item').hide();
            $('#addMenuModal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });

        $('#addMenuModal #add_menu_form').on('change', '#is_child', function () {
            if ($('#addMenuModal #add_menu_form #is_child').is(':checked')) {
                $('#addMenuModal #add_menu_form #parent_menu_dropdown').show();
            } else {
                $('#addMenuModal #add_menu_form #parent_menu_dropdown').hide();
            }
        });

        $('#addMenuModal #add_menu_form #menu_code').keyup(function () {
            $(this).val($(this).val().replace(/ /g, "_").toUpperCase());
        });
        var is_edit = false;
        var menu_form_validator = $('#add_menu_form').validate({
            rules: {
                menu_code: {
                    required: true,
                    remote: {
                        url: base_url + "access/menus/check_menu_code",
                        type: "post",
                        data: {
                            menu_code: function () {
                                return $("#menu_code").val();
                            }
                        }
                    }
                },
                menu_order: {
                    required: true,
                    remote: {
                        url: base_url + "access/menus/check_menu_order",
                        type: "post",
                        data: {
                            menu_order: function () {
                                return $("#menu_order").val();
                            },
                            parent: function () {
                                return $('#parent_menu').val();
                            },
                            is_edit: function () {
                                return is_edit;
                            }
                        }
                    }
                }
            },
            messages: {
                menu_code: {
                    remote: "This value already in exists",
                },
                menu_order: {
                    remote: "This value already in exists",
                }
            },
            highlight: function (input) {
                $(input).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (input) {
                $(input).closest('.form-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').append(error);
            }
        });

        $('#addMenuModal .modal-footer').on('click', '#save_menu_item', function (e) {
            if ($('#add_menu_form').valid()) {
                $.ajax({
                    url: base_url + 'access/menus/save_menu',
                    type: 'POST',
                    data: $('#add_menu_form').serializeArray(),
                    success: function (res) {
                        console.log(res);
                        dt_table.draw();
                        $('#addMenuModal .modal-body #add_menu_msg').html('<span style="color:green;">Saved successfully</span>');
                        setTimeout(function () {
                            $('#addMenuModal').modal('hide');
                        }, 2000);
                    }
                });
            } else {
                $('#addMenuModal .modal-body #add_menu_msg').html('<span style="color:red;">Error! Please check the input provided</span>');
            }
            e.preventDefault();
        });
        $('#addMenuModal .modal-footer').on('click', '#update_menu_item', function (e) {
            if ($('#add_menu_form').valid()) {
                $.ajax({
                    url: base_url + 'access/menus/update_menu',
                    type: 'POST',
                    data: $('#add_menu_form').serializeArray(),
                    success: function (res) {
                        console.log(res);
                        dt_table.draw();
                        $('#addMenuModal .modal-body #add_menu_msg').html('<span style="color:green;">Updated successfully</span>');
                        setTimeout(function () {
                            $('#addMenuModal').modal('hide');
                        }, 2000);
                    }
                });
            } else {
                $('#addMenuModal .modal-body #add_menu_msg').html('<span style="color:red;">Error! Please check the input provided</span>');
            }
            e.preventDefault();
        });

        function reset_form() {
            menu_form_validator.resetForm();
            document.getElementById("add_menu_form").reset();
            $('input').removeClass('has-error');
            $('#addMenuModal .modal-body #add_menu_msg').html('');
        }

        //edit functionalities
        $('#menu_table').on('click', '.btn_edit_menu', function (e) {
            reset_form();
            $("#menu_code").rules("remove");
            $('#addMenuModal #myModalLabel').html('Update menu details');
            $('#addMenuModal #update_menu_item').show();
            $('#addMenuModal #save_menu_item').hide();
            var perm_id = $(this).data('perm_id');
            $.ajax({
                url: base_url + 'access/menus/get_menu_details',
                type: 'POST',
                dataType: 'json',
                data: {'perm_id': perm_id},
                success: function (res) {
                    if (res.num_rows > 0) {
                        $.each(res.data, function (item) {
                            if (res.data[item].perm_parent != '0') {
                                $('#is_child').prop('checked', true);
                                $('#parent_menu').val(res.data[item].perm_parent);
                                $('#parent_menu_dropdown').show();

                            } else {
                                $('#is_child').prop('checked', false);
                                $('#parent_menu_dropdown').hide();
                                $('#parent_menu').val('');
                            }
                            $('#menu_title').val(res.data[item].perm_desc);
                            $('#menu_code').val(res.data[item].perm_code);
                            $('#menu_code').attr('readonly', 'readonly');
                            $('#menu_order').val(res.data[item].perm_order);
                            is_edit = res.data[item].perm_order;
                            $('#menu_url').val(res.data[item].perm_url);
                            if (res.data[item].perm_status == 'Active') {
                                $("#menu_status_act").prop("checked", true);
                            } else {
                                $("#menu_status_inact").prop("checked", true);
                            }
                        });
                        $('#addMenuModal').modal('show');
                    }
                }
            });
        });

        $('#menu_table').on('click', '.btn_manage_menu', function () {
            var perm_id = $(this).data('perm_id');
            var perm_name = $(this).data('perm_name');
            $('#accessPermModalMSG').html('');
            $('#accessPermModal #myModalLabel').html('Access permissions for the menu: <b>' + perm_name + '</b>');
            $.ajax({
                url: base_url + 'access/menus/get_menu_access_permissions',
                type: 'POST',
                dataType: 'json',
                data: {'perm_id': perm_id},
                success: function (res) {
                    var content = '<input type="hidden" name="count" id="count" value="' + res.data.length + '"/>';
                    content += '<input type="hidden" name="perm_id" id="perm_id" value="' + perm_id + '"/>';
                    content += '<table class="table table-bordered">';
                    content += '<tr>';
                    content += '<th>Role</th>';
                    content += '<th>Permission</th>';
                    content += '<th>Access rights</th>';
                    content += '</tr>';
                    $.each(res.data, function (i) {
                        content += '<tr>';
                        content += '<td><input type="hidden" name="role_info_' + i + '" id="role_info" value="' + res.data[i].role_id + '">'
                            + res.data[i].role_name + '</td>';
                        var chk = '';
                        if (res.data[i].perm_id != '0' && res.data[i].status == 'Active') {
                            chk = 'checked=checked';
                        }
                        var checkbox_perm = '<input type="checkbox" name="is_enabled_' + i + '" id="is_enabled_" ' + chk + ' value="' + res.data[i].perm_id + '">';

                        content += '<td><center>' + checkbox_perm + '</center></td>';
                        var chk1 = '', chk2 = '';
                        if (res.data[i].access_perm == '1') {
                            chk1 = 'checked="checked"';
                        } else if (res.data[i].access_perm == '2') {
                            chk2 = 'checked="checked"';
                        }
                        var checkboxes = '<input type="radio" name="rw_perm_' + i + '" id="rw_perm" ' + chk1 + ' value="1" style="margin-left: 5%;"> &nbsp;&nbsp;<i title="Read only" class="text-primary glyphicon glyphicon-eye-open"></i>';
                        checkboxes += '<input type="radio" name="rw_perm_' + i + '" id="rw_perm" ' + chk2 + ' value="2" style="margin-left: 15%;"> &nbsp;&nbsp;<i title="Read and wrire" class="text-warning glyphicon glyphicon-edit"></i>';
                        content += '<td>' + checkboxes + '</td>';
                        content += '</tr>';
                    });
                    content += '</table>';
                    $('#accessPermForm').html(content);
                },
                error: function (err) {

                }
            });
            $('#accessPermModal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });
        $('#update_menu_item_perm').on('click', function () {
            $.ajax({
                url: base_url + 'access/menus/update_menu_access_permissions',
                type: 'POST',
                data: $('#accessPermForm').serializeArray(),
                success: function (res) {
                    if (res) {
                        $('#accessPermModalMSG').html('<span style="color:green;">updated successfully</span>');
                        setTimeout(function () {
                            $('#accessPermModal').modal('hide');
                        }, 2000);
                    }
                }
            });
        });
    });
</script>