<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Users - <?php echo isset($user_data['id']) ? 'Edit Profile' : '' ?></h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($user_data['id']) ? 'Edit Profile' : '' ?></h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form method="post" action="<?php echo base_url() . 'dashboard/save_profile' ?>" class="edit_profile" novalidate>
                                <input type="hidden" name='user_id' value='<?php echo isset($user_data['id']) ? base64_encode($user_data['id']) : '' ?>'/>
                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Name</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($user_data['name']) ? $user_data['name'] : '' ?>" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the name
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hEmail" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Email</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="email" class="form-control" id="hEmail" name="email" placeholder="" value="<?php echo isset($user_data['email']) ? $user_data['email'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the email OR valid email
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="hPhoneNo" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Phone Number</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($user_data['phone_number']) ? $user_data['phone_number'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the phone
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hPassword" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Password</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="password" class="form-control" id="hPassword" name="password" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="cPassword" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Confirm Password</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="password" class="form-control" id="cPassword" name="cpassword" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="form-group row text-left">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn-creative btn-3 btn-3e flaticon-arrow-left mb-4 mt-3"><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/dashboard.js"></script>