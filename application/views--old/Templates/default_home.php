<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo (isset($title) && $title != '') ? $title : 'Wishes - Admin Panel' ?></title>
        <link rel="icon" type="image/x-icon" href="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>favicon.ico"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>plugins.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>default-dashboard/style.css" rel="stylesheet" type="text/css" /> 
        <link href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.13.4/datatables.min.css" rel="stylesheet"/>
        <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet"/>
        <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet"/>

        <link rel="stylesheet" type="text/css" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/table/datatable/datatables.css">
        <link rel="stylesheet" type="text/css" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/table/datatable/custom_dt_customer.css">
        <link rel="stylesheet" type="text/css" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/select2/select2.min.css">
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/buttons/creative/creative-icon-buttons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/buttons/creative/creative-gradients.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/buttons/creative/creative-fill.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/buttons/creative/creative-material.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>forms/form-validation.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/animate/animate.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/sweetalerts/promise-polyfill.js"></script>
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/custom-sweetalert.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>design-css/design.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/bootstrap_date_range_picker/daterangepicker.css" rel="stylesheet" type="text/css">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/timepicker/jquery.timepicker.css" rel="stylesheet" type="text/css">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/custom_datetimepicker_style/custom_datetimepicker.css" rel="stylesheet" type="text/css">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/editors/tinymce/stylesheet.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>custom.css" rel="stylesheet" type="text/css" /> 
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>ui-kit/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/custom-page_style_datetime.css">
        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/emojionearea/emojionearea.min.css">

        <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>libs/jquery-3.1.1.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/popper.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/blockui/jquery.blockUI.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>app.js"></script>
        <script>
            var base_url = '<?php echo base_url(); ?>';
            $(document).ready(function () {
                App.init();

            });
        </script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"  />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<!--        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/intl-tel-input/intlTelInput.css"  />
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/intl-tel-input/intlTelInput.min.js"></script>-->
        <!-- END GLOBAL MANDATORY SCRIPTS -->

        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<!--        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/table/datatable/datatables.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH . "plugins/table/datatable/datatables.min.js"; ?>"></script>-->
       <!-- <script type="text/javascript" src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>plugins/tables/datatables/datatables.min.js"></script>-->

<!--        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>-->

        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/datatables.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/extensions/select.min.js"></script>
        <!--<script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/forms/selects/select2.min.js"></script>-->
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/tables/datatables/extensions/buttons.min.js"></script>

        <script type="text/javascript" src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>plugins/tables/datatables/extensions/responsive.min.js"></script>

        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH ?>plugins/select2/select2.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH ?>plugins/select2/custom-select2.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/calendar/pignose/moment.latest.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/calendar/pignose/pignose.calendar.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>forms/bootstrap_validation/bs_validation_script.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/input-mask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/input-mask/input-mask.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/sweetalerts/sweetalert2.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>design-js/design.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/forms/tags/tagsinput.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/timepicker/jquery.timepicker.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/bootstrap_date_range_picker/moment.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/date_time_pickers/bootstrap_date_range_picker/daterangepicker.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/timepicker/custom-timepicker.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/editors/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/emojionearea/emojionearea.min.js"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

        <style>
            .dataTables_wrapper .dt-buttons {
                float:right;
            }
        </style>
        <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->   

    </head>
    <body class="default-sidebar">
        <!-- Tab Mobile View Header -->
        <header class="tabMobileView header navbar fixed-top d-lg-none">
            <ul class="navbar-nav flex-row ml-lg-auto w-100">
                <li class="nav-item dropdown user-profile-dropdown w-100 text-center">
                    <div class="nav-toggle d-inline-block float-left mt-2">
                        <a href="javascript:void(0);" class="nav-link sidebarCollapse d-inline-block" data-placement="bottom">
                            <i class="flaticon-menu-line-2"></i>
                        </a>
                        <a href="<?php echo base_url() . "/dashboard" ?>" class="ml-3"> <img src="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>logo-3.png" class="img-fluid" alt="logo"></a>
                    </div>
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user d-inline-block float-right" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <img src="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>default_user.png" class="img-fluid mr-2" alt="admin-profile">
                            <div class="media-body align-self-center">
                                <h6 class="mb-1"><?php echo isset($user_data['name']) ? $user_data['name'] : ''; ?></h6>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu p-0 mt-5" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item d-flex mt-5" href="<?php echo base_url() . "edit_profile" ?>">
                            <i class="mr-3 flaticon-user-11"></i> <span class="align-self-center">Profile</span>
                        </a>
                        <a class="dropdown-item d-flex mt-5" href="<?php echo base_url() . "settings" ?>">
                            <i class="mr-3 flaticon-settings-1"></i> <span class="align-self-center">API settings</span>
                        </a>
                        <a href="<?php echo base_url() . "logout" ?>" class="dropdown-item dropdown-item-btn">
                            <i class="mr-2 flaticon-power-off"></i> <span class="align-self-center">Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </header>
        <!-- Tab Mobile View Header -->

        <!--  BEGIN NAVBAR  -->
        <header class="desktop-nav header navbar fixed-top">
            <div class="nav-logo mr-5 ml-4 d-lg-inline-block d-none">
                <a href="i<?php echo base_url() . "/dashboard" ?>" class=""> <img src="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>logo-3.png" class="img-fluid" alt="logo"></a>
            </div>
            <ul class="navbar-nav flex-row mr-auto">
                <li class="nav-item ml-4 d-lg-none">
                    <form class="form-inline search-full form-inline search animated-search" role="search">
                        <i class="flaticon-search-1 d-lg-none d-block"></i>
                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                    </form>
                </li>
            </ul>

            <ul class="navbar-nav flex-row ml-lg-auto">
                <li class="nav-item mr-5 d-lg-block d-none">
                    <form class="form-inline form-inline search animated-search" role="search">
                        <i class="flaticon-search-1 d-lg-none d-block"></i>
                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                    </form>
                </li>

                <li class="nav-item dropdown user-profile-dropdown mr-5  d-lg-inline-block d-none">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <img src="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>default_user.png" class="img-fluid mr-2" alt="admin-profile">
                            <div class="media-body align-self-center">
                                <h6 class="mb-1"><?php echo $user_data['name']; ?></h6>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  position-absolute p-0" aria-labelledby="user-profile-dropdown">
                        <div class="dropdown-item d-flex justify-content-around">
                            <p class="mb-0 align-self-center">Your Account</p>
                            <div class="">
                                <i class="flaticon-star-outline"></i>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item d-flex" href="<?php echo base_url() . "edit_profile" ?>">
                            <i class="mr-3 flaticon-user-11"></i> <span class="align-self-center">Profile</span>
                        </a>
                        <a class="dropdown-item d-flex " href="<?php echo base_url() . "settings" ?>">
                            <i class="mr-3 flaticon-settings-1"></i> <span class="align-self-center">API settings</span>
                        </a>
                        <div class="dropdown-item dropdown-item-btn d-flex justify-content-around">
                            <a class="" href="<?php echo base_url() . "logout" ?>">
                                <i class="mr-2 flaticon-power-off"></i> <span class="align-self-center">Logout</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
        
        <!--  END NAVBAR  -->

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">
            <div class="overlay"></div>
            <div class="cs-overlay"></div>
            <div class="search-overlay"></div>

            <div class="topbar-nav header navbar fixed-top" role="banner">
                <div id="dismiss" class="d-lg-none text-right"><i class="flaticon-cancel-12 mr-3"></i></div>
                <nav id="topbar">
                    <ul class="list-unstyled menu-categories d-lg-flex justify-content-lg-around mb-0" id="topAccordion">
                        <?php
                        $controller = $this->router->fetch_class();
                        $Method = $this->router->fetch_method();
                        ?>
                        <li class="menu <?php echo ($controller == 'dashboard') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() . "dashboard" ?>" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <i class="flaticon-computer-6"></i>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <?php if (isset($user_data) && $user_data['type'] == 'admin') { ?>
                            <li class="menu <?php echo ($controller == 'users') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "users" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-users"></i>
                                        <span>Users</span>
                                    </div>
                                </a>
                            </li>

                        <?php } elseif (isset($user_data) && $user_data['type'] == 'user') { ?>
                            <li class="menu <?php echo ($controller == 'clients') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "clients" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-user-11"></i>
                                        <span>Clients</span>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="menu <?php echo ($controller == 'templates') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() . "templates" ?>" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <i class="flaticon-simple-screen-line"></i>
                                    <span>Templates</span>
                                </div>
                            </a>
                        </li>
                        <?php if (isset($user_data) && $user_data['type'] == 'user') { ?>
                            <li class="menu <?php echo ($controller == 'automations') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "automations" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-time"></i>
                                        <span>Automation</span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu <?php echo ($controller == 'inquiries') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "inquiries" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-notes"></i>
                                        <span>Inquiries</span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu <?php echo ($controller == 'recurrings') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "recurrings" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-refresh"></i>
                                        <span>Recurrings</span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu <?php echo ($controller == 'indiamart_leads') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "indiamart_leads" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-user-11"></i>
                                        <span>Indiamart Leads</span>
                                    </div>
                                </a>
                            </li>

                            <li class="menu <?php echo ($controller == 'replyMessage') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "replyMessage" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-reply"></i>
                                        <span>Reply Trigger Message</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="menu <?php echo ($controller == 'replyResponse') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "replyResponse" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-send-arrow"></i>
                                        <span>Button Response</span>
                                    </div>
                                </a>
                            </li>
                            <li class="menu <?php echo ($controller == 'chatLogs') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url() . "chatLogs" ?>" aria-expanded="false" class="dropdown-toggle">
                                    <div class="">
                                        <i class="flaticon-chat"></i>
                                        <span>Chat Logs</span>
                                    </div>
                                </a>
                            </li>

                        <?php } ?>
                        <li class="menu">
                        </li>
                    </ul>
                </nav>
            </div>

            <!--  BEGIN CONTENT PART  -->
            <div id="content" class="main-content">
                <?php echo $body; ?>
                <div class="modal fade" id="modal_view_template" role="dialog">
                    <div class="modal-dialog vertical-align-center">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title template_name"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body template_details">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--  END CONTENT PART  -->

        </div>
        <!-- END MAIN CONTAINER -->

        <!--  BEGIN FOOTER  -->
        <footer class="footer-section theme-footer">

            <div class="footer-section-1  sidebar-theme">
            </div>

            <div class="footer-section-2 container-fluid">
                <div class="row">
                    <div id="toggle-grid" class="col-xl-7 col-md-6 col-sm-6 col-12 text-sm-left text-center">
                    </div>
                    <div class="col-xl-5 col-md-6 col-sm-6 col-12">
                        <ul class="list-inline mb-0 d-flex justify-content-sm-end justify-content-center mr-sm-3 ml-sm-0 mx-3">
                            <li class="list-inline-item  mr-3">
                                <p class="bottom-footer">&#xA9; <?php echo date('Y') ?></p>
                            </li>
                            <li class="list-inline-item align-self-center">
                                <div class="scrollTop"><i class="flaticon-up-arrow-fill-1"></i></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!--  END FOOTER  -->    
    </body>
</html>