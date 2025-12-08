<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-medkit"></i>
            Medicine Master
            <small>Manage Medicine Database</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li class="active">Medicines</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Medicine List</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#medicineModal">
                                <i class="fa fa-plus"></i> Add Medicine
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="medicinesTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Medicine Code</th>
                                        <th>Medicine Name</th>
                                        <th>Generic Name</th>
                                        <th>Category</th>
                                        <th>Manufacturer</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add/Edit Medicine Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Medicine</h4>
            </div>
            <form id="medicineForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Medicine Name *</label>
                                <input type="text" name="medicine_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Generic Name</label>
                                <input type="text" name="generic_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category *</label>
                                <select name="category_id" class="form-control chosen-select" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo $category['category_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Manufacturer *</label>
                                <select name="manufacturer_id" class="form-control chosen-select" required>
                                    <option value="">Select Manufacturer</option>
                                    <?php foreach ($manufacturers as $manufacturer): ?>
                                                <option value="<?php echo $manufacturer['id']; ?>"><?php echo $manufacturer['manufacturer_name']; ?></option>
                                            <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit_id" class="form-control chosen-select">
                                    <option value="">Select Unit</option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?php echo $unit['id']; ?>"><?php echo $unit['unit_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Medicine Type</label>
                                <select name="medicine_type" class="form-control">
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Ointment">Ointment</option>
                                    <option value="Drops">Drops</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Composition</label>
                                <textarea name="composition" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Strength</label>
                                <input type="text" name="strength" class="form-control" placeholder="e.g. 500mg">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Therapeutic Class</label>
                                <input type="text" name="therapeutic_class" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reorder Level</label>
                                <input type="number" name="reorder_level" class="form-control" value="10">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="prescription_required" value="1"> Prescription Required
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.chosen-select').chosen({
            width: '100%'
        });
        $('.chosen-select-deselect').chosen({
            allow_single_deselect: true
        });
    });
    $(document).ready(function () {
        // Initialize chosen selects
        $('.chosen-select').chosen();

        // Initialize DataTable
        var medicinesTable = $('#medicinesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '<?php echo site_url("pharmacy_pro/pharmacy/get_medicines_data"); ?>',
                "type": "POST"
            },
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "order": [[1, "asc"]],
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { "data": 3 },
                { "data": 4 },
                { "data": 5 },
                { "data": 6, "orderable": false }
            ]
        });

        // Handle form submission
        $('#medicineForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/save_medicine"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert(response.message);
                        $('#medicineModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('An error occurred while saving the medicine.');
                }
            });
        });

        // Row actions: Edit and Delete
        $('#medicinesTable').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/get_medicine"); ?>',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        var m = res.data;
                        $('#medicineModal .modal-title').text('Edit Medicine');
                        $('[name="medicine_id"]').remove();
                        $('#medicineForm').append('<input type="hidden" name="medicine_id" value="'+m.id+'">');
                        $('[name="medicine_name"]').val(m.medicine_name);
                        $('[name="generic_name"]').val(m.generic_name);
                        $('[name="category_id"]').val(m.category_id).trigger('chosen:updated');
                        $('[name="manufacturer_id"]').val(m.manufacturer_id).trigger('chosen:updated');
                        $('[name="unit_id"]').val(m.unit_id).trigger('chosen:updated');
                        $('[name="medicine_type"]').val(m.medicine_type);
                        $('[name="composition"]').val(m.composition);
                        $('[name="strength"]').val(m.strength);
                        $('[name="therapeutic_class"]').val(m.therapeutic_class);
                        $('[name="reorder_level"]').val(m.reorder_level);
                        if (m.prescription_required == 'yes') {
                            $('[name="prescription_required"]').prop('checked', true);
                        } else {
                            $('[name="prescription_required"]').prop('checked', false);
                        }
                        $('#medicineModal').modal('show');
                    } else {
                        alert(res.message);
                    }
                }
            });
        });

        $('#medicinesTable').on('click', '.btn-delete', function() {
            if (!confirm('Are you sure you want to delete this medicine?')) return;
            var id = $(this).data('id');
            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/delete_medicine"); ?>',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        alert(res.message);
                        medicinesTable.ajax.reload(null, false);
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    });
</script>