<div class="row">
    <div class="col-md-12">
        <div class="page-header" style="margin-top:0;">
            <h3 class="no-margin">Billing &ndash; <?= html_escape($context) ?> #<?= html_escape($ref_no) ?></h3>
        </div>
    </div>
</div>

<div id="alerts"></div>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">Add Service Item</div>
            <div class="panel-body">
                <form id="add-item-form" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Service Group</label>
                        <div class="col-sm-8">
                            <select id="service-group" name="group_id" class="form-control" required>
                                <option value="">Select group</option>
                                <?php foreach ($service_groups as $group): ?>
                                    <option value="<?= (int) $group['group_id'] ?>"><?= html_escape($group['group_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Service</label>
                        <div class="col-sm-8">
                            <select id="service-id" name="service_id" class="form-control" required>
                                <option value="">Select service</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Billing Date</label>
                        <div class="col-sm-8">
                            <input type="date" id="billing-date" name="billing_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Amount Override (?)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control" placeholder="Leave blank for default price">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                Current Items
                <button type="button" class="btn btn-xs btn-default pull-right" id="refresh-items">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="current-items-table">
                        <thead>
                            <tr>
                                <th style="width:60%;">Service</th>
                                <th style="width:20%;" class="text-center">Date</th>
                                <th style="width:20%;" class="text-right">Amount (?)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">No items added yet.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Subtotal</th>
                                <th class="text-right" id="subtotal-cell">?0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel panel-success">
            <div class="panel-heading">Finalize Invoice</div>
            <div class="panel-body">
                <form id="finalize-form">
                    <div class="form-group">
                        <label for="discount">Discount (?)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="discount" name="discount" value="0">
                    </div>
                    <div class="form-group">
                        <label for="tax-percent">Tax (%)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="tax-percent" name="tax_percent" value="0">
                    </div>
                    <div class="alert alert-info" id="summary-panel" style="display:none;">
                        <p><strong>Subtotal:</strong> <span id="summary-subtotal">?0.00</span></p>
                        <p><strong>Discount:</strong> <span id="summary-discount">?0.00</span></p>
                        <p><strong>Tax:</strong> <span id="summary-tax">?0.00</span></p>
                        <p><strong>Estimated Total:</strong> <span id="summary-total">?0.00</span></p>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fa fa-file-text-o"></i> Finalize Invoice
                    </button>
                </form>
            </div>
        </div>
        <div class="well well-sm">
            <p class="text-muted" style="margin-bottom:0;">
                <small>Tip: Leave amount blank to use the default service price. All values are shown in INR (?).</small>
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        var context = '<?= addslashes($context) ?>';
        var refNo = <?= (int) $ref_no ?>;
        var csrfTokenName = '<?= $this->security->get_csrf_token_name() ?>';
        var csrfCookieName = '<?= $this->config->item('csrf_cookie_name') ?>';

        function getCookie(name) {
            var value = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return value ? value.pop() : '';
        }

        function getCsrfToken() {
            return getCookie(csrfCookieName);
        }

        function notify(message, type) {
            type = type || 'info';
            var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                message + '</div>');
            $('#alerts').html(alert);
        }

        function formatCurrency(amount) {
            amount = parseFloat(amount) || 0;
            return '?' + amount.toFixed(2);
        }

        function setSummary(subtotal) {
            var discount = parseFloat($('#discount').val()) || 0;
            if (discount > subtotal) {
                discount = subtotal;
            }
            var taxable = subtotal - discount;
            var taxPercent = parseFloat($('#tax-percent').val()) || 0;
            var taxAmount = taxable * (taxPercent / 100);
            var total = taxable + taxAmount;
            $('#summary-subtotal').text(formatCurrency(subtotal));
            $('#summary-discount').text(formatCurrency(discount));
            $('#summary-tax').text(formatCurrency(taxAmount));
            $('#summary-total').text(formatCurrency(total));
            $('#summary-panel').toggle(subtotal > 0);
        }

        function loadServices(groupId) {
            if (!groupId) {
                $('#service-id').html('<option value="">Select service</option>');
                return;
            }
            $.ajax({
                url: '<?= site_url('api/services/by-group') ?>',
                type: 'POST',
                dataType: 'json',
                data: (function() {
                    var payload = {
                        group_id: groupId
                    };
                    payload[csrfTokenName] = getCsrfToken();
                    return payload;
                })(),
                success: function(response) {
                    if (response.ok) {
                        var options = '<option value="">Select service</option>';
                        $.each(response.services, function(_, service) {
                            options += '<option value="' + service.service_id + '" data-price="' + parseFloat(service.price || 0).toFixed(2) + '">' +
                                $('<div/>').text(service.service_name).html() + ' (' + formatCurrency(service.price) + ')</option>';
                        });
                        $('#service-id').html(options);
                    } else {
                        notify(response.msg || 'Unable to load services.', 'danger');
                    }
                },
                error: function() {
                    notify('Failed to load services. Please try again.', 'danger');
                }
            });
        }

        function renderItems(items, subtotal) {
            var tbody = $('#current-items-table tbody');
            tbody.empty();
            if (!items.length) {
                tbody.append('<tr><td colspan="3" class="text-center">No items added yet.</td></tr>');
            } else {
                $.each(items, function(_, item) {
                    tbody.append('<tr>' +
                        '<td>' + $('<div/>').text(item.service_name || 'Service').html() + '</td>' +
                        '<td class="text-center">' + $('<div/>').text(item.billing_date).html() + '</td>' +
                        '<td class="text-right">' + formatCurrency(item.amount) + '</td>' +
                        '</tr>');
                });
            }
            $('#subtotal-cell').text(formatCurrency(subtotal));
            setSummary(subtotal);
        }

        function loadCurrentItems() {
            $.getJSON('<?= site_url('billing/current-items') ?>', {
                    context: context,
                    ref_no: refNo
                })
                .done(function(response) {
                    if (response.ok) {
                        renderItems(response.items || [], response.subtotal || 0);
                    } else {
                        notify(response.msg || 'Unable to fetch current items.', 'danger');
                    }
                })
                .fail(function() {
                    notify('Failed to fetch current items.', 'danger');
                });
        }

        $('#service-group').on('change', function() {
            loadServices($(this).val());
        });

        $('#service-id').on('change', function() {
            var price = $(this).find(':selected').data('price');
            if (price) {
                $('#amount').attr('placeholder', 'Default: ' + formatCurrency(price));
            } else {
                $('#amount').attr('placeholder', 'Leave blank for default price');
            }
        });

        $('#add-item-form').on('submit', function(e) {
            e.preventDefault();
            var serviceId = $('#service-id').val();
            if (!serviceId) {
                notify('Please select a service to add.', 'warning');
                return;
            }
            var payload = {
                context: context,
                ref_no: refNo,
                service_id: serviceId,
                billing_date: $('#billing-date').val(),
                amount: $('#amount').val()
            };
            payload[csrfTokenName] = getCsrfToken();

            $.ajax({
                url: '<?= site_url('billing/add-item') ?>',
                type: 'POST',
                dataType: 'json',
                data: payload,
                success: function(response) {
                    if (response.ok) {
                        notify('Item added successfully.', 'success');
                        $('#amount').val('');
                        loadCurrentItems();
                    } else {
                        notify(response.msg || 'Failed to add item.', 'danger');
                    }
                },
                error: function() {
                    notify('Unable to add item. Please try again.', 'danger');
                }
            });
        });

        $('#refresh-items').on('click', function() {
            loadCurrentItems();
        });

        $('#finalize-form').on('submit', function(e) {
            e.preventDefault();
            var payload = {
                context: context,
                ref_no: refNo,
                discount: $('#discount').val(),
                tax_percent: $('#tax-percent').val()
            };
            payload[csrfTokenName] = getCsrfToken();

            $.ajax({
                url: '<?= site_url('billing/finalize') ?>',
                type: 'POST',
                dataType: 'json',
                data: payload,
                success: function(response) {
                    if (response.ok) {
                        notify('Invoice finalized. Redirecting...', 'success');
                        setTimeout(function() {
                            window.location.href = '<?= site_url('billing/invoice') ?>/' + response.invoice_id;
                        }, 800);
                    } else {
                        notify(response.msg || 'Failed to finalize invoice.', 'danger');
                    }
                },
                error: function() {
                    notify('Unable to finalize invoice at this time.', 'danger');
                }
            });
        });

        $('#discount, #tax-percent').on('input', function() {
            var subtotalText = $('#subtotal-cell').text().replace('?', '').replace(',', '');
            setSummary(parseFloat(subtotalText) || 0);
        });

        loadCurrentItems();
    })(jQuery);
</script>