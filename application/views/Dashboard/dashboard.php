
            <!--  BEGIN BREADCRUMBS  -->
            <div class="secondary-nav">
                <div class="breadcrumbs-container" data-page-heading="Analytics">
                    <header class="header navbar navbar-expand-sm">
                        <div class="d-flex breadcrumb-content">
                            <div class="page-header">
                                <div class="page-title"><h3>Dashboard</h3></div>
                            </div>
                        </div>
                    </header>
                </div>
            </div>
            <!--  END BREADCRUMBS  -->
            
            <div class="row layout-top-spacing">
                <div class="col-lg-12 mb-1">
                    <?php $this->load->view('Partial/alert_view'); ?> 
                </div>
                <?php if (isset($user_data) && $user_data['type'] == 'admin') { ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                    <div class="widget widget-t-sales-widget widget-m-sales">
                        <div class="media">
                            <div class="icon ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            </div>
                            <div class="media-body">
                                <p class="widget-text">Users</p>
                                <p class="widget-numeric-value"><?php echo (isset($users_cnt) ? $users_cnt : 0) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else if (isset($user_data) && $user_data['type'] == 'user') { ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                    <div class="widget widget-t-sales-widget widget-m-sales">
                        <div class="media">
                            <div class="icon ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            </div>
                            <div class="media-body">
                                <p class="widget-text">Customers</p>
                                <p class="widget-numeric-value"><?php echo (isset($users_cnt) ? $users_cnt : 0) ?></p>
                            </div>
                        </div>
                    </div>
                </div>   
                <?php }?>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                    <div class="widget widget-t-sales-widget widget-m-orders">
                        <div class="media">
                            <div class="icon ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            </div>
                            <div class="media-body">
                                <p class="widget-text">Templates</p>
                                <p class="widget-numeric-value"><?php echo (isset($templates_cnt) ? $templates_cnt : 0) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php /*if (isset($user_data) && !empty($user_data['crm_lead_access'])) { ?>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 layout-spacing">
                        <div class="widget widget-t-sales-widget widget-m-orders">
                            <div class="media">
                                <div class="icon ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                </div>
                                <div class="media-body">
                                    <p class="widget-text">Templates</p>
                                    <p class="widget-numeric-value"><?php echo (isset($templates_cnt) ? $templates_cnt : 0) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }*/?>


            </div>