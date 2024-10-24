
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>CRM Leads</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>CRM Message Logs</h4></div>    
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="table-responsive pt-3">
                        <table id="crm_message_logs_dttble" class="table table-striped  no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Leads Source</th>
                                    <th>Template</th>
                                    <th>Message Status</th>
                                    <th>Created</th>
                                    <!--<th>Sent Time</th>-->
                                    <th>Deliver Time</th>
                                    <th>Read Time</th>
                                    <!--<th class="text-center">Action</th>-->
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
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/indiamart_leads.js?t=<?php echo date('YmdHis') ?>"></script>