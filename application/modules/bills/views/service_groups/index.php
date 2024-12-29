<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Service Groups: </h3>
            <a class="btn btn-primary btn-sm pull-right float-right" style="margin-left: 1% !important;" href="<?= base_url('bill-services'); ?>"><i class="fa fa-list"></i> List services</a>
            <button class="btn btn-success btn-sm pull-right float-right" data-toggle="modal" data-target="#groupModal" onclick="resetForm()"><i class="fa fa-plus"></i> Add Group</button>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($service_groups as $group):
                        ?>
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><?= $group['group_name'] ?></td>
                            <td><?= $group['description'] ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="editGroup(<?= $group['group_id'] ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteGroup(<?= $group['group_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div id="groupModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Service Group</h4>
            </div>
            <form id="groupForm">
                <div class="modal-body">
                    <input type="hidden" id="group_id" name="group_id">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input type="text" id="group_name" name="group_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="description" name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="input-group pull-right float-right">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>    
                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function resetForm() {
        $('#groupForm')[0].reset();
        $('#group_id').val('');
    }

    function editGroup(id) {
        $.post('<?= base_url("bills/service_groups/get_group") ?>', {group_id: id}, function (data) {
            const group = JSON.parse(data);
            $('#group_id').val(group.group_id);
            $('#group_name').val(group.group_name);
            $('#description').val(group.description);
            $('#groupModal').modal('show');
        });
    }

    function deleteGroup(id) {
        if (confirm('Are you sure you want to delete this group?')) {
            $.post('<?= base_url("bills/service_groups/delete_group") ?>', {group_id: id}, function () {
                location.reload();
            });
        }
    }

    $('#groupForm').on('submit', function (e) {
        e.preventDefault();
        $.post('<?= base_url("bills/service_groups/save_group") ?>', $(this).serialize(), function () {
            $('#groupModal').modal('hide');
            location.reload();
        });
    });
</script>