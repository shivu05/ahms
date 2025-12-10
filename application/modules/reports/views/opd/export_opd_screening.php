<table class="table table-bordered">
    <thead>
        <tr>
            <th class="center" width="4%">Sl No.</th>
            <th width="8%">C.OPD</th>
            <th width="14%">Name</th>
            <th width="4%">Age</th>
            <th width="6%">Gender</th>
            <th width="18%">City</th>
            <th width="8%">Blood Pressure</th>
            <th width="6%">Pulse</th>
            <th width="8%">Resp. Rate</th>
            <th width="8%">Temperature</th>
            <th width="6%">SpO2</th>
            <th width="6%">Weight</th>
            <th width="8%">Date of Visit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (empty($opd_patients)) {
            echo '<tr><td colspan="15" class="center">No data</td></tr>';
        } else {
            $i = 0;
//            pma($opd_patients, 1);
            foreach ($opd_patients as $patients) {
                $i++;
                $id = isset($patients['id']) ? $patients['id'] : '';
                $opd_no = isset($patients['opd_no']) ? $patients['opd_no'] : '';
                $date_of_visit = isset($patients['date_of_visit']) ? $patients['date_of_visit'] : '';
                $bp = isset($patients['blood_pressure']) ? $patients['blood_pressure'] : '';
                $pulse = isset($patients['pulse_rate']) ? $patients['pulse_rate'] : '';
                $rr = isset($patients['respiratory_rate']) ? $patients['respiratory_rate'] : '';
                $temp = isset($patients['body_temperature']) ? $patients['body_temperature'] : '';
                $spo2 = isset($patients['spo2']) ? $patients['spo2'] : '';
                $weight = isset($patients['weight']) ? $patients['weight'] : '';
                $created_at = isset($patients['created_at']) ? $patients['created_at'] : '';
                $name = isset($patients['name']) ? $patients['name'] : '';
                $age = isset($patients['Age']) ? $patients['Age'] : '';
                $gender = isset($patients['gender']) ? $patients['gender'] : '';
                $address = isset($patients['address']) ? $patients['address'] : '';
                $city = isset($patients['city']) ? $patients['city'] : '';

                echo '<tr>';
                echo '<td class="center">' . $i . '</td>';
                echo '<td>' . htmlspecialchars($opd_no) . '</td>';
                echo '<td>' . htmlspecialchars($name) . '</td>';
                echo '<td class="center">' . htmlspecialchars($age) . '</td>';
                echo '<td class="center">' . htmlspecialchars($gender) . '</td>';
                echo '<td>' . htmlspecialchars(trim($city)) . '</td>';
                echo '<td>' . htmlspecialchars($bp) . '</td>';
                echo '<td class="center">' . htmlspecialchars($pulse) . '</td>';
                echo '<td class="center">' . htmlspecialchars($rr) . '</td>';
                echo '<td class="center">' . htmlspecialchars($temp) . '</td>';
                echo '<td class="center">' . htmlspecialchars($spo2) . '</td>';
                echo '<td class="center">' . htmlspecialchars($weight) . '</td>';
                echo '<td>' . (!empty($date_of_visit) ? htmlspecialchars(date('Y-m-d', strtotime($date_of_visit))) : '') . '</td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>