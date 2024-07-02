<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/portlets/portlet.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Inquiries - <?php echo isset($inquiry_datas['id']) ? 'Edit' : 'Add' ?> Inquiry</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($inquiry_datas['id']) ? 'Edit' : 'Add' ?> Inquiry</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form method="post" action="<?php echo base_url() . 'inquiries/save' ?>" class="add_inquiry" novalidate >
                                <input type="hidden" name='inquiry_id' value='<?php echo isset($inquiry_datas['id']) ? base64_encode($inquiry_datas['id']) : '' ?>'/>

                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Name</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($inquiry_datas['name']) ? $inquiry_datas['name'] : '' ?>" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the name
                                        </div>
                                    </div>
                                </div>       
                                <div class="form-group row mb-4">
                                    <label for="hPhoneNo" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Phone Number</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($inquiry_datas['phone_number']) ? $inquiry_datas['phone_number'] : '' ?>" required>
                                        <input type="hidden" id="country_code" name='country_code' value=''/>
                                        <div class="invalid-feedback">
                                            Please fill the phone
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="inquiry_type" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Type</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <select class="form-control basic" id="inquiry_type" name= "inquiry_type" required>
                                            <?php
                                            if (isset($inquiry_types) && !empty($inquiry_types)) {
                                                foreach ($inquiry_types as $inquiry_type) {
                                                    $selected = '';
                                                    if (isset($inquiry_datas['inquiry_type']) && $inquiry_datas['inquiry_type'] == $inquiry_type['id']) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                    ?>
                                                    <option <?php echo $selected ?> value='<?php echo $inquiry_type['id'] ?>'><?php echo $inquiry_type['name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select the type
                                        </div>
                                    </div>
                                </div>                               
                                <div class="form-group row mb-4">
                                    <label for="automation_id" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Automation</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <select class="form-control basic" id="automation_id" name= "automation_id" required>
                                            <?php
                                            if (isset($automations) && !empty($automations)) {
                                                foreach ($automations as $automation) {
                                                    $selected = '';
                                                    if (isset($inquiry_datas['automation_id']) && $inquiry_datas['automation_id'] == $automation['id']) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                    ?>
                                                    <option <?php echo $selected ?> value='<?php echo $automation['id'] ?>'><?php echo $automation['name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select the automation
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
    </div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/inquiries.js"></script>