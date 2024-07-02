<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Automations</h3></div>
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
                        <div class="col-lg-6 col-12"><h4><?php echo isset($automation_datas['id']) ? 'Edit' : 'Add' ?> Automations</h4></div>    
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                            <form method="post" action="<?php echo base_url() . 'automations/save' ?>" class="add_automation" novalidate enctype="multipart/form-data" >
                                <input type="hidden" name='automation_id' value='<?php echo isset($automation_datas['id']) ? base64_encode($automation_datas['id']) : '' ?>'/>
                                <input type="hidden" id='automation_templates' value='<?php echo isset($automation_templates) ? json_encode($automation_templates) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hName">Name</label>
                                            <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($automation_datas['name']) ? $automation_datas['name'] : '' ?>">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the name</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hTime">Trigger Time</label>
                                            <input id="hTime" name='trigger_time' value="<?php echo isset($automation_datas['trigger_time']) ? $automation_datas['trigger_time'] : '' ?>" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Trigger Time">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the trigger time</div>
                                        </div> 
                                    </div>
                                    <div class="automation_details">
                                    <?php
                                    $details = array();
                                    if (isset($automation_datas['details']) && !empty($automation_datas['details'])) {
                                        $details = json_decode($automation_datas['details']);
                                    }
                                    $template_values = (!empty($automation_datas['template_values'])) ? (array) json_decode($automation_datas['template_values']) : array();
                                    $template_media = (!empty($automation_datas['template_media'])) ? (array) json_decode($automation_datas['template_media']) : array();
                                    $template_media_names = (!empty($automation_datas['template_media_names'])) ? (array) json_decode($automation_datas['template_media_names']) : array();
                                    $template_button_url = (!empty($automation_datas['template_button_url'])) ? (array) json_decode($automation_datas['template_button_url']) : array();
                                    if (!empty($details)) {
                                        foreach ($details as $key => $detail) {
                                            if (is_numeric($detail)) {
                                                $options = '';
                                                $Temp_html = '';
                                                $temp_name = '';
                                                $temp_response = '';
                                                if (isset($automation_templates) && !empty($automation_templates)) {
                                                    foreach ($automation_templates as $automation_template) {
                                                        $selected = ($automation_template['id'] == $detail) ? 'selected="selected"' : '';
                                                        $options .= '<option value="' . $automation_template['id'] . '" ' . $selected . '>' . $automation_template['name'] . '</option>';
                                                        if ($automation_template['id'] == $detail) {
                                                            $automation_media = array(
                                                                'template_value' => isset($template_values[$key]) ? $template_values[$key] : array(),
                                                                'template_media' => isset($template_media[$key]) ? $template_media[$key] : '',
                                                                'template_media_name' => isset($template_media_names[$key]) ? $template_media_names[$key] : '',
                                                                'template_button_url' => isset($template_button_url[$key]) ? $template_button_url[$key] : '',
                                                            );
                                                            $temp_detail = get_template_details($automation_media, $automation_template['id'], $key, 'edit');

                                                            if (isset($temp_detail) && !empty($temp_detail)) {
                                                                $temp_name = $temp_detail['name'];
                                                                $temp_response = $temp_detail['response'];
                                                            }
                                                        }
                                                    }
                                                }
                                                $Temp_html .=   '<div class="automation_template_div" id="automation_template_div_' . $key . '" data-seq="' . $key . '">';
                                                $Temp_html .=   '<div class="col-12 mt-2">';
                                                $Temp_html .=   '<div class="form-group row mb-4">';
                                                $Temp_html .=   '<label class="automation_template_name" id="automation_template_name_' . $key . '">' . $temp_name . '</label>' .
                                                                '<div class="col-xl-9 col-lg-9 col-sm-9 col-9">' .
                                                                '<div class="automation_template_details_div" id="automation_template_details_div_' . $key . '" data-seq="' . $key . '">';
                                                $Temp_html .=   $temp_response;
                                                $Temp_html .=   '</div>';
                                                $Temp_html .=   '</div></div></div></div>';
                                                
                                                $html = '<div class="automation_details_div" id="automation_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                        '<div class="col-12 mt-2">'.
                                                        '<div class="form-group row mb-4">' .
                                                        '<label>Select Template</label>' .
                                                        '<div class="col-xl-8 col-lg-8 col-sm-8 col-12">' .
                                                        '<select class="form-control basic template" id="template_' . $key . '" name= "templates[' . $key . ']">' .
                                                        $options .
                                                        '</select>' .
                                                        '</div>' .
                                                        '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' .
                                                        '<div class="actions  align-self-center">' .
                                                        '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_automation" data-id="' . $key . '">' .
                                                        '<i class="fa fa-trash"></i>' .
                                                        '</a>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>';
//                                                $html .= '<div class="automation_template_details_div" id="automation_template_details_div_' . $key . '"  data-seq="' . $key . '">';
                                                if (!empty($Temp_html)) {
                                                    $html .= $Temp_html;
                                                }
//                                                $html .= '</div>';
                                            } else {
                                                $detail_array = explode(' ', $detail);
                                                $delay_count = (isset($detail_array[0])) ? $detail_array[0] : '';
                                                $delay_duration = (isset($detail_array[1])) ? $detail_array[1] : '';
                                                $html = '<div class="automation_details_div" id="automation_details_div_' . $key . '" data-seq="' . $key . '">' .
                                                        '<div class="col-12 mt-2">'.
                                                        '<div class="form-group row mb-4 delay_div">' .
                                                        '<label for="delay_' . $key . '" >Select Delay</label>' .
                                                        '<div class="col-xl-4 col-lg-4 col-sm-4 col-4">' .
                                                        '<input type="text" class="form-control delay_count" id="delay_count_' . $key . '" name="delay_count[' . $key . ']" value="' . $delay_count . '">' .
                                                        '</div>' .
                                                        '<div class="col-xl-4 col-lg-4 col-sm-4 col-4">' .
                                                        '<select class="form-control delay_duration" name="delay_duration[' . $key . ']" id="delay_duration_' . $key . '">' .
                                                        '<option ' . (($delay_duration == "Minutes") ? "selected=\"selected\"" : "") . '>Minutes</option>' .
                                                        '<option ' . (($delay_duration == "Hours") ? "selected=\"selected\"" : "") . '>Hours</option>' .
                                                        '<option ' . (($delay_duration == "Days") ? "selected=\"selected\"" : "") . '>Days</option>' .
                                                        '<option ' . (($delay_duration == "Weeks") ? "selected=\"selected\"" : "") . '>Weeks</option>' .
                                                        '</select>' .
                                                        '</div>' .
                                                        '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' .
                                                        '<div class="actions align-self-center">' .
                                                        '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_automation" data-id="' . $key . '">' .
                                                        '<i class="fa fa-trash"></i>' .
                                                        '</a>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>';
                                            }
                                            echo $html;
                                        }
                                    }
                                    ?>

                                    </div>


                                    <div class="col-12 mt-2 text-center mt-4">
                                        <button type="button" class="btn btn-info btn-add-message">
                                            <i class="fa fa-message"></i>
                                            <span>Add Message</span>
                                        </button> 
                                        <button type="button" class="btn btn-info btn-add-delay ml-3">
                                            <i class="fa fa-hourglass-half"></i>
                                            <span>Add Delay</span>
                                        </button>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/automations.js"></script>