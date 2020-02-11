<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Panchakarma procedures:</h3>
                <button class="btn btn-sm btn-primary pull-right" id="add_main_pancha"><i class="fa fa-plus"></i> Add main procedure</button></div>
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
                        if (!empty($main_proc)) {
                            $i = 0;
                            $tbody = '';
                            foreach ($main_proc as $proc) {
                                $tbody .= '<tr>';
                                $tbody .= '<td>' . ( ++$i) . '</td>';
                                $tbody .= '<td>' . $proc['proc_name'] . '</td>';
                                $tbody .= '<td width="30%;">' . prepare_pancha_table($proc['sub_grp_name']) . '</td>';
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#add_main_pancha').on('click', function () {
            $('#modal-add-main-procedure').modal({
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
    });
</script>