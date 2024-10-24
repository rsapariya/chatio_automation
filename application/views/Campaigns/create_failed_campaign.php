<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Campaign</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Create Campaign For Failed Contact</h4></div>   
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-5 col-md-6 col-sm-12 col-12">
                            <form method="post" id="create_failed_campaign_form">
                                <input type="hidden" name='campaign_id' value='<?php echo isset($campaign_id) ? base64_encode($campaign_id) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2" id="contact-counter">
                                        Total Contacts : <?php echo isset($contacts) && !empty($contacts) ? $contacts : '0' ?>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="campaign_name">Campaign Name</label>
                                            <input type="text" class="form-control" id="campaign_name" name="campaign_name" value="">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback" id="campaign_name_error">Please fill the name</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group mb-4">
                                            <label for="campaign_template">Select Templates</label><br/>
                                            <select class="form-control basic template" id="campaign_template" name="campaign_template">
                                                <?php
                                                if (isset($templates) && !empty($templates)) {
                                                    ?>
                                                    <option disabled="disabled" selected="selected">Select Template</option>
                                                    <?php
                                                    foreach ($templates as $temp) {
                                                        echo '<option value="' . $temp['id'] . '">' . $temp['name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please select templates</div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="campaign_template_preview"></div>
                                    <div class="col-12 mt-2">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input type="radio" id="send_now" name='notification_campaign' value='send_now' class="custom-control-input radio-classic-primary notification_campaign" checked="checked">
                                            <label class="form-check-label" for="send_now">
                                                Send now
                                            </label>
                                        </div>
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input type="radio" id="schedule_campaign" name='notification_campaign' value='schedule_campaign' class="custom-control-input radio-classic-primary notification_campaign">
                                            <label class="form-check-label" for="schedule_campaign">
                                                Schedule Campaign
                                            </label>
                                        </div>
                                        <div id="notification_date_block">
                                            
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2 user-message"></div>
                                    <div class="col-12 mt-2">
                                        <button type="button" class="mt-4 mb-4 btn btn-primary" id="btn-create-failed-campaign">Create Campaign</button>
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
<script>
    var userTags = '<?php echo isset($user_tags) && !empty($user_tags) ? json_encode($user_tags) : "" ?>';
</script>

<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/campaigns.js?t=<?php echo date('YmdHis') ?>"></script>
