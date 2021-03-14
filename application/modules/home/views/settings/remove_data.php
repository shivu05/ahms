<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-database"></i> Delete records:</h3>
            </div>
            <form action="<?php echo base_url('delete-patients'); ?>" method="POST" name="delete_form" id="delete_form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 text-success">
                            <?php
                            if ($this->session->flashdata('noty_msg') != '') {
                                echo $this->session->flashdata('noty_msg');
                            }
                            ?>
                        </div>
                        <div class="col-md-12">
                            <p class="alert alert-warning">Note: Once the data is deleted can not be restored, and from the selected date
                                all the further data will be deleted including the selected date</p>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="cdate">Date: <span class="text-danger">*</span></label>
                            <div class="">
                                <input name="cdate" type="text" autocomplete="off" class="form-control date_picker required" id="cdate" placeholder="Enter Date" required="required" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-4">
                        <button class="btn btn-danger pull-right btn-sm" id="delete_btn" name="delete_btn"><i class="fa fa-trash-o"></i> Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">    // When the document is ready    
    $(document).ready(function () {
        $('#delete_form').validate();
        $('#delete_btn').on('click', function (e) {
            e.preventDefault();
            if ($('#delete_form').valid()) {
                var cdate = $('#cdate').val();
                //$('#delete_form').submit();
                BootstrapDialog.show({
                    title: 'Delete the records',
                    message: 'Are you sure? you want to delete the patient records from the date :' + cdate + ' onwards',
                    buttons: [{
                            label: 'Yes',
                            cssClass: 'btn-sm btn-primary',
                            action: function (dialog) {
                                //dialog.setMessage('Message 1');
                                $('#delete_form').submit();
                            }
                        }, {
                            label: 'No',
                            cssClass: 'btn-sm btn-danger',
                            action: function (dialogItself) {
                                dialogItself.close();
                            }
                        }]
                });
            } else {
                alter('invalid');
            }
        });

        $('#date').datepicker({
            format: "yyyy-mm-dd"
        });
    });
</script>
