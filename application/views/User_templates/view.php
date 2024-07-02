<?php
if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
    $desc_json = $template_datas['description'];
    $desc_json_array = (!empty($desc_json)) ? json_decode($desc_json, true) : array();
    $template_values = (!empty($automation_media['template_value'])) ? $automation_media['template_value'] : array();
    $template_media = (!empty($automation_media['template_media'])) ? $automation_media['template_media'] : '';
    $template_media_name = (!empty($automation_media['template_media_name'])) ? $automation_media['template_media_name'] : '';
    $template_button_url = (!empty($automation_media['template_button_url'])) ? $automation_media['template_button_url'] : '';

    $message = $header_message = $btn_text = $media_type = $media_text = $btn_url_text = $header_paramter_text = '';
    $media = $btn_url = $header_paramter = false;
    $buttons = array();
//    pr($desc_json_array);
    if (!empty($desc_json_array)) {
        foreach ($desc_json_array as $key => $desc) {
            $desc = (array) $desc;
            if (isset($desc['type']) && $desc['type'] == 'HEADER') {
                if (isset($desc['format']) && $desc['format'] == 'VIDEO') {
                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose MP4 file';
                    $video_url = '';
                    $desc['example'] = (array) $desc['example'];
                    if (isset($desc['example']['header_handle'][0])) {
                        $video_url = $desc['example']['header_handle'][0];
                    }
                    if (isset($update_option) && $update_option) {
                        
                    } else {
                        $message .= '<video width="320" height="240" src="' . $video_url . '" controls>Your browser does not support the video tag.</video>';
                    }
                }
                if (isset($desc['format']) && $desc['format'] == 'LOCATION') {
                    $media = true;
                    $media_type = $desc['format'];
                    $location_url = '';
                    $desc['example'] = (array) $desc['example'];
                    if (isset($desc['example']['header_handle'][0])) {
                        $location_url = $desc['example']['header_handle'][0];
                    }
                    if (isset($update_option) && $update_option) {
                        
                    } else {
                        $message .= $location_url;
                    }
                }
                if (isset($desc['format']) && $desc['format'] == 'DOCUMENT') {
                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose PDF file';
                    $document_url = '';
                    $desc['example'] = (array) $desc['example'];
                    if (isset($desc['example']['header_handle'][0])) {
                        $document_url = $desc['example']['header_handle'][0];
                    }
                    if (!empty($document_url)) {
                        if (isset($update_option) && $update_option) {
                            
                        } else {
                            $message .= '<a class="text-primary" href= "' . $document_url . '">Check Document</a>';
                        }
                    }
                }
                if (isset($desc['format']) && $desc['format'] == 'IMAGE') {
                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose JPG or PNG file';
                    $image_url = DEFAULT_ADMIN_ASSET_PATH . '/img/default-img.png';
                    $desc['example'] = (array) $desc['example'];
                    if (isset($desc['example']['header_handle'][0])) {
                        $image_url = $desc['example']['header_handle'][0];
                    }
                    if (isset($update_option) && $update_option) {
                        
                    } else {
                        $message .= '<img height="100px" src="' . $image_url . '"/>';
                    }
                }
                if (isset($desc['format']) && $desc['format'] == 'TEXT') {
                    $message .= '<span class="text-dark font-weight-bold">' . $desc['text'] . '</span>';
                    if (stripos($desc['text'], "{{1}}") !== false) {
                        $header_paramter = true;
                        $header_message .= $message;
                        $message = '';
                    }
                }
            } elseif (isset($desc['type']) && $desc['type'] == 'BODY') {
                $br_text = ($message != '') ? '<br/><br/>' : '';
                $message .= $br_text . '<span class="text-dark">' . $desc['text'] . '</span>';
            } elseif (isset($desc['type']) && $desc['type'] == 'FOOTER') {
                $br_text = ($message != '') ? '<br/><br/>' : '';
                $message .= $br_text . '<span class="text-muted fs-6">' . $desc['text'] . '</span>';
            } elseif (isset($desc['type']) && $desc['type'] == 'BUTTONS') {
                if (isset($desc['buttons']) && !empty($desc['buttons'])) {
                    $buttons = (array) $desc['buttons'];
                    if (!empty($buttons)) {
                        foreach ($buttons as $button) {
                            $button = (array) $button;
                            if (isset($button['type']) && $button['type'] == 'URL') {
                                if (stripos($button['url'], "{{1}}") !== false) {
                                    $btn_url = true;
                                    $btn_url_text = $button['url'];
                                }
                            }
                            $button_url = '';
                            if (isset($button['example'][0])) {
                                $button_url = $button['example'][0];
                            }
                            $btn_text .= '<button disabled onclick="window.location.href = ' . $button_url . '" class="btn btn-dark mb-4 mr-2"><span>' . $button['text'] . '</span></button>&nbsp';
                        }
                    }
                }
            }
        }
        if (isset($update_option) && $update_option) {
            $param_text_message = '';
            if (!empty($template_values)) {
                $header_text_paramter = '';
                if ($header_paramter) {
                    $header_text_paramter = $template_values[0];
                    unset($template_values[0]);
                    $template_values = array_values($template_values);
                    $template_values['header'] = $header_text_paramter;
                }

                if (!empty($header_message)) {
                    preg_match_all("/{{[0-9]}}/", $header_message, $param_header_matches);
                    if (isset($param_header_matches[0]) && !empty($param_header_matches[0])) {
                        foreach ($param_header_matches[0] as $phkey => $param_header_match) {
                            $header_custom_text = get_custom_message_text($temp_add_seq, $template_values['header']);
                            $header_message = str_replace($param_header_match, $header_custom_text, $header_message);
                        }
                    }
                }
                preg_match_all("/{{[0-9]}}/", $message, $param_matches);
                if (isset($param_matches[0]) && !empty($param_matches[0])) {
                    foreach ($param_matches[0] as $pkey => $param_match) {
                        $custom_text = get_custom_message_text($temp_add_seq, $template_values[$pkey]);
                        $message = str_replace($param_match, $custom_text, $message);
                    }
                }
            } else {
                $custom_text = get_custom_message_text($temp_add_seq);
                if ($header_paramter && !empty($header_message)) {
                    $header_message = preg_replace("/{{[0-9]}}/", $custom_text, $header_message);
                }
                $message = preg_replace("/{{[0-9]}}/", $custom_text, $message);
            }
        }
        if ($header_paramter) {
            $message = $header_message . '<br/><br/>' . $message;
        }
        $message = nl2br($message);
        ?>
        <div class="temp_details">
            <div id="temp_description" readonly>
                <?php echo $message ?>
            </div>
            <div  id="temp_buttons" readonly>
                <?php echo $btn_text; ?>
            </div>
            <?php
            if (isset($view_media) && $view_media) {
                if (isset($media) && $media) {
                    ?>
                    <div class="statbox widget box mt-3">
                        <div class="widget-content widget-content-area-">
                            <!--<h4>Uploaded <?php //echo ucfirst($media_type) ?></h4>-->
                            <h5>Added <?php echo ucfirst(strtolower($media_type)) ?> Link</h5>
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <div class="uploaded_media_section">
                                        <?php
                                        $uploaded_media_html = '';
//                                        $media_input_name = "default_value[' . $temp_add_seq . '][]";
                                        $media_input_name = 'default_temp_media[' . $temp_add_seq . ']';

                                        if ($media_type == 'IMAGE') {
//                                            $uploaded_media_html = '<img id="template_uploaded_preview_holder_' . $temp_add_seq . '" width="250px" height="250px" src="' . base_url() . '' . DEFAULT_IMAGE_UPLOAD_PATH . '' . $template_media . '"/>';
                                            $uploaded_media_html = '<div class="header_media_input"><input type="text" class="form-control default_value col-12" placeholder="Default Value" name="' . $media_input_name . '" id="default_value_' . $temp_add_seq . '" value="' . $template_media . '" readonly/></div>';
                                        }
                                        if ($media_type == 'VIDEO') {
//                                            $uploaded_media_html = '<video src="' . base_url() . '' . DEFAULT_IMAGE_UPLOAD_PATH . '' . $template_media . '" id="template_uploaded_preview_holder_' . $temp_add_seq . '" width="320" height="240" controls>Your browser does not support the video tag.</video>';
                                            $uploaded_media_html = '<div class="header_media_input"><input type="text" class="form-control default_value col-12" placeholder="Default Value" name="' . $media_input_name . '" id="default_value_' . $temp_add_seq . '" value="' . $template_media . '" readonly/></div>';
                                        }
                                        if ($media_type == 'DOCUMENT') {
                                            $document_name = '';
//                                            $uploaded_media_html = '<a class="text-primary" href="' . base_url() . '' . DEFAULT_IMAGE_UPLOAD_PATH . '' . $template_media . '" target="_blank" id="template_uploaded_preview_holder_' . $temp_add_seq . '">' . $template_media_name . '</a>';
                                            $uploaded_media_html = '<div class="header_media_input"><input type="text" class="form-control default_value col-12" placeholder="Default Value" name="' . $media_input_name . '" id="default_value_' . $temp_add_seq . '" value="' . $template_media . '" readonly/></div>';
                                        }
                                        echo $uploaded_media_html;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if (isset($btn_url) && $btn_url) {
                    ?>
                    <div class="statbox widget box mt-3">
                        <div class="widget-content widget-content-area">
                            <h5>Added Button URL Parameter</h5>
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <div class="uploaded_button_section">
                                        <?php
                                        $btn_url_text_value = preg_replace("/{{[0-9]}}/", $template_button_url, $btn_url_text);
                                        $button_url_html = '';
                                        $button_url_html .= '<div class="header_media_input"><input type="text" class="form-control default_value col-12" id="default_temp_btn_url_' . $temp_add_seq . '" name="default_temp_btn_url[' . $temp_add_seq . ']" value="' . $template_button_url . '" readonly>';
                                        $button_url_html .= '<small id="passwordHelpInline" class="text-muted">' . $btn_url_text_value . '</small>';

                                        echo $button_url_html;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            if (isset($update_option) && $update_option) {
                if (isset($media) && $media) {
                    $required = 'required';
                    if (isset($for) && $for == 'edit') {
                        $required = '';
                    }
                    ?>
                    <div class="statbox widget box mt-3">
                        <div class="widget-content widget-content-area-">
                            <!--<h4>Upload <?php // echo ucfirst($media_type)                                                                                                                             ?></h4>-->
                            <h5>Add <?php echo ucfirst(strtolower($media_type)) ?> URL</h5>
                            <div class="row">
                                <!--                                <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                                                    <p class="card-title pl-2 mb-0 mt-1"><?php echo $media_text ?></p>
                                                                </div>-->
                                <!--<div class="col-xl-8 col-md-8 col-sm-8 col-8">-->
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <div class="upload_media_section">
                                        <?php
                                        $media_html = '';
                                        if ($media_type == 'IMAGE') {
//                                            $media_html = '<input type="file" class="form-control-file" id="temp_image_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" accept=".jpg,.jpeg, .png">' .
//                                                    '<small id="passwordHelpInline" class="text-muted">' .
//                                                    'Allowed file type: JPG, PNG.' .
//                                                    '</small>';
                                            $media_html = '<input type="text" class="form-control" id="temp_image_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" ' . $required . '>';
                                        }
                                        if ($media_type == 'VIDEO') {
//                                            $media_html = '<input type="file" class="form-control-file" id="temp_video_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" accept=".mp4">' .
//                                                    '<small id="passwordHelpInline" class="text-muted">' .
//                                                    'Allowed file type: MP4.' .
//                                                    '</small>';
                                            $media_html = '<input type="text" class="form-control" id="temp_video_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" ' . $required . '>';
                                        }
                                        if ($media_type == 'DOCUMENT') {
//                                            $media_html = '<input type="file" class="form-control-file" id="temp_document_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" accept=".pdf">' .
//                                                    '<small id="passwordHelpInline" class="text-muted">' .
//                                                    'Allowed file type: PDF.' .
//                                                    '</small>';
                                            $media_html = '<input type="text" class="form-control" id="temp_document_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" ' . $required . '>';
                                        }
                                        echo $media_html;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row media_preview_div_<?php echo $temp_add_seq ?> hide">
                                <div class="col-12">
                                    <?php
                                    $media_preview_html = '';
                                    if ($media_type == 'IMAGE') {
                                        $media_preview_html = '<img id="template_preview_holder_' . $temp_add_seq . '" width="250px" height="250px" />';
                                    }
                                    if ($media_type == 'VIDEO') {
                                        $media_preview_html = '<video id="template_preview_holder_' . $temp_add_seq . '" width="320" height="240" controls>Your browser does not support the video tag.</video>';
                                    }
                                    echo $media_preview_html;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if (isset($btn_url) && $btn_url) {
                    $required = 'required';
                    if (isset($for) && $for == 'edit') {
                        $required = '';
                    }
                    ?>
                    <div class="statbox widget box mt-3">
                        <div class="widget-content widget-content-area">
                            <h5>Add Button URL Parameter</h5>
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <div class="upload_button_section">
                                        <?php
                                        $button_url_html = '';
                                        $button_url_html .= '<input type="text" class="form-control" id="temp_btn_url_' . $temp_add_seq . '" name="temp_btn_url[' . $temp_add_seq . ']" placeholder="" ' . $required . '>';
                                        $button_url_html .= '<small id="passwordHelpInline" class="text-muted">' . $btn_url_text . '</small>';

                                        echo $button_url_html;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
}
?>