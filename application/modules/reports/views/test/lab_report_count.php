<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    $result = array();
    foreach ($patient as $element) {
        $result[$element['lab_cat_name']][] = $element;
    }
    //pma($result, 1);
    ?>
    <hr/>
    <table class="table table-bordered" cellspacing="0"  width="100%" style="margin:auto;">
        <thead>	
            <tr style="background: lightgray;font-weight: bold;">
                <th>Sl no.</th>
                <th>Test Name</th>
                <th>No. Of Tests</th>
            </tr>
        </thead>
        <tbody>	
            <?php
            if (!empty($result)) {
                foreach ($result as $key => $row) {
                    $tr = '<tr style="background-color:#c9c8c5;color:#0000FF;"><td colspan="3">' . $key . '</td></tr>';
                    if (!empty($row)) {
                        $i = 1;
                        foreach ($row as $r) {
                            $tr .= '<tr>';
                            $tr .= '<td>' . $i . '</td>';
                            $tr .= '<td>' . $r['lab_inv_name'] . '</td>';
                            $tr .= '<td>' . $r['ccount'] . '</td>';
                            $tr .= '</tr>';
                            $i++;
                        }
                    }
                    echo $tr;
                }
            }
            ?>
        </tbody>
    </table>
    <?php
}