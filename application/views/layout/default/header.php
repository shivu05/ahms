<script type="text/javascript">
    var base_url = '<?= base_url() ?>';
    jQuery.ui.autocomplete.prototype._resizeMenu = function () {
        var ul = this.menu.element;
        ul.outerWidth(this.element.outerWidth());
    }
</script>
<header class="main-header">
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="<?= base_url() ?>login/dashboard" class="navbar-brand" style="padding: 2px;margin-left: 5px;">
                    <img src="<?= base_url('assets/your_logo.png') ?>" class="logo-mini" style="background-color: white;" height="50px" width="50px" />
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <?php echo ($this->rbac->show_user_menu_top()) ?>
                <?php if ($this->rbac->is_admin()) { ?>
                    <form class="navbar-form navbar-left" role="search" action="<?php echo base_url('search-data'); ?>" method="POST">
                        <div class="form-group">
                            <input class="form-control" name="key_word" id="navbar-search-input" type="password" placeholder="Search">
                            <button type="submit" class="app-search__button" id="search_auto_btn"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                <?php } ?>
            </div>
            <!-- /.navbar-collapse -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if ($this->rbac->is_login()): ?>

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu" style="cursor: pointer; width:auto;" data-toggle="tooltip" data-placement="left" title="">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url('/assets/images/user.png') ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs">
                                    <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url('/assets/img/user_icon.png') ?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?= $this->rbac->get_logged_in_user_name(); ?>
                                        <small></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->                        
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?= base_url('login/passwordChange'); ?>" class="btn btn-default btn-flat">Change password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?= base_url('logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>