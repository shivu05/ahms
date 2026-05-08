<?php
$values = isset($values) && is_array($values) ? $values : array();
$errors = isset($errors) && is_array($errors) ? $errors : array();
$department_list = isset($department_list) && is_array($department_list) ? $department_list : array();
$gender = isset($values['gender']) ? $values['gender'] : '';
$selected_department = isset($values['department']) ? $values['department'] : '';
$selected_sub_department = isset($values['sub_dept']) ? $values['sub_dept'] : '';
$is_shalakya_department = $selected_department === 'SHALAKYA_TANTRA';
?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= html_escape($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="FirstName">First Name:</label>
            <input class="form-control required" id="FirstName" name="FirstName" type="text" value="<?= html_escape(isset($values['FirstName']) ? $values['FirstName'] : '') ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="MidName">Middle Name:</label>
            <input class="form-control" id="MidName" name="MidName" type="text" value="<?= html_escape(isset($values['MidName']) ? $values['MidName'] : '') ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="LastName">Last Name:</label>
            <input class="form-control" id="LastName" name="LastName" type="text" value="<?= html_escape(isset($values['LastName']) ? $values['LastName'] : '') ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="Age">Age:</label>
            <input class="form-control required" id="Age" name="Age" type="text" value="<?= html_escape(isset($values['Age']) ? $values['Age'] : '') ?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control required" id="gender" name="gender">
                <option value="">Choose gender</option>
                <option value="Male" <?= $gender === 'Male' ? 'selected="selected"' : '' ?>>Male</option>
                <option value="Female" <?= $gender === 'Female' ? 'selected="selected"' : '' ?>>Female</option>
                <option value="Others" <?= $gender === 'Others' ? 'selected="selected"' : '' ?>>Others</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="occupation">Occupation:</label>
            <input class="form-control required" id="occupation" name="occupation" type="text" value="<?= html_escape(isset($values['occupation']) ? $values['occupation'] : '') ?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="Mobileno">Mobile No:</label>
            <input class="form-control" id="Mobileno" maxlength="10" name="Mobileno" type="text" value="<?= html_escape(isset($values['Mobileno']) ? $values['Mobileno'] : '') ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="address">Address:</label>
            <input class="form-control required" id="address" name="address" type="text" value="<?= html_escape(isset($values['address']) ? $values['address'] : '') ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="city">City:</label>
            <input class="form-control required" id="city" name="city" type="text" value="<?= html_escape(isset($values['city']) ? $values['city'] : '') ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="diagnosis">Diagnosis:</label>
            <input class="form-control required" id="diagnosis" name="diagnosis" type="text" value="<?= html_escape(isset($values['diagnosis']) ? $values['diagnosis'] : '') ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="department">Department:</label>
            <select class="form-control required" id="department" name="department">
                <option value="">Choose department</option>
                <?php foreach ($department_list as $dept): ?>
                    <option value="<?= html_escape($dept['dept_unique_code']) ?>" <?= $selected_department === $dept['dept_unique_code'] ? 'selected="selected"' : '' ?>>
                        <?= html_escape($dept['department']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="sub_dept">Sub Department:</label>
            <select class="form-control" id="sub_dept" name="sub_dept" <?= $is_shalakya_department ? '' : 'disabled="disabled"' ?>>
                <option value="">Choose sub department</option>
                <option value="Netra-Roga Vibhaga" <?= $selected_sub_department === 'Netra-Roga Vibhaga' ? 'selected="selected"' : '' ?>>Netra-Roga Vibhaga</option>
                <option value="karna-Nasa-Mukha &amp; Danta Vibhaga" <?= $selected_sub_department === 'karna-Nasa-Mukha &amp; Danta Vibhaga' ? 'selected="selected"' : '' ?>>karna-Nasa-Mukha &amp; Danta Vibhaga</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="procedures">Procedures:</label>
            <input class="form-control" id="procedures" name="procedures" type="text" value="<?= html_escape(isset($values['procedures']) ? $values['procedures'] : '') ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="complaints">Complaints:</label>
            <input class="form-control" id="complaints" name="complaints" type="text" value="<?= html_escape(isset($values['complaints']) ? $values['complaints'] : '') ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="Trtment">Treatment:</label>
            <textarea class="form-control required" id="Trtment" name="Trtment" rows="4"><?= html_escape(isset($values['Trtment']) ? $values['Trtment'] : '') ?></textarea>
            <small class="text-muted">Enter comma-separated values. Trailing commas are auto-cleaned with a warning.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="medicines">Medicines:</label>
            <textarea class="form-control required" id="medicines" name="medicines" rows="4"><?= html_escape(isset($values['medicines']) ? $values['medicines'] : '') ?></textarea>
            <small class="text-muted">Enter comma-separated values. Empty items are removed automatically.</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="aadhaar_number">Aadhaar Number:</label>
            <input class="form-control" id="aadhaar_number" maxlength="12" name="aadhaar_number" type="text" value="<?= html_escape(isset($values['aadhaar_number']) ? $values['aadhaar_number'] : '') ?>">
            <small class="text-muted">Optional. Enter 12 digits only.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="abha_id">ABHA ID:</label>
            <input class="form-control" id="abha_id" maxlength="50" name="abha_id" type="text" value="<?= html_escape(isset($values['abha_id']) ? $values['abha_id'] : '') ?>">
            <small class="text-muted">Optional. Use 14 digits or `username@abdm`.</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"><?= html_escape(isset($values['notes']) ? $values['notes'] : '') ?></textarea>
        </div>
    </div>
</div>
