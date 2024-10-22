
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3><?php echo isset($user_data['id']) ? 'Campaigns' : '' ?></h3></div>
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
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-lg-6 col-12"><h4>Campaign List</h4></div>    
                        <div class="col-lg-6 col-12 d-flex justify-content-end mt-3">
                            <a href="<?php echo base_url() ?>new-campaign" class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-add-campaign">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                <span class="btn-text-inner">New Campaign</span>
                            </a>
                        </div>
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="userMessage"></div>
                    <div class="table-responsive pt-3">
                        <table id="campaign_dttble" class="table table-striped  no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Campaign Name</th>
                                    <th>Template</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Contact</th>
                                    <th>Sent</th>
                                    <th>Failed</th>
                                    <th>Delivered</th>
                                    <th>Read</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="campaign_modal_block"></div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/campaigns.js?t=<?php echo date('YmdHis'); ?>"></script>