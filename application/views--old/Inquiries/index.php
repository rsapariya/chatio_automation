<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Inquiries</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <h4>Inquiries List</h4>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-right mt-2">
                            <button data-target="<?php echo base_url() . 'inquiries/add' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-inquiry"><span>Add Inquiry</span></button>
                            <button data-target="<?php echo base_url() . 'inquiries/add_multiple' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-multiple-inquiry"><span>Add Multiple Inquiries</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area border-tab">
                    <div class="table-responsive mb-4">
                        <table id="inquiries_dttble" class="table style-3 table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Type</th>
                                    <th>Automation</th>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/inquiries.js"></script>