<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-title">Department list:</div>
            <div class="tile-body">
                <?php //pma($depts) ?>
                <div class="col-4">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Sl. No</th>
                                <th>Department name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($depts)) {
                                $i = 1;
                                foreach ($depts as $dept) {
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td>' . $dept['department'] . '</td>';
                                    echo '</tr>';
                                    $i++;
                                }
                            } else {
                                echo '<tr><td colspan=2>No departments found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>