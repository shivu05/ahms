<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> Medicine Management:</h3>
                <button class="btn btn-sm btn-primary pull-right" onclick="addMedicine()">Add Medicine</button>
            </div>
            <div class="box-body">
                <div class="">
                    <table id="medicines_table" class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Modal -->
                <div id="medicineModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add/Edit Medicine</h4>
                            </div>
                            <div class="modal-body">
                                <form id="medicineForm">
                                    <input type="hidden" name="id">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Brand</label>
                                        <input type="text" name="brand" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Stock</label>
                                        <input type="number" name="stock" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="saveMedicine()">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var table;
    $(document).ready(function () {
        table = $('#medicines_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('pharmacy/medicines/fetch_medicines'); ?>",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "brand" },
                { "data": "price" },
                { "data": "stock" },
                { "data": "expiry_date" },
                {
                    "data": null, "render": function (data, type, row) {
                        return '<button class="btn btn-primary" onclick="editMedicine(' + row.id + ')">Edit</button>' +
                            ' <button class="btn btn-danger" onclick="deleteMedicine(' + row.id + ')">Delete</button>';
                    }
                }
            ]
        });
    });

    function addMedicine() {
        $('#medicineForm')[0].reset();
        $('#medicineModal').modal('show');
    }

    function saveMedicine() {
        var url = $('#medicineForm input[name="id"]').val() ? '<?php echo site_url('medicines/update'); ?>' : '<?php echo site_url('medicines/add'); ?>';
        $.ajax({
            url: url,
            type: 'POST',
            data: $('#medicineForm').serialize(),
            success: function (data) {
                $('#medicineModal').modal('hide');
                table.ajax.reload();
            },
            error: function (xhr) {
                alert('Error saving data');
            }
        });
    }

    function editMedicine(id) {
        $.ajax({
            url: '<?php echo site_url('pharmacy/medicines/edit/'); ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                $('#medicineForm input[name="id"]').val(data.id);
                $('#medicineForm input[name="name"]').val(data.name);
                $('#medicineForm input[name="brand"]').val(data.brand);
                $('#medicineForm input[name="price"]').val(data.price);
                $('#medicineForm input[name="stock"]').val(data.stock);
                $('#medicineForm input[name="expiry_date"]').val(data.expiry_date);
                $('#medicineModal').modal('show');
            },
            error: function (xhr) {
                alert('Error fetching data');
            }
        });
    }

    function deleteMedicine(id) {
        if (confirm('Are you sure you want to delete this medicine?')) {
            $.ajax({
                url: '<?php echo site_url('pharmacy/medicines/delete/'); ?>' + id,
                type: 'POST',
                success: function (data) {
                    table.ajax.reload();
                },
                error: function (xhr) {
                    alert('Error deleting data');
                }
            });
        }
    }
</script>