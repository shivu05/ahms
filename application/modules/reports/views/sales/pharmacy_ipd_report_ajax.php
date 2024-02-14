<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered dataTable" id="ipd_pharma_table" cellspacing="0"  width="100%">
        <thead>
            <tr>
                <th width="4%">Sl no.</th>
                <th width="5%">C.OPD</th>
                <th width="5%">D.OPD</th>
                <th width="5%">C.IPD</th>
                <th width="15%">Name</th>
                <th width="4%">Age</th>
                <th width="5%">Gender</th>
                <th width="15%">Doctor</th>
                <th width="10%">Department</th>
                <th width="5%">DOA</th>
                <th width="5%">DOD</th>
                <!--<th>Sales Details</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            $last_id = -1;
            foreach ($patient as $row) {
                $count++;
                ?> 
                <tr class="warning">
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row->OpdNO; ?></td>
                    <td><?php echo $row->deptOpdNo; ?></td>
                    <td><?php echo $row->ipdno; ?></td>
                    <td><?php echo $row->FName; ?></td>
                    <td><?php echo $row->Age; ?></td>
                    <td><?php echo $row->Gender; ?></td>
                    <td><?php echo $row->Doctor; ?></td>
                    <td><?php echo $row->department; ?></td>
                    <td><?php echo format_date($row->DoAdmission); ?></td>
                    <td><?php echo format_date($row->DoDischarge); ?></td>
                </tr>
                <?php
                $product_ids = explode(',', $row->product_ids);
                $product = explode(',', $row->product);
                $batch = explode(',', $row->batch);
                $qty = explode(',', $row->qty);
                ?>
                <tr>
                    <td colspan="11" cellspacing="0" style="boder:none;border-collapse:collapse;">
                        <table class="table table-bordered" cellspacing="0" width="70%" style="margin:auto;">
                            <tr class="info" style="color:black">
                                <th width="60%">Product</th>
                                <th width="15%">QTY</th>
                                <th width="25%">Action</th>
                            </tr>
                            <?php for ($i = 0; $i < sizeof($product); $i++) { ?>
                                <tr>
                                    <td width="80%"><?php echo remove_chars_from_product($product[$i]); ?></td>
                                    <!--<td><?php echo $batch[$i]; ?></td>-->
                                    <td><?php echo $qty[$i]; ?></td>
                                    <td width="10%"><i class="glyphicon glyphicon-edit product_edit" style="color:green;cursor: pointer;" data-id="<?= $product_ids[$i] ?>" data-prod_name="<?= $product[$i] ?>" data-prod_qty="<?= $qty[$i] ?>"></i> 
                                        | <i class="glyphicon glyphicon-trash text-error product_delete" style="color:red;cursor: pointer;" data-id="<?= $product_ids[$i] ?>" data-prod_name="<?= $product[$i] ?>" data-prod_qty="<?= $qty[$i] ?>"></i></td>
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