<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Pharmacy Management:</h3>
                <button class="pull-right btn btn-sm btn-primary" data-toggle="modal" data-target="#medicineModal"> <i class="fa fa-plus"></i> Add Medicine</button>
            </div>
            <div class="box-body">
                <div id="notification" class="alert" style="display:none;"></div>
                <table id="medicineTable" class="dataTable table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                            <th>Price</th>
                            <th>GST</th>
                            <th>Discount</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div id="medicineModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add/Edit Medicine</h4>
                        </div>
                        <div class="modal-body">
                            <form id="medicineForm">
                                <input type="hidden" name="id" id="medicine_id">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Batch No:</label>
                                    <input type="text" class="form-control" name="batch_no" id="batch_no" required>
                                </div>
                                <div class="form-group">
                                    <label>Expiry Date:</label>
                                    <input type="text" class="form-control date_picker" name="expiry_date" id="expiry_date" required>
                                </div>
                                <div class="form-group">
                                    <label>Price:</label>
                                    <input type="number" class="form-control" name="price" id="price" required>
                                </div>
                                <div class="form-group">
                                    <label>GST:</label>
                                    <input type="number" class="form-control" name="gst" id="gst" required>
                                </div>
                                <div class="form-group">
                                    <label>Discount:</label>
                                    <input type="number" class="form-control" name="discount" id="discount" required>
                                </div>
                                <div class="form-group">
                                    <label>Stock:</label>
                                    <input type="number" class="form-control" name="stock" id="stock" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="deleteModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Medicine</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this medicine?</p>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#medicineTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('pharmacy/fetch_medicines') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "render": function(data, type, row) {
                    return row[7];
                }
            }]
        });

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).closest('tr').find('.btn-edit').data('id');
            $.get("<?= base_url('pharmacy/edit_medicine/') ?>" + id, function(data) {
                $('#medicine_id').val(data.id);
                $('#name').val(data.name);
                $('#batch_no').val(data.batch_no);
                $('#expiry_date').val(data.expiry_date);
                $('#price').val(data.price);
                $('#gst').val(data.gst);
                $('#discount').val(data.discount);
                $('#stock').val(data.stock);
                $('#medicineModal').modal('show');
            }, 'json');
        });

        $(document).on('click', '.btn-delete', function() {
            var id = $(this).closest('tr').find('.btn-delete').data('id');
            $('#confirmDelete').data('id', id);
            $('#deleteModal').modal('show');
        });

        $('#confirmDelete').click(function() {
            var id = $(this).data('id');
            $.post("<?= base_url('pharmacy/delete_medicine') ?>", {
                id: id
            }, function(response) {
                $('#deleteModal').modal('hide');
                if (response.status === 'OK') {
                    $('#notification').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
                } else {
                    $('#notification').removeClass('alert-success').addClass('alert-danger').text(response.message).show();
                }
                table.ajax.reload();
                setTimeout(function() {
                    $('#notification').hide();
                }, 3000);
            }, 'json');
        });

        $('#medicineForm').submit(function(e) {
            e.preventDefault();
            $.post("<?= base_url('pharmacy/add_medicine') ?>", $(this).serialize(), function(response) {
                $('#medicineModal').modal('hide');
                table.ajax.reload();
            }, 'json');
        });
    });
</script>