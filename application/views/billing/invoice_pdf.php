<table class="lines-table" width="100%" style="font-size: 12px;" cellspacing="0" cellpadding="5" border="1">
    <thead>
        <tr>
            <th style="width:8%;" class="text-center">#</th>
            <th style="width:52%;">Service</th>
            <th style="width:20%;" class="text-center">Date</th>
            <th style="width:20%;" class="text-right">Amount (?)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($lines)): ?>
            <?php foreach ($lines as $idx => $line): ?>
                <tr>
                    <td class="text-center"><?= $idx + 1 ?></td>
                    <td><?= html_escape($line['service_name'] ?? 'Service') ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($line['billing_date'])) ?></td>
                    <td style="text-align: right;"><?= inr($line['amount']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No billable items.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<table class="summary-table mt-20" style="margin-top: 20px; font-size: 12px;" width="100%" cellspacing="0" cellpadding="5" border="1">
    <tr>
        <th style="width:80%;" style="text-align: right;">Subtotal</th>
        <td style="text-align: right;"><?= inr($invoice['subtotal']) ?></td>
    </tr>
    <tr>
        <th style="text-align: right;">Discount</th>
        <td style="text-align: right;">-<?= inr($invoice['discount']) ?></td>
    </tr>
    <tr>
        <th style="text-align: right;">Tax (<?= (float) $invoice['tax_percent'] ?>%)</th>
        <td style="text-align: right;"><?= inr($invoice['tax_amount']) ?></td>
    </tr>
    <tr>
        <th style="text-align: right;">Grand Total</th>
        <td style="text-align: right;"><?= inr($invoice['grand_total']) ?></td>
    </tr>
    <tr>
        <th style="text-align: right;">Amount Paid</th>
        <td style="text-align: right;"><?= inr($invoice['paid_amount']) ?></td>
    </tr>
    <tr>
        <th style="text-align: right;">Outstanding Balance</th>
        <td style="text-align: right;"><?= inr($invoice['balance_amount']) ?></td>
    </tr>
</table>

<?php if (!empty($payments)): ?>
    <table class="payments-table mt-20" style="margin-top: 20px;font-size: 12px;" width="100%" cellspacing="0" cellpadding="5" border="1">
        <thead>
            <tr>
                <th style="width:25%;">Paid On</th>
                <th style="width:15%;">Mode</th>
                <th style="width:30%;">Reference</th>
                <th style="width:30%;" style="text-align: right;">Amount (?)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= date('d-m-Y H:i', strtotime($payment['paid_on'])) ?></td>
                    <td><?= html_escape($payment['mode']) ?></td>
                    <td><?= html_escape($payment['reference_no']) ?></td>
                    <td style="text-align: right;"><?= inr($payment['amount']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>