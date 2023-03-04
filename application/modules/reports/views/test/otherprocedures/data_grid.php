<table class="table table-bordered" id="physic_grid" width="100%">
    <thead>
        <tr>
            <th>Sl.No</th>
            <th>C.OPD</th>
            <th>C.IPD</th>
            <th>D.OPD</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Diagnosis</th>
            <th>Department</th>
            <th>Procedure</th>
            <th>Physician</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($physic_list)) {
            $i = 0;
            foreach ($physic_list as $row) {
                $tr = '<tr>';
                $tr .= '<td>' . (++$i) . '</td>';
                $tr .= '<td>' . $row['OpdNo'] . '</td>';
                $tr .= '<td>' . $row['IpNo'] . '</td>';
                $tr .= '<td>' . $row['deptOpdNo'] . '</td>';
                $tr .= '<td>' . $row['name'] . '</td>';
                $tr .= '<td>' . $row['Age'] . '</td>';
                $tr .= '<td>' . $row['gender'] . '</td>';
                $tr .= '<td>' . $row['diagnosis'] . '</td>';
                $tr .= '<td>' . $row['department'] . '</td>';
                $tr .= '<td>' . $row['therapy_name'] . '</td>';
                $tr .= '<td>' . $row['physician'] . '</td>';
                $tr .= '<td>' . $row['referred_date'] . '</td>';
                $tr .= "<td><center><i class='fa fa-edit hand_cursor edit' data-id='" . $row['id'] . "'></i></center></td>";
                $tr .= '</tr>';
                echo $tr;
            }
        }
        ?>
    </tbody>
</table>
    <?php

