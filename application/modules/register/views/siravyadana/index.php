<!DOCTYPE html>
<html>
<head>
    <title>Siravyadana Records</title>
</head>
<body>
    <h1>Siravyadana Records</h1>
    <a href="<?php echo base_url('siravyadana/create'); ?>">Add New Record</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>OPD No</th>
                <th>IPD No</th>
                <th>Treat ID</th>
                <th>Ref Date</th>
                <th>Doctor Name</th>
                <th>Procedure Details</th>
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
                <td><?php echo $record->doctor_name; ?></td>
                <td><?php echo $record->procedure_details; ?></td>
                <td><?php echo $record->doctor_remarks; ?></td>
                <td><?php echo $record->last_updated; ?></td>
                <td>
                    <a href="<?php echo base_url('siravyadana/edit/'.$record->id); ?>">Edit</a>
                    <a href="<?php echo base_url('siravyadana/delete/'.$record->id); ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>