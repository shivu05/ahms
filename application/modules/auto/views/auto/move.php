<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-database"></i> Record analysis:</h3>
                <a class="pull-right btn btn-primary btn-sm" href="<?php echo base_url('show-ref'); ?>"><i class="fa fa-eye"></i> View patient data</a>
            </div>
            <form action="<?php echo base_url('analyse-data'); ?>" method="POST" name="gen_form" id="gen_form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 text-success">
                            <?php
                            if ($this->session->flashdata('noty_msg') != '') {
                                echo $this->session->flashdata('noty_msg');
                            }
                            ?>
                        </div>
                        <div class="col-md-12 text-danger">
                            <?php
                            if ($doc_duty_count == 'N') {
                                echo "<p>Please check the doctors duty chart. Its incomplete</p>";
                            }
                            ?>
                        </div>
                        <div class="col-md-4">                
                            <label class="control-label" for="textinput">Target:</label>                  
                            <div class="">
                                <input name="target" type="text" class="form-control required" id="target" placeholder="Enter Target "required="required" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="textinput">Date:</label>
                            <div class="">
                                <input name="cdate" type="text" autocomplete="off" class="form-control date_picker required" id="cdate" placeholder="Enter Date" required="required" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="textinput">New Patients %:</label>
                            <div class="">
                                <input name="newpatient" type="text" class="form-control required" id="newpatient" placeholder="Enter New Patient %" required="required" />
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none;">
                            <label class="control-label" for="textinput">Panchakarma procedure count:</label>
                            <div class="">
                                <input name="pancha_count" disabled="disabled" type="text" class="form-control required disabled" id="pancha_count" placeholder="Enter New Patient %" required="required" value="5"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="singlebutton"></label>
                            <div class=""></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <?php if ($doc_duty_count != 'N') { ?>
                        <input type="submit" class="btn btn-primary btn-sm pull-right disable" id="generate" value="Generate">
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">    // When the document is ready    
    $(document).ready(function () {
        $('.disable').click(function () {
            $(this).prop('disabled', true);
            $('#gen_form').submit();
        });

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
