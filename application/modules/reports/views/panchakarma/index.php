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
                        <th>Treatment</th>
                        <th>Procedure</th>
                        <th>Procedure Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unique_procedures as $procedure): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($procedure['treatment']); ?></td>
                            <td><?php echo htmlspecialchars($procedure['procedure']); ?></td>
                            <td><?php echo htmlspecialchars($procedure['procedure_count']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>