<h2 class="text-center">Service Groups</h2>
<button class="btn btn-success" data-toggle="modal" data-target="#groupModal" onclick="resetForm()">Add New Group</button>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Group Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($service_groups as $group): ?>
            <tr>
                <td><?= $group['group_id'] ?></td>
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

<!-- Modal for Add/Edit -->
<div id="groupModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Service Group</h4>
            </div>
            <div class="modal-body">
                <form id="groupForm">
                    <input type="hidden" id="group_id" name="group_id">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input type="text" id="group_name" name="group_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="description" name="description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        $('#groupForm')[0].reset();
        $('#group_id').val('');
    }

    function editGroup(id) {
        $.post('<?= base_url("Service_groups/get_group") ?>', {group_id: id}, function (data) {
            const group = JSON.parse(data);
            $('#group_id').val(group.group_id);
            $('#group_name').val(group.group_name);
            $('#description').val(group.description);
            $('#groupModal').modal('show');
        });
    }

    function deleteGroup(id) {
        if (confirm('Are you sure you want to delete this group?')) {
            $.post('<?= base_url("Service_groups/delete_group") ?>', {group_id: id}, function () {
                location.reload();
            });
        }
    }

    $('#groupForm').on('submit', function (e) {
        e.preventDefault();
        $.post('<?= base_url("Service_groups/save_group") ?>', $(this).serialize(), function () {
            $('#groupModal').modal('hide');
            location.reload();
        });
    });
</script>
