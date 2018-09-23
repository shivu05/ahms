<div class="error-page">    
    <?php
    if (isset($only_msg)) {
        echo '<p class="text-center">'.$only_msg.'</p>';
    } else {
        ?>
        <h2 class="headline text-yellow"><?= (isset($status_code) ? $status_code : '') ?></h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Error!</h3>
            <p><?= (isset($message) ? $message : '') ?></p>        
        </div>
    <?php }
    ?>

    <!-- /.error-content -->
</div>