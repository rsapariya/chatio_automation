<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo (isset($title) && $title != '') ? $title : 'Automation' ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>favicon.ico" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>loader.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    
    <!-- END GLOBAL MANDATORY STYLES -->
    <!--<link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">-->

    <link rel="stylesheet" type="text/css" href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>elements/alert.css">
    <link rel="stylesheet"
        href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet"
        href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/fontawesome.css">
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/font-icons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/flaticon/style.css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/flatpickr/flatpickr.css" rel="stylesheet"
        type="text/css">

    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/2.0.3/css/select.bootstrap5.min.css" rel="stylesheet" />

    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>elements/tooltip.css" rel="stylesheet" type="text/css" />
    <!--<link href="<?php //echo DEFAULT_ADMIN_ASSET_PATH ?>plugins/src/sweetalerts2/sweetalerts2.css">-->
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/emojionearea/emojionearea.min.css">
    <!--<link href="<?php //echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/select2/select2.min.css">-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/carousel.css" rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/modal.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/tabs.css" rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/date_time_pickers/daterangepicker.css"
        rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/date_time_pickers/bootstrap-datetimepicker.min.css"
        rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/tagify/tagify.css" rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/tomSelect/tom-select.default.min.css"
        rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>apps/chat.css" rel="stylesheet" type="text/css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/filepond.min.css">
    <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/FilePondPluginImagePreview.min.css">

    <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>dashboard/dash_1.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/perfect-scrollbar/perfect-scrollbar.min.js">
    </script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/mousetrap/mousetrap.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/waves/waves.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>app.js"></script>

    <?php
    //!empty($this->session->userdata('time_zone')) ? date_default_timezone_set(trim($this->session->userdata('time_zone'))) : '';
    ?>

    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/select.bootstrap5.min.js"></script>

    <!--<script src="<?php //echo DEFAULT_ADMIN_JS_PATH; ?>dashboard/dash_1.js"></script>-->
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/feather/feather.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/flatpickr/flatpickr.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/input-mask/jquery.inputmask.bundle.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/date_time_pickers/moment.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/date_time_pickers/daterangepicker.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>elements/tooltip.js"></script>
    <script src="https://kit.fontawesome.com/afcc2a9994.js" crossorigin="anonymous"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/sweetalerts2/sweetalert2All.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/emojionearea/emojionearea.min.js"></script>
    <!--<script src="<?php //echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/select2/select2.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/tagify/tagify.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/tomSelect/tom-select.base.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/filepond.min.js"></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/FilePondPluginImagePreview.min.js"></script>
    <script src = "<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/FilePondPluginImageCrop.min.js" ></script>
    <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/filepond/FilePondPluginImageResize.min.js"></script>
  
    <script>
        var base_url = '<?php echo base_url(); ?>';
        feather.replace();
    </script>
</head>

<body class="layout-boxed enable-secondaryNav">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg></a>

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo-">
                    <!--<a href="<?php //echo base_url() . "dashboard"    
                                    ?>">
                            <img src="<?php //echo DEFAULT_ADMIN_IMAGE_PATH    
                                        ?>logo2.svg" class="navbar-logo" alt="logo">
                        </a>-->
                </li>
                <li class="nav-item theme-text">
                    <a href="<?php echo base_url() . "dashboard" ?>" class="nav-link"><span
                            class="theme-text-color decorated-first-char">A</span>UTOMATION </a>
                </li>
            </ul>
            <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar"
                                    src="<?php echo !empty($this->session->userdata('wa_profile_image_url')) ? $this->session->userdata('wa_profile_image_url') : DEFAULT_ADMIN_IMAGE_PATH . 'profile-30.png' ?>"
                                    class="rounded-circle">
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5><?php echo isset($user_data['name']) ? $user_data['name'] : ''; ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="<?php echo base_url() ?>edit_profile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> <span>Profile</span>
                            </a>
                        </div>
                        <?php if (isset($user_data) && $user_data['type'] !== 'member') { ?>
                        <div class="dropdown-item">
                            <a href="<?php echo base_url() ?>settings">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg> <span>API Settings</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="<?php echo base_url() ?>WABA-status">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg> <span>WABA Status</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a target="_blank" href="<?php echo base_url() ?>api/docs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg> <span>API Documentation</span>
                            </a>
                        </div>
                        <?php } ?>
                        <div class="dropdown-item">
                            <a href="<?php echo base_url() ?>logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <div class="navbar-nav theme-brand flex-row  text-center">
                    <div class="nav-logo">
                        <!--<div class="nav-item theme-logo-">
                                <a href="<?php //echo base_url();    
                                            ?>dashboard">
                                    <img src="<?php //echo DEFAULT_ADMIN_IMAGE_PATH    
                                                ?>logo.svg" class="navbar-logo" alt="logo">
                                </a>
                            </div>-->
                        <div class="nav-item theme-text">
                            <a href="<?php echo base_url(); ?>dashboard" class="nav-link"> EQUATION </a>
                        </div>
                    </div>
                    <div class="nav-item sidebar-toggle">
                        <div class="btn-toggle sidebarCollapse">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevrons-left">
                                <polyline points="11 17 6 12 11 7"></polyline>
                                <polyline points="18 17 13 12 18 7"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <?php
                    $controller = $this->router->fetch_class();
                    $Method = $this->router->fetch_method();
                    ?>
                    <li class="menu <?php echo ($controller == 'dashboard') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>dashboard" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <?php if (isset($user_data) && $user_data['type'] == 'admin') { ?>
                    <li class="menu <?php echo ($controller == 'users') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>users" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>Users</span>
                            </div>
                        </a>
                    </li>
                    <?php } elseif (isset($user_data) && $user_data['type'] == 'user' && !empty($user_data['waba_access'])) { ?>
                    <li class="menu <?php echo ($controller == 'team') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>team" class="dropdown-toggle">
                            <div class="">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Team</span>
                            </div>
                        </a>
                    </li>

                    <li
                        class="menu <?php echo ($controller == 'clients') || ($controller == 'tag') || ($controller == 'Campaigns') ? 'active' : ''; ?>">
                        <a href="#client-menu" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>Contacts</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="dropdown-menu submenu list-unstyled" id="client-menu"
                            data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?php echo base_url(); ?>campaigns"> Campaigns </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>contacts"> Contacts </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>tag"> Manage Tags </a>
                            </li>
                        </ul>
                    </li>

                    <?php
                    }

                    if ((isset($user_data) && $user_data['type'] == 'user' && !empty($user_data['waba_access']))) {
                    ?>
                    <li class="menu <?php echo ($controller == 'templates') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>templates" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-table">
                                    <path
                                        d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />
                                </svg>
                                <span>Templates</span>
                            </div>
                        </a>
                    </li>
                    <?php } ?>

                    <?php
                    if (isset($user_data) && $user_data['type'] == 'user') {

                        if (!empty($user_data['waba_access'])) {
                    ?>

                    
                    <li class="menu <?php echo ($controller == 'automations') || (($controller == 'automations') && ($Method == 'logs')) ? 'active' : '' ?>">
                        <a href="#automation-menu" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-cpu">
                                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                    <rect x="9" y="9" width="6" height="6"></rect>
                                    <line x1="9" y1="1" x2="9" y2="4"></line>
                                    <line x1="15" y1="1" x2="15" y2="4"></line>
                                    <line x1="9" y1="20" x2="9" y2="23"></line>
                                    <line x1="15" y1="20" x2="15" y2="23"></line>
                                    <line x1="20" y1="9" x2="23" y2="9"></line>
                                    <line x1="20" y1="14" x2="23" y2="14"></line>
                                    <line x1="1" y1="9" x2="4" y2="9"></line>
                                    <line x1="1" y1="14" x2="4" y2="14"></line>
                                </svg>
                                <span>Automations</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="dropdown-menu submenu list-unstyled" id="recurrings-menu"
                            data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?php echo base_url();?>automations"> Automations </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>automations/logs"> Automations Log </a>
                            </li>
                        </ul>
                    </li>
                    <!--<li class="menu <?php //echo ($controller == 'inquiries') ? 'active' : '';    
                                                ?>">
                                    <a href="<?php //echo base_url();    
                                                ?>inquiries" class="dropdown-toggle">
                                        <div class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                            <span>Inquiries</span>
                                        </div>
                                    </a>
                                </li>-->
                                <!--<li class="menu <?php echo ($controller == 'recurrings') ? 'active' : ''; ?>">
                                    <a href="<?php //echo base_url(); ?>recurrings" class="dropdown-toggle">
                                        <div class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rotate-cw"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                                            <span>Recurrings</span>
                                        </div>
                                    </a>
                                </li>-->
                                <li class="menu <?php echo ($controller == 'recurrings') || (($controller == 'recurrings') && ($Method == 'logs')) ? 'active' : '' ?>">
                        <a href="#recurrings-menu" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rotate-cw"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                                <span>Recurrings</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="dropdown-menu submenu list-unstyled" id="recurrings-menu"
                            data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?php echo base_url();?>recurrings"> Recurrings </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>recurrings/logs"> Recurrings Log </a>
                            </li>
                        </ul>
                    </li>

                    <?php } ?>
                    <?php if (isset($user_data) && !empty($user_data['waba_access']) && !empty($user_data['crm_lead_access'])) { ?>
                    <li class="menu <?php echo ($controller == 'indiamart_leads') ? 'active' : ''; ?>">
                        <a href="#crmlead-menu" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span>CRM Leads</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="dropdown-menu submenu list-unstyled" id="crmlead-menu"
                            data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?php echo base_url(); ?>crm-leads"> CRM Leads </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>crm-message-logs"> CRM Message Log</a>
                            </li>
                        </ul>
                    </li>
                    <?php } elseif (isset($user_data) && !empty($user_data['crm_lead_access'])) { ?>
                    <li class="menu <?php echo ($controller == 'indiamart_leads') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>crm-leads" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span>CRM Leads</span>
                            </div>
                        </a>
                    </li>
                    <?php
                        }

                        if (!empty($user_data['waba_access'])) {
                        ?>
                    <li class="menu <?php echo ($controller == 'replyMessage') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>replyMessage" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-corner-up-left">
                                    <polyline points="9 14 4 9 9 4"></polyline>
                                    <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
                                </svg>
                                <span>Reply Trigger Message</span>
                            </div>
                        </a>
                    </li>
                    <li
                        class="menu <?php echo ($controller == 'replyResponse' || $controller == 'ChatLogs') ? 'active' : ''; ?>">
                        <a href="#chatlog-menu" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-message-circle">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                    </path>
                                </svg>
                                <span>Chat Log</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="dropdown-menu submenu list-unstyled" id="chatlog-menu"
                            data-bs-parent="#accordionExample">
                            <li>
                                <a href="<?php echo base_url(); ?>replyResponse"> Button Response </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>text-logs"> Text Log</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>api-logs"> API Log</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>live-chat"> Live Chat</a>
                            </li>
                        </ul>
                    </li>

                    <?php
                        }
                    }
                    if (isset($user_data) && $user_data['type'] == 'member') {
                        ?>
                    <li class="menu <?php echo ($controller == 'ChatLogs') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url(); ?>live-chat" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-message-circle">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                    </path>
                                </svg>
                                <span>Live Chat</span>
                            </div>
                        </a>
                    </li>


                    <?php
                    }
                    ?>

                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <?php
                    
                    if($this->session->userdata('type')== 'user' && empty($this->session->userdata('time_zone'))){
                    ?>
                    <div class="col-12 mt-3">
                        <div class="alert alert-arrow-left alert-icon-left alert-light-warning alert-dismissible fade show mb-4" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" data-bs-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell fa-shake"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <strong>Please update your timezone in API settings!</strong> 
                        </div>
                    </div>
                    <?php } ?>
                    <?php echo $body ?>
                    <div class="modal fade" id="modal_view_template" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title template_name" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body template_details">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  BEGIN FOOTER  -->
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <span class="dynamic-year"><?php echo date('Y') ?></span>, All rights
                        reserved.</p>
                </div>
            </div>
            <!--  END FOOTER  -->
        </div>
        <!--  END CONTENT AREA  -->
        <script>
        const mainContainer = document.querySelector('.main-container');
        const overlay = document.querySelector('.overlay ');
        $(document).ready(function() {
            var windowWidth = window.innerWidth;
            if (windowWidth <= 991) {
                mainContainer.classList.add('sidebar-closed');
            }

            var bsTooltip = document.querySelectorAll('.bs-tooltip');
            if (bsTooltip) {
                for (let index = 0; index < bsTooltip.length; index++) {
                    new bootstrap.Tooltip(bsTooltip[index]);
                }
            }
        });

        $(document).on('click', '.sidebarCollapse', function(e) {
            e.preventDefault();
            if (mainContainer.classList.contains('sidebar-closed')) {
                mainContainer.classList.remove('sidebar-closed');
                mainContainer.classList.add('sbar-open');
                overlay.classList.add('show');
            }
        });
        $(document).on('click', '.overlay', function(e) {
            e.preventDefault();
            if (mainContainer.classList.contains('sbar-open')) {
                mainContainer.classList.add('sidebar-closed');
                mainContainer.classList.remove('sbar-open');
                overlay.classList.remove('show');
            }
        });
        </script>
    </div>
    <!-- END MAIN CONTAINER -->
</body>

</html>