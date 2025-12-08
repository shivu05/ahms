<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart"></i>
            Sales Report
            <small>Detailed Sales Analysis</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy'); ?>">Pharmacy</a></li>
            <li><a href="<?php echo site_url('pharmacy_pro/pharmacy/reports'); ?>">Reports</a></li>
            <li class="active">Sales Report</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter Sales Data</h3>
                    </div>
                    <div class="box-body">
                        <form id="salesReportForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                            value="<?php echo date('Y-m-01'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                            value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Payment Mode</label>
                                        <select name="payment_mode" id="payment_mode" class="form-control">
                                            <option value="">All Payment Modes</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Credit">Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Generate Report
                                            </button>
                                            <button type="button" class="btn btn-success" id="exportBtn">
                                                <i class="fa fa-download"></i> Export
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row" id="summaryRow" style="display: none;">
            <div class="col-md-3">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sales</span>
                        <span class="info-box-number" id="totalSales">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Amount</span>
                        <span class="info-box-number" id="totalAmount">₹0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Average Sale</span>
                        <span class="info-box-number" id="avgSale">₹0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Items Sold</span>
                        <span class="info-box-number" id="totalItems">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Data Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sales Details</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="salesTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Bill No</th>
                                        <th>Date</th>
                                        <th>Patient Name</th>
                                        <th>Payment Mode</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Cashier</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <em>Click "Generate Report" to load sales data</em>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        let salesTable;

        // Initialize DataTable
        function initializeTable(data) {
            if (salesTable) {
                salesTable.destroy();
            }

            salesTable = $('#salesTable').DataTable({
                data: data,
                columns: [
                    { data: data.bill_number },
                    {
                        data: 'sale_date',
                        render: function (data) {
                            return new Date(data).toLocaleDateString('en-IN');
                        }
                    },
                    { data: 'patient_display_name' },
                    { data: 'payment_mode' },
                    {
                        data: 'subtotal',
                        render: function (data) {
                            return '₹' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'discount_amount',
                        render: function (data) {
                            return '₹' + parseFloat(data || 0).toFixed(2);
                        }
                    },
                    {
                        data: 'tax_amount',
                        render: function (data) {
                            return '₹' + parseFloat(data || 0).toFixed(2);
                        }
                    },
                    {
                        data: 'total_amount',
                        render: function (data) {
                            return '₹' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'cashier_name' }
                ],
                order: [[1, 'desc']],
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        }

        // Generate report
        $('#salesReportForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '<?php echo site_url("pharmacy_pro/pharmacy/get_sales_report_data"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response && response.length > 0) {
                        initializeTable(response);

                        // Calculate and display summary
                        let totalSales = response.length;
                        let totalAmount = response.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
                        let avgSale = totalAmount / totalSales;

                        $('#totalSales').text(totalSales);
                        $('#totalAmount').text('₹' + totalAmount.toFixed(2));
                        $('#avgSale').text('₹' + avgSale.toFixed(2));

                        $('#summaryRow').show();
                    } else {
                        $('#salesTableBody').html('<tr><td colspan="9" class="text-center"><em>No sales found for the selected criteria</em></td></tr>');
                        $('#summaryRow').hide();
                    }
                },
                error: function () {
                    alert('Error loading sales data');
                }
            });
        });

        // Export functionality
        $('#exportBtn').click(function () {
            if (salesTable) {
                salesTable.button('.buttons-excel').trigger();
            } else {
                alert('Please generate a report first');
            }
        });
    });
</script>