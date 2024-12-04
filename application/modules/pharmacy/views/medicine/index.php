<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-book"></i> Medicine List:</h3>
                <a href="<?php echo site_url('medicine/add'); ?>" class="btn btn-sm btn-primary pull-right">Add Medicine</a>
            </div>
            <div class="box-body">
                <table class="table table-bordered datatable">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    if (empty($medicines)) {
                        echo '<tr><td colspan=7 style="text-align:center;color:orange">No data found</td></tr>';
                    } else {
                        $i=0;
                        foreach ($medicines as $medicine) :
                            ?>
                            <tr>
                                <td><?php echo (++$i); ?></td>
                                <td><?php echo $medicine->name; ?></td>
                                <td><?php echo $medicine->category; ?></td>
                                <td><?php echo $medicine->price; ?></td>
                                <td><?php echo $medicine->quantity; ?></td>
                                <td><?php echo $medicine->expiry_date; ?></td>
                                <td>
                                    <a href="<?php echo site_url('medicine/edit/' . $medicine->id); ?>"><i class="fa fa-edit"></i></a>
                                    |
                                    <a href="<?php echo site_url('medicine/delete/' . $medicine->id); ?>"><i class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>