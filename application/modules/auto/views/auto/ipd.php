<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-database"></i> IPD Record analysis:</h3>
            </div>
            <form action="<?php echo base_url('analyse-ipd-data'); ?>" method="POST" name="gen_form" id="gen_form">
                <div class="box-body">
                    Date: <input type="text" name="cdate" class="form-control date_picker required" id="cdate"
                        placeholder="Enter Date" required="required" style="width:25%" />
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Department</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($departments as $index => $dept): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo $dept['department']; ?></td>
                                                <td>
                                                    <input type="number" id="<?php echo $dept['dept_unique_code']; ?>"
                                                        name="<?php echo $dept['dept_unique_code']; ?>" value="0"
                                                        class="form-control form-control-sm" style="width: 50%;" />
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="form-group pull-right">
                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" id="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.gen_form').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: 'POST',
                url: url,
                data: form.serializeArray(),
                success: function (response) {
                    // Handle success response
                    alert('Data submitted successfully!');
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    alert('An error occurred: ' + error);
                }
            });
        });
    });

</script>