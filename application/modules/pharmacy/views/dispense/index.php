<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Medicines & Generate Bill</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (for rounded corners and general aesthetics) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* Tailwind gray-50 */
        }

        .container {
            max-width: 1200px;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            border-radius: 0.75rem;
            /* Tailwind rounded-xl */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            /* Tailwind shadow-md */
        }

        .form-control,
        .btn {
            border-radius: 0.5rem;
            /* Tailwind rounded-lg */
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #3b82f6;
            /* Tailwind blue-500 */
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            /* Tailwind blue-600 */
            border-color: #2563eb;
        }

        .btn-danger {
            background-color: #ef4444;
            /* Tailwind red-500 */
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            /* Tailwind red-600 */
            border-color: #dc2626;
        }

        .btn-success {
            background-color: #22c55e;
            /* Tailwind green-500 */
            border-color: #22c55e;
        }

        .btn-success:hover {
            background-color: #16a34a;
            /* Tailwind green-600 */
            border-color: #16a34a;
        }

        .modal-content {
            border-radius: 0.75rem;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Dispense Medicines & Generate Bill</h2>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success rounded-lg" role="alert">
                <?php echo $this->session->flashdata('success_message'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger rounded-lg" role="alert">
                <?php echo $this->session->flashdata('error_message'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Patient Section -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h4 class="text-xl font-semibold mb-4 text-gray-700">Patient Information</h4>
                    <div class="mb-3">
                        <label for="patientSearch" class="form-label">Search Patient (Name/Phone):</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="patientSearch" placeholder="Search by name or phone">
                            <button class="btn btn-primary" type="button" id="searchPatientBtn">Search</button>
                        </div>
                        <div id="patientSearchResults" class="list-group mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="selectedPatient" class="form-label">Selected Patient:</label>
                        <input type="text" class="form-control" id="selectedPatient" readonly>
                        <input type="hidden" id="selectedPatientId">
                    </div>
                    <button class="btn btn-success w-full" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add New Patient</button>
                </div>
            </div>

            <!-- Medicine Dispensing Section -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h4 class="text-xl font-semibold mb-4 text-gray-700">Add Medicine to Cart</h4>
                    <div class="mb-3">
                        <label for="medicineSelect" class="form-label">Select Medicine:</label>
                        <select class="form-select" id="medicineSelect">
                            <option value="">-- Select Medicine --</option>
                            <?php foreach ($medicines as $medicine): ?>
                                <option value="<?php echo $medicine->MedicineID; ?>"
                                    data-unit-price="<?php echo $medicine->UnitPrice; ?>"
                                    data-medicine-name="<?php echo htmlspecialchars($medicine->MedicineName); ?>"
                                    data-dosage="<?php echo htmlspecialchars($medicine->Dosage); ?>">
                                    <?php echo htmlspecialchars($medicine->MedicineName . ' - ' . $medicine->Dosage); ?> (Price: <?php echo $medicine->UnitPrice; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="batchSelect" class="form-label">Select Batch:</label>
                        <select class="form-select" id="batchSelect" disabled>
                            <option value="">-- Select Batch --</option>
                        </select>
                        <small class="form-text text-muted" id="batchInfo"></small>
                    </div>
                    <div class="mb-3">
                        <label for="quantityInput" class="form-label">Quantity:</label>
                        <input type="number" class="form-control" id="quantityInput" min="1" value="1" disabled>
                        <small class="form-text text-muted" id="availableStock"></small>
                    </div>
                    <button class="btn btn-primary w-full" id="addMedicineToCartBtn" disabled>Add to Cart</button>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="card p-4 mt-4">
            <h4 class="text-xl font-semibold mb-4 text-gray-700">Dispensing Cart</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch No.</th>
                            <th>Expiry Date</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <!-- Cart items will be dynamically added here -->
                        <tr>
                            <td colspan="7" class="text-center text-muted">No medicines added to cart yet.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total Amount:</th>
                            <th id="totalAmount" class="text-end">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button class="btn btn-success w-full mt-4" id="generateBillBtn" disabled>Generate Bill</button>
        </div>
    </div>

    <!-- Modals -->

    <!-- Add New Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPatientForm">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name">
                        </div>
                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phone_number">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-3">
                            <label for="dateOfBirth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="medicalHistory" class="form-label">Medical History</label>
                            <textarea class="form-control" id="medicalHistory" name="medical_history" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePatientBtn">Save Patient</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Dispensing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to generate the bill for the following items?</p>
                    <div id="confirmationSummary">
                        <!-- Summary will be populated here -->
                    </div>
                    <p class="mt-3"><strong>Total Amount: <span id="finalTotalAmount">0.00</span></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmGenerateBillBtn">Confirm & Generate Bill</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="messageModalBody">
                    <!-- Message will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Base URL for AJAX calls (important for CodeIgniter)
        const BASE_URL = '<?php echo base_url(); ?>';

        let cart = []; // Global array to hold cart items
        let selectedPatientId = null;

        $(document).ready(function() {
            // Initialize tooltips if needed
            // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            //     return new bootstrap.Tooltip(tooltipTriggerEl)
            // })

            // --- Patient Search and Selection ---
            $('#searchPatientBtn').on('click', function() {
                const searchTerm = $('#patientSearch').val().trim();
                if (searchTerm.length < 3) {
                    $('#patientSearchResults').html('<div class="list-group-item text-danger">Please enter at least 3 characters.</div>');
                    return;
                }

                $.ajax({
                    url: BASE_URL + 'dispense/search_patient_ajax',
                    method: 'POST',
                    data: {
                        search_term: searchTerm
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#patientSearchResults').empty();
                        if (response.status === 'success' && response.patients.length > 0) {
                            response.patients.forEach(function(patient) {
                                $('#patientSearchResults').append(
                                    `<a href="#" class="list-group-item list-group-item-action"
                                       data-patient-id="${patient.PatientID}"
                                       data-first-name="${patient.FirstName}"
                                       data-last-name="${patient.LastName}"
                                       data-phone-number="${patient.PhoneNumber}">
                                       ${patient.FirstName} ${patient.LastName} (${patient.PhoneNumber})
                                    </a>`
                                );
                            });
                        } else {
                            $('#patientSearchResults').html('<div class="list-group-item">No patients found.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        $('#patientSearchResults').html('<div class="list-group-item text-danger">Error searching patients.</div>');
                    }
                });
            });

            // Handle patient selection from search results
            $('#patientSearchResults').on('click', '.list-group-item-action', function(e) {
                e.preventDefault();
                selectedPatientId = $(this).data('patient-id');
                const firstName = $(this).data('first-name');
                const lastName = $(this).data('last-name');
                const phoneNumber = $(this).data('phone-number');

                $('#selectedPatient').val(`${firstName} ${lastName} (${phoneNumber})`);
                $('#selectedPatientId').val(selectedPatientId);
                $('#patientSearchResults').empty(); // Clear search results

                updateGenerateBillButtonState();
            });

            // --- Add New Patient Modal ---
            $('#savePatientBtn').on('click', function() {
                const formData = $('#addPatientForm').serialize();
                $.ajax({
                    url: BASE_URL + 'dispense/add_patient_ajax',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#addPatientModal').modal('hide');
                            $('#addPatientForm')[0].reset(); // Clear the form
                            selectedPatientId = response.patient_id; // Automatically select the new patient
                            // Fetch and display details of the newly added patient
                            $.ajax({
                                url: BASE_URL + 'dispense/get_patient_details_ajax',
                                method: 'POST',
                                data: {
                                    patient_id: selectedPatientId
                                },
                                dataType: 'json',
                                success: function(patientResponse) {
                                    if (patientResponse.status === 'success') {
                                        const p = patientResponse.patient;
                                        $('#selectedPatient').val(`${p.FirstName} ${p.LastName} (${p.PhoneNumber})`);
                                        $('#selectedPatientId').val(p.PatientID);
                                        updateGenerateBillButtonState();
                                        showMessageModal('Success', response.message);
                                    } else {
                                        showMessageModal('Error', 'Patient added but failed to select: ' + patientResponse.message);
                                    }
                                },
                                error: function() {
                                    showMessageModal('Error', 'Patient added but failed to select due to network error.');
                                }
                            });
                        } else {
                            showMessageModal('Error', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        showMessageModal('Error', 'Failed to add patient due to network error.');
                    }
                });
            });

            // --- Medicine Selection and Batch Loading ---
            $('#medicineSelect').on('change', function() {
                const medicineId = $(this).val();
                if (medicineId) {
                    // Get medicine unit price from data attribute
                    const unitPrice = $(this).find(':selected').data('unit-price');
                    $('#quantityInput').data('unit-price', unitPrice); // Store unit price on quantity input for easy access

                    $.ajax({
                        url: BASE_URL + 'dispense/get_batches_ajax',
                        method: 'POST',
                        data: {
                            medicine_id: medicineId
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#batchSelect').empty().append('<option value="">-- Select Batch --</option>');
                            $('#batchInfo').text('');
                            $('#availableStock').text('');
                            $('#quantityInput').val(1).prop('disabled', true);
                            $('#addMedicineToCartBtn').prop('disabled', true);

                            if (response.status === 'success' && response.batches.length > 0) {
                                response.batches.forEach(function(batch) {
                                    $('#batchSelect').append(
                                        `<option value="${batch.BatchID}"
                                           data-expiry-date="${batch.ExpiryDate}"
                                           data-quantity-in-stock="${batch.QuantityInStock}">
                                           ${batch.BatchNumber} (Exp: ${batch.ExpiryDate}, Stock: ${batch.QuantityInStock})
                                        </option>`
                                    );
                                });
                                $('#batchSelect').prop('disabled', false);
                            } else {
                                $('#batchSelect').append('<option value="">No batches available</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                            $('#batchSelect').empty().append('<option value="">Error loading batches</option>');
                            $('#batchSelect').prop('disabled', true);
                        }
                    });
                } else {
                    // Reset everything if no medicine is selected
                    $('#batchSelect').empty().append('<option value="">-- Select Batch --</option>').prop('disabled', true);
                    $('#batchInfo').text('');
                    $('#availableStock').text('');
                    $('#quantityInput').val(1).prop('disabled', true);
                    $('#addMedicineToCartBtn').prop('disabled', true);
                }
            });

            // --- Batch Selection and Quantity Update ---
            $('#batchSelect').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const expiryDate = selectedOption.data('expiry-date');
                const quantityInStock = selectedOption.data('quantity-in-stock');

                if (selectedOption.val()) {
                    $('#batchInfo').text(`Expiry: ${expiryDate}`);
                    $('#availableStock').text(`Available Stock: ${quantityInStock}`);
                    $('#quantityInput').attr('max', quantityInStock).prop('disabled', false);
                    $('#addMedicineToCartBtn').prop('disabled', false);
                } else {
                    $('#batchInfo').text('');
                    $('#availableStock').text('');
                    $('#quantityInput').val(1).prop('disabled', true);
                    $('#addMedicineToCartBtn').prop('disabled', true);
                }
            });

            // Quantity input validation (client-side)
            $('#quantityInput').on('input', function() {
                let val = parseInt($(this).val());
                const max = parseInt($(this).attr('max'));
                if (isNaN(val) || val < 1) {
                    val = 1;
                } else if (val > max) {
                    val = max;
                }
                $(this).val(val);
            });

            // --- Add Medicine to Cart ---
            $('#addMedicineToCartBtn').on('click', function() {
                const medicineSelect = $('#medicineSelect');
                const batchSelect = $('#batchSelect');
                const quantityInput = $('#quantityInput');

                const medicineId = medicineSelect.val();
                const medicineName = medicineSelect.find(':selected').data('medicine-name');
                const dosage = medicineSelect.find(':selected').data('dosage');
                const unitPrice = parseFloat(medicineSelect.find(':selected').data('unit-price'));

                const batchId = batchSelect.val();
                const batchNumber = batchSelect.find(':selected').text().split(' ')[0]; // Extract batch number
                const expiryDate = batchSelect.find(':selected').data('expiry-date');
                const quantityInStock = parseInt(batchSelect.find(':selected').data('quantity-in-stock'));
                const quantity = parseInt(quantityInput.val());

                if (!medicineId || !batchId || isNaN(quantity) || quantity <= 0 || quantity > quantityInStock) {
                    showMessageModal('Warning', 'Please select a medicine, a valid batch, and enter a valid quantity within available stock.');
                    return;
                }

                // Check if this specific batch of medicine is already in the cart
                const existingCartItemIndex = cart.findIndex(item => item.batch_id === batchId);

                if (existingCartItemIndex > -1) {
                    // If exists, update quantity
                    const existingItem = cart[existingCartItemIndex];
                    const newQuantity = existingItem.quantity + quantity;

                    if (newQuantity > quantityInStock) {
                        showMessageModal('Warning', `Cannot add more. Total quantity for this batch would exceed available stock (${quantityInStock}).`);
                        return;
                    }
                    existingItem.quantity = newQuantity;
                    existingItem.subtotal = (newQuantity * existingItem.price).toFixed(2);
                } else {
                    // Add new item to cart
                    cart.push({
                        medicine_id: medicineId,
                        medicine_name: medicineName,
                        dosage: dosage,
                        batch_id: batchId,
                        batch_number: batchNumber,
                        expiry_date: expiryDate,
                        quantity: quantity,
                        price: unitPrice,
                        subtotal: (quantity * unitPrice).toFixed(2)
                    });
                }

                renderCart();
                resetMedicineSelectionForm();
                updateGenerateBillButtonState();
            });

            // --- Remove Item from Cart ---
            $('#cartItems').on('click', '.remove-item-btn', function() {
                const indexToRemove = $(this).data('index');
                cart.splice(indexToRemove, 1); // Remove item from array
                renderCart();
                updateGenerateBillButtonState();
            });

            // --- Generate Bill Button Click ---
            $('#generateBillBtn').on('click', function() {
                if (!selectedPatientId) {
                    showMessageModal('Warning', 'Please select or add a patient before generating the bill.');
                    return;
                }
                if (cart.length === 0) {
                    showMessageModal('Warning', 'Please add medicines to the cart before generating the bill.');
                    return;
                }

                // Populate confirmation modal
                let summaryHtml = '<ul class="list-group">';
                cart.forEach(item => {
                    summaryHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                        ${item.medicine_name} (${item.dosage}) - Batch: ${item.batch_number}
                                        <span class="badge bg-primary rounded-pill">${item.quantity} x ${item.price.toFixed(2)} = ${item.subtotal}</span>
                                    </li>`;
                });
                summaryHtml += '</ul>';
                $('#confirmationSummary').html(summaryHtml);
                $('#finalTotalAmount').text(calculateTotalAmount().toFixed(2));

                // Show confirmation modal
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            });

            // --- Confirm Generate Bill in Modal ---
            $('#confirmGenerateBillBtn').on('click', function() {
                const totalAmount = calculateTotalAmount();
                const cartItemsForAPI = cart.map(item => ({
                    medicine_id: item.medicine_id,
                    batch_id: item.batch_id,
                    quantity: item.quantity,
                    price: item.price
                }));

                // Close confirmation modal
                const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                confirmationModal.hide();

                $.ajax({
                    url: BASE_URL + 'dispense/process_dispense_ajax',
                    method: 'POST',
                    data: {
                        patient_id: selectedPatientId,
                        cart_items: cartItemsForAPI,
                        total_amount: totalAmount
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessageModal('Success', response.message + `<br>Sale ID: ${response.sale_id}`);
                            // Clear cart and reset UI after successful dispense
                            cart = [];
                            selectedPatientId = null;
                            $('#selectedPatient').val('');
                            $('#selectedPatientId').val('');
                            renderCart();
                            updateGenerateBillButtonState();
                            $('#patientSearch').val(''); // Clear patient search field
                        } else {
                            showMessageModal('Error', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        showMessageModal('Error', 'Failed to generate bill due to network error.');
                    }
                });
            });

            // --- Helper Functions ---

            function renderCart() {
                const cartItemsBody = $('#cartItems');
                cartItemsBody.empty();
                if (cart.length === 0) {
                    cartItemsBody.html('<tr><td colspan="7" class="text-center text-muted">No medicines added to cart yet.</td></tr>');
                } else {
                    cart.forEach((item, index) => {
                        cartItemsBody.append(`
                            <tr>
                                <td>${item.medicine_name} (${item.dosage})</td>
                                <td>${item.batch_number}</td>
                                <td>${item.expiry_date}</td>
                                <td>${item.quantity}</td>
                                <td>${item.price.toFixed(2)}</td>
                                <td>${item.subtotal}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item-btn" data-index="${index}">Remove</button>
                                </td>
                            </tr>
                        `);
                    });
                }
                $('#totalAmount').text(calculateTotalAmount().toFixed(2));
            }

            function calculateTotalAmount() {
                let total = 0;
                cart.forEach(item => {
                    total += parseFloat(item.subtotal);
                });
                return total;
            }

            function resetMedicineSelectionForm() {
                $('#medicineSelect').val('');
                $('#batchSelect').empty().append('<option value="">-- Select Batch --</option>').prop('disabled', true);
                $('#batchInfo').text('');
                $('#quantityInput').val(1).prop('disabled', true);
                $('#availableStock').text('');
                $('#addMedicineToCartBtn').prop('disabled', true);
            }

            function updateGenerateBillButtonState() {
                if (selectedPatientId && cart.length > 0) {
                    $('#generateBillBtn').prop('disabled', false);
                } else {
                    $('#generateBillBtn').prop('disabled', true);
                }
            }

            function showMessageModal(title, message) {
                $('#messageModalLabel').text(title);
                $('#messageModalBody').html(message);
                const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                messageModal.show();
            }
        });
    </script>
</body>

</html>