<!DOCTYPE html>
<html>
<head>
    <title>Add Siravyadana Record</title>
</head>
<body>
    <h1>Add Siravyadana Record</h1>
    <form action="<?php echo base_url('siravyadana/store'); ?>" method="POST">
        <label>OPD No:</label>
        <input type="text" name="opd" required><br>
        <label>IPD No:</label>
        <input type="text" name="ipd"><br>
        <label>Treat ID:</label>
        <input type="text" name="tid"><br>
        <label>Ref Date:</label>
        <input type="date" name="ref_date" required><br>
        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" required><br>
        <label>Procedure Details:</label>
        <textarea name="procedure_details" required></textarea><br>
        <label>Doctor Remarks:</label>
        <textarea name="doctor_remarks"></textarea><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>