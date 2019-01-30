<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <h5>Record analysis:</h5>
            </div>
            <div class="row tile-body">
                <div class="col-lg-12">
                    <?php
                    if ($this->session->flashdata('noty_msg') != '') {
                        echo $this->session->flashdata('noty_msg');
                    }
                    ?>
                    <form action="<?php echo base_url('auto/move'); ?>" method="POST">
                        <div class="form-group">                
                            <label class="col-md-1 control-label" for="textinput">Target:</label>                  
                            <div class="col-md-4">
                                <input name="target" type="text" class="form-control required" id="target" placeholder="Enter Target "required="required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Date:</label>
                            <div class="col-md-4">
                                <input name="cdate" type="text" autocomplete="off" class="form-control date_picker required" id="cdate" placeholder="Enter Date" required="required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="textinput">New Patients %:</label>
                            <div class="col-md-4">
                                <input name="newpatient" type="text" class="form-control required" id="newpatient" placeholder="Enter New Patient %" required="required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="textinput">Panchakarma procedure count:</label>
                            <div class="col-md-4">
                                <input name="pancha_count" type="text" class="form-control required" id="pancha_count" placeholder="Enter New Patient %" required="required" value="5"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary btn-block" value="Generate">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">    // When the document is ready    
    $(document).ready(function () {
        $('#date').datepicker({
            format: "yyyy-mm-dd"
        });

        $('#pancha_count').keypress(function (event) {
            var keycode = event.which;
            if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
                event.preventDefault();
            }
        });
    });
</script>
