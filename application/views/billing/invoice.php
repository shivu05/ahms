<?php
/** @var array $invoice */
/** @var array $lines */
/** @var array $payments */
/** @var array $payment_modes */
?>
<style type="text/css">
    @media print {
        .no-print { display: none !important; }
        body { background: #fff; }
        .panel { border: none; }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="no-print" style="margin-bottom:15px;">
            <a class="btn btn-warning" href="<?= site_url('billing/invoice/' . (int) $invoice['id'] . '?format=pdf') ?>" target="_blank">
                <i class="fa fa-print"></i> Print / Download
            </a>
            <a href="<?= site_url('billing/list') ?>" class="btn btn-primary">&larr; Back to Invoices</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Invoice Details</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <p><strong>Invoice No:</strong> <?= html_escape($invoice['invoice_no']) ?></p>
                        <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($invoice['invoice_date'])) ?></p>
                        <p><strong>Status:</strong> <span class="label label-<?= strtolower($invoice['status']) === 'paid' ? 'success' : (strtolower($invoice['status']) === 'partial' ? 'warning' : 'danger') ?>"><?= html_escape($invoice['status']) ?></span></p>
                    </div>
                    <div class="col-sm-6">
                        <p><strong>Context:</strong> <?= html_escape($invoice['context']) ?></p>
                        <p><strong><?= $invoice['context'] === 'OPD' ? 'OPD No' : 'IPD No' ?>:</strong> <?= html_escape($invoice['ref_no']) ?></p>
                        <p><strong>Currency:</strong> INR (?)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading"><strong>Invoice Line Items</strong></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width:10%;" class="text-center">#</th>
                            <th style="width:50%;">Service</th>
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
                                    <td class="text-center"><?= date('d M Y', strtotime($line['billing_date'])) ?></td>
                                    <td class="text-right"><?= inr($line['amount']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No items found for this invoice.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Subtotal</th>
                            <th class="text-right"><?= inr($invoice['subtotal']) ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Discount</th>
                            <th class="text-right">-<?= inr($invoice['discount']) ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Tax (<?= (float) $invoice['tax_percent'] ?>%)</th>
                            <th class="text-right"><?= inr($invoice['tax_amount']) ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Grand Total</th>
                            <th class="text-right"><?= inr($invoice['grand_total']) ?></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Paid</th>
                            <th class="text-right text-success">
                                <?= inr($invoice['paid_amount']) ?>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-right">Balance</th>
                            <th class="text-right text-danger">
                                <?= inr($invoice['balance_amount']) ?>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Payments</strong></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width:25%;">Date</th>
                            <th style="width:20%;">Mode</th>
                            <th style="width:25%;">Reference</th>
                            <th style="width:30%;" class="text-right">Amount (?)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= date('d M Y H:i', strtotime($payment['paid_on'])) ?></td>
                                    <td><?= html_escape($payment['mode']) ?></td>
                                    <td><?= html_escape($payment['reference_no']) ?></td>
                                    <td class="text-right"><?= inr($payment['amount']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No payments recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel panel-success no-print">
            <div class="panel-heading"><strong>Record Payment</strong></div>
            <div class="panel-body">
                <div id="payment-alerts"></div>
                <form id="payment-form">
                    <input type="hidden" name="invoice_id" value="<?= (int) $invoice['id'] ?>">
                    <div class="form-group">
                        <label for="payment-amount">Amount (?)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" id="payment-amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="payment-mode">Mode</label>
                        <select class="form-control" id="payment-mode" name="mode" required>
                            <?php foreach ($payment_modes as $mode): ?>
                                <option value="<?= html_escape($mode) ?>"><?= html_escape($mode) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment-reference">Reference No.</label>
                        <input type="text" class="form-control" id="payment-reference" name="reference_no" maxlength="64" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label for="payment-notes">Notes</label>
                        <textarea class="form-control" id="payment-notes" name="notes" rows="2" maxlength="255" placeholder="Optional"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fa fa-check"></i> Save Payment
                    </button>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Summary</strong></div>
            <div class="panel-body">
                <p><strong>Grand Total:</strong> <?= inr($invoice['grand_total']) ?></p>
                <p><strong>Amount Paid:</strong> <?= inr($invoice['paid_amount']) ?></p>
                <p><strong>Outstanding:</strong> <?= inr($invoice['balance_amount']) ?></p>
                <p><strong>Status:</strong> <?= html_escape($invoice['status']) ?></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        var csrfTokenName = '<?= $this->security->get_csrf_token_name() ?>';
        var csrfCookieName = '<?= $this->config->item('csrf_cookie_name') ?>';

        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
            return match ? decodeURIComponent(match[1]) : '';
        }

        function notify(target, message, type) {
            type = type || 'info';
            var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                message + '</div>');
            $(target).html(alert);
        }

        $('#payment-form').on('submit', function(e) {
            e.preventDefault();
            var payload = $(this).serializeArray();
            payload.push({ name: csrfTokenName, value: getCookie(csrfCookieName) });

            $.ajax({
                url: '<?= site_url('billing/pay') ?>',
                type: 'POST',
                dataType: 'json',
                data: $.param(payload),
                success: function(response) {
                    if (response.ok) {
                        notify('#payment-alerts', 'Payment recorded successfully.', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 600);
                    } else {
                        notify('#payment-alerts', response.msg || 'Failed to save payment.', 'danger');
                    }
                },
                error: function() {
                    notify('#payment-alerts', 'Unable to save payment at this time.', 'danger');
                }
            });
        });
    })(jQuery);
</script>
