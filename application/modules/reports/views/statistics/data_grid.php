<table class="table table-bordered table-primary dataTable" style="width:50%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($data)) {
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>'.$row['cur_date'].'</td>';
            echo '<td>'.$row['count_p'].'</td>';
             echo '</tr>';
        }
    }
    ?>
    </tbody>
</table>