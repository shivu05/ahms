<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-book"></i> Add Medicine:</h3>
                <a href="<?php echo site_url('medicine/'); ?>" class="btn btn-sm btn-primary pull-right">Medicine List</a>
            </div>
            <div class="box-body">
                <p class="text-danger">Note: All fields are mandatory</p>
                <form class="form-horizontal" name="med_add_form" id="med_add_form" action="" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="name">Medicine Name: </label>
                                        <input class="form-control required" id="name" name="name" type="text" aria-describedby="nameHelp" placeholder="Enter Medicine name" />
                                        <small class="form-text text-muted" id="nameHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="category">Category: </label>
                                        <input class="form-control required" id="category" name="category" type="text" aria-describedby="categoryHelp" placeholder="Enter Category" />
                                        <small class="form-text text-muted" id="categoryHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="supplier_id">Supplier: </label>
                                        <input class="form-control required" id="supplier_id" name="supplier_id" type="text" aria-describedby="supplier_idHelp" placeholder="Enter Supplier" />
                                        <small class="form-text text-muted" id="supplier_idHelp"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="price">Price: </label>
                                        <input class="form-control required" id="price" name="price" type="text" aria-describedby="priceHelp" placeholder="Enter Price" />
                                        <small class="form-text text-muted" id="priceHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="quantity">Quantity: </label>
                                        <input class="form-control required" id="quantity" name="quantity" type="text" aria-describedby="quantityHelp" placeholder="Enter Quantity" />
                                        <small class="form-text text-muted" id="quantityHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <label for="expiry_date">Expiry date: </label>
                                        <input class="form-control required date_picker" id="expiry_date" name="expiry_date" type="text" aria-describedby="expiry_dateHelp" placeholder="Enter Date" />
                                        <small class="form-text text-muted" id="expiry_dateHelp"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <button type="submit" class="btn btn-primary pull-right btn-sm mr-1 pr-2" style="padding-right: 1%"><i class="fa fa-plus"></i> Add Medicine</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
