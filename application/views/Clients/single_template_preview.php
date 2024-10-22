<?php
if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
    $desc_json = $template_datas['description'];
    $desc_json_array = (!empty($desc_json)) ? json_decode($desc_json, true) : array();

    $message = $header_message = $btn_text = $media_type = $media_text = $btn_url_text = $header_paramter_text = '';
    $media = $btn_url = $header_paramter = false;
    $buttons = array();
    $temp_media = $temp_values = $temp_sel_values =[];
    if(isset($update_data) && !empty($update_data)){
        $temp_media = !empty($update_data['template_media']) ? json_decode($update_data['template_media'], 1) : [];
        $temp_values = !empty($update_data['template_values']) ? json_decode($update_data['template_values'], 1) : [];
        $temp_sel_values = !empty($update_data['selected_values']) ? json_decode($update_data['selected_values'], 1) : [];
    }
    $seq_id = isset($seq) && !empty($seq) ? $seq : ''; 
    $temp_media_field = !empty($seq_id) ? 'temp_media['.$seq_id.']' : 'temp_media';
    $card_media_field = !empty($seq_id) ? 'card_media['.$seq_id.'][]' : 'card_media[]';
    $temp_btn_url_field = !empty($seq_id) ? 'temp_btn_url['.$seq_id.'][]' : 'temp_btn_url[]';
    
    if (!empty($desc_json_array)) {
        foreach ($desc_json_array as $key => $desc) {
            $desc = (array) $desc;
            if (isset($desc['type']) && $desc['type'] == 'HEADER') {
                $url = isset($desc['example']['header_handle'][0]) ? $desc['example']['header_handle'][0] : '';
                if (isset($desc['format']) && $desc['format'] == 'VIDEO') {
                    $message .= '<input type="file" class="form-control-file upload_temp_media" name="file" data-id="temp_media" accept="video/3gp, video/mp4">';
                    $message .= '<small class="text-danger upload_temp_media_err"><b>max. video file size below 50 MB</b></small>';
                    $message .= '<input type="hidden" name="'.$temp_media_field.'" id="temp_media" value="'.(!empty($temp_media) ? $temp_media : '').'" />';
                    $message .= '<div class="mt-2 temp_header_preview">';
                    if(!empty($temp_media)){
                        $message .= '<video width="240" height="160" src="'.$temp_media.'" controls="">Your browser does not support the video tag.</video>';
                    }
                    $message .='</div>';
                }
                if (isset($desc['format']) && $desc['format'] == 'LOCATION') {
                    $media = true;
                    $media_type = $desc['format'];
                    $message .= $url;
                }
                if (isset($desc['format']) && $desc['format'] == 'DOCUMENT') {
                    $message .= '<input type="file" class="form-control-file upload_temp_media" name="file" data-id="temp_media" accept="application/pdf">';
                    $message .= '<small class="text-danger upload_temp_media_err"><b>max. document file size below 50 MB</b></small>';
                    $message .= '<input type="hidden" name="'.$temp_media_field.'" id="temp_media" value="'.(!empty($temp_media) ? $temp_media : '').'" />';
                    if(!empty($temp_media)){
                        $message .= '<a href="'.$temp_media.'" target="_blank">Check Document</a>';
                    }
                    $message .='</div>';
                }
                if (isset($desc['format']) && $desc['format'] == 'IMAGE') {
                    $message .= '<input type="file" class="form-control-file upload_temp_media" name="file" data-id="temp_media" accept="image/png, image/jpeg, image/jpg">';
                    $message .= '<small class="text-danger upload_temp_media_err"><b>max. image file size below 5 MB</b></small>';
                    $message .= '<input type="hidden" name="'.$temp_media_field.'" id="temp_media" value="'.(!empty($temp_media) ? $temp_media : '').'" />';
                    $message .= '<div class="mt-2 temp_header_preview">';
                    if(!empty($temp_media)){
                        $message .= '<img src="'.$temp_media.'" height="100px" />';
                    }
                    $message .='</div>';
                }
                if (isset($desc['format']) && $desc['format'] == 'TEXT') {
                    $desc_text = $desc['text'];
                    if (isset($desc['example']) && !empty($desc['example'])) {
                        $example = $desc['example'];
                        if (isset($example['header_text'][0]) && !empty($example['header_text'][0])) {
                            $header_text = !empty($temp_values) ? $temp_values[0] : $example['header_text'][0];
                            $sel_header_text = !empty($temp_sel_values) ? $temp_sel_values[0] : '';
                            if(!empty($temp_values)){
                                unset($temp_values[0]);
                            }
                            if(!empty($sel_header_text)){
                                unset($sel_header_text[0]);
                            }
                            if (stripos($desc['text'], "{{1}}") !== false) {
                                $custom_text = get_field_value($header_text,'' ,$seq_id, $sel_header_text);
                                $desc_text = str_replace("{{1}}", $custom_text, $desc_text);
                            }
                        }
                    }
                    $br_text = ($message != '') ? '<br/><br/>' : '';
                    $message .= $br_text . '<span class="text-dark">' . $desc_text . '</span>';
                }
            } elseif (isset($desc['type']) && $desc['type'] == 'BODY') {
                $desc_text = $desc['text'];

                if (isset($desc['example']) && !empty($desc['example'])) {
                    $example = $desc['example'];
                    if (isset($example['body_text'][0]) && !empty($example['body_text'][0])) {
                        $body_text = !empty($temp_values) ? $temp_values : $example['body_text'][0];
                        $sel_body_text = !empty($temp_sel_values) ? $temp_sel_values : '';
                        foreach ($body_text as $btk => $bt) {
                            $replaceble_text = '{{' . ($btk + 1) . '}}';
                            if (strpos($desc_text, $replaceble_text) != false) {
                                if(!empty($sel_body_text)){
                                    $custom_text = get_field_value($bt, ($btk + 1), $seq_id,$sel_body_text[$btk]);
                                }else{
                                    $custom_text = get_field_value($bt, ($btk + 1), $seq_id);
                                }
                                
                                $desc_text = str_replace($replaceble_text, $custom_text, $desc_text);
                            }
                        }
                    }
                }

                $br_text = ($message != '') ? '<br/><br/>' : '';
                $message .= $br_text . '<span class="text-dark">' . $desc_text . '</span>';
            } elseif (isset($desc['type']) && $desc['type'] == 'FOOTER') {
                $br_text = ($message != '') ? '<br/><br/>' : '';
                $message .= $br_text . '<span class="text-muted fs-6">' . $desc['text'] . '</span>';
            } elseif (isset($desc['type']) && $desc['type'] == 'BUTTONS') {
                if (isset($desc['buttons']) && !empty($desc['buttons'])) {
                    $buttons = (array) $desc['buttons'];
                    if (!empty($buttons)) {
                        foreach ($buttons as $buttonK => $button) {
                            $button_url = isset($button['example'][0]) && !empty($button['example'][0]) ? $button['example'][0] : '';
                            $btn_text .= '<button disabled onclick="window.location.href = ' . $button_url . '" class="btn btn-dark mb-4 mr-2"><span>' . $button['text'] . '</span></button>&nbsp';
                            if (isset($button['type']) && $button['type'] == 'URL') {
                                if (stripos($button['url'], "{{1}}") !== false) {
                                    $btn_url_text = $button['url'];
                                    $base_url = str_replace('{{1}}', '', $button['url']);
                                    $btn_param = !empty($button_url) ? str_replace($base_url, '', $button_url) : '';

                                    $btn_text .= '<h5>Button URL Parameter</h5>';
                                    $btn_text .= '<input type="text" class="form-control" id="temp_btn_url_' . $buttonK . '" name="temp_btn_url[' . $buttonK . ']" placeholder="" value="' . $btn_param . '" >';
                                    $btn_text .= '<small id="passwordHelpInline" class="text-muted">' . $btn_url_text . '</small>';
                                }
                            }
                        }
                    }
                }
            } elseif (isset($desc['type']) && $desc['type'] == 'CAROUSEL') {
                $cards_arr = isset($desc['cards']) && !empty($desc['cards']) ? $desc['cards'] : '';
                if (!empty($cards_arr)) {
                    foreach ($cards_arr as $cardi => $card) {
                        $components_arr = $card['components'];
                        $message .= '<div class="mt-2 p-3 bg-white rounded" id="card-block-' . ($cardi + 1) . '"> <h6><b>Card ' . ($cardi + 1) . '</b></h6>';

                        foreach ($components_arr as $carri => $carr) {
                            $carri += 1;
                            if (isset($carr['type']) && $carr['type'] == 'HEADER') {
                                $url = isset($carr['example']['header_handle'][0]) ? $carr['example']['header_handle'][0] : '';
                                $media_format = isset($carr['format']) && !empty($carr['format']) ? $carr['format'] : '';
                                if ($media_format == 'VIDEO') {
                                    $message .= '<input type="file" class="form-control-file card_upload_temp_media" name="file_' . ($cardi + 1) . '" data-cardid="' . ($cardi + 1) . '" accept="video/3gp, video/mp4">';
                                    $message .= '<small class="text-danger card_upload_temp_media_err_' . ($cardi + 1) . '"><b>max. video file size below 50 MB</b></small>';
                                    $message .= '<input type="hidden" id="card_media_' . ($cardi + 1) . '" name="'.$card_media_field.'" value="'.(isset($temp_media[$cardi]) && !empty($temp_media[$cardi]) ? $temp_media[$cardi] : '').'" />';
                                    $message .= '<div class="mt-2 card_media_preview_' . ($cardi + 1) . '">';
                                    if(!empty($temp_media[$cardi])){
                                        $message .= '<video width="240" height="160" src="'.$temp_media[$cardi].'" controls="">Your browser does not support the video tag.</video>';
                                    }
                                    $message .= '</div>';
                                }
                                if ($media_format == 'IMAGE') {
                                    $message .= '<input type="file" class="form-control-file card_upload_temp_media" name="file_' . ($cardi + 1) . '" data-cardid="' . ($cardi + 1) . '" accept="image/png, image/jpeg, image/jpg">';
                                    $message .= '<small class="text-danger card_upload_temp_media_err_' . ($cardi + 1) . '"><b>max. image file size below 5 MB</b></small>';
                                    $message .= '<input type="hidden" id="card_media_' . ($cardi + 1) . '" name="'.$card_media_field.'" value="'.(isset($temp_media[$cardi]) && !empty($temp_media[$cardi]) ? $temp_media[$cardi] : '').'" />';
                                    $message .= '<div class="mt-2 card_media_preview_' . ($cardi + 1) . '">';
                                    if(isset($temp_media[$cardi]) && !empty($temp_media[$cardi])){
                                        $message .= '<img src="'.$temp_media[$cardi].'" height="100px" />';
                                    }
                                    $message .= '</div>';
                                }
                            } elseif (isset($carr['type']) && $carr['type'] == 'BODY') {
                                $desc_text = $carr['text'];

                                if (isset($carr['example']) && !empty($carr['example'])) {
                                    $example = $carr['example'];
                                    if (isset($example['body_text'][0]) && !empty($example['body_text'][0])) {
                                        $body_text = !empty($temp_values) ? $temp_values : $example['body_text'][0];
                                        $sel_body_text = !empty($temp_sel_values) ? $temp_sel_values : '';
                                        foreach ($body_text as $btk => $bt) {
                                            $replaceble_text = '{{' . ($btk + 1) . '}}';
                                            if (strpos($desc_text, $replaceble_text) != false) {
                                                if(!empty($sel_body_text)){
                                                    $custom_text = get_field_value($bt, ($btk + 1), $seq_id,$sel_body_text[$btk]);
                                                }else{
                                                    $custom_text = get_field_value($bt, ($btk + 1), $seq_id);
                                                }
                                                $desc_text = str_replace($replaceble_text, $custom_text, $desc_text);
                                            }
                                        }
                                    }
                                }

                                $br_text = ($message != '') ? '<br/>' : '';
                                $message .= $br_text . '<div class="text-dark">' . $desc_text . '</div>';
                            } elseif (isset($carr['type']) && $carr['type'] == 'BUTTONS') {
                                //pr($carr);
                                if (isset($carr['buttons']) && !empty($carr['buttons'])) {
                                    $buttons = (array) $carr['buttons'];

                                    if (!empty($buttons)) {
                                        $car_btn_text = '<div class="mt-2">';
                                        $btni = 1;
                                        foreach ($buttons as $button) {
                                            $button_url = isset($button['example'][0]) && !empty($button['example'][0]) ? $button['example'][0] : '';
                                            $car_btn_text .= '<button disabled onclick="window.location.href = ' . $button_url . '" class="btn btn-dark mb-3 mr-2 mt-3"><span>' . $button['text'] . '</span></button>&nbsp';
                                            if (isset($button['type']) && $button['type'] == 'URL') {
                                                if (stripos($button['url'], "{{1}}") !== false) {
                                                    $btn_url_text = $button['url'];
                                                    $base_url = str_replace('{{1}}', '', $button['url']);
                                                    $btn_param = !empty($button_url) ? str_replace($base_url, '', $button_url) : '';

                                                    $car_btn_text .= '<h5>Button URL Parameter</h5>';
                                                    $car_btn_text .= '<input type="text" class="form-control" id="temp_btn_url_' . $btni . '" name="'.$temp_btn_url_field.'" placeholder="" value="' . $btn_param . '" >';
                                                    $car_btn_text .= '<small id="passwordHelpInline" class="text-muted">' . $btn_url_text . '</small>';
                                                }
                                            }
                                            $btni++;
                                        }
                                        $car_btn_text .= '</div>';
                                        $message .= $car_btn_text;
                                    }
                                }
                            }
                        }
                        $message .= '</div>';
                    }
                }
            }
        }
        $message = nl2br($message);
        ?>
        <div class="temp_details">
            <div id="temp_description" readonly>
                <?php echo $message ?>
            </div>
            <div id="temp_buttons" readonly>
                <?php echo $btn_text; ?>
            </div>
        </div>
        <?php
    }
}
?>