<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title">
                        <h3><?php echo isset($user_data['id']) ? 'API Settings' : '' ?></h3>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>
<!--  END BREADCRUMBS  -->
<div class="row layout-top-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-8 mb-1 mx-auto">
            <?php $this->load->view('Partial/alert_view'); ?>
        </div>
        <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 mx-auto">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="text-bold mb-4">Important WhatsApp and Facebook Panel Links</h5>
                    <div class="row">
                        <div class="col-9">
                            <h6><b>Desciption</b></h6>
                        </div>
                        <div class="col-3">
                            <h6><b>Link</b></h6>
                        </div>
                    </div>
                    <hr class="mt-1 mb-2"/>
                    <?php if (isset($user_settings['business_account_id']) && !empty($user_settings['business_account_id'])) { ?>
                        <div class="row">
                            <div class="col-9">
                                <p>WhatsApp Messages Payment (direct to Facebook)</p>
                            </div>
                            <div class="col-3">
                                <a target="_blank" href="https://business.facebook.com/settings/whatsapp-business-accounts/<?php echo $user_settings['business_account_id'] ?>/?tab=whatsapp_settings">Click Here</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-9">
                                <p>WhatsApp Direct Support</p>
                            </div>
                            <div class="col-3">
                                <a target="_blank" href="https://business.facebook.com/direct-support?business_id=<?php echo $user_settings['business_account_id'] ?>">Click Here</a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-9">
                            <p>WhatsApp Business Manager</p>
                        </div>
                        <div class="col-3">
                            <a target="_blank" href="https://business.facebook.com/wa/manage/home/">Click Here</a>
                        </div>
                    </div>
                </div>
            </div>

            <form method="post" action="<?php echo base_url() . 'users/settings_save' ?>" class="save_settings" novalidate>
                <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>' />
                <input type="hidden" name='setting_id' value='<?php echo isset($user_settings['id']) ? base64_encode($user_settings['id']) : '' ?>' />
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="form-group mb-4">
                                     <label for="select-time">Time Zone</label>
                                    <select id="select-beast" name="time_zone" placeholder="Select Time Zone" autocomplete="off">
                                        <?php if (isset($time_zone) && !empty($time_zone)) { ?>
                                            <option <?php echo isset($user_settings['time_zone']) && !empty($user_settings['time_zone']) ? '' : 'selected="selected"' ?> disabled="disabled">Select Time Zone</option>
                                            <?php
                                            foreach ($time_zone as $tz) {
                                                ?>
                                                <option <?php echo isset($user_settings['time_zone']) && $user_settings['time_zone'] == $tz['time_zone'] ? 'selected="selected"' : '' ?> value="<?php echo $tz['time_zone'] ?>">
                                                    <?php echo $tz['country_name'] . ' ' . $tz['time_zone'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="hgroupNo">Default Tag</label>
                                    <input type="text" class="form-control" id="hgroupNo" name="group_ids" placeholder="Tags" value="<?php echo isset($user_settings['default_tags']) ? $user_settings['default_tags'] : '' ?>">
                                    <?php if (isset($user_tags) && empty($user_tags)) { ?>
                                        <small id="passwordHelpInline" class="text-muted"> <i class="fa fa-info-circle"></i> Please add <a href="<?php echo base_url() ?>tag">Tags</a> first to assign</small>
                                    <?php }else{ ?>
                                        <small id="passwordHelpInline" class="text-muted"> <i class="fa fa-info-circle"></i> Set default tag for new user(new user who is not in contact list). </small>
                                    <?php } ?>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (($this->session->userdata('type') == 'user' && isset($user_datas['waba_access']) && !empty($user_datas['waba_access'])) || $this->session->userdata('type') == 'admin') { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="text-bold">WhatsApp Credentials</h5>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="app_id">App ID</label>
                                        <input type="text" class="form-control" id="app_id" name="app_id" placeholder="" value="<?php echo isset($user_settings['app_id']) && !empty($user_settings['app_id']) ? $user_settings['app_id'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the app id</div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hToken">Permanent Access Token</label>
                                        <textarea class="form-control" name="permanent_access_token" id="hToken" rows="3"><?php echo isset($user_settings['permanent_access_token']) ? $user_settings['permanent_access_token'] : '' ?></textarea>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">Please fill the permanent access token</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hInstance">Phone Number ID</label>
                                        <input type="text" class="form-control" id="hInstance" name="phone_number_id" placeholder="" value="<?php echo isset($user_settings['phone_number_id']) ? $user_settings['phone_number_id'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the phone number id</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hbusinessid">Business Account ID</label>
                                        <input type="text" class="form-control" id="hbusinessid" name="business_account_id" placeholder="" value="<?php echo isset($user_settings['business_account_id']) ? $user_settings['business_account_id'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the business account id</div>
                                    </div>
                                </div>
                                <?php if (!empty($user_datas['waba_access']) && $user_data['type'] == 'user') { ?>
                                    <div class="col-lg-12 col-12">
                                        <div class="row">
                                            <div class="col-lg-9 col-9">
                                                <p>If you want to stop this message forwarding service then switch this button off. &nbsp;</p>
                                            </div>
                                            <div class="col-lg-2 col-2">
                                                <div class="form-group mb-4--">
                                                    <div class="form-check form-switch form-check-inline">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="forward_text" name="forward_text" <?php echo isset($user_settings['forward_text']) && !empty($user_settings['forward_text']) ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-12 forward_text_block <?php echo isset($user_settings['forward_text']) && !empty($user_settings['forward_text']) ? '' : 'hide' ?>">
                                                <label>WhatsApp Number (with country code)</label>
                                                <input type="text" class="form-control" id="forward_to" name="forward_to" value="<?php echo isset($user_settings['forward_to']) && !empty($user_settings['forward_to']) ? $user_settings['forward_to'] : '' ?>" />
                                                <div id="toggleAccordion" class="accordion mt-1">
                                                    <div class="card">
                                                        <div class="card-header" id="...">
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#defaultAccordionOne" aria-expanded="false" aria-controls="defaultAccordionOne">
                                                                    <i class="fa fa-circle-info"></i> Please create template name with "forward_1" in following format!
                                                                </div>
                                                            </section>
                                                        </div>

                                                        <div id="defaultAccordionOne" class="collapse" aria-labelledby="..." data-bs-parent="#toggleAccordion">
                                                            <div class="card-body">
                                                                <div class="col-12 automation_template_div mb-0 forward_inquiry_template_block">
                                                                    <div class="temp_details">
                                                                        <div id="temp_description" readonly="">
                                                                            <span class="text-dark">A new message is received on your number {{1}}<br>
                                                                                <br>
                                                                                Name: {{2}}<br>
                                                                                <br>
                                                                                Number: {{3}}<br>
                                                                                <br>
                                                                                Message : {{4}}<br>
                                                                                <br>
                                                                                Support Team.</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="text-bold">API Credentials</h5>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="form-group mb-4">
                                    <label for="hapicred">Access Token</label>
                                    <input type="text" class="form-control" id="hapicred" <?php echo isset($user_settings['api_token']) && !empty($user_settings['api_token']) ? 'readonly' : 'name="api_token"' ?> placeholder="" value="<?php echo isset($user_settings['api_token']) ? $user_settings['api_token'] : '' ?>" required>
                                    <div class="invalid-feedback">Please fill the access token</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (($this->session->userdata('type') == 'user' && isset($user_datas['crm_lead_access']) && !empty($user_datas['crm_lead_access'])) || $this->session->userdata('type') == 'admin') { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="text-bold">India Mart Credentials</h5>
                            <div class="row mb-2">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hcrm_key">CRM Key</label>
                                        <input type="text" class="form-control" id="hcrm_key" name="crm_key" placeholder="" value="<?php echo isset($user_settings['crm_key']) ? $user_settings['crm_key'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the access token</div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-bold">TradeIndia Credentials</h5>
                            <div class="row mb-2">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hUserid">User ID</label>
                                        <input type="text" class="form-control" id="hUserid" name="tradeindia_user_id" placeholder="TradeIndia UserID" value="<?php echo isset($user_settings['tradeindia_user_id']) ? $user_settings['tradeindia_user_id'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the User ID</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hProfileid">Profile ID</label>
                                        <input type="text" class="form-control" id="hProfileid" name="tradeindia_profile_id" placeholder="TradeIndia ProfileID" value="<?php echo isset($user_settings['tradeindia_profile_id']) ? $user_settings['tradeindia_profile_id'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the Profile ID</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="hkey">Key</label>
                                        <input type="text" class="form-control" id="hkey" name="tradeindia_key" placeholder="TradeIndia KEY" value="<?php echo isset($user_settings['tradeindia_key']) ? $user_settings['tradeindia_key'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the Key</div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-bold">ExportersIndia Credentials</h5>
                            <div class="row mb-2">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="eikey">ExportersIndia Key</label>
                                        <input type="text" class="form-control" id="eikey" name="exportersindia_key" placeholder="ExportersIndia KEY" value="<?php echo isset($user_settings['exportersindia_key']) ? $user_settings['exportersindia_key'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the ExportersIndia KEY</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-4">
                                        <label for="eiemail">ExportersIndia Email</label>
                                        <input type="text" class="form-control" id="eiemail" name="exportersindia_email" placeholder="ExportersIndia Email" value="<?php echo isset($user_settings['exportersindia_email']) ? $user_settings['exportersindia_email'] : '' ?>" required>
                                        <div class="invalid-feedback">Please fill the ExportersIndia Email</div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($user_datas['waba_access']) && $user_data['type'] == 'user') { ?>
                                <div class="row mb-2">
                                    <div class="col-lg-5 col-9">
                                        <p>Do you want to send message on each inquiry? &nbsp;</p>
                                    </div>
                                    <div class="col-lg-2 col-2">
                                        <div class="form-group mb-4--">
                                            <div class="form-check form-switch form-check-inline">
                                                <input class="form-check-input" type="checkbox" role="switch" id="message_on_inquiry" name="message_on_inquiry" <?php echo isset($user_settings['message_on_inquiry']) && !empty($user_settings['message_on_inquiry']) ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <section class="mb-3 col-lg-8 col-12 message_on_inquiry_block <?php echo isset($user_settings['message_on_inquiry']) && !empty($user_settings['message_on_inquiry']) ? '' : 'hide' ?>">
                                    <?php
                                    $template_id = '';
                                    $hide_class = 'hide';
                                    if (isset($user_settings['inquiry_temp']) && !empty($user_settings['inquiry_temp'])) {

                                        $inquiry_temp = $user_settings['inquiry_temp'];
                                        $template_id = isset($inquiry_temp['template_id']) ? $inquiry_temp['template_id'] : '';

                                        if (!empty($template_id)) {
                                            $hide_class = '';
                                            $template_values = (!empty($inquiry_temp['template_values'])) ? (array) json_decode($inquiry_temp['template_values']) : array();
                                            $template_media = (!empty($inquiry_temp['template_media'])) ? (array) json_decode($inquiry_temp['template_media']) : array();
                                            $key = 1;
                                            $automation_media = array(
                                                'template_value' => isset($template_values[$key]) ? $template_values[$key] : array(),
                                                'template_media' => isset($template_media[$key]) ? $template_media[$key] : '',
                                            );
                                            $temp_detail = get_template_details($automation_media, $inquiry_temp['template_id'], $key, 'edit', 'settings');

                                            if (isset($temp_detail) && !empty($temp_detail)) {
                                                $temp_name = $temp_detail['name'];
                                                $temp_response = $temp_detail['response'];
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-9 col-12">
                                            <div class="form-group mb-4">
                                                <label for="inquiry_template">Select Templates</label><br />
                                                <select class="form-control basic template" id="inquiry_template" name="inquiry_template">
                                                    <?php
                                                    $options = $selected = '';
                                                    if (isset($templates) && !empty($templates)) {
                                                        ?>
                                                        <option value="">Select Template</option>
                                                        <?php
                                                        foreach ($templates as $temp) {
                                                            $selected = !empty($template_id) && ($temp['id'] == $template_id) ? 'selected="selected"' : '';
                                                            echo '<option value="' . $temp['id'] . '" ' . $selected . '>' . $temp['name'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback">Please select templates</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class=" automation_template_div inquiry_template_block <?php echo $hide_class ?> ">
                                                <?php echo!empty($temp_response) ? $temp_response : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="row">
                                    <div class="col-lg-5 col-9">
                                        <p>Do you want to forward each inquiry details to your number? &nbsp;</p>
                                    </div>
                                    <div class="col-lg-2 col-2">
                                        <div class="form-group mb-4--">
                                            <div class="form-check form-switch form-check-inline">
                                                <input class="form-check-input" type="checkbox" role="switch" id="forward_inquiry" name="forward_inquiry" <?php echo isset($user_settings['forward_inquiry']) && !empty($user_settings['forward_inquiry']) ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <section class="col-lg-8 col-12 forward_inquiry_block <?php echo isset($user_settings['forward_inquiry']) && !empty($user_settings['forward_inquiry']) ? '' : 'hide' ?>">
                                    <p aria-readonly=""><i class="fa fa-circle-info"></i> <b>Please create template name with
                                            "forward_1" in following format!</b></p>
                                    <div class="row">

                                        <!--<div class="col-12">
                                                <div class="form-group mb-4">
                                                    <label for="forward_inquiry_template">Select Templates</label><br/>
                                                    <select class="form-control basic template" id="forward_inquiry_template" name="forward_inquiry_template">
                                        <?php
                                        /* $options = $selected = '';
                                          if (isset($templates) && !empty($templates)) {
                                          ?>
                                          <option value="">Select Template</option>
                                          <?php
                                          foreach ($templates as $temp) {
                                          $selected = !empty($user_settings['forward_inquiry_template_id']) && ($temp['id'] == $user_settings['forward_inquiry_template_id']) ? 'selected="selected"' : '';
                                          echo '<option value="' . $temp['id'] . '" ' . $selected . '>' . $temp['name'] . '</option>';
                                          }
                                          } */
                                        ?>
                                                    </select>
                                                    <div class="valid-feedback"></div>
                                                    <div class="invalid-feedback">Please select templates</div>
                                                </div>
                                            </div>-->
                                        <div class="col-12 automation_template_div  forward_inquiry_template_block <?php //echo isset($user_settings['forward_inquiry_template_id']) && !empty($user_settings['forward_inquiry_template_id']) ? '' : 'hide'                 
                                        ?> ">
                                                 <?php //echo isset($user_settings['forward_inquiry_template']) && !empty($user_settings['forward_inquiry_template']) ? $user_settings['forward_inquiry_template'] : ''   
                                                 ?>


                                            <div class="temp_details">
                                                <div id="temp_description" readonly="">
                                                    <span class="text-dark">A new message is received on your number {{1}}<br>
                                                        <br>
                                                        Name: {{2}}<br>
                                                        <br>
                                                        Number: {{3}}<br>
                                                        <br>
                                                        Message : {{4}}<br>
                                                        <br>
                                                        Support Team.</span>
                                                </div>
                                                <div id="temp_buttons" readonly=""></div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                            <?php } ?>


                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-lg-12 col-12 text-center">
                        <input type="submit" name="Save" class="mt-4 mb-4 btn btn-primary btn-lg">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script>
    new TomSelect("#select-beast", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
    var userTags = '<?php echo isset($user_tags) && !empty($user_tags) ? json_encode($user_tags) : "" ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/users.js?t=<?php echo date('YmdHis') ?>"></script>