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
    <table class="table table-bordered" width="100%" style="margin:auto;">
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
                    }
                    echo $tr;
                }
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>