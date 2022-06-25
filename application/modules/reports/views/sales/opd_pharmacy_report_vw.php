<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" id="opd_pharma_table" cellspacing="0"  width="100%">
        <thead>
            <tr class="bg-aqua">
                <th width="5%">Sl no.</th>
                <th width="5%"> C.OPD</th>
                <th width="5%"> D.OPD.</th>
                <th width="25%">Name</th>
                <th width="4%">Age</th>
                <th>Sex</th>
                <th>Pat Type</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Date</th>
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
                    <td style="text-align: center;"><?php echo $count; ?></td>
                    <td style="text-align: center;"><?php echo $row->OpdNo; ?></td>
                    <td style="text-align: center;"><?php echo $row->deptOpdNo; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td style="text-align: center;"><?php echo $row->Age; ?></td>
                    <td style="text-align: center;"><?php echo $row->gender; ?></td>
                    <td style="text-align: center;"><?php echo $row->PatType; ?></td>
                    <td><?php echo $row->AddedBy; ?></td>
                    <td><?php echo $row->dept; ?></td>
                    <td><?php echo format_date($row->CameOn); ?></td>
                </tr>
                <?php
                $product_ids = explode(',', $row->product_ids);
                $product = explode(',', $row->product);
                $batch = explode(',', $row->batch);
                $qty = explode(',', $row->qty);
                ?>
                <tr>
                    <td colspan='10' cellspacing="0" style="boder:none;border-collapse:collapse;">
                        <table class="table table-bordered" cellspacing="0" width="70%" style="margin:auto;">
                            <tr class="info" style="color:black">
                                <th style="width: 60%" width="60%">Product</th>
                                <th width="20%">QTY</th>
                                <?php if (!$is_print): ?>
                                    <th width="20%">Action</th>
                                <?php endif; ?>
                            </tr>
                            <?php
                            for ($i = 0; $i < sizeof($product); $i++) {
                                if (trim($product[$i]) != '') {
                                    ?>
                                    <tr>
                                        <td width="80%"><?php echo remove_chars_from_product($product[$i]); ?></td>
                                        <td width="10%" style="text-align: center;"><?php echo $qty[$i]; ?></td>
                                        <?php if (!$is_print): ?>
                                            <td width="10%"><i class="fa fa-edit product_edit" style="color:green;cursor: pointer;" data-id="<?= $product_ids[$i] ?>" data-prod_name="<?= $product[$i] ?>" data-prod_qty="<?= $qty[$i] ?>"></i> 
                                                | <i class="fa fa-trash text-error product_delete" style="color:red;cursor: pointer;" data-id="<?= $product_ids[$i] ?>" data-prod_name="<?= $product[$i] ?>" data-prod_qty="<?= $qty[$i] ?>"></i></td>
                                            <?php endif; ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
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