<hr>
<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    $result = array();
    foreach ($patient as $element) {
        $result[$element['treatment']][] = $element;
    }
    ?>
    <table class="table table-bordered" width="100%" cellpadding="0">
        <thead>
            <tr style="background: lightgray;font-weight: bold;">
                <th width='50'>Sl no.</th>
                <th>Procedure Name</th>
                <th>Procedures count</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($result)) {
                $total = 0;
                foreach ($result as $key => $row) {
                    $tr = '<tr style="background-color:#c9c8c5;color:#0000FF;"><td colspan="3">' . $key . '</td></tr>';
                    $i = 1;
                    foreach ($row as $r) {
                        $tr .= "<tr>";
                        $tr .= "<td width='50'>" . $i . "</td>";
                        $tr .= "<td>" . $r['procedure'] . "</td>";
                        $tr .= '<td align="center">' . $r['procedure_count'] . '</td>';
                        $tr .= "</tr>";
                        $i++;
                        $total += $r['procedure_count'];
                    }
                    echo $tr;
                }
                echo '<tr style="background-color:#c9c8c5;color:#0000FF;"><td colspan="2">Total:</td><td align="center">' . $total . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>