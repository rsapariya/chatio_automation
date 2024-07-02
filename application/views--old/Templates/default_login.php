<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo (isset($title) && $title != '') ? $title : 'Wishes - Admin Panel' ?></title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>plugins.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>users/login-2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>users/register-2.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->    
    </head>
    <body>
        <?php echo $body; ?>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>libs/jquery-3.1.1.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/popper.min.js"></script>
    </body>
</html>
