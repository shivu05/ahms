<form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo $submit_url; ?>">
    <div class="form-group col-md-2 col-sm-12">
        <label class="control-label sr-only">From:</label>
        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
    </div>
    <div class="form-group col-md-2 col-sm-12">
        <label class="control-label  sr-only">To:</label>
        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
    </div>
    <?php if (!$dept_hide) { ?>
        <div class="form-group col-md-3 col-sm-12">
            <label class="control-label  sr-only">Department:</label>
            <select name="department" id="department" class="form-control" required="required">
                <option value="">Select Department</option>
                <option value="1">Central</option>
                <?php
                if (!empty($dept_list)) {
                    foreach ($dept_list as $dept) {
                        echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    <?php } ?>
    <div class="form-group col-md-4 col-sm-12 align-self-end">
        <button class="btn btn-primary btn-sm" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
        <div class="btn-group" role="group" id="export">
            <button class="btn btn-info  btn-sm" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a></li>
                </ul>
            </div>
        </div>
        <?php if ($delete_btn_active == true) { ?>
            <button class="btn btn-danger btn-sm" type="button" id="btn_delete"><i class="fa fa-fw fa-lg fa-trash"></i>Delete</button>
        <?php } ?>
    </div>
</form>