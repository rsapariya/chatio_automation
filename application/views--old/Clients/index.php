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
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <h4>Clients List</h4>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-right mt-2">
                            <button data-target="<?php echo base_url() . 'clients/add' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-client"><span>Add Client</span></button>
                            <button data-target="<?php echo base_url() . 'clients/add_multiple' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-multiple-client"><span>Add Multiple Client</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive mb-4">
                        <table id="clients_dttble" class="table style-3 table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Birth Date</th>
                                    <th>Anniversary Date</th>
                                    <th>Created</th>
                                    <th class="text-center">Action</th>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/clients.js"></script>