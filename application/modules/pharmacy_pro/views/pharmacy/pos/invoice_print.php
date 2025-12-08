<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="invoice-print">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <img src="<?php echo base_url('assets/your_logo.png'); ?>" class="img-responsive" style="max-width: 150px;">
                <small class="pull-right">Date: <?php echo date('d/m/Y', strtotime($sale->sale_date)); ?></small>
            </h2>
        </div>
    </div>

    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            From
            <address>
                <strong><?php echo isset($app_settings['hospital_name']) ? $app_settings['hospital_name'] : 'Hospital Name'; ?></strong><br>
                <?php echo isset($app_settings['address']) ? nl2br($app_settings['address']) : 'Address'; ?><br>
                Phone: <?php echo isset($app_settings['phone']) ? $app_settings['phone'] : ''; ?><br>
                Email: <?php echo isset($app_settings['email']) ? $app_settings['email'] : ''; ?>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            To
            <address>
                <strong><?php echo $sale->patient_name; ?></strong><br>
                Phone: <?php echo $sale->patient_phone; ?><br>
                Patient ID: <?php echo $sale->patient_id; ?><br>
                Patient Type: <?php echo ucfirst($sale->patient_type); ?>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Invoice #<?php echo $sale->bill_number; ?></b><br>
            <br>
            <b>Bill Date:</b> <?php echo date('d/m/Y', strtotime($sale->sale_date)); ?><br>
            <?php if($sale->doctor_id): ?>
            <b>Doctor:</b> <?php echo $sale->doctor_name; ?><br>
            <?php endif; ?>
            <b>Payment Mode:</b> <?php echo ucfirst($sale->payment_mode); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Qty</th>
                        <th>MRP</th>
                        <th>Discount</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($sale_items as $item): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $item->medicine_name; ?></td>
                        <td><?php echo $item->batch_number; ?></td>
                        <td><?php echo date('m/Y', strtotime($item->expiry_date)); ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td>₹<?php echo number_format($item->mrp, 2); ?></td>
                        <td>₹<?php echo number_format($item->discount_amount, 2); ?></td>
                        <td>₹<?php echo number_format($item->total_amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?php if($sale->remarks): ?>
            <p class="lead">Remarks:</p>
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                <?php echo $sale->remarks; ?>
            </p>
            <?php endif; ?>
        </div>
        <div class="col-xs-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>₹<?php echo number_format($sale->subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Discount:</th>
                        <td>₹<?php echo number_format($sale->discount_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Tax:</th>
                        <td>₹<?php echo number_format($sale->tax_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>₹<?php echo number_format($sale->total_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Paid Amount:</th>
                        <td>₹<?php echo number_format($sale->paid_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Balance:</th>
                        <td>₹<?php echo number_format($sale->total_amount - $sale->paid_amount, 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <p class="text-muted">
                <small>This is a computer generated invoice. No signature required.</small>
            </p>
        </div>
    </div>
</div>

<style type="text/css">
    @media print {
        .no-print {
            display: none;
        }
        .invoice-print {
            padding: 20px;
        }
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        window.print();
    });
</script>
