<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo $invoice['invoice_number']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 18px;
            color: #7f8c8d;
            font-weight: normal;
        }
        
        .hospital-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .hospital-logo {
            max-height: 70px;
            max-width: 160px;
            margin-bottom: 8px;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-details .left,
        .invoice-details .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .invoice-details .right {
            text-align: right;
        }
        
        .info-block {
            margin-bottom: 15px;
        }
        
        .info-block h3 {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .info-block p {
            margin: 3px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #34495e;
            color: white;
        }
        
        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        table th {
            font-weight: bold;
            font-size: 12px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table td.text-right,
        table th.text-right {
            text-align: right;
        }
        
        table td.text-center,
        table th.text-center {
            text-align: center;
        }
        
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .totals table {
            margin-bottom: 0;
        }
        
        .totals table td {
            border: none;
            padding: 5px 10px;
        }
        
        .totals table tr.grand-total td {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
        }
        
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
        }
        
        .notes h4 {
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-paid {
            background-color: #27ae60;
            color: white;
        }
        
        .status-unpaid {
            background-color: #e74c3c;
            color: white;
        }
        
        .status-partial {
            background-color: #f39c12;
            color: white;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="print-button no-print">
        <button onclick="window.print()" class="btn">
            <i class="fa fa-print"></i> Print Invoice
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <h2><?php echo htmlspecialchars($hospital_name ?? 'Hospital Management System'); ?></h2>
        </div>

        <!-- Hospital Info -->
        <div class="hospital-info">
            <?php if (!empty($hospital_logo)): ?>
                <?php $logo_src = preg_match('#^https?://#i', $hospital_logo) ? $hospital_logo : base_url($hospital_logo); ?>
                <p>
                    <img src="<?php echo htmlspecialchars($logo_src); ?>" alt="Hospital Logo" class="hospital-logo">
                </p>
            <?php endif; ?>
            <p><strong><?php echo htmlspecialchars($hospital_name ?? 'Hospital Name'); ?></strong></p>
            <p><?php echo htmlspecialchars($hospital_address ?? ''); ?></p>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="left">
                <div class="info-block">
                    <h3>Bill To:</h3>
                    <p><strong><?php echo $invoice['patient_name'] ?? 'N/A'; ?></strong></p>
                    <?php if (!empty($invoice['opd_no'])): ?>
                        <p>OPD No: <?php echo $invoice['opd_no']; ?></p>
                    <?php elseif (!empty($invoice['ipd_no'])): ?>
                        <p>IPD No: <?php echo $invoice['ipd_no']; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($invoice['patient_uhid'])): ?>
                        <p>UHID: <?php echo $invoice['patient_uhid']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right">
                <div class="info-block">
                    <h3>Invoice Details:</h3>
                    <p><strong>Invoice #:</strong> <?php echo $invoice['invoice_number']; ?></p>
                    <p><strong>Date:</strong> <?php echo date('d-M-Y', strtotime($invoice['invoice_date'])); ?></p>
                    <p><strong>Type:</strong> <?php echo $invoice['invoice_type']; ?></p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-<?php echo strtolower($invoice['payment_status']); ?>">
                            <?php echo $invoice['payment_status']; ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="40%">Service / Item</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="10%" class="text-right">Disc%</th>
                    <th width="10%" class="text-right">GST%</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoice['items'])): ?>
                    <?php $i = 1; foreach ($invoice['items'] as $item): ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td>
                                <strong><?php echo $item['service_name'] ?? 'Service'; ?></strong>
                                <?php if (!empty($item['description'])): ?>
                                    <br><small><?php echo $item['description']; ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-right">₹<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td class="text-right"><?php echo number_format($item['discount_percentage'] ?? 0, 2); ?>%</td>
                            <td class="text-right"><?php echo number_format($item['gst_rate'] ?? 0, 2); ?>%</td>
                            <td class="text-right">₹<?php echo number_format($item['line_total'] ?? $item['item_amount'] ?? 0, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No items found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">₹<?php echo number_format($invoice['subtotal_amount'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">- ₹<?php echo number_format($invoice['discount_amount'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>GST:</td>
                    <td class="text-right">₹<?php echo number_format($invoice['gst_amount'] ?? 0, 2); ?></td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total:</td>
                    <td class="text-right">₹<?php echo number_format($invoice['total_amount'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Amount Paid:</td>
                    <td class="text-right">₹<?php echo number_format($invoice['amount_paid'] ?? 0, 2); ?></td>
                </tr>
                <tr class="grand-total" style="color: #e74c3c;">
                    <td>Balance Due:</td>
                    <td class="text-right">₹<?php echo number_format($invoice['balance_due'] ?? 0, 2); ?></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        <?php if (!empty($invoice['remarks'])): ?>
            <div class="notes">
                <h4>Notes / Remarks:</h4>
                <p><?php echo nl2br(htmlspecialchars($invoice['remarks'])); ?></p>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing our services!</strong></p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact our billing department.</p>
        </div>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
