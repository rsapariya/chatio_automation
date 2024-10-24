<?php
if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
    $desc_json = $template_datas['description'];
    $desc_json_array = (!empty($desc_json)) ? json_decode($desc_json, true) : array();

    $message = $header_message = $btn_text = $media_type = $media_text = $btn_url_text = $header_paramter_text = '';
    $media = $btn_url = $header_paramter = false;
    $buttons = array();
    if (!empty($desc_json_array)) {

        foreach ($desc_json_array as $key => $desc) {
            $desc = (array) $desc;
            $media_input_name = 'default_temp_media[' . $temp_add_seq . ']';
            if (isset($desc['type']) && $desc['type'] == 'HEADER') {
                $url = isset($desc['example']['header_handle'][0]) ? $desc['example']['header_handle'][0] : '';
                if (isset($desc['format']) && $desc['format'] == 'VIDEO') {
                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose MP4 file';
                    if (!empty($url)) {
                        $message .= '<video width="320" height="240" src="' . $url . '" controls>Your browser does not support the video tag.</video>';
                    }
                    $message .= '<h5>' . ucfirst($desc['format']) . ' URL</h5>';
                    $message .= '<input type="text" class="form-control" id="temp_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" value="' . $url . '" >';
                }
                if (isset($desc['format']) && $desc['format'] == 'LOCATION') {
                    $media = true;
                    $media_type = $desc['format'];
                    $message .= $url;
                }
                if (isset($desc['format']) && $desc['format'] == 'DOCUMENT') {

                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose PDF file';
                    if (isset($desc['example']['header_handle'][0])) {
                        if (!empty($url)) {
                            $message .= '<a class="text-primary mt-2 mb-2" target="_blank" href= "' . $url . '">Check Document</a>';
                        }
                    }
                    $message .= '<h5>' . ucfirst($desc['format']) . ' URL</h5>';
                    $message .= '<input type="text" class="form-control" id="temp_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" value="' . $url . '">';
                }
                if (isset($desc['format']) && $desc['format'] == 'IMAGE') {

                    $media = true;
                    $media_type = $desc['format'];
                    $media_text = 'Choose JPG or PNG file';
                    if (!empty($url)) {
                        $message .= '<img height="100px" src="' . $url . '"/>';
                    }
                    $message .= '<h5>' . ucfirst($desc['format']) . ' URL</h5>';
                    $message .= '<input type="text" class="form-control" id="temp_media_' . $temp_add_seq . '" name="temp_media[' . $temp_add_seq . ']" placeholder="" value="' . $url . '"  >';
                }
                if (isset($desc['format']) && $desc['format'] == 'TEXT') {
                    $desc_text = $desc['text'];
                    if (isset($desc['example']) && !empty($desc['example'])) {
                        $example = $desc['example'];
                        if (isset($example['header_text'][0]) && !empty($example['header_text'][0])) {
                            if (stripos($desc['text'], "{{1}}") !== false) {
                                $custom_text = get_field_value($example['header_text'][0]);
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
                        $body_text = $example['body_text'][0];
                        foreach ($body_text as $btk => $bt) {
                            $replaceble_text = '{{' . ($btk + 1) . '}}';
                            if (strpos($desc_text, $replaceble_text) != false) {
                                $custom_text = get_field_value($bt, ($btk + 1));
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
                        foreach ($buttons as $button) {
                            $button_url = isset($button['example'][0]) && !empty($button['example'][0]) ? $button['example'][0] : '';
                            $btn_text .= '<button disabled onclick="window.location.href = ' . $button_url . '" class="btn btn-dark mb-4 mr-2"><span>' . $button['text'] . '</span></button>&nbsp';
                            if (isset($button['type']) && $button['type'] == 'URL') {
                                if (stripos($button['url'], "{{1}}") !== false) {
                                    $btn_url_text = $button['url'];
                                    $base_url = str_replace('{{1}}', '', $button['url']);
                                    $btn_param = !empty($button_url) ? str_replace($base_url, '', $button_url) : '';

                                    $btn_text .= '<h5>Button URL Parameter</h5>';
                                    $btn_text .= '<input type="text" class="form-control" id="temp_btn_url_' . $temp_add_seq . '" name="temp_btn_url[]" placeholder="" value="' . $btn_param . '" >';
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
                        $message .= '<div class="mt-2 p-3 bg-white rounded"> <h6><b>Card ' . ($cardi + 1) . '</b></h6>';

                        foreach ($components_arr as $carri => $carr) {
                            $carri += 1;
                            $temp_add_seq = time() . $carri;
                            if (isset($carr['type']) && $carr['type'] == 'HEADER') {
                                $url = isset($carr['example']['header_handle'][0]) ? $carr['example']['header_handle'][0] : '';
                                $media_format = isset($carr['format']) && !empty($carr['format']) ? $carr['format'] : '';
                                if ($media_format == 'VIDEO') {
                                    $media = true;
                                    $media_text = 'Choose MP4 file';
                                    if (!empty($url)) {
                                        $message .= '<video width="320" height="240" src="' . $url . '" controls>Your browser does not support the video tag.</video>';
                                    }
                                    $message .= '<h6 class="mt-2 mb-1">' . ucfirst($carr['format']) . ' URL</h6>';
                                    //$message .= '<input type="hidden" class="form-control" id="temp_media_default_' . $carri . '" name="temp_media_default[]" placeholder="" value="' . $url . '" />';
                                    $message .= '<input type="text" class="form-control" id="temp_media_' . $carri . '" name="temp_media[]" placeholder="" value="' . $url . '" />';
                                }
                                if ($media_format == 'IMAGE') {
                                    $media = true;
                                    $media_text = 'Choose JPG or PNG file';
                                    if (!empty($url)) {
                                        $message .= '<img height="100px" src="' . $url . '"/>';
                                    }
                                    $message .= '<h6 class="mt-2 mb-1">' . ucfirst($carr['format']) . ' URL</h6>';
                                    //$message .= '<input type="hidden" class="form-control" id="temp_media_default_' . $carri . '" name="temp_media_default[]" placeholder="" value="' . $url . '" />';
                                    $message .= '<input type="text" class="form-control" id="temp_media_' . $carri . '" name="temp_media[]" placeholder="" value="' . $url . '"  />';
                                }
                            } elseif (isset($carr['type']) && $carr['type'] == 'BODY') {
                                $desc_text = $carr['text'];

                                if (isset($carr['example']) && !empty($carr['example'])) {
                                    $example = $carr['example'];
                                    if (isset($example['body_text'][0]) && !empty($example['body_text'][0])) {
                                        $body_text = $example['body_text'][0];
                                        foreach ($body_text as $btk => $bt) {
                                            $replaceble_text = '{{' . ($btk + 1) . '}}';
                                            if (strpos($desc_text, $replaceble_text) != false) {
                                                $custom_text = get_field_value($bt, ($btk + 1));
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
                                        $car_btn_text = '';
                                        $btni = 1;
                                        foreach ($buttons as $button) {
                                            $button_url = isset($button['example'][0]) && !empty($button['example'][0]) ? $button['example'][0] : '';
                                            $car_btn_text .= '<div><button disabled onclick="window.location.href = ' . $button_url . '" class="btn btn-dark mb-3 mr-2 mt-3"><span>' . $button['text'] . '</span></button>&nbsp';
                                            if (isset($button['type']) && $button['type'] == 'URL') {
                                                if (stripos($button['url'], "{{1}}") !== false) {
                                                    $btn_url_text = $button['url'];
                                                    $base_url = str_replace('{{1}}', '', $button['url']);
                                                    $btn_param = !empty($button_url) ? str_replace($base_url, '', $button_url) : '';

                                                    $car_btn_text .= '<h5>Button URL Parameter</h5>';
                                                    $car_btn_text .= '<input type="text" class="form-control" id="temp_btn_url_' . $btni . '" name="temp_btn_url[]" placeholder="" value="' . $btn_param . '" >';
                                                    $car_btn_text .= '<small id="passwordHelpInline" class="text-muted">' . $btn_url_text . '</small></div>';
                                                }
                                            }
                                            $btni++;
                                        }
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
    <?php
    }
}
    ?>