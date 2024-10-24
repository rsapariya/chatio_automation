
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Clients</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Client List</h4></div>    
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="table-responsive pt-3">
                        <table id="user_clients_dttble" class="table table-striped no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Birth Date</th>
                                    <th>Anniversary Date</th>
                                    <th>Created</th>
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
<script>
    var client_user_id = '<?php echo isset($user_id) ? $user_id : '' ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js"></script>