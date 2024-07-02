<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3> Inquiries</h3></div>
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
                        <div class="col-lg-6 col-12"><h4><?php echo isset($inquiry_datas['id']) ? 'Edit' : 'Add' ?> Inquiry</h4></div>   
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                            <form method="post" action="<?php echo base_url() . 'inquiries/save' ?>" class="add_inquiry" novalidate>
                            <input type="hidden" name='inquiry_id' value='<?php echo isset($inquiry_datas['id']) ? base64_encode($inquiry_datas['id']) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hName">Name</label>
                                            <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($inquiry_datas['name']) ? $inquiry_datas['name'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the name</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hPhoneNo">Phone Number</label><br/>
                                            <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($inquiry_datas['phone_number']) ? $inquiry_datas['phone_number'] : '' ?>" required>
                                            <input type="hidden" id="country_code" name='country_code' value=''/>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the phone</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="inquiry_type">Type</label><br/>
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
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the Type</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="inquiry_type">Automation</label><br/>
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
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the automation</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <input type="submit" name="Save" class="mt-4 mb-4 btn btn-primary">
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/clients.js"></script>
