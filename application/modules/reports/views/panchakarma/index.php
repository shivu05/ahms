<div class="row">
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-list"></i>
            <h3 class="box-title">Panchakarma stats: </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped table-hover dataTable">
                <thead>
                    <tr>
                        <th>Procedure</th>
                        <th>From OPD</th>
                        <th>From IPD</th>
                        <th>Procedure Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grand_total = 0; ?>
                    <?php foreach ($unique_procedures as $procedure): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($procedure['procedure_name']); ?></td>
                            <td><?php echo htmlspecialchars($procedure['from_opd']); ?></td>
                            <td><?php echo htmlspecialchars($procedure['from_ipd']); ?></td>
                            <td><?php echo htmlspecialchars($procedure['total']); ?></td>
                        </tr>
                        <?php $grand_total += (int) $procedure['total']; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Total</strong></td>
                        <td><strong><?php echo htmlspecialchars((string) $grand_total); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>