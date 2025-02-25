<!DOCTYPE html>
<html>
<head>
    <title>Edit Siravyadana Record</title>
</head>
<body>
    <h1>Edit Siravyadana Record</h1>
    <form action="<?php echo base_url('siravyadana/update/'.$record->id); ?>" method="POST">
        <label>OPD No:</label>
        <input type="text" name="opd_no" value="<?php echo $record->opd_no; ?>" required><br>
        <label>IPD No:</label>
        <input type="text" name="ipd_no" value="<?php echo $record->ipd_no; ?>"><br>
        <label>Ref Date:</label>
        <input type="date" name="ref_date" value="<?php echo $record->ref_date; ?>" required><br>
        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" value="<?php echo $record->doctor_name; ?>" required><br>
        <label>Procedure Details:</label>
        <textarea name="procedure_details" required><?php echo $record->procedure_details; ?></textarea><br>
        <label>Doctor Remarks:</label>
        <textarea name="doctor_remarks"><?php echo $record->doctor_remarks; ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>