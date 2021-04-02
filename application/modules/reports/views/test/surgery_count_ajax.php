<?php
if (empty($patient)) {
    echo "<h4 class='center red'>No Records found</h4>";
} else {
    ?>
    <table class="table table-bordered" width="80%" style="margin:auto;">
        <thead>
            <tr>
                <th width="10%">Sl no.</th>
                <th width="25%">Surgery Name</th>
                <th width="10%">Surgery type</th>
                <th width="10%">No. Of Times</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $major = 0;
            $minor = 0;
            foreach ($patient as $row) {
                echo "<tr>";
                echo "<td style='text-align:center;'>" . ($i + 1) . "</td>";
                echo "<td>" . $row->surgeryname . "</td>";
                echo "<td style='text-align:center;'>" . $row->surgType . "</td>";
                echo "<td style='text-align:center;'>" . $row->count . "</td>";
                if ($row->surgType == "MAJOR") {
                    $major = $major + $row->count;
                } else {
                    $minor = $minor + $row->count;
                }
                $i++;
            }
            ?>

        </tbody>
    </table>
    <table class="table table-bordered" width="40%" style="margin:auto;padding-left:5%">
        <tr><th>Major surgeries:</th><th>Minor Surgeries:</th></tr>
        <tr><td style="text-align:center"><?php echo $major; ?></td><td style="text-align:center"><?php echo $minor; ?></td></tr>
    </table>
    <?php
}