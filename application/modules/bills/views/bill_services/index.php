<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Bill Services: </h3>
            <a class="btn btn-success btn-sm pull-right float-right" style="margin-left: 1% !important;" href="<?= base_url('service-groups'); ?>"><i class="fa fa-plus"></i> Add Group</a>
            <button class="btn btn-success btn-sm pull-right float-right" data-toggle="modal" data-target="#serviceModal" onclick="resetForm()"><i class="fa fa-plus"></i> Add Service</button>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Service Name</th>
                        <th>Group Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($services as $service):
                        ?>
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><?= $service['service_name'] ?></td>
                            <td><?= $service['group_name'] ?></td>
                            <td><?= $service['price'] ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="editService(<?= $service['service_id'] ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $service['service_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div id="serviceModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Bill Service</h4>
            </div>
            <form id="serviceForm">
                <div class="modal-body">
                    <input type="hidden" id="service_id" name="service_id">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" id="service_name" name="service_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Group</label>
                        <select id="group_id" name="group_id" class="form-control" required>
                            <option value="">Choose</option>
                            <?php foreach ($service_groups as $group): ?>
                                <option value="<?= $group['group_id'] ?>"><?= $group['group_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" id="price" name="price" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="input-group pull-right float-right">
                        <button type="submit" class="btn btn-primary btn-sm ">Save</button>
                        <button type="reset" class="btn btn-danger btn-sm ">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Deletion</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this service?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="deleteBtn">Delete</button>
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        $('#serviceForm')[0].reset();
        $('#service_id').val('');
    }

    function editService(id) {
        $.post('<?= base_url("bills/bill_services/get_service") ?>', {service_id: id}, function (data) {
            const service = JSON.parse(data);
            $('#service_id').val(service.service_id);
            $('#service_name').val(service.service_name);
            $('#group_id').val(service.group_id);
            $('#price').val(service.price);
            $('#serviceModal').modal('show');
        });
    }

    function confirmDelete(id) {
        $('#deleteModal').modal('show');
        $('#deleteBtn').off('click').on('click', function () {
            $.post('<?= base_url("bills/bill_services/delete_service") ?>', {service_id: id}, function () {
                location.reload();
            });
        });
    }

    $('#serviceForm').on('submit', function (e) {
        e.preventDefault();
        $.post('<?= base_url("bills/bill_services/save_service") ?>', $(this).serialize(), function () {
            $('#serviceModal').modal('hide');
            location.reload();
        });
    });
</script>