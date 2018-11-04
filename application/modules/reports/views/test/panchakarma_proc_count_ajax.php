<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered col-5" width="40%" style="margin:auto;">
        <thead>
            <tr>
                <th>Sl no.</th>
                <th>Procedure Name</th>
                <th>Procedures count</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /* EDITED BY SHIVARAJ B */
            $treatments = array('SNEHANA', 'SWEDANA', 'VAMANA', 'VIRECHANA', 'BASTI', 'NASYA', 'RAKTAMOKSHANA', 'AGNIKARMA');
            $mysol = '';
            $agnikarma = array();
            $basti = array();
            $snehana = array();
            $nasya = array();
            $swedana = array();
            $vamana = array();
            $virechana = array();
            $raktamokshna = array();
            $count = 0;
            foreach ($patient as $row) {
                $data_proc = explode(',', $row->procedure);
                $data_treatment = explode(',', $row->treatment);
                for ($i = 0; $i < sizeof($data_treatment); $i++) {
                    if ($data_treatment[$i] == "SNEHANA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($snehana, $data_proc[$i]);
                        }
                    }

                    if ($data_treatment[$i] == "SWEDANA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($swedana, $data_proc[$i]);
                        }
                    }
                    if ($data_treatment[$i] == "VAMANA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($vamana, $data_proc[$i]);
                        }
                    }
                    if ($data_treatment[$i] == "VIRECHANA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($virechana, $data_proc[$i]);
                        }
                    }

                    if ($data_treatment[$i] == "VASTI") {
                        if (sizeof($data_proc) > $i) {
                            array_push($basti, $data_proc[$i]);
                        }
                    }
                    if ($data_treatment[$i] == "NASYA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($nasya, $data_proc[$i]);
                        }
                    }
                    if ($data_treatment[$i] == "RAKTAMOKSHANA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($raktamokshna, $data_proc[$i]);
                        }
                    }
                    if ($data_treatment[$i] == "AGNIKARMA") {
                        if (sizeof($data_proc) > $i) {
                            array_push($agnikarma, $data_proc[$i]);
                        }
                    }
                }
            }

            $snh_counts = array_count_values($snehana);
            $swd_counts = array_count_values($swedana);
            $vamana_counts = array_count_values($vamana);
            $virechana_counts = array_count_values($virechana);
            $basti_counts = array_count_values($basti);
            $nasya_counts = array_count_values($nasya);
            $raktamokshna_counts = array_count_values($raktamokshna);
            $agnikarma_counts = array_count_values($agnikarma);
            ?>
            <tr><td colspan="3" align='center'><strong>SNEHANA</strong></td></tr>
            <?php
            $grand_total = 0;
            $total_snh = 0;
            foreach ($snh_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_snh += $v;
                    ?>
                </tr>

                <?php
            }
            $grand_total += $total_snh;
            ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_snh; ?></td>
            </tr>

            <tr><td colspan="3" align='center'><strong>SWEDANA</strong></td></tr>
            <?php
            $total_swed = 0;
            foreach ($swd_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_swed += $v;
                    ?>
                </tr>

            <?php } $grand_total += $total_swed; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_swed; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>VAMANA</strong></td></tr>
            <?php
            $total_vamana = 0;
            foreach ($vamana_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_vamana += $v;
                    ?>
                </tr>

            <?php } $grand_total += $total_vamana; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_vamana; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>VIRECHANA</strong></td></tr>
            <?php
            $total_virechana = 0;
            foreach ($virechana_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_virechana += $v;
                    ?>
                </tr>

            <?php } $grand_total += $total_virechana; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_virechana; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>BASTI</strong></td></tr>
            <?php
            $total_basti = 0;
            foreach ($basti_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_basti += $v;
                    ?>
                </tr>
            <?php } $grand_total += $total_basti; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_basti; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>NASYA</strong></td></tr>
            <?php
            $total_nasya = 0;
            foreach ($nasya_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_nasya += $v;
                    ?>
                </tr>

                <?php
            }
            $grand_total += $total_nasya
            ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_nasya; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>RAKTAMOKSHANA</strong></td></tr>
            <?php
            $total_raksh = 0;
            foreach ($raktamokshna_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_raksh += $v;
                    ?>
                </tr>
            <?php } $grand_total += $total_raksh; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_raksh; ?></td>
            </tr>
            <tr><td colspan="3" align='center'><strong>AGNIKARMA</strong></td></tr>
            <?php
            $total_agni = 0;
            foreach ($agnikarma_counts as $key => $v) {
                $count++;
                ?>
                <tr>
                    <?php
                    echo "<td align='center'>" . $count . "</td><td>" . $key . "</td>" . "<td align='center'>" . $v . "</td>";
                    $total_agni += $v;
                    ?>
                </tr>

            <?php } $grand_total += $total_agni; ?>
            <tr>
                <td></td><td> <td align='center'><?php echo $total_agni; ?></td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr class="alert alert-success">
                <td></td><td>GRAND TOTAL: </td> <td align='center'><?php echo $grand_total; ?></td>
            </tr>
        </tbody>
    </table>
    <?php
}
?>