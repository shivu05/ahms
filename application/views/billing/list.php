<?php

/** @var array $rows */
/** @var array $filters */
/** @var array $pagination */
/** @var string $query_string */
$baseQuery = $query_string !== '' ? $query_string . '&' : '';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Invoices</h3>
    </div>
    <div class="box-body">
        <form class="form-inline" method="get" action="<?= site_url('billing/list') ?>">
            <div class="form-group">
                <label for="filter-context">Type: </label>
                <select class="form-control" id="filter-context" name="context">
                    <option value="">All</option>
                    <option value="OPD" <?= $filters['context'] === 'OPD' ? 'selected' : '' ?>>OPD</option>
                    <option value="IPD" <?= $filters['context'] === 'IPD' ? 'selected' : '' ?>>IPD</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-status">Status</label>
                <select class="form-control" id="filter-status" name="status">
                    <option value="">All</option>
                    <option value="UNPAID" <?= $filters['status'] === 'UNPAID' ? 'selected' : '' ?>>Unpaid</option>
                    <option value="PARTIAL" <?= $filters['status'] === 'PARTIAL' ? 'selected' : '' ?>>Partial</option>
                    <option value="PAID" <?= $filters['status'] === 'PAID' ? 'selected' : '' ?>>Paid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-ref">Ref No</label>
                <input type="number" class="form-control" id="filter-ref" name="ref_no" value="<?= html_escape($filters['ref_no']) ?>" placeholder="OPD/IPD no">
            </div>
            <div class="form-group">
                <label for="filter-from">From</label>
                <input type="text" class="form-control date_picker" id="filter-from" name="date_from" value="<?= html_escape($filters['date_from']) ?>" placeholder="YYYY-MM-DD" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="filter-to">To</label>
                <input type="text" class="form-control date_picker" id="filter-to" name="date_to" value="<?= html_escape($filters['date_to']) ?>" placeholder="YYYY-MM-DD" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="filter-per-page">Per Page</label>
                <select class="form-control" id="filter-per-page" name="per_page">
                    <?php foreach ([10, 20, 50, 100] as $option): ?>
                        <option value="<?= $option ?>" <?= (int) $pagination['per_page'] === $option ? 'selected' : '' ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Apply</button>
            <a href="<?= site_url('billing/list') ?>" class="btn btn-default">Reset</a>
        </form>
        <hr />
        <div class="table-responsive">
            <table class="table table-striped table-bordered dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Context</th>
                        <th>Ref No</th>
                        <th class="text-right">Subtotal (?)</th>
                        <th class="text-right">Grand Total (?)</th>
                        <th class="text-right">Paid (?)</th>
                        <th class="text-right">Balance (?)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $index => $row): ?>
                            <tr>
                                <td><?= ($pagination['current_page'] - 1) * $pagination['per_page'] + $index + 1 ?></td>
                                <td><?= html_escape($row['invoice_no']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($row['invoice_date'])) ?></td>
                                <td><?= html_escape($row['context']) ?></td>
                                <td><?= html_escape($row['ref_no']) ?></td>
                                <td class="text-right"><?= inr($row['subtotal']) ?></td>
                                <td class="text-right"><?= inr($row['grand_total']) ?></td>
                                <td class="text-right"><?= inr($row['paid_amount']) ?></td>
                                <td class="text-right"><?= inr($row['balance_amount']) ?></td>
                                <td>
                                    <span class="label label-<?= strtolower($row['status']) === 'paid' ? 'success' : (strtolower($row['status']) === 'partial' ? 'warning' : 'danger') ?>">
                                        <?= html_escape($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= site_url('billing/invoice/' . $row['id']) ?>" class="btn btn-xs btn-primary">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">No invoices found for the selected filters.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
            <?php
            $current = (int) $pagination['current_page'];
            $totalPages = (int) $pagination['total_pages'];
            $start = max(1, $current - 2);
            $end = min($totalPages, $start + 4);
            $start = max(1, $end - 4);
            ?>
            <nav aria-label="Invoice pagination">
                <ul class="pagination">
                    <li class="<?= $current <= 1 ? 'disabled' : '' ?>">
                        <a href="<?= $current <= 1 ? '#' : site_url('billing/list?' . $baseQuery . 'page=' . ($current - 1)) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="<?= $i === $current ? 'active' : '' ?>">
                            <a href="<?= site_url('billing/list?' . $baseQuery . 'page=' . $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="<?= $current >= $totalPages ? 'disabled' : '' ?>">
                        <a href="<?= $current >= $totalPages ? '#' : site_url('billing/list?' . $baseQuery . 'page=' . ($current + 1)) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>