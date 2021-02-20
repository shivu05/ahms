<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"> Department list:</h3></div>
            <div class="box-body">
                <div class="col-md-4">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Sl. No</th>
                                <th>Department name</th>
                                <th>% required</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($depts)) {
                                $i = 1;
                                foreach ($depts as $dept) {
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td>' . $dept['department'] . '</td>';
                                    echo '<td style="text-align:right;">' . $dept['percentage'] . '</td>';
                                    echo '<td><center><i style="margin-right:15px !important; cursor:pointer;" data-name="' . $dept['department'] . '" data-per="' . $dept['percentage'] . '" class="fa fa-pencil text-primary edit_dept" data-id="' . $dept['ID'] . '" aria-hidden="true"></i></center></td>';
                                    echo '</tr>';
                                    $i++;
                                }
                            } else {
                                echo '<tr><td colspan=2>No departments found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal fade" id="edit_modal_box" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit department </h4>
            </div>
            <div class="modal-body" style="margin: 0 10px 0 10px;">
                <form class="form-horizontal" method="POST" name="edit_dept_form" id="edit_dept_form">
                    <div class="form-group">
                        <label for="dept_name">Department</label>
                        <input type="text" name="dept_name" id="dept_name" readonly="readonly" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="week_day">Percentage:</label>
                        <input type="type" name="perc" id="perc" class="form-control"/>
                        <input type="hidden" name="dept_id" id="dept_id" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="edit_btn" id="edit_btn">Update</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.edit_dept').on('click', function () {
            var per = $(this).data('per');
            $('#perc').val(per);
            $('#dept_id').val($(this).data('id'));
            $('#dept_name').val($(this).data('name'));
            $('#edit_modal_box').modal('show');
        });
        $('#edit_modal_box').on('click', '#edit_btn', function () {
            var form_data = $('#edit_dept_form').serializeArray();
            $.ajax({
                url: base_url + 'master/department/update_dept_percentage',
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success: function (response) {
                    if (response.status) {
                        $('#edit_modal_box').modal('hide');
                        $.notify({
                            title: "Updated:",
                            message: "Data updated successfully",
                            icon: 'fa fa-check'
                        }, {
                            type: "success",
                            placement: {
                                from: "top",
                                align: "center"
                            }
                        });
                        window.location.reload();
                    } else {
                        $('#edit_modal_box').modal('hide');
                        $.notify({
                            title: "Failed:",
                            message: "Failed to update data please try again",
                            icon: 'fa fa-times'
                        }, {
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "center"
                            }
                        });
                    }
                }
            });
        });
    });
</script>