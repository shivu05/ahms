<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-title">Add user:</div>
            <div class="tile-body">
                <div class="col-4">
                    <?php echo form_open(); ?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name:<span class="form_astrisk">*</span> </label>
                        <input class="form-control" id="name" name="name" type="text" aria-describedby="nameHelp" placeholder="Enter name" autocomplete="off">
                        <small class="form-text text-muted" id="nameHelp">Full name of user</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email: <span class="form_astrisk">*</span></label>
                        <input class="form-control" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" autocomplete="off">
                        <small class="form-text text-muted" id="emailHelp">Email will be your username</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mobile: <span class="form_astrisk">*</span></label>
                        <input class="form-control" id="mobile" name="mobile" type="text" aria-describedby="mobileHelp" placeholder="Enter mobile number" autocomplete="off">
                        <small class="form-text text-muted" id="mobileHelp">10-digit mobile number</small>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>