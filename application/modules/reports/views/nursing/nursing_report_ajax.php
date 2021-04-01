<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered dataTable" cellspacing="0"  width="100%">
        <thead>
            <tr>
                <th width="5%">Sl.No</th>
                <th width="5%">C.IPD</th>
                <th width="5%">C.OPD</th>
                <th width="5%">D.OPD</th>
                <th width="20%">Name</th>
                <th width="5%">Age</th>
                <th width="8%">Gender</th>
                <th width="8%">DOA</th>
                <th width="8%">DOD</th>
                <th width="3%">Ward</th>
                <th width="3%">Bed</th>
                <th width="10%">Department</th>
                <th width="15%">Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            $arr_id = array();
            foreach ($patient as $row) {
                $count++;
                ?>
                <tr class="warning">
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row->IpNo; ?></td>
                    <td><?php echo $row->OpdNo; ?></td>
                    <td><?php echo $row->deptOpdNo; ?></td>
                    <td><?php echo $row->FName; ?></td>
                    <td><?php echo $row->Age; ?></td>
                    <td><?php echo $row->Gender; ?></td>
                    <td><?php echo format_date($row->DoAdmission); ?></td>
                    <td><?php echo format_date($row->DoDischarge); ?></td>
                    <td><?php echo $row->WardNo; ?></td>
                    <td><?php echo $row->BedNo; ?></td>
                    <td><?php echo $row->department; ?></td>
                    <td><?php echo $row->Doctor; ?></td>
                    <?php
                    $product = explode(',', $row->product);
                    $indentdate = explode(',', $row->indentdate);
                    ?>
                </tr>
                <tr>
                    <td colspan='13'>
                        <table class="table table-bordered" cellspacing="0"  width="70%" style="margin:auto;">
                            <tr class="info" style="color:black;"><th width="75%">Product</th><th width="20%">Date</th></tr>
                                    <?php for ($i = 0; $i < sizeof($product); $i++) { ?>
                                <tr>
                                    <td><?php echo $product[$i]; ?></td>
                                    <td><?php echo format_date($indentdate[$i]); ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>