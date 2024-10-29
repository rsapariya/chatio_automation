<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Users</h3></div>
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
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12"><h4>Add User</h4></div>    
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="<?php echo base_url() . 'users/save' ?>" class="add_user" novalidate>
                        <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>'/>
                        <div class="row">
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">   
                                    <label for="hName">Name</label>
                                    <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($user_datas['name']) ? $user_datas['name'] : '' ?>" required>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the name</div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hEmail">Email</label>
                                    <input type="email" class="form-control" id="hEmail" name="email" placeholder="" value="<?php echo isset($user_datas['email']) ? $user_datas['email'] : '' ?>" required>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the email</div>
                                </div>
                            </div>
                            <?php if (!isset($user_datas)) { ?>
                                <div class="col-lg-8 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hPassword">Password</label>
                                        <input type="password" class="form-control" id="hPassword" name="password" placeholder="" value="" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">Please fill the password</div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-lg-8 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hPassword">Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" value="<?php echo !empty($user_datas['password']) ? $this->encrypt->decode($user_datas['password']) : ''  ?>">
                                            <button class="btn btn-primary hide_show_password" type="button" id="view-password"><i class="fa fa-eye-slash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            
                            <?php } ?>
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hPhoneNo">Phone</label>
                                    <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($user_datas['phone_number']) ? $user_datas['phone_number'] : '' ?>" required>
                                    <input type="hidden" id="country_code" name='country_code' value=''/>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the phone</div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hPhoneNo">Type</label><br/>
                                    <div class="form-check form-check-primary form-check-inline">
                                        <input type="radio" id="hRadio2 "name='type' value='user'  <?php echo isset($user_datas['type']) && $user_datas['type'] == 'user' ? 'checked="checked"' : 'checked="checked"' ?> class="form-check-input" required>
                                        <label class="form-check-label" for="hRadio2">
                                            User
                                        </label>
                                    </div>
                                    <div class="form-check form-check-primary form-check-inline">
                                        <input type="radio" id="hRadio1" name='type' value='admin' <?php echo isset($user_datas['type']) && $user_datas['type'] == 'admin' ? 'checked="checked"' : '' ?>class="form-check-input" required>
                                        <label class="form-check-label" for="hRadio1">
                                            Admin
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12 ">
                                <label >Access</label><br/>
                                <div class="form-group mb-4">    
                                    <div class="form-check form-switch form-check-inline">
                                        <input class="form-check-input" type="checkbox" role="switch" id="waba_access" name="waba_access" <?php echo isset($user_datas['waba_access']) && !empty($user_datas['waba_access']) ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="waba_access">WhatsApp Business API Access &nbsp;</label>
                                    </div>
                                </div>
                                <div class="form-group mb-4">    
                                    <div class="form-check form-switch form-check-inline">
                                        <input class="form-check-input" type="checkbox" role="switch" id="crm_lead_access" name="crm_lead_access" <?php echo isset($user_datas['crm_lead_access']) && !empty($user_datas['crm_lead_access']) ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="crm_lead_access">CRM Leads Access &nbsp;</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12 mb-4">
                                <label >License</label><br/>
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label for="license_start">License Start</label>
                                            <input id="license_start" name='license_start' value="<?php echo isset($user_datas['license_start']) ? date('d-m-Y', strtotime(getServerTimeZone($user_datas['license_start']))) : '' ?>" class="form-control flatpickr" type="text" placeholder="Select Start Date">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the License Start</div>
                                        </div> 
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label for="license_end">License End</label>
                                            <input id="license_end" name='license_end' value="<?php echo isset($user_datas['license_end']) ? date('d-m-Y', strtotime(getServerTimeZone($user_datas['license_end']))) : '' ?>" class="form-control flatpickr" type="text" placeholder="Select End Date">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the License End</div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hPhoneNo">Status</label><br/>
                                    <div class="form-check form-check-primary form-check-inline">
                                        <input type="radio" id="status2 "name='status' value='active'  <?php echo isset($user_datas['status']) && $user_datas['status'] == 'active' ? 'checked="checked"' : 'checked="checked"' ?> class="form-check-input" required>
                                        <label class="form-check-label" for="status2">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-primary form-check-inline">
                                        <input type="radio" id="status1" name='status' value='inactive' <?php echo isset($user_datas['status']) && $user_datas['status'] == 'inactive' ? 'checked="checked"' : '' ?>class="form-check-input" required>
                                        <label class="form-check-label" for="status1">
                                            Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <input type="submit" name="Save" class="mt-4 mb-4 btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let licenseStart = '<?php echo isset($user_datas["license_start"]) ? date("d-m-Y", strtotime(getServerTimeZone($user_datas["license_start"]))) : '' ?>';
    let licenseEnd = '<?php echo isset($user_datas["license_end"]) ? date("d-m-Y", strtotime(getServerTimeZone($user_datas["license_end"]))) : '' ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js?t=<?php echo date('YmdHis') ?>"></script>