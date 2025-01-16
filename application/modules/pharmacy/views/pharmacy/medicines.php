<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-list"></i> Medicines Management:</h3> 
                <button class="pull-right btn btn-sm btn-primary" onclick="addMedicine()"><i class="fa fa-plus"></i> Add Medicine</button>
            </div>
            <div class="box-body">
                <table id="medicinesTable" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Expiry Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
                <!-- Bootstrap Modal -->
                <div class="modal fade" id="medicineModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Medicine Form</h4>
                            </div>
                            <div class="modal-body">
                                <form id="medicineForm">
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <input type="text" name="brand" id="brand" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" name="price" id="price" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" name="stock" id="stock" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" onclick="saveMedicine()" class="btn btn-primary">Save</button>
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
        table = $('#medicinesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('pharmacy/get_medicines'); ?>",
                "type": "POST"
            },
            "columns": [
                {"data": "0"},
                {"data": "1"},
                {"data": "2"},
                {"data": "3"},
                {"data": "4"},
                {"data": "5"}
            ]
        });
    });

    function addMedicine() {
        $('#medicineForm')[0].reset();
        $('#medicineModal').modal('show');
    }

    function saveMedicine() {
        $.ajax({
            url: "<?php echo site_url('pharmacy/save_medicine'); ?>",
            type: "POST",
            data: $('#medicineForm').serialize(),
            success: function (response) {
                response = JSON.parse(response);
                if (response.status) {
                    $('#medicineModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.errors);
                }
            }
        });
    }

    $('#medicinesTable').on('click', '.edit', function () {
        const id = $(this).data('id');
        $.getJSON("<?php echo site_url('pharmacy/get_medicine'); ?>" + "/" + id, function (data) {
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#brand').val(data.brand);
            $('#price').val(data.price);
            $('#stock').val(data.stock);
            $('#expiry_date').val(data.expiry_date);
            $('#medicineModal').modal('show');
        });
    });

    $('#medicinesTable').on('click', '.delete', function () {
        if (confirm("Are you sure to delete this record?")) {
            const id = $(this).data('id');
            $.post("<?php echo site_url('pharmacy/delete_medicine'); ?>" + "/" + id, function () {
                table.ajax.reload();
            });
        }
    });
</script>