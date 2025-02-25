<!DOCTYPE html>
<html>
<head>
    <title>Edit Wound Dressing Record</title>
</head>
<body>
    <h1>Edit Wound Dressing Record</h1>
    <form action="<?php echo base_url('wound_dressing/update/'.$record->id); ?>" method="POST">
        <label>OPD No:</label>
        <input type="text" name="opd_no" value="<?php echo $record->opd_no; ?>" required><br>
        <label>IPD No:</label>
        <input type="text" name="ipd_no" value="<?php echo $record->ipd_no; ?>"><br>
        <label>Ref Date:</label>
        <input type="date" name="ref_date" value="<?php echo $record->ref_date; ?>" required><br>
        <label>Wound Location:</label>
        <input type="text" name="wound_location" value="<?php echo $record->wound_location; ?>" required><br>
        <label>Wound Type:</label>
        <input type="text" name="wound_type" value="<?php echo $record->wound_type; ?>" required><br>
        <label>Dressing Material:</label>
        <textarea name="dressing_material" required><?php echo $record->dressing_material; ?></textarea><br>
        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" value="<?php echo $record->doctor_name; ?>" required><br>
        <label>Next Dressing Date:</label>
        <input type="date" name="next_dressing_date" value="<?php echo $record->next_dressing_date; ?>"><br>
        <label>Doctor Remarks:</label>
        <textarea name="doctor_remarks"><?php echo $record->doctor_remarks; ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>