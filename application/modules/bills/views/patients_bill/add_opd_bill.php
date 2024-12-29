<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<div class="row">
    <div class="col-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Add OPD Bill:</h3></div>
            <div class="box-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                <?php endif; ?>
                <div class="row">
                    <form method="post" action="">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="patient_name" id="patient_name" class="form-control" autocomplete="off" placeholder="Search for a patient" required>
                                <input type="hidden" name="patient_id" id="patient_id">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" id="search" type="button">Go!</button>
                                </span>
                            </div><!-- /input-group -->
                            <table>
                                <tr>
                                    <td>Name</td><td><span id="pat_name"></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_group">Service Group</label>
                                <select name="service_group" id="service_group" class="form-control" required>
                                    <option value="">Select Service Group</option>
                                    <?php foreach ($service_groups as $group): ?>
                                        <option value="<?= $group['group_id']; ?>"><?= $group['group_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="service_id">Bill Service</label>
                                <select name="service_id" id="service_id" class="form-control" required>
                                    <option value="">Select Service</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="billing_date">Bill date</label>
                                <input type="text" name="billing_date" id="billing_date" class="form-control date_picker" required>
                            </div>
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Bill</button>
                                <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#patient_name').typeahead({
            source: function (query, process) {
                $.ajax({
                    url: "<?= base_url('common_methods/get_patients') ?>",
                    method: "POST",
                    data: {opd: query},
                    dataType: "json",
                    success: function (data) {
                        const patientNames = [];
                        const patientMap = {};

                        $.each(data, function (index, patient) {
                            const patientDisplay = `${patient.FirstName} - OPD: ${patient.OpdNo} `;
                            patientNames.push(patientDisplay);
                            patientMap[patientDisplay] = patient.OpdNo;
                        });

                        process(patientNames);

                        // Attach the selected patient ID to the hidden field
                        $('#patient_name').on('blur', function () {
                            const selected = $(this).val();
                            if (patientMap[selected]) {
                                $('#patient_id').val(patientMap[selected]);
                            } else {
                                $('#patient_id').val('');
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        alert('An error occurred while fetching patients: ' + error);
                    }
                });
            },
            autoSelect: true,
            items: 8
        });

        // Populate Bill Services when a Service Group is selected
        $('#service_group').on('change', function () {
            const groupId = $(this).val();
            const serviceDropdown = $('#service_id');

            serviceDropdown.empty().append('<option value="">Loading...</option>');

            if (groupId) {
                $.ajax({
                    url: "<?= base_url('bills/patients_bill/get_services_by_group') ?>",
                    method: "GET",
                    data: {group_id: groupId},
                    dataType: "json",
                    success: function (data) {
                        serviceDropdown.empty().append('<option value="">Select Service</option>');
                        $.each(data, function (index, service) {
                            serviceDropdown.append('<option value="' + service.service_id + '">' + service.service_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        alert("An error occurred while fetching services: " + error);
                        serviceDropdown.empty().append('<option value="">Select Service</option>');
                    }
                });
            } else {
                serviceDropdown.empty().append('<option value="">Select Service</option>');
            }
        });

        // Populate Amount when a Bill Service is selected
        $('#service_id').on('change', function () {
            const serviceId = $(this).val();
            if (serviceId) {
                $.ajax({
                    url: "<?= base_url('get-service-details') ?>",
                    method: "GET",
                    data: {service_id: serviceId},
                    dataType: "json",
                    success: function (data) {
                        $('#amount').val(data.price || '');
                    },
                    error: function (xhr, status, error) {
                        alert("An error occurred while fetching service details: " + error);
                        $('#amount').val('');
                    }
                });
            } else {
                $('#amount').val('');
            }
        });
    });
</script>
