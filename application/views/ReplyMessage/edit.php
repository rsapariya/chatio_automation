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
                        <div class="col-lg-6 col-12"><h4><?php echo isset($reply_message_datas['id']) ? 'Edit' : 'Add' ?> Reply Messages</h4></div>    
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                            <form method="post" action="<?php echo base_url() . 'replyMessage/save' ?>" class="add_reply_message" novalidate enctype="multipart/form-data" >
                                <input type="hidden" name='reply_message_id' value='<?php echo isset($reply_message_datas['id']) ? base64_encode($reply_message_datas['id']) : '' ?>'/>
                                <input type="hidden" name='reply_id' value='<?php echo isset($reply_message_datas['reply_id']) ? $reply_message_datas['reply_id'] : '' ?>'/>
                                <input type="hidden" id='list_templates' value='<?php echo isset($list_templates) ? json_encode($list_templates) : '' ?>'/>
                                <input type="hidden" id='meta_templates' value='<?php echo isset($meta_templates) ? json_encode($meta_templates) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hReplyText">Trigger Text</label>
                                            <input type="text" class="tagsinput form-control" id="hReplyText" data-role="tagsinput" name="reply_text" placeholder="" value="<?php echo isset($reply_message_datas['reply_text']) ? $reply_message_datas['reply_text'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the trigger text</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2 reply_message_attachment_details">
                                        <?php
                                        $attachments_details = array();
                                        if (isset($reply_message_datas['attachments']) && !empty($reply_message_datas['attachments'])) {
                                            $attachments_details = json_decode($reply_message_datas['attachments']);
                                        }
                                        $attachments_caption = (!empty($reply_message_datas['attachments_caption'])) ? (array) json_decode($reply_message_datas['attachments_caption']) : array();
                                        $template_values = (!empty($reply_message_datas['template_values'])) ? (array) json_decode($reply_message_datas['template_values']) : array();
                                        $template_media = (!empty($reply_message_datas['template_media'])) ? (array) json_decode($reply_message_datas['template_media']) : array();
                                        $template_button_url = (!empty($reply_message_datas['template_button_url'])) ? (array) json_decode($reply_message_datas['template_button_url']) : array();
                                        $attachments_html = '';
                                        if (!empty($attachments_details)) {
                                            foreach ($attachments_details as $key => $detail) {
                                                if (is_numeric($detail)) {
                                                    $options = '';
                                                    $Temp_html = '';
                                                    $temp_name = '';
                                                    $temp_response = '';
                                                    $meta_templates_Keys = array_column($meta_templates, 'id');
                                                    $list_templates_Keys = array_column($list_templates, 'id');
                                                    if (in_array($detail, $meta_templates_Keys)) {
                                                        $automation_media = array(
                                                            'template_value' => isset($template_values[$key]) ? $template_values[$key] : array(),
                                                            'template_media' => isset($template_media[$key]) ? $template_media[$key] : '',
                                                            'template_button_url' => isset($template_button_url[$key]) ? $template_button_url[$key] : '',
                                                        );
                                                        $temp_detail = get_template_details($automation_media, $detail, $key, 'edit');
                                                        if (isset($temp_detail) && !empty($temp_detail)) {
                                                            $temp_name = $temp_detail['name'];
                                                            $temp_response = $temp_detail['response'];
                                                        }
    
                                                        $Temp_html .= '<div class="reply_meta_template_div automation_template_div" id="reply_meta_template_div_' . $key . '" data-seq="' . $key . '">';
                                                        $Temp_html .=       '<div class="form-group row mb-4">';
                                                        $Temp_html .=           '<label class="reply_meta_template_name" id="reply_meta_template_name_' . $key . '">' . $temp_name . '</label>' .
                                                                                '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">' .
                                                                                    '<div class="reply_meta_template_desc_div" id="reply_meta_template_desc_div_' . $key . '" data-seq="' . $key . '">';
                                                        $Temp_html .= $temp_response;
                                                    $Temp_html .=                   '</div>';
                                                    $Temp_html .=                '</div></div></div>';
    
                                                        $attachments_html .= '<div class="reply_meta_template_details_div reply_attachment_details_div" id="reply_meta_template_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                                '<div class="form-group row mb-3">' .
                                                                '<label > Select Meta Template</label>' .
                                                                '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">';
                                                        $options .= '<option value="">Select Meta Template</option>';
    
                                                        if (isset($meta_templates) && !empty($meta_templates)) {
                                                            foreach ($meta_templates as $meta_template) {
                                                                $selected = '';
                                                                if ($meta_template['id'] == $detail) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                                $options .= '<option value="' . $meta_template['id'] . '" ' . $selected . '>' . $meta_template['name'] . '</option>';
                                                            }
                                                        }
                                                        $attachments_html .= '<select class="form-control basic reply_meta_template" required id="reply_meta_template_' . $key . '" name= "reply_meta_templates[' . $key . ']">' . $options . '</select>';
                                                    } elseif (in_array($detail, $list_templates_Keys)) {
                                                        $attachments_html .= '<div class="reply_template_details_div reply_attachment_details_div" id="reply_template_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                                '<div class="form-group row mb-3">' .
                                                                '<label> Select Custom Template</label>' .
                                                                '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">';
                                                        $options .= '<option value="">Select Custom Template</option>';
    
                                                        if (isset($list_templates) && !empty($list_templates)) {
                                                            foreach ($list_templates as $list_template) {
                                                                $selected = '';
                                                                if ($list_template['id'] == $detail) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                                $options .= '<option value="' . $list_template['id'] . '" ' . $selected . '>' . $list_template['name'] . ' (' . $list_template['custom_type'] . ')</option>';
                                                            }
                                                        }
                                                        $attachments_html .= '<select class="form-control basic reply_template" required id="reply_template_' . $key . '" name= "reply_templates[' . $key . ']">' . $options . '</select>';
                                                    }
                                                } else {
                                                    $caption = isset($attachments_caption[$key]) && !empty($attachments_caption[$key]) ? $attachments_caption[$key] : "";
                                                    $attachments_html .= '<div class="reply_attachment_details_div" id="reply_attachment_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                            '<div class="form-group row mb-3">' .
                                                            '<label> Attachments</label>' .
                                                            '<div class="col-xl-11 col-lg-11 col-sm-11 col-11">';
                                                    $attachments_html .= '<div clas="row>';
                                                    $attachments_html .= '<div class="col-12">';
                                                    $attachments_html .= '<a class="text-success" target="_blank" href="' . base_url() . '' . ATTACHMENT_IMAGE_UPLOAD_PATH . '' . $detail . '">' . $detail . '</a>' .
                                                            '<input type="hidden" target="_blank" name="existing_attachments[' . $key . ']" value="' . $detail . '"/>';
                                                    $attachments_html .= '</div>';
                                                    $attachments_html .= '<div class="col-12">';
                                                    $attachments_html .= '<input type="text" class="form-control" id="attachment_caption'.$key.'" name="attachment_caption['.$key.']" value="'.$caption.'" placeholder="enter caption for attachment" maxlength="1000"  />';
                                                    $attachments_html .= '</div>';
                                                }
                                                $attachments_html .= '</div>' .
                                                        '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' .
                                                        '<div class="actions  align-self-center">' .
                                                        '<a href="javascript:void(0);" class="btn btn-danger btn-rounded delete_message" data-id="' . $key . '">' .
                                                        '<i class="fa fa-trash"></i>' .
                                                        '</a>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>' .
                                                        '</div>';
                                                if (!empty($Temp_html)) {
                                                    $attachments_html .= $Temp_html;
                                                }
                                            }
                                            echo $attachments_html;
                                        }
                                        ?>
                                    </div>

                                    <div class="col-12 mt-2 text-center mt-4">
                                        <button type="button" class="btn btn-info btn-add-template">
                                            <i class="fa fa-receipt"></i>
                                            <span>Add Template</span>
                                        </button> 
                                        <button type="button" class="btn btn-info btn-add-attachment ml-3">
                                            <i class="fa fa-paperclip"></i>
                                            <span>Add Attachment</span>
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
<script>
    // The DOM element you wish to replace with Tagify
    var input = document.querySelector('input[name=reply_text]');
    // initialize Tagify on the above input node reference
    new Tagify(input)
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/reply_messages.js?<?php echo date('YmdHis') ?>"></script>