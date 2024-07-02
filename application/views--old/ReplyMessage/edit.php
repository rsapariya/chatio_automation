<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/portlets/portlet.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/bootstrap-tags.css" rel="stylesheet" type="text/css" />

<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Reply Messages - <?php echo isset($reply_message_datas['id']) ? 'Edit' : 'Add' ?> Reply Message</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($reply_message_datas['id']) ? 'Edit' : 'Add' ?> Reply Messages</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-9 col-md-9 col-sm-9 col-9">
                            <form method="post" action="<?php echo base_url() . 'replyMessage/save' ?>" class="add_reply_message" novalidate enctype="multipart/form-data" >
                                <input type="hidden" name='reply_message_id' value='<?php echo isset($reply_message_datas['id']) ? base64_encode($reply_message_datas['id']) : '' ?>'/>
                                <input type="hidden" name='reply_id' value='<?php echo isset($reply_message_datas['reply_id']) ? $reply_message_datas['reply_id'] : '' ?>'/>
                                <input type="hidden" id='list_templates' value='<?php echo isset($list_templates) ? json_encode($list_templates) : '' ?>'/>
                                <input type="hidden" id='meta_templates' value='<?php echo isset($meta_templates) ? json_encode($meta_templates) : '' ?>'/>
                                <div class="form-group row mb-4">
                                    <label for="hReplyText" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Trigger Text</label>
                                    <div class="col-xl-8 col-lg-8 col-sm-9">
                                        <input type="text" class="tagsinput form-control" id="hReplyText" data-role="tagsinput" name="reply_text" placeholder="" value="<?php echo isset($reply_message_datas['reply_text']) ? $reply_message_datas['reply_text'] : '' ?>" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the Trigger Text
                                        </div>
                                    </div>
                                </div>
                                <div class="reply_message_attachment_details">
                                    <?php
//                                    $messages_details = array();
//                                    if (isset($reply_message_datas['messages']) && !empty($reply_message_datas['messages'])) {
//                                        $messages_details = json_decode($reply_message_datas['messages']);
//                                    }
//                                    $messages_html = '';
//                                    if (!empty($messages_details)) {
//                                        foreach ($messages_details as $key => $detail) {
//                                            $messages_html = '<div class="reply_message_details_div" id="reply_message_details_div_' . $key . '"  data-seq="' . $key . '">' .
//                                                    '<div class="form-group row mb-4">' .
//                                                    '<label class="col-xl-3 col-sm-3 col-sm-3 col-3 col-form-label" >Enter Message</label>' .
//                                                    '<div class="col-xl-8 col-lg-8 col-sm-8 col-8">' .
//                                                    '<input type="text" class="form-control" id="message_' . $key . '" name="messages[' . $key . ']" placeholder="" value="' . $detail . '" required>' .
//                                                    '</div>' .
//                                                    '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' .
//                                                    '<div class="actions  align-self-center">' .
//                                                    '<a href="javascript:void(0);" class="btn btn-red btn-circle delete_message" data-id="' . $key . '">' .
//                                                    '<i class="flaticon-delete"></i>' .
//                                                    '</a>' .
//                                                    '</div>' .
//                                                    '</div>' .
//                                                    '</div>' .
//                                                    '</div>';
//                                            echo $messages_html;
//                                        }
//                                    }


                                    $attachments_details = array();
                                    if (isset($reply_message_datas['attachments']) && !empty($reply_message_datas['attachments'])) {
                                        $attachments_details = json_decode($reply_message_datas['attachments']);
                                    }
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
                                                    $Temp_html .= '<div class="form-group row mb-4">';
                                                    $Temp_html .= '<label class="col-xl-3 col-sm-3 col-sm-3 col-3 col-form-label reply_meta_template_name" id="reply_meta_template_name_' . $key . '">' . $temp_name . '</label>' .
                                                            '<div class="col-xl-9 col-lg-9 col-sm-9 col-9">' .
                                                            '<div class="reply_meta_template_desc_div" id="reply_meta_template_desc_div_' . $key . '" data-seq="' . $key . '">';
                                                    $Temp_html .= $temp_response;
                                                    $Temp_html .= '</div>';
                                                    $Temp_html .= '</div></div></div>';

                                                    $attachments_html .= '<div class="reply_meta_template_details_div reply_attachment_details_div" id="reply_meta_template_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                            '<div class="form-group row mb-3">' .
                                                            '<label class="col-xl-3 col-sm-3 col-sm-3 col-3 col-form-label"> Select Meta Template</label>' .
                                                            '<div class="col-xl-8 col-lg-8 col-sm-8 col-8">';
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
                                                            '<label class="col-xl-3 col-sm-3 col-sm-3 col-3 col-form-label"> Select Custom Template</label>' .
                                                            '<div class="col-xl-8 col-lg-8 col-sm-8 col-8">';
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
                                                $attachments_html .= '<div class="reply_attachment_details_div" id="reply_attachment_details_div_' . $key . '"  data-seq="' . $key . '">' .
                                                        '<div class="form-group row mb-3">' .
                                                        '<label class="col-xl-3 col-sm-3 col-sm-3 col-3 col-form-label"> Attachments</label>' .
                                                        '<div class="col-xl-8 col-lg-8 col-sm-8 col-8">';
                                                $attachments_html .= '<a class="text-success" target="_blank" href="' . base_url() . '' . ATTACHMENT_IMAGE_UPLOAD_PATH . '' . $detail . '">' . $detail . '</a>' .
                                                        '<input type="hidden" target="_blank" name="existing_attachments[' . $key . ']" value="' . $detail . '"/>';
                                            }
                                            $attachments_html .= '</div>' .
                                                    '<div class="col-xl-1 col-lg-1 col-sm-1 col-1 pt-2">' .
                                                    '<div class="actions  align-self-center">' .
                                                    '<a href="javascript:void(0);" class="btn btn-red btn-circle delete_message" data-id="' . $key . '">' .
                                                    '<i class="flaticon-delete"></i>' .
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
                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-3 col-sm-4 col-sm-3 col-form-label"></label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
<!--                                        <button type="button" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-meta-template"><span>Add Meta Template</span></button> -->
                                        <button type="button" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-template"><span>Add Template</span></button> 
                                        <button type="button" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-attachment ml-3"><span>Add Attachment</span></button>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/reply_messages.js?<?php echo date('YmdHis') ?>"></script>