<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-book"></i> Medicine Management:</h3>
                <button class="btn btn-sm btn-primary pull-right" onclick="addMedicine()">Add Medicine</button>
            </div>
            <div class="box-body">
                <div class="">
                    <table id="medicines_table" class="table table-striped table-hover table-bordered dataTable" style="width:100%"></table>
                </div>

                <!-- Modal -->
                <div id="medicineModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg" style="width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add/Edit Medicine</h4>
                            </div>
                            <div class="modal-body">
                                <form id="medicineForm" class="">
                                    <input type="hidden" name="id">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Generic Name</label>
                                                <input type="text" name="generic_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Dosage</label>
                                                <input type="text" name="dosage" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Form</label>
                                                <select name="form" id="form" class="form-control select2" required style="width: 100%;">
                                                    <option value="">Choose type</option>
                                                    <!-- Ayurveda-Specific Medicine Forms -->
                                                    <option value="Churna">Churna (Herbal Powder)</option>
                                                    <option value="Vati">Vati (Tablets/Pills)</option>
                                                    <option value="Guggulu">Guggulu (Resin-based Formulation)</option>
                                                    <option value="Arishta">Arishta (Fermented Herbal Decoction)</option>
                                                    <option value="Asava">Asava (Fermented Herbal Infusion)</option>
                                                    <option value="Kashayam">Kashayam (Herbal Decoction)</option>
                                                    <option value="Lehya">Lehya (Herbal Paste/Jam like Chyawanprash)</option>
                                                    <option value="Bhasma">Bhasma (Herbo-mineral Ash Form)</option>
                                                    <option value="Ghrita">Ghrita (Medicated Ghee)</option>
                                                    <option value="Taila">Taila (Medicated Oil)</option>
                                                    <option value="Avaleha">Avaleha (Herbal Electuary/Thick Liquid Paste)</option>
                                                    <option value="Rasayana">Rasayana (Rejuvenation Therapy Formulation)</option>
                                                    <option value="Lepa">Lepa (External Paste Application)</option>
                                                    <option value="Gutikas">Gutikas (Ayurvedic Tablets)</option>
                                                    <option value="Siddha Taila">Siddha Taila (Special Medicated Oils)</option>
                                                    <option value="Paka">Paka (Herbal Confectionery)</option>
                                                    <!-- General Medicine Forms -->
                                                    <option value="Tablet">Tablet</option>
                                                    <option value="Capsule">Capsule</option>
                                                    <option value="Syrup">Syrup</option>
                                                    <option value="Injection">Injection</option>
                                                    <option value="Ointment">Ointment</option>
                                                    <option value="Cream">Cream</option>
                                                    <option value="Gel">Gel</option>
                                                    <option value="Drops">Drops</option>
                                                    <option value="Powder">Powder</option>
                                                    <option value="Suspension">Suspension</option>
                                                    <option value="Solution">Solution</option>
                                                    <option value="Suppository">Suppository</option>
                                                    <option value="Lotion">Lotion</option>
                                                    <option value="Patch">Patch</option>
                                                    <option value="Inhaler">Inhaler</option>
                                                    <option value="Spray">Spray</option>
                                                    <option value="Granules">Granules</option>
                                                    <option value="Tea">Tea</option>
                                                    <option value="Oil">Oil</option>
                                                    <option value="Tincture">Tincture</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category" id="category" class="form-control select2" required style="width: 100%;">
                                                    <option value="">Select Category</option>
                                                    <!-- Ayurveda-Specific Categories -->
                                                    <optgroup label="Ayurveda">
                                                        <option value="Rasayana">Rasayana (Rejuvenation Therapy)</option>
                                                        <option value="Shodhana">Shodhana (Detoxification)</option>
                                                        <option value="Balya">Balya (Strengthening Medicine)</option>
                                                        <option value="Deepana-Pachana">Deepana-Pachana (Digestive & Carminative)</option>
                                                        <option value="Medhya">Medhya (Brain & Nervous System Health)</option>
                                                        <option value="Hridya">Hridya (Heart Health)</option>
                                                        <option value="Pramehahara">Pramehahara (Diabetes Management)</option>
                                                        <option value="Arshoghna">Arshoghna (Piles Treatment)</option>
                                                        <option value="Jwarahara">Jwarahara (Fever Management)</option>
                                                        <option value="Vranaropaka">Vranaropaka (Wound Healing)</option>
                                                        <option value="Vatahara">Vatahara (Joint & Nerve Care)</option>
                                                        <option value="Pittahara">Pittahara (Acidic & Bile Disorders)</option>
                                                        <option value="Kaphahara">Kaphahara (Respiratory Health)</option>
                                                        <option value="Vrishya">Vrishya (Aphrodisiac & Fertility)</option>
                                                        <option value="Shukrala">Shukrala (Reproductive Health)</option>
                                                        <option value="Udara Roga">Udara Roga (Liver & Digestion)</option>
                                                        <option value="Kusthaghna">Kusthaghna (Skin & Hair Care)</option>
                                                        <option value="Agnivardhaka">Agnivardhaka (Metabolism Booster)</option>
                                                        <option value="Sothahara">Sothahara (Anti-inflammatory & Edema)</option>
                                                        <option value="Nidrajanana">Nidrajanana (Sleep Aid)</option>
                                                    </optgroup>
                                                    <!-- General Medicine Categories -->
                                                    <optgroup label="General Medicine">
                                                        <option value="Painkiller">Painkiller</option>
                                                        <option value="Antibiotic">Antibiotic</option>
                                                        <option value="Antiseptic">Antiseptic</option>
                                                        <option value="Antihistamine">Antihistamine (Allergy Medicine)</option>
                                                        <option value="Antacid">Antacid</option>
                                                        <option value="Antipyretic">Antipyretic (Fever Reducer)</option>
                                                        <option value="Antiviral">Antiviral</option>
                                                        <option value="Antifungal">Antifungal</option>
                                                        <option value="Antidiabetic">Antidiabetic</option>
                                                        <option value="Cardiovascular">Cardiovascular Medicine</option>
                                                        <option value="Hypertension">Hypertension Control</option>
                                                        <option value="Cholesterol">Cholesterol Management</option>
                                                        <option value="Gastrointestinal">Gastrointestinal Medicine</option>
                                                        <option value="Cough & Cold">Cough & Cold Medicine</option>
                                                        <option value="Respiratory">Respiratory Medicine</option>
                                                        <option value="Hormonal">Hormonal Therapy</option>
                                                        <option value="Vitamins & Supplements">Vitamins & Supplements</option>
                                                        <option value="Neurological">Neurological Medicine</option>
                                                        <option value="Dermatology">Dermatology (Skin Care)</option>
                                                        <option value="Eye Care">Eye Care</option>
                                                        <option value="Women's Health">Women's Health</option>
                                                        <option value="Men's Health">Men's Health</option>
                                                        <option value="Pediatric">Pediatric Medicine</option>
                                                        <option value="Oncology">Oncology (Cancer Treatment)</option>
                                                        <option value="Psychiatry">Psychiatric Medicine</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Manufacturer</label>
                                                <input type="text" name="manufacturer" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Unit Price</label>
                                                <input type="number" name="unit_price" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Reorder Level</label>
                                                <input type="number" name="reorder_level" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control"></textarea>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Storage Conditions</label>
                                                <textarea name="storage_conditions" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Side Effects</label>
                                                <textarea name="side_effects" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Interactions</label>
                                                <textarea name="interactions" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Requires Prescription</label>
                                                <input type="checkbox" name="requires_prescription">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Controlled Substance</label>
                                                <input type="checkbox" name="controlled_substance">
                                            </div>
                                        </div>
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
    $(document).ready(function() {
        var is_admin = '<?= $is_admin ?>';

        var columns = [{
                title: "Sl. No",
                class: "ipd_no",
                data: function(item) {
                    return item.id;
                }
            },
            {
                title: "Name",
                class: "opd_no",
                data: function(item) {
                    var icon = '<i class="fa fa-times text-warning" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Does not require prescription"></i>';
                    if (item.requires_prescription == '0') {
                        icon = '<i class="fa fa-check-circle-o text-success" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Requires prescription"></i>';
                    }
                    return item.name + ' ' + icon;
                }
            },
            {
                title: "Generic Name",
                data: function(item) {
                    return item.generic_name;
                }
            },
            {
                title: "Dosage",
                data: function(item) {
                    return item.dosage;
                }
            },
            {
                title: "Type",
                data: function(item) {
                    return item.form;
                }
            },
            {
                title: "Manufacturer",
                data: function(item) {
                    return item.manufacturer;
                }
            },
            {
                title: "Storage Conditions",
                data: function(item) {
                    return item.storage_conditions;
                }
            },
            {
                title: "Side Effects",
                data: function(item) {
                    return item.side_effects;
                }
            },
            {
                title: "Interactions",
                data: function(item) {
                    return item.interactions;
                }
            },
            {
                title: "Unit Price",
                data: function(item) {
                    return item.unit_price;
                }
            },
            {
                title: "Reorder Level",
                data: function(item) {
                    return item.reorder_level;
                }
            },
            {
                title: "Category",
                data: function(item) {
                    return item.category;
                }
            },
            {
                title: "Date Added",
                data: function(item) {
                    return item.date_added;
                }
            },
            {
                title: "Last Updated",
                data: function(item) {
                    return item.last_updated;
                }
            }
        ];
        if (is_admin == '1') {
            columns.push({
                title: "Action",
                data: function(item) {
                    return '<i class="fa fa-edit hand_cursor text-primary" onclick="editMedicine(' + item.id + ')"></i>' +
                        ' <i class="fa fa-trash text-danger hand_cursor" onclick="deleteMedicine(' + item.id + ')"></i>';
                }
            });
        }

        table = $('#medicines_table').DataTable({
            "processing": true,
            "serverSide": true,
            "columns": columns,
            "columnDefs": [{
                "targets": 'ipd_no',
                "orderable": false
            }],
            'searching': true,
            'paging': true,
            'pageLength': 25,
            'lengthChange': true,
            'aLengthMenu': [10, 25, 50, 100],
            'processing': true,
            'serverSide': true,
            'ordering': false,
            "ajax": {
                "url": "<?php echo site_url('pharmacy/medicines/fetch_medicines'); ?>",
                "type": "POST"
            },
            order: [
                [0, 'desc']
            ],
            info: true,
            sScrollX: true

        });
    });

    function addMedicine() {
        $('#medicineForm')[0].reset();
        $('#medicineModal').modal('show');
    }

    function saveMedicine() {
        var url = $('#medicineForm input[name="id"]').val() ? '<?php echo site_url('pharmacy/medicines/update'); ?>' : '<?php echo site_url('pharmacy/medicines/add'); ?>';
        $.ajax({
            url: url,
            type: 'POST',
            data: $('#medicineForm').serialize(),
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status) {
                    $('#medicineModal').modal('hide');
                    table.ajax.reload();
                } else {
                    var errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(response.errors, function(key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#medicineForm').prepend(errorHtml);
                }
            },
            error: function(xhr) {
                alert('Error saving data');
            }
        });
    }

    function editMedicine(id) {
        $.ajax({
            url: '<?php echo site_url('pharmacy/medicines/edit/'); ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#medicineForm input[name="id"]').val(data.id);
                $('#medicineForm input[name="name"]').val(data.name);
                $('#medicineForm input[name="generic_name"]').val(data.generic_name);
                $('#medicineForm input[name="dosage"]').val(data.dosage);
                $('#medicineForm select[name="form"]').val(data.form).trigger('change');
                $('#medicineForm input[name="manufacturer"]').val(data.manufacturer);
                $('#medicineForm input[name="ndc"]').val(data.ndc);
                $('#medicineForm textarea[name="description"]').val(data.description);
                $('#medicineForm input[name="controlled_substance"]').prop('checked', data.controlled_substance == '1');
                $('#medicineForm input[name="requires_prescription"]').prop('checked', data.requires_prescription == '1');
                $('#medicineForm textarea[name="storage_conditions"]').val(data.storage_conditions);
                $('#medicineForm textarea[name="side_effects"]').val(data.side_effects);
                $('#medicineForm textarea[name="interactions"]').val(data.interactions);
                $('#medicineForm input[name="unit_price"]').val(data.unit_price);
                $('#medicineForm input[name="reorder_level"]').val(data.reorder_level);
                $('#medicineForm select[name="category"]').val(data.category).trigger('change');
                $('#medicineForm input[name="date_added"]').val(data.date_added);
                $('#medicineForm input[name="last_updated"]').val(data.last_updated);
                $('#medicineModal').modal('show');
            },
            error: function(xhr) {
                alert('Error fetching data');
            }
        });
    }

    function deleteMedicine(id) {
        if (confirm('Are you sure you want to delete this medicine?')) {
            $.ajax({
                url: '<?php echo site_url('pharmacy/medicines/delete/'); ?>' + id,
                type: 'POST',
                success: function(data) {
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Error deleting data');
                }
            });
        }
    }
</script>