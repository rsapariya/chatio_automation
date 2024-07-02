<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Dashboard</h3>
        </div>
    </div>

    <div class="row layout-spacing ">
        <div class="col-lg-12 mb-1">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>
        <?php if (isset($user_data) && $user_data['type'] == 'admin') { ?>
            <div class="col-xl-3 mb-xl-0 col-lg-6 mb-4 col-md-6 col-sm-6 div-dashboard-wrapper" data-url="<?php echo base_url() . 'users' ?>">
                <div class="widget-content-area  data-widgets br-4">
                    <div class="widget  t-sales-widget">
                        <div class="media">
                            <div class="icon ml-2">
                                <i class="flaticon-users"></i>
                            </div>
                            <div class="media-body text-right">
                                <p class="widget-text mb-0">Users</p>
                                <p class="widget-numeric-value"><?php echo (isset($users_cnt) ? $users_cnt : 0) ?></p>
                            </div>
                            <!--<p class="widget-total-stats mt-2">552 New Orders</p>-->
                        </div>
                    </div>
                </div>
            </div>
        <?php } else if (isset($user_data) && $user_data['type'] == 'user') { ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-4 div-dashboard-wrapper" data-url="<?php echo base_url() . 'clients' ?>">
                <div class="widget-content-area  data-widgets br-4">
                    <div class="widget  t-customer-widget">
                        <div class="media">
                            <div class="icon ml-2">
                                <i class="flaticon-user-11"></i>
                            </div>
                            <div class="media-body text-right">
                                <p class="widget-text mb-0">Customers</p>
                                <p class="widget-numeric-value"><?php echo (isset($users_cnt) ? $users_cnt : 0) ?></p>
                            </div>
                        </div>
                        <!--<p class="widget-total-stats mt-2">390 New Customers</p>-->
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-4 div-dashboard-wrapper" data-url="<?php echo base_url() . 'templates' ?>">
            <div class="widget-content-area data-widgets br-4">
                <div class="widget t-order-widget">
                    <div class="media">
                        <div class="icon ml-2">
                            <i class="flaticon-simple-screen-line"></i>
                        </div>
                        <div class="media-body text-right">
                            <p class="widget-text mb-0">Templates</p>
                            <p class="widget-numeric-value"><?php echo (isset($templates_cnt) ? $templates_cnt : 0) ?></p>
                        </div>
                    </div>
                    <!--<p class="widget-total-stats mt-2">390 New Customers</p>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/dashboard.js"></script>-->