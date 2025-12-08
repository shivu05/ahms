<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice Print</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <style type="text/css">
        body {
            font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
            font-size: 14px;
        }
        .page-header {
            margin: 10px 0 20px 0;
            font-size: 22px;
            border-bottom: 1px solid #eee;
            padding-bottom: 9px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-col {
            margin-bottom: 20px;
        }
        @media print {
            a[href]:after {
                content: none !important;
            }
        }
    </style>
</head>
<body>
    <?php echo $content; ?>
    <script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
</body>
</html>
