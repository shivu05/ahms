<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" cellspacing="0"  width="40%" style="margin:auto;">
        <thead>	
            <tr>
                <th>Sl no.</th>
                <th>Test Name</th>
                <th>No. Of Tests</th>
            </tr>
        </thead>
        <tbody>	
            <?php
            $mysol = '';
            foreach ($patient as $row) {
                if (!empty($row->testName)) {
                    $procedurename = array();
                    $procedurename = $row->testName;
                    $procedurename = $procedurename . "" . $mysol;
                    $mysol = "," . $procedurename;
                }
            }
            $done = array();
            $done = explode(',', $procedurename);
            $vals = array_count_values($done);
            $done = array_unique($done);
            $count = 0;
            $lastarr = array();
            foreach ($vals as $res) {
                array_push($lastarr, $res);
            }
            foreach ($done as $val) {
                echo "<tr>";
                ?> <td style='text-align:center;'><?php echo ($count + 1); ?></td>
            <?php
            echo "<td>" . $val . "</td>";
            echo "<td style='text-align:center;'>" . $lastarr[$count] . "</td>";
            $count++;
        }
        ?>
    </tbody>
    </table>
    <?php
}