<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Other procedure reference:</h3>
                <button class="btn btn-sm btn-success pull-right" style="margin-right: 1%;" data-backdrop="static" data-keyboard="false"  id="add_new"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered dataTable" id="physio_grid">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Procedure Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($proc_list)) {
                            $i = 0;
                            foreach ($proc_list as $row) {
                                $tr = '<tr>';
                                $tr .= '<td>' . ++$i . '</td>';
                                $tr .= '<td>' . $row['name'] . '</td>';
                                $tr .= '<td>' . (($row['status'] == '1') ? 'Active' : 'Inactive') . '</td>';
                                $tr .= '<td><i class="fa fa-edit hand_cursor text-primary edit_btn" data-id="' . $row['id'] . '"></i> | <i class="fa fa-trash hand_cursor text-danger delete_btn"  data-id="' . $row['id'] . '"></i></td>';
                                $tr .= '</tr>';
                                echo $tr;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="physio_add_modal_box" tabindex="-1" role="dialog" aria-labelledby="physio_add_modal_box_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="physio_add_modal_box_label">Add new Procedure</h4>
            </div>
            <div class="modal-body" id="lab_modal_body">
                <form action="" name="test_form" id="test_form" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label class="control-label">Procedure name:</label>
                            <input type="hidden" name="physi_id" id="physi_id"/>
                            <input class="form-control required" type="text" placeholder="Procedure name" name="physi_name" id="physi_name" required="required" autocomplete="off">
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
        $('#physio_grid').dataTable({
            'ordering': false
        });
        $('#add_new').on('click', function () {
            $('#physio_add_modal_box #physio_add_modal_box_label').html('Add new Physiotherapy');
            $('#test_form #physi_id').val('');
            $('#test_form #physi_name').val('');
            $('#physio_add_modal_box').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });
        $('#test_form').validate();
        $('#physio_add_modal_box').on('click', '#btn-ok', function () {
            if ($('#test_form').valid()) {
                var form_data = $('#test_form').serializeArray();
                $.ajax({
                    url: base_url + 'add-other-procedures',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        $('#physio_add_modal_box').modal('hide');
                        $.notify({
                            title: res.title,
                            message: res.msg,
                            icon: 'fa fa-check',
                        }, {
                            type: res.type,
                        });
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000);
                    },
                    error: function (res) {
                        console.log(res)
                    }
                });
            }
        });
        $('#physio_grid').on('click', '.edit_btn', function () {
            $('#physio_add_modal_box #physio_add_modal_box_label').html('Update Physiotherapy');
            var id = $(this).data('id');
            var name = $(this).closest('td').prev('td').prev('td').text();
            console.log(name);
            $('#test_form #physi_id').val(id);
            $('#test_form #physi_name').val(name);
            $('#physio_add_modal_box').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });
        $('#physio_grid').on('click', '.delete_btn', function () {
            var id = $(this).data('id');
            if (confirm('Are you sure want to delete?')) {
                $.ajax({
                    url: base_url + 'delete-other-procedures',
                    type: 'POST',
                    dataType: 'json',
                    data: {physi_id: id},
                    success: function (res) {
                        $('#physio_add_modal_box').modal('hide');
                        $.notify({
                            title: res.title,
                            message: res.msg,
                            icon: 'fa fa-check',
                        }, {
                            type: res.type,
                        });
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000);
                    },
                    error: function (res) {
                        console.log(res)
                    }
                });
            }
        });
    });
</script>