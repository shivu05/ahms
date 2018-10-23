<form class="row" name="search_form" id="search_form" method="POST" target="_blank" action="<?php echo $submit_url; ?>">
    <div class="form-group col-md-2 col-sm-12">
        <label class="control-label">From:</label>
        <input class="form-control date_picker" type="text" placeholder="From date" name="start_date" id="start_date" autocomplete="off" required="required">
    </div>
    <div class="form-group col-md-2 col-sm-12">
        <label class="control-label">To:</label>
        <input class="form-control date_picker" type="text" placeholder="To date" name="end_date" id="end_date" required="required" autocomplete="off">
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label class="control-label">Department:</label>
        <select name="department" id="department" class="form-control" required="required">
            <option value="">Select Department</option>
            <option value="1">Central OPD</option>
            <?php
            if (!empty($dept_list)) {
                foreach ($dept_list as $dept) {
                    echo "<option value='" . $dept['dept_unique_code'] . "'>" . $dept['department'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md-4 col-sm-12 align-self-end">
        <button class="btn btn-primary" type="button" id="search"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
        <div class="btn-group" role="group" id="export">
            <button class="btn btn-info" type="button"><i class="fa fa-fw fa-lg fa-upload"></i> Export</button>
            <div class="btn-group" role="group">
                <button class="btn btn-info dropdown-toggle" id="btnGroupDrop3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(36px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="#" id="export_to_pdf">.pdf</a>
                    <a class="dropdown-item" href="#" id="export_to_xls">.xls</a>
                </div>
            </div>
        </div>
    </div>
</form>