<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Users - Settings</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Settings</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="<?php echo base_url() . 'users/settings_save' ?>" class="save_settings" novalidate>
                        <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>'/>
                        <input type="hidden" name='setting_id' value='<?php echo isset($user_settings['id']) ? base64_encode($user_settings['id']) : '' ?>'/>
                        <div class="row col-12">
<!--                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h2 style="margin-bottom: 21px;">WhatsApp Credentials</h2>
                                <div class="form-group row mb-4">
                                    <label for="hToken" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Access Token</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <textarea class="form-control" id="hToken" rows="3" name="access_token" required><?php echo isset($user_settings['access_token']) ? $user_settings['access_token'] : '' ?></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the access token
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hInstance" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Instance ID</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hInstance" name="instance_id" placeholder="" value="<?php echo isset($user_settings['instance_id']) ? $user_settings['instance_id'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the instance id
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hTime" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Trigger Time</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control timepicker" id="hTime" name="trigger_time" placeholder="" value="<?php echo isset($user_settings['trigger_time']) ? $user_settings['trigger_time'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the trigger time
                                        </div>
                                    </div>
                                </div>
                            </div>-->

                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h2 style="margin-bottom: 21px;">Official WhatsApp Credentials</h2>
                                <div class="form-group row mb-4">
                                    <label for="hToken" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Permanent Access Token</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <textarea class="form-control" id="hToken" rows="3" name="permanent_access_token" required><?php echo isset($user_settings['permanent_access_token']) ? $user_settings['permanent_access_token'] : '' ?></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the permanent access token
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hInstance" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Phone Number ID</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hInstance" name="phone_number_id" placeholder="" value="<?php echo isset($user_settings['phone_number_id']) ? $user_settings['phone_number_id'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the phone number id
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hInstance" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Business Account ID</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hInstance" name="business_account_id" placeholder="" value="<?php echo isset($user_settings['business_account_id']) ? $user_settings['business_account_id'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the phone number id
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row col-12">
                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h2 style="margin-bottom: 21px;">API Credentials</h2>
                                <div class="form-group row mb-4">
                                    <label for="hToken" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">API Token</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" readonly class="form-control" name="api_token" value="<?php echo isset($user_settings['api_token']) ? $user_settings['api_token'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-12">
                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h2 style="margin-bottom: 21px;">India Mart Credentials</h2>
                                <div class="form-group row mb-4">
                                    <label for="hToken" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">CRM key</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text"  class="form-control" name="crm_key" value="<?php echo isset($user_settings['crm_key']) ? $user_settings['crm_key'] : '' ?>" />
                                        <div class="invalid-feedback">
                                            Please fill the CRM key
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-12">
                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h2 style="margin-bottom: 21px;">TradeIndia Credentials</h2>
                                <div class="form-group row mb-4">
                                    <label for="hUserid" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">User ID</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                    <input type="text" class="form-control" id="hUserid" name="tradeindia_user_id" placeholder="TradeIndia UserID" value="<?php echo isset($user_settings['tradeindia_user_id']) ? $user_settings['tradeindia_user_id'] : '' ?>" required>    
                                        <div class="invalid-feedback">
                                            Please fill the UserID
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hProfileid" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Profile ID</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                    <input type="text" class="form-control" id="hProfileid" name="tradeindia_profile_id" placeholder="TradeIndia ProfileID" value="<?php echo isset($user_settings['tradeindia_profile_id']) ? $user_settings['tradeindia_profile_id'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the ProfileID
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hkey" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Key</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                    <input type="text" class="form-control" id="hkey" name="tradeindia_key" placeholder="TradeIndia KEY" value="<?php echo isset($user_settings['tradeindia_key']) ? $user_settings['tradeindia_key'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the Key
                                        </div>
                                    </div>
                                </div>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js"></script>