<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Recurring</h3></div>
                </div>
            </div>
        </header>
    </div>
</div>
<!--  END BREADCRUMBS  -->
<div class="row layout-top-spacing">
    <div class="col-lg-12 mb-1">
        <?php $this->load->view('Partial/alert_view');
        //pr($recurring_datas, 1);
        
        
        ?> 
    </div>
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-lg-6 col-12"><h4><?php echo isset($recurring_datas['id']) ? 'Edit' : 'Add' ?> Recurring</h4></div>    
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                            <form method="post" id="add_recurring_form">
                                <div class="user-message"></div>
                                <input type="hidden" name='recurring_id' value='<?php echo isset($recurring_datas['id']) ? base64_encode($recurring_datas['id']) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2 mb-2">
                                        <div class="form-group">
                                            <label for="hName">Contact Name</label>
                                            <div>
                                                <select class="select-search js-example-basic-single" name="client_id">
                                                    <?php
                                                    if (isset($contacts_arr) && !empty($contacts_arr)) {
                                                        foreach ($contacts_arr as $cont) {
                                                            $selected = ($cont['id'] == $recurring_datas['client_id']) ? 'selected="selected"' : '';
                                                            ?>
                                                            <option value="<?php echo $cont['id'] ?>"  <?php echo $selected ?> ><?php echo $cont['name'] . ' (' . $cont['phone_number_full'] . ')' ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group mb-4">
                                            <label for="template_id">Select Templates</label><br/>
                                            <select class="form-control basic template" id="template_id" name="template_id">
                                                <?php
                                                $options = $selected = '';
                                                $options .= '<option value="">Select Template</option>';
                                                if (isset($automation_templates) && !empty($automation_templates)) {
                                                    foreach ($automation_templates as $automation_template) {
                                                        $selected = ($automation_template['id'] == $recurring_datas['template_id']) ? 'selected="selected"' : '';
                                                        $options .= '<option value="' . $automation_template['id'] . '" ' . $selected . '>' . $automation_template['name'] . '</option>';
                                                    }
                                                }
                                                $options .= '<option value="other" ' . ($recurring_datas['template_id'] == 'other' ? 'selected="selected"' : '') . '>Other</option>';
                                                echo $options;
                                                ?>
                                            </select>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please select templates</div>
                                        </div>
                                    </div>
                                    <?php
                                    $hide_class = $hide_other_class = 'hide';
                                    $template_id = isset($recurring_datas['template_id']) ? $recurring_datas['template_id'] : '';
                                    if (!empty($template_id) && $template_id != 'other') {
                                        $hide_class = '';
                                        $hide_other_class = 'hide';
                                    } else if (!empty($template_id) && $template_id == 'other') {
                                        $hide_class = 'hide';
                                        $hide_other_class = '';
                                    }
                                    ?>
                                    <div class="col-12 mt-3 automation_template_div <?php echo $hide_class ?>" id="template_preview">
                                        <?php echo isset($template_preview) && !empty($template_preview) ? $template_preview : '' ?>
                                    </div>




                                    <?php
                                    /*$temp_detail = $temp_name = $temp_response = '';
                                    if (!empty($template_id) && $template_id != 'other') {
                                        $hide_class = '';
                                        $hide_other_class = 'hide';
                                        $description = isset($recurring_datas['description']) ? $recurring_datas['description'] : '';
                                        $template_values = (!empty($recurring_datas['template_values'])) ? (array) json_decode($recurring_datas['template_values']) : array();
                                        $template_media = (!empty($recurring_datas['template_media'])) ? (array) json_decode($recurring_datas['template_media']) : array();
                                        $key = 1;
                                        $automation_media = array(
                                            'template_value' => isset($template_values[$key]) ? $template_values[$key] : array(),
                                            'template_media' => isset($template_media[$key]) ? $template_media[$key] : '',
                                        );
                                        $temp_detail = get_template_details($automation_media, $recurring_datas['template_id'], $key, 'edit', 'recurring');
                                        if (isset($temp_detail) && !empty($temp_detail)) {
                                            $temp_name = $temp_detail['name'];
                                            $temp_response = $temp_detail['response'];
                                        }
                                    } else if (!empty($template_id) && $template_id == 'other') {
                                        $hide_class = 'hide';
                                        $hide_other_class = '';
                                    }*/
                                    ?>
<!--<div class="col-lg-8 col-12 automation_details <?php //echo $hide_class  ?>">
    <div class="automation_template_div" id="automation_template_div_1" data-seq="1">
        <div class="form-group">
            <label class="automation_template_name" id="automation_template_name_1"><?php //echo $temp_name   ?></label>
            <div class="automation_template_details_div" id="automation_template_details_div_1" data-seq="1">
                                    <?php //echo $temp_response  ?>
            </div>
        </div> 
    </div>
</div>-->
                                    <div class="col-12 mt-2">
                                        <div class="form-group row mb-4 other_template_div <?php echo $hide_other_class ?>">
                                            <label for="hDescription">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="5"><?php echo isset($recurring_datas['description']) ? $recurring_datas['description'] : 'Hello, ||name||' ?></textarea>
                                            <small id="passwordHelpInline" class="text-muted"> Add ||name|| for replace customer name</small>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the description</div>
                                        </div> 
                                    </div>
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4">
                                            <label for="hPhoneNo">Type</label><br/>
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input type="radio" id="hRadio1" name='trigger_type' value='daily' <?php echo isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'daily' ? 'checked="checked"' : 'checked="checked"' ?> class="custom-control-input radio-classic-primary recurring-cls" required>
                                                <label class="form-check-label" for="hRadio1">
                                                    Daily
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input type="radio" id="hRadio2" name='trigger_type' value='weekly' <?php echo isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'weekly' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary recurring-cls" required>
                                                <label class="form-check-label" for="hRadio2">
                                                    Weekly
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input type="radio" id="hRadio3" name='trigger_type' value='monthly' <?php echo isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'monthly' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary recurring-cls" required>
                                                <label class="form-check-label" for="hRadio3">
                                                    Monthly
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input type="radio" id="hRadio4" name='trigger_type' value='yearly' <?php echo isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'yearly' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary recurring-cls" required>
                                                <label class="form-check-label" for="hRadio4">
                                                    Yearly
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $hide_weekly_class = 'hide';
                                    $required_weekly_class = '';
                                    $hide_monthly_class = 'hide';
                                    $required_monthly_class = '';
                                    $hide_yearly_class = 'hide';
                                    $required_yearly_class = '';
                                    if (isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'yearly') {
                                        $hide_yearly_class = '';
                                        $hide_weekly_class = 'hide';
                                        $hide_monthly_class = 'hide';
                                        $required_weekly_class = '';
                                        $required_monthly_class = '';
                                        $required_yearly_class = 'required';
                                    } else if (isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'weekly') {
                                        $hide_weekly_class = '';
                                        $hide_yearly_class = 'hide';
                                        $hide_monthly_class = 'hide';
                                        $required_weekly_class = 'required';
                                        $required_monthly_class = '';
                                        $required_yearly_class = '';
                                    } else if (isset($recurring_datas['trigger_type']) && $recurring_datas['trigger_type'] == 'monthly') {
                                        $hide_monthly_class = '';
                                        $hide_weekly_class = 'hide';
                                        $hide_yearly_class = 'hide';
                                        $required_weekly_class = '';
                                        $required_monthly_class = 'required';
                                        $required_yearly_class = '';
                                    }
                                    ?>
                                    <div class='col-lg-8 col-12 trigger_weekly_div <?php echo $hide_weekly_class ?>'>
                                        <div class="form-group mb-4">
                                            <?php $weekly_day = isset($recurring_datas['weekly_day']) ? $recurring_datas['weekly_day'] : 'Monday'; ?>
                                            <label for="">Week Day</label><br/>
                                            <select class='weekly_day form-control' name='weekly_day' id='weekly_day'>
                                                <?php
                                                $selected = 'selected="selected"';
                                                ?>
                                                <option value='Monday' <?php echo ($weekly_day == 'Monday') ? $selected : '' ?>>Monday</option>
                                                <option value='Tuesday' <?php echo ($weekly_day == 'Tuesday') ? $selected : '' ?>>Tuesday</option>
                                                <option value='Wednesday' <?php echo ($weekly_day == 'Wednesday') ? $selected : '' ?>>Wednesday</option>
                                                <option value='Thursday' <?php echo ($weekly_day == 'Thursday') ? $selected : '' ?>>Thursday</option>
                                                <option value='Friday' <?php echo ($weekly_day == 'Friday') ? $selected : '' ?>>Friday</option>
                                                <option value='Saturday' <?php echo ($weekly_day == 'Saturday') ? $selected : '' ?>>Saturday</option>
                                                <option value='Sunday' <?php echo ($weekly_day == 'Sunday') ? $selected : '' ?>>Sunday</option>
                                            </select>
                                            <div class="invalid-feedback">Please fill the week day</div>
                                        </div>
                                    </div>
                                    <div class='col-lg-8 col-12 trigger_monthly_div <?php echo $hide_monthly_class ?>'>
                                        <div class="form-group mb-4">
                                            <?php $monthly_date = isset($recurring_datas['monthly_date']) ? $recurring_datas['monthly_date'] : 1; ?>
                                            <label for="">Month Date</label>
                                            <div class="col-xl-10 col-lg-10 col-sm-10 col-10">
                                                <select class='monthly_date form-control' name='monthly_date' id='monthly_date'>
                                                    <?php
                                                    for ($i = 1; $i <= 31; $i++) {
                                                        $selected = '';
                                                        if ($i == $monthly_date) {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value='<?php echo $i ?>' <?php echo $selected ?>><?php echo $i ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback">Please fill the month date</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-lg-8 col-12 trigger_yearly_div <?php echo $hide_yearly_class ?>'>
                                        <div class="form-group mb-4">
                                            <?php $yearly_date = isset($recurring_datas['yearly_date']) ? $recurring_datas['yearly_date'] : date('Y-m-d'); ?>
                                            <label for="yearly_date">Yearly Date</label>
                                            <input id="yearly_date" name='yearly_date' value="<?php echo $yearly_date ?>" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Year Time">
                                            <div class="invalid-feedback"> Please fill the yearly date</div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hTime">Trigger Time</label>
                                            <input id="hTime" name='trigger_time' value="<?php echo isset($recurring_datas['trigger_time']) ? date('H:i', strtotime(getServerTimeZone($recurring_datas['trigger_time']))) : '' ?>" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Trigger Time">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the trigger time</div>
                                        </div> 
                                    </div>





                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary" id="btn-save-recurring">Save</button>
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
    let flatTriggerTime = '<?php echo isset($recurring_datas["trigger_time"]) ? date("H:i", strtotime(getServerTimeZone($recurring_datas["trigger_time"]))) : date('H:i') ?>';
    var contactsArr = '<?php echo isset($contacts_arr) && !empty($contacts_arr) ? json_encode($contacts_arr) : "" ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/recurrings.js?<?php echo date('ymdhis'); ?>"></script>