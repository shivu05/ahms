<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>    <table class="table table-bordered dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="5%">Sl No</th>
                <th width="8%">C.IPD</th>
                <th width="8%">C.OPD</th>
                <th width="8%">D.OPD</th>
                <th width="25%">Name</th>
                <th width="5%">Age</th>
                <th width="8%">Gender</th>
                <th width="10%">Doctor</th>
                <th width="10%">Department</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($patient as $row) {
                $count++;
                ?> 
                <tr class="warning">
                    <td style="text-align:center;"><?php echo $count; ?></td>
                    <td style="text-align:center;"><?php echo $row->IpNo; ?></td>
                    <td style="text-align:center;"><?php echo $row->OpdNo; ?></td>
                    <td style="text-align:center;"><?php echo $row->deptOpdNo; ?></td>
                    <td><?php echo $row->FName; ?></td>
                    <td style="text-align:center;"><?php echo $row->Age; ?></td>
                    <td style="text-align:center;"><?php echo $row->Gender; ?></td>
                    <td><?php echo $row->Doctor; ?></td>
                    <td><?php echo $row->department; ?></td>
                </tr>
                <?php
                $product = explode(',', $row->product);
                $indentdate = explode(',', $row->indentdate);
                $morning = explode(',', $row->morning);
                $afternoon = explode(',', $row->afternoon);
                $night = explode(',', $row->night);
                $totalqty = explode(',', $row->totalqty);
                ?>
                <tr><td colspan=9 style="text-align: center;font-size: 1.2em">Indent details</td></tr>
                <tr>
                    <td colspan=9 style="white-space: nowrap;page-break-inside: avoid;">
                        <table class="table table-condensed table-bordered"  width="70%" style="margin:auto;white-space: nowrap;">
                            <thead>
                                <tr class="info" style="color:black;">
                                    <th>Product</th>
                                    <th>M</th>
                                    <th>A</th>
                                    <th>N</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < sizeof($product); $i++) { ?>
                                    <tr>
                                        <td style="text-align:left;"><?php echo $product[$i]; ?></td>					
                                        <td><?php echo $morning[$i]; ?></td>
                                        <td><?php echo $afternoon[$i]; ?></td>
                                        <td><?php echo $night[$i]; ?></td>
                                        <td><?php echo $totalqty[$i]; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($indentdate[$i])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
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