<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo (isset($title) && $title != '') ? $title : 'Wishes - Admin Panel' ?></title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
        
        <!-----## CSS ##----->
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>plugins.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>loader.css" rel="stylesheet" type="text/css" /> 
        
        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/regular.css">
        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/fontawesome.css">       
        <!-- END GLOBAL MANDATORY STYLES -->    
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>/elements/alert.css">


        <!-----## JS ##----->
        <!-- BEGIN GLOBAL MANDATORY JS -->
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>loader.js"></script>
        <!-- END GLOBAL MANDATORY JS -->   
        
        <script src="https://kit.fontawesome.com/afcc2a9994.js" crossorigin="anonymous"></script>



    </head>
    <body class="form">
        <!-- BEGIN LOADER -->
        <div id="load_screen"> 
            <div class="loader"> 
                <div class="loader-content">
                    <div class="spinner-grow align-self-center"></div>
                </div>
            </div>
        </div>
        <!--  END LOADER -->
        <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
    
            <div class="row">
    
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
                            <?php echo $body; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
