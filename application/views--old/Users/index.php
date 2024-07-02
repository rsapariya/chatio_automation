<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Users</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                            <h4>Users List</h4>
                        </div>
                        <div class="col-xl-2 col-md-2 col-sm-2 col-2 text-right mt-2">
                            <button data-target="<?php echo base_url() . 'users/add' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-user"><span>Add User</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive mb-4">
                        <table id="users_dttble" class="table style-3 table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Type</th>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js"></script>