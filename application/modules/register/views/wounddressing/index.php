<!DOCTYPE html>
<html>
<head>
    <title>Wound Dressing Records</title>
</head>
<body>
    <h1>Wound Dressing Records</h1>
    <a href="<?php echo base_url('wound_dressing/create'); ?>">Add New Record</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>OPD No</th>
                <th>IPD No</th>
                <th>Treat ID</th>
                <th>Ref Date</th>
                <th>Wound Location</th>
                <th>Wound Type</th>
                <th>Dressing Material</th>
                <th>Doctor Name</th>
                <th>Next Dressing Date</th>
                <th>Doctor Remarks</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo $record->id; ?></td>
                <td><?php echo $record->opd_no; ?></td>
                <td><?php echo $record->ipd_no; ?></td>
                <td><?php echo $record->treat_id; ?></td>
                <td><?php echo $record->ref_date; ?></td>
                <td><?php echo $record->wound_location; ?></td>
                <td><?php echo $record->wound_type; ?></td>
                <td><?php echo $record->dressing_material; ?></td>
                <td><?php echo $record->doctor_name; ?></td>
                <td><?php echo $record->next_dressing_date; ?></td>
                <td><?php echo $record->doctor_remarks; ?></td>
                <td><?php echo $record->last_updated; ?></td>
                <td>
                    <a href="<?php echo base_url('wound_dressing/edit/'.$record->id); ?>">Edit</a>
                    <a href="<?php echo base_url('wound_dressing/delete/'.$record->id); ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>