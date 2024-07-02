<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Clients</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                            <h4>Clients List</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive mb-4">
                        <table id="user_clients_dttble" class="table style-3 table-bordered  table-hover">
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