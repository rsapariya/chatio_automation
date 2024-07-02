<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Users - <?php echo isset($user_datas['id']) ? 'Edit' : 'Add' ?> User</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($user_datas['id']) ? 'Edit' : 'Add' ?> User</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form method="post" action="<?php echo base_url() . 'users/save' ?>" class="add_user" novalidate>
                                <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>'/>
                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Name</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($user_datas['name']) ? $user_datas['name'] : '' ?>" required>
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
                                        <input type="email" class="form-control" id="hEmail" name="email" placeholder="" value="<?php echo isset($user_datas['email']) ? $user_datas['email'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the email
                                        </div>
                                    </div>
                                </div>
                                <?php if (!isset($user_datas)) { ?>
                                    <div class="form-group row mb-4">
                                        <label for="hPassword" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Password</label>
                                        <div class="col-xl-9 col-lg-8 col-sm-9">
                                            <input type="password" class="form-control" id="hPassword" name="password" placeholder="" value="" required>
                                            <div class="invalid-feedback">
                                                Please fill the password
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row mb-4">
                                    <label for="hPhoneNo" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Phone Number</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($user_datas['phone_number']) ? $user_datas['phone_number'] : '' ?>" required>
                                        <input type="hidden" id="country_code" name='country_code' value=''/>
                                        <div class="invalid-feedback">
                                            Please fill the phone
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="form-group mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3 col-sm-4 col-sm-3 pt-0">Type</label>
                                        <div class="col-xl-9 col-lg-8 col-sm-9">
                                            <div class="form-check mb-2">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio2 "name='type' value='user'  <?php echo isset($user_datas['type']) && $user_datas['type'] == 'user' ? 'checked="checked"' : 'checked="checked"' ?> class="custom-control-input radio-classic-primary" required>
                                                    <label class="custom-control-label" for="hRadio2"  >User</label>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio1" name='type' value='admin' <?php echo isset($user_datas['type']) && $user_datas['type'] == 'admin' ? 'checked="checked"' : '' ?>class="custom-control-input radio-classic-primary" required>
                                                    <label class="custom-control-label" for="hRadio1" >Admin</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js"></script>