<!DOCTYPE html>
<html>
<head>
    <title>Add Wound Dressing Record</title>
</head>
<body>
    <h1>Add Wound Dressing Record</h1>
    <form action="<?php echo base_url('wound_dressing/store'); ?>" method="POST">
        <label>OPD No:</label>
        <input type="text" name="opd" required><br>
        <label>IPD No:</label>
        <input type="text" name="ipd"><br>
        <label>Treat ID:</label>
        <input type="text" name="tid"><br>
        <label>Ref Date:</label>
        <input type="date" name="ref_date" required><br>
        <label>Wound Location:</label>
        <input type="text" name="wound_location" required><br>
        <label>Wound Type:</label>
        <input type="text" name="wound_type" required><br>
        <label>Dressing Material:</label>
        <textarea name="dressing_material" required></textarea><br>
        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" required><br>
        <label>Next Dressing Date:</label>
        <input type="date" name="next_dressing_date"><br>
        <label>Doctor Remarks:</label>
        <textarea name="doctor_remarks"></textarea><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>