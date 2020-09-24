<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Panchakarma procedures:</h3>
                <button class="btn btn-sm btn-primary pull-right" id="add_main_pancha"><i class="fa fa-plus"></i> Add main procedure</button>
                <button class="btn btn-sm btn-success pull-right" style="margin-right: 1%;" id="add_sub_pancha"><i class="fa fa-plus"></i> Add sub procedure</button></div>
            <div class="box-body">
                <table class="table table-bordered table-responsive" id="pancha_list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Procedure name</th>
                            <th>Sub Procedures</th>
                            <th>Last updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pancha_dropdown = '<option value="">Choose procedure</option>';
                        if (!empty($main_proc)) {
                            $i = 0;
                            $tbody = '';
                            foreach ($main_proc as $proc) {
                                $pancha_dropdown .= '<option value="' . $proc['id'] . '">' . $proc['proc_name'] . '</option>';
                                $tbody .= '<tr>';
                                $tbody .= '<td>' . ( ++$i) . '</td>';
                                $tbody .= '<td>' . $proc['proc_name'] . '</td>';
                                $tbody .= '<td width="30%;">' . prepare_pancha_table($proc) . '</td>';
                                $tbody .= '<td>' . $proc['last_modified_date'] . '</td>';
                                $tbody .= '<td><i class="fa fa-edit hand_cursor text-primary"></i> | <i class="fa fa-trash text-danger hand_cursor"></i></td>';

                                $tbody .= '</tr>';
                            }
                            echo $tbody;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal-add-main-procedure">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Panchakarma procedure</h4>
            </div>
            <div class="modal-body">
                <form name="panchakarma_proc_form" id="panchakarma_proc_form" method="POST">
                    <div class="form-group">
                        <label for="proc_name">Procedure name:</label>
                        <input type="text" class="form-control" name="proc_name" id="proc_name" placeholder="Procedure name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_proc_main">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade in" id="modal-add-sub-procedure">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Panchakarma sub procedure</h4>
            </div>
            <div class="modal-body">
                <form name="panchakarma_sub_proc_form" id="panchakarma_sub_proc_form" method="POST">
                    <div class="form-group">
                        <label for="proc_name">Procedure:</label>
                        <select name="proc_name" id="proc_name" class="form-control required">
                            <?= $pancha_dropdown ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sub_proc_name">Sub-Procedure name:</label>
                        <input type="text" name="sub_proc_name" id="sub_proc_name" class="form-control required" />
                    </div>
                    <div class="form-group">
                        <label for="no_of_treatment_days">No.of treatment days:</label>
                        <input type="text" name="no_of_treatment_days" id="no_of_treatment_days" class="form-control required" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_sub_proc_main">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var sub_validator = $('#panchakarma_sub_proc_form').validate();
        $('#add_main_pancha').on('click', function () {
            $('#modal-add-main-procedure').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });

        $('#add_sub_pancha').on('click', function () {
            sub_validator.resetForm();
            $('#modal-add-sub-procedure').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });

        $('#modal-add-main-procedure').on('click', '#save_proc_main', function () {
            var form_data = $('#panchakarma_proc_form').serializeArray();
            $.ajax({
                url: base_url + 'master/panchakarma/add_pancha_procedure',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $.notify({
                            title: "Panchakarma procedure:",
                            message: "Added successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success"
                        });
                        $('#modal-add-main-procedure').modal('hide');
                        window.location = base_url + 'master/panchakarma/list_procedures';
                    } else {
                        $.notify({
                            title: "Panchakarma procedure:",
                            message: "Failed to Add. Please try again",
                            icon: 'fa fa-cross'
                        }, {
                            type: "danger"
                        });
                    }
                }
            });
        });

        $('#modal-add-sub-procedure').on('click', '#save_sub_proc_main', function () {
            if ($('#panchakarma_sub_proc_form').valid()) {
                var form_data = $('#panchakarma_sub_proc_form').serializeArray();
                $.ajax({
                    url: base_url + 'master/panchakarma/add_sub_pancha_procedure',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            $.notify({
                                title: "Panchakarma procedure:",
                                message: "Added successfully",
                                icon: 'fa fa-check'
                            }, {
                                type: "success"
                            });
                            $('#modal-add-main-procedure').modal('hide');
                            window.location = base_url + 'master/panchakarma/list_procedures';
                        } else {
                            $.notify({
                                title: "Panchakarma procedure:",
                                message: "Failed to Add. Please try again",
                                icon: 'fa fa-cross'
                            }, {
                                type: "danger"
                            });
                        }
                    }
                });
            }
        });
    });
</script>