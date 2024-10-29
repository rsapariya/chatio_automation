<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function get_all_query() {
    $CI = &get_instance();
    $queries = $CI->db->queries;
    foreach ($queries as $query) {
        echo $query . PHP_EOL;
    }
}

function get_admin_data() {
    $ci = &get_instance();
    $ci->load->model('Admin_model');
    $controller = $ci->router->fetch_class();
    $action = $ci->router->fetch_method();
    $allowed_action = array('login');
    $nonallowed_admin_action = array('clients');
    $nonallowed_user_action = array('users');
    $nonallowed_member_action = array('users, clients');
    $path = $controller;
    if (!in_array($path, $allowed_action)) {
        if ($ci->session->userdata('id') != '') {
            if ($ci->session->userdata('type') == 'admin') {
                if (!in_array($path, $nonallowed_admin_action)) {
                    $user_id = $ci->session->userdata('id');
                    $data['user_data'] = $ci->Admin_model->get_user_data($user_id);
                    return $data;
                } else {
                    $ci->session->set_flashdata('error_msg', 'You have not access for this page');
                    redirect('dashboard');
                }
            } else if ($ci->session->userdata('type') == 'user') {
                if (!in_array($path, $nonallowed_user_action)) {
                    $user_id = $ci->session->userdata('id');
                    $data['user_data'] = $ci->Admin_model->get_user_data($user_id);
                    return $data;
                } elseif ($controller == 'users' && ($action == 'settings' || $action == 'settings_save' || $action == 'download_indiamart_data')) {
                    $user_id = $ci->session->userdata('id');
                    $data['user_data'] = $ci->Admin_model->get_user_data($user_id);
                    return $data;
                } else {
                    $ci->session->set_flashdata('error_msg', 'You have not access for this page');
                    redirect('dashboard');
                }
            } else if ($ci->session->userdata('type') == 'member') {
                if (!in_array($path, $nonallowed_member_action) && ($action != 'settings')) {
                    $user_id = $ci->session->userdata('id');
                    $data['user_data'] = $ci->Admin_model->get_user_data($user_id);
                    return $data;
                } else {
                    $ci->session->set_flashdata('error_msg', 'You have not access for this page');
                    redirect('dashboard');
                }
            } else {
                $data['user_data'] = '';
                $ci->session->set_flashdata('error_msg', 'You have not access for this page');
                //                redirect('dashboard');
            }
        } else {
            redirect('login');
        }
    }
}

function get_admin_user_data() {
    $ci = &get_instance();
    $ci->load->model('Admin_model');
    $controller = $ci->router->fetch_class();
    $action = $ci->router->fetch_method();
    $allowed_action = array('login');
    $path = $controller . '|' . $action;
    if (!in_array($path, $allowed_action)) {
        if ($ci->session->userdata('id') != '') {
            if ($ci->session->userdata('type') == 'user') {
                $user_id = $ci->session->userdata('id');
                $data['user_data'] = $ci->Admin_model->get_user_data($user_id);
                return $data;
            } else {
                $data['user_data'] = '';
                $ci->session->set_flashdata('error_msg', 'You have not access for this page');
                //                redirect('dashboard');
            }
        } else {
            redirect('login');
        }
    }
}

/**
 * This function is used print array in proper format.
 * develop by : HPA
 */
function pr($data, $is_die = null) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if (!empty($is_die)) {
        die();
    }
}

/**
 * This function is used to set details for send mail.
 * develop by : HPA
 */
function mail_config() {
    $CI = &get_instance();
    $CI->load->model('Users_model');
    $smtp_details = $CI->Users_model->get_smtp_details();
    $keys = array_column($smtp_details, 'key');
    $values = array_column($smtp_details, 'value');
    $combined = array_combine($keys, $values);
    if (isset($combined) && !empty($combined)) {
        $configs = array(
            'protocol' => 'smtp',
            'smtp_host' => $combined['smtp_host'],
            'smtp_port' => $combined['smtp_port'],
            'smtp_crypto' => 'tls',
            'smtp_user' => $combined['smtp_email'],
            'smtp_pass' => $combined['smtp_password'],
            'transport' => 'Smtp',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'headerCharset' => 'iso-8859-1',
            'mailtype' => 'html',
            'validate' => TRUE,
        );
    }
    return $configs;
}

function get_day_name($timestamp, $format = null) {
    $date = change_date('d/m/Y', strtotime($timestamp));
    $yesturday = change_date('d/m/Y', strtotime('-1 days'));
    $today = change_date('d/m/Y', false);
    if ($date == $today) {
        $date = 'Today , ' . change_date('h:i A', strtotime($timestamp));
    } else if ($date == $yesturday) {
        $date = 'Yesturday , ' . change_date('h:i A', strtotime($timestamp));
    } else {
        if ($format != null) {
            $date = change_date('d F h:i A', strtotime($timestamp));
        } else {
            $date = change_date('D , d M h:i A', strtotime($timestamp));
        }
    }
    return $date;
}

function change_date($format = "r", $timestamp = false, $timezone = false) {
    $userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'UTC');
    $gmtTimezone = new DateTimeZone('UTC');
    $myDateTime = new DateTime(($timestamp != false ? date("r", (int) $timestamp) : date("r")), $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    return date($format, ($timestamp != false ? (int) $timestamp : $myDateTime->format('U')) + $offset);
}

function generate_api_key($length = 30) {
    $pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}

function extract_url($text) {
    $pattern = '/\bhttps?:\/\/\S+/i';  // Regular expression pattern to match URLs
    preg_match($pattern, $text, $matches);  // Search for the URL in the text

    if (isset($matches[0])) {
        return $matches[0];  // Return the first URL found
    } else {
        return null;  // Return null if no URL found
    }
}

function getBaseUrl($url) {
    $parsedUrl = parse_url($url);

    // Check if the URL was successfully parsed
    if ($parsedUrl === false || !isset($parsedUrl['host'])) {
        return null;
    }

    // Return the host (base URL)
    return $parsedUrl['host'];
}

function getPageTitle($url) {
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Execute cURL session
    $html = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Check if cURL request was successful
    if ($html === false) {
        return null;
    }

    // Extract page title from HTML
    if (preg_match('/<title>(.*?)<\/title>/', $html, $matches)) {
        $title = $matches[1];
        // Clean up the title (remove extra spaces and newlines)
        $title = trim(preg_replace('/\s+/', ' ', $title));
        return $title;
    } else {
        return null;
    }
}

function get_template_details($automation_media, $template_id, $seq = 0, $for = 'edit', $function = '') {
    $response = '';
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    if ($ci->data['user_data']['type'] == 'user') {
        $user_id = $ci->data['user_data']['id'];
        $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
        $check_existing_template = $ci->CMS_model->get_result(tbl_templates, $where, null, 1);
        if (isset($check_existing_template) && !empty($check_existing_template)) {
            $data['template_datas'] = $check_existing_template;
            $data['automation_media'] = $automation_media;
            if ($seq > 0) {
                $data['update_option'] = true;
                $data['temp_add_seq'] = $seq;
            }
            if (isset($for) && !empty($for)) {
                //for = edit or view
                $data['view_media'] = true;
                $data['for'] = $for;
                if ($for == 'view') {
                    $data['update_option'] = false;
                }
            }
            if (isset($function) && !empty($function)) {
                $data['function'] = $function;
            }
            $response = $ci->load->view('User_templates/view', $data, TRUE);
            $template_response['name'] = $check_existing_template['name'];
            $template_response['response'] = $response;
        }
        return $template_response;
    }
}

function get_custom_message_text($temp_add_seq, $param_match = '') {
    $custom_text = '';
    $option_array = array(
        'name' => 'Full Name',
        'birth_date' => 'Birth Date',
        'anniversary_date' => 'Anniversary Date',
        'column1' => 'Column 1',
        'column2' => 'Column 2',
        'column3' => 'Column 3',
        'column4' => 'Column 4',
        'column5' => 'Column 5',
        'column6' => 'Column 6',
        'column7' => 'Column 7',
        'column8' => 'Column 8',
        'column9' => 'Column 9',
        'column10' => 'Column 10',
    );
    $value = '';
    /* if ($param_match != 'name_field') {
      $value = $param_match;
      } */
    $field = str_replace('_field', '', $param_match);
    if (!array_key_exists($field, $option_array)) {
        $value = $param_match;
    }

    $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_value[' . $temp_add_seq . '][]" id="default_select_value_' . $temp_add_seq . '">';
    if (isset($option_array) && !empty($option_array)) {
        $custom_text .= '<option disabled="disabled"  selected="selected"> Select Field</option>';
        foreach ($option_array as $key => $val) {
            $selected = $field == $key ? ' selected="selected" ' : "";
            $custom_text .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
        }
    }
    $custom_text .= '</select></span>';
    $custom_text .= '<strong>OR</strong>';
    $custom_text .= '<span><input type="text" class="form-control-sm default_value col-3" placeholder="Default Value" name="default_value[' . $temp_add_seq . '][]" id="default_value_' . $temp_add_seq . '" value="' . $value . '"/></span>';

    return $custom_text;
}

function get_field_value($def_value = '', $field_id = '', $seq = '', $selected = '') {
    $custom_text = '';
    $option_array = array(
        'name' => 'Full Name',
        'birth_date' => 'Birth Date',
        'anniversary_date' => 'Anniversary Date',
        'column1' => 'Column 1',
        'column2' => 'Column 2',
        'column3' => 'Column 3',
        'column4' => 'Column 4',
        'column5' => 'Column 5',
        'column6' => 'Column 6',
        'column7' => 'Column 7',
        'column8' => 'Column 8',
        'column9' => 'Column 9',
        'column10' => 'Column 10'
    );
    $value = '';

    if (!empty($def_value)) {
        $value = $def_value;
    }

    if (!empty($field_id)) {
        if (!empty($seq)) {
            $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_value[' . $seq . '][]" id="default_select_value_' . $field_id . '">';
        } else {
            $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_value[]" id="default_select_value_' . $field_id . '">';
        }
    } else {
        if (!empty($seq)) {
            $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_header_value[' . $seq . ']" id="default_select_header_value">';
        } else {
            $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_header_value" id="default_select_header_value">';
        }
    }
    if (isset($option_array) && !empty($option_array)) {
        $sel_none = empty($selected) ? 'selected="selected"' : '';

        $custom_text .= '<option value="" ' . $sel_none . '> None</option>';
        foreach ($option_array as $key => $val) {
            $sel = !empty($selected) && $selected == $key ? 'selected="selected"' : '';

            $custom_text .= '<option value="' . $key . '" ' . $sel . '>' . $val . '</option>';
        }
    }
    $custom_text .= '</select></span>';
    $custom_text .= '<strong>OR</strong>';
    if (!empty($field_id)) {
        if (!empty($seq)) {
            $custom_text .= '<span><input type="text" class="form-control-sm default_value col-3" placeholder="Default Value" name="default_value[' . $seq . '][]" id="default_value_' . $field_id . '" value="' . $value . '"/></span>';
        } else {
            $custom_text .= '<span><input type="text" class="form-control-sm default_value col-3" placeholder="Default Value" name="default_value[]" id="default_value_' . $field_id . '" value="' . $value . '"/></span>';
        }
    } else {
        if (!empty($seq)) {
            $custom_text .= '<span><input type="text" class="form-control-sm header_value col-3" placeholder="Default Value" name="header_value" id="header_value" value="' . $value . '"/></span>';
        } else {
            $custom_text .= '<span><input type="text" class="form-control-sm header_value col-3" placeholder="Default Value" name="header_value[' . $seq . ']" id="header_value" value="' . $value . '"/></span>';
        }
    }
    return $custom_text;
}

//function create_template_message($automation_id, $temp_seq, $template_id, $name = '', $for = '', $user_id = 0) {
//    $ci = & get_instance();
//    $ci->load->model('CMS_model');
//    if ($user_id == 0 && $ci->data['user_data']['type'] == 'user') {
//        $user_id = $ci->data['user_data']['id'];
//    }
//
//    if ($user_id > 0) {
//        $where_a = 'id = ' . $ci->db->escape($automation_id);
//        if (!empty($for)) {
//            $check_automation = $ci->CMS_model->get_result(tbl_recurrings, $where_a, null, 1);
//        } else {
//            $check_automation = $ci->CMS_model->get_result(tbl_automations, $where_a, null, 1);
//        }
//        if (isset($check_automation) && !empty($check_automation)) {
//
//            $template_values = (!empty($check_automation['template_values'])) ? (array) json_decode($check_automation['template_values'], true) : array();
//            $template_media = (!empty($check_automation['template_media'])) ? (array) json_decode($check_automation['template_media'], true) : array();
//            $template_button_url = (!empty($check_automation['template_button_url'])) ? (array) json_decode($check_automation['template_button_url'], true) : array();
//            $details = array();
//            $template_media_names = array();
//            if (empty($for)) {
//                $details = (!empty($check_automation['details'])) ? (array) json_decode($check_automation['details'], true) : array();
//                $template_media_names = (!empty($check_automation['template_media_names'])) ? (array) json_decode($check_automation['template_media_names'], true) : array();
//            }
//
//            if (!empty($temp_seq)) {
//                if (empty($for)) {
//                    $current_detail = (isset($details[$temp_seq]) && !empty($details[$temp_seq])) ? $details[$temp_seq] : 0;
//                } else {
//                    $current_detail = (isset($check_automation['template_id']) && !empty($check_automation['template_id'])) ? $check_automation['template_id'] : 0;
//                }
//                if ($current_detail == $template_id) {
//                    $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
//                    $check_existing_template = $ci->CMS_model->get_result(tbl_templates, $where, null, 1);
//                    if (isset($check_existing_template) && !empty($check_existing_template)) {
//
//                        $current_values = (isset($template_values[$temp_seq]) && !empty($template_values[$temp_seq])) ? $template_values[$temp_seq] : array();
//                        $current_media = (isset($template_media[$temp_seq]) && !empty($template_media[$temp_seq])) ? $template_media[$temp_seq] : '';
//                        $current_media_name = (isset($template_media_names[$temp_seq]) && !empty($template_media_names[$temp_seq])) ? $template_media_names[$temp_seq] : '';
//                        $current_btn_url = (isset($template_button_url[$temp_seq]) && !empty($template_button_url[$temp_seq])) ? $template_button_url[$temp_seq] : '';
//
//                        $temp_desc = (!empty($check_existing_template['description'])) ? (array) json_decode($check_existing_template['description'], true) : array();
//
//                        $components = array();
//                        if (isset($temp_desc) && !empty($temp_desc)) {
//                            foreach ($temp_desc as $key => $desc) {
//                                $desc = (array) $desc;
//                                if (isset($desc['type']) && $desc['type'] == 'HEADER') {
////                                    $media_url = base_url() . DEFAULT_IMAGE_UPLOAD_PATH . '' . $current_media;
//                                    $header_text = isset($desc['text']) ? $desc['text'] : '';
//                                    $add_param = true;
//                                    if (isset($desc['format']) && !empty($desc['format']) && $desc['format'] == 'TEXT') {
//                                        $add_param = false;
//                                        if (stripos($desc['text'], "{{1}}") !== false) {
//                                            $add_param = true;
//                                            $header_text_paramter = $current_values[0];
//                                            unset($current_values[0]);
//                                            $current_values = array_values($current_values);
//                                            $current_values['header'] = $header_text_paramter;
//
//                                            preg_match_all("/{{[0-9]}}/", $header_text, $param_header_matches);
//                                            $header_parameter_values = array();
//                                            if (isset($param_header_matches[0]) && !empty($param_header_matches[0])) {
//                                                foreach ($param_header_matches[0] as $phkey => $param_header_match) {
//                                                    if ($current_values['header'] == 'name_field') {
//                                                        $header_text = $name;
//                                                    } else {
//                                                        $header_text = $current_values['header'];
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                    if ($add_param) {
//                                        $media_url = $current_media;
//                                        $components[$desc['type']]['type'] = strtolower($desc['type']);
//                                        $components[$desc['type']]['parameters'][] = array(
//                                            'type' => strtolower($desc['format']),
//                                            strtolower($desc['format']) => (!empty($media_url)) ? array("link" => $media_url) : $header_text
//                                        );
//                                    }
//                                } elseif (isset($desc['type']) && $desc['type'] == 'BODY') {
//                                    $body_text = $desc['text'];
//                                    $components[$desc['type']] = array(
//                                        'type' => strtolower($desc['type']),
//                                        'text' => $body_text
//                                    );
//                                    preg_match_all("/{{[0-9]}}/", $body_text, $param_matches);
//                                    $parameter_values = array();
//                                    if (isset($param_matches[0]) && !empty($param_matches[0])) {
//                                        foreach ($param_matches[0] as $Mkey => $param_match) {
//                                            if ($current_values[$Mkey] == 'name_field') {
//                                                $text_value = $name;
//                                            } else {
//                                                $text_value = $current_values[$Mkey];
//                                            }
//                                            $components[$desc['type']]['parameters'][] = array(
//                                                "type" => 'text',
//                                                'text' => $text_value
//                                            );
//                                        }
//                                    }
//                                } elseif (isset($desc['type']) && $desc['type'] == 'FOOTER') {
//                                    $footer_text = $desc['text'];
//                                    $components[$desc['type']] = array(
//                                        'type' => strtolower($desc['type']),
//                                        'text' => $footer_text
//                                    );
//                                } elseif (isset($desc['type']) && $desc['type'] == 'BUTTONS') {
//                                    if (isset($desc['buttons']) && !empty($desc['buttons'])) {
//                                        $buttons = (array) $desc['buttons'];
//                                        if (!empty($buttons)) {
////                                            $button_params = array();
//                                            foreach ($buttons as $bkey => $button) {
//                                                $sub_type = '';
//                                                if (isset($button['type']) && !empty($button['type']) && $button['type'] == 'URL') {
//                                                    if (stripos($button['url'], "{{1}}") !== false) {
//                                                        unset($button['example']);
//                                                        unset($button['url']);
//                                                        $button['type'] = 'text';
//                                                        $button['text'] = $current_btn_url;
//                                                        $buttons[$bkey] = $button;
//                                                        $sub_type = 'url';
//                                                    }
//                                                }
////                                                $button_params[] = (array) $button;
//                                                $button_parameter = array(
//                                                    "type" => 'button',
//                                                    "index" => ($bkey),
//                                                    "parameters" => array(
//                                                        (array) $button
//                                                    )
//                                                );
//                                                if (!empty($sub_type)) {
//                                                    $button_parameter['sub_type'] = $sub_type;
//                                                    $components[$desc['type']][] = $button_parameter;
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                        return $components;
//                    }
//                }
//            }
//        }
//    }
//}

function create_template_message($automation_id, $temp_seq, $template_id, $name = '', $for = '', $user_id = 0) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    if ($user_id == 0 && $ci->data['user_data']['type'] == 'user') {
        $user_id = $ci->data['user_data']['id'];
    }

    if ($user_id > 0) {
        $where_a = 'id = ' . $ci->db->escape($automation_id);
        if (!empty($for)) {
            if ($for == 'reply_message') {
                $check_automation = $ci->CMS_model->get_result(tbl_reply_messages, $where_a, null, 1);
            }
            if ($for == 'recurring') {
                $check_automation = $ci->CMS_model->get_result(tbl_recurrings, $where_a, null, 1);
            }
            if ($for == 'default_template') {
                $check_automation = $ci->CMS_model->get_result(tbl_default_templates, $where_a, null, 1);
            }
        } else {
            $check_automation = $ci->CMS_model->get_result(tbl_automations, $where_a, null, 1);
        }
        if (isset($check_automation) && !empty($check_automation)) {
            $template_values = (!empty($check_automation['template_values'])) ? (array) json_decode($check_automation['template_values'], true) : array();
            $template_media = (!empty($check_automation['template_media'])) ? (array) json_decode($check_automation['template_media'], true) : array();
            $template_button_url = (!empty($check_automation['template_button_url'])) ? (array) json_decode($check_automation['template_button_url'], true) : array();
            $details = array();
            $template_media_names = array();
            if (empty($for)) {
                $details = (!empty($check_automation['details'])) ? (array) json_decode($check_automation['details'], true) : array();
                $template_media_names = (!empty($check_automation['template_media_names'])) ? (array) json_decode($check_automation['template_media_names'], true) : array();
            } elseif (!empty($for) && $for == 'reply_message') {
                $details = (!empty($check_automation['attachments'])) ? (array) json_decode($check_automation['attachments'], true) : array();
            }

            if (!empty($temp_seq)) {
                if (empty($for)) {
                    $current_detail = (isset($details[$temp_seq]) && !empty($details[$temp_seq])) ? $details[$temp_seq] : 0;
                } else {
                    if ($for == 'reply_message') {
                        $current_detail = (isset($details[$temp_seq]) && !empty($details[$temp_seq])) ? $details[$temp_seq] : 0;
                    }
                    if ($for == 'recurring') {
                        $current_detail = (isset($check_automation['template_id']) && !empty($check_automation['template_id'])) ? $check_automation['template_id'] : 0;
                    }
                    if ($for == 'default_template') {
                        $current_detail = (isset($check_automation['template_id']) && !empty($check_automation['template_id'])) ? $check_automation['template_id'] : 0;
                    }
                }
                if ($current_detail == $template_id) {
                    $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
                    $check_existing_template = $ci->CMS_model->get_result(tbl_templates, $where, null, 1);

                    if (isset($check_existing_template) && !empty($check_existing_template)) {
                        //pr($check_existing_template);
                        $current_values = (isset($template_values[$temp_seq]) && !empty($template_values[$temp_seq])) ? $template_values[$temp_seq] : array();
                        $current_media = (isset($template_media[$temp_seq]) && !empty($template_media[$temp_seq])) ? $template_media[$temp_seq] : '';
                        $current_media_name = (isset($template_media_names[$temp_seq]) && !empty($template_media_names[$temp_seq])) ? $template_media_names[$temp_seq] : '';
                        $current_btn_url = (isset($template_button_url[$temp_seq]) && !empty($template_button_url[$temp_seq])) ? $template_button_url[$temp_seq] : '';

                        $temp_desc = (!empty($check_existing_template['description'])) ? (array) json_decode($check_existing_template['description'], true) : array();

                        $components = array();
                        if (isset($temp_desc) && !empty($temp_desc)) {
                            foreach ($temp_desc as $key => $desc) {
                                $desc = (array) $desc;
                                if (isset($desc['type']) && $desc['type'] == 'HEADER') {
                                    //                                    $media_url = base_url() . DEFAULT_IMAGE_UPLOAD_PATH . '' . $current_media;
                                    $header_text = isset($desc['text']) ? $desc['text'] : '';
                                    $add_param = true;
                                    if (isset($desc['format']) && !empty($desc['format']) && $desc['format'] == 'TEXT') {
                                        $add_param = false;
                                        if (stripos($desc['text'], "{{1}}") !== false) {
                                            $add_param = true;
                                            $header_text_paramter = $current_values[0];
                                            unset($current_values[0]);
                                            $current_values = array_values($current_values);
                                            $current_values['header'] = $header_text_paramter;

                                            preg_match_all("/{{[0-9]}}/", $header_text, $param_header_matches);
                                            $header_parameter_values = array();
                                            if (isset($param_header_matches[0]) && !empty($param_header_matches[0])) {
                                                foreach ($param_header_matches[0] as $phkey => $param_header_match) {
                                                    if ($current_values['header'] == 'name_field') {
                                                        $header_text = $name;
                                                    } else {
                                                        $header_text = $current_values['header'];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if ($add_param) {
                                        $media_url = $current_media;
                                        $components[$desc['type']]['type'] = strtolower($desc['type']);
                                        $components[$desc['type']]['parameters'][] = array(
                                            'type' => strtolower($desc['format']),
                                            strtolower($desc['format']) => (!empty($media_url)) ? array("link" => $media_url) : $header_text
                                        );
                                    }
                                } elseif (isset($desc['type']) && $desc['type'] == 'BODY') {
                                    $body_text = $desc['text'];
                                    $components[$desc['type']] = array(
                                        'type' => strtolower($desc['type']),
                                        'text' => $body_text
                                    );
                                    preg_match_all("/{{[0-9]}}/", $body_text, $param_matches);
                                    $parameter_values = array();
                                    if (isset($param_matches[0]) && !empty($param_matches[0])) {
                                        foreach ($param_matches[0] as $Mkey => $param_match) {
                                            if ($current_values[$Mkey] == 'name_field') {
                                                if (!empty($name)) {
                                                    $text_value = $name;
                                                } else {
                                                    $text_value = $current_values[$Mkey];
                                                }
                                            } else {
                                                $text_value = $current_values[$Mkey];
                                            }
                                            $components[$desc['type']]['parameters'][] = array(
                                                "type" => 'text',
                                                'text' => $text_value
                                            );
                                        }
                                    }
                                } elseif (isset($desc['type']) && $desc['type'] == 'FOOTER') {
                                    $footer_text = $desc['text'];
                                    $components[$desc['type']] = array(
                                        'type' => strtolower($desc['type']),
                                        'text' => $footer_text
                                    );
                                } elseif (isset($desc['type']) && $desc['type'] == 'BUTTONS') {
                                    if (isset($desc['buttons']) && !empty($desc['buttons'])) {
                                        $buttons = (array) $desc['buttons'];
                                        if (!empty($buttons)) {
                                            //                                            $button_params = array();
                                            foreach ($buttons as $bkey => $button) {
                                                $sub_type = '';
                                                if (isset($button['type']) && !empty($button['type']) && $button['type'] == 'URL') {
                                                    if (stripos($button['url'], "{{1}}") !== false) {
                                                        unset($button['example']);
                                                        unset($button['url']);
                                                        $button['type'] = 'text';
                                                        $button['text'] = $current_btn_url;
                                                        $buttons[$bkey] = $button;
                                                        $sub_type = 'url';
                                                    }
                                                }
                                                //                                                $button_params[] = (array) $button;
                                                $button_parameter = array(
                                                    "type" => 'button',
                                                    "index" => ($bkey),
                                                    "parameters" => array(
                                                        (array) $button
                                                    )
                                                );
                                                if (!empty($sub_type)) {
                                                    $button_parameter['sub_type'] = $sub_type;
                                                    $components[$desc['type']][] = $button_parameter;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        return $components;
                    }
                }
            }
        }
    }
}

function get_user_meta_templates($user_id = '') {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    if (empty($user_id)) {
        $user_id = $ci->session->userdata('id');
    }
    $where = 'user_id = ' . $ci->db->escape($user_id) . 'AND temp_status = "APPROVED" AND temp_id IS NOT NULL AND is_deleted IS NOT NULL';
    $fields = 'id, name, temp_language';
    $template_arr = $ci->CMS_model->get_result(tbl_templates, $where, $fields);
    return $template_arr;
}

function get_meta_template_details($user_id, $template_id, $seq = 0) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    $template_id = $template_id;
    $seq = $seq;
    $where = 'id = ' . $template_id;

    $template_response = array();
    $response = '';
    if ($user_id > 0) {
        $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
        $check_existing_template = $ci->CMS_model->get_result(tbl_templates, $where, null, 1);
        if (isset($check_existing_template) && !empty($check_existing_template)) {
            $data['template_datas'] = $check_existing_template;
            if ($seq > 0) {
                $data['update_option'] = true;
                $data['temp_add_seq'] = $seq;
            }
            $response = $ci->load->view('User_templates/view', $data, TRUE);
            $template_response['name'] = $check_existing_template['name'];
            $template_response['response'] = $response;
        }
    }
    return $template_response;
}

function conver_file_size($size) {
    if ($size < 1024) {
        return "{$size} bytes";
    } elseif ($size < 1048576) {
        $size_kb = round($size / 1024);
        return "{$size_kb} KB";
    } else {
        $size_mb = round($size / 1048576, 1);
        return "{$size_mb} MB";
    }
}

function get_time_zone() {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    //$fields = 'country_name, time_zone';
    $time_zone_arr = $ci->db->get(tbl_country_time_zone)->result_array();
    return $time_zone_arr;
}

function differenceInHours($startdate, $enddate) {
    $starttimestamp = strtotime($startdate);
    $endtimestamp = strtotime($enddate);
    $difference = abs($endtimestamp - $starttimestamp) / 3600;
    return $difference;
}

function getTimeBaseOnTimeZone($date_time_str, $is_time = false) {
    $ci = &get_instance();
    // Create a DateTime object with the Singapore time and time zone
    if(!empty($date_time_str)){
        $singapore_time_zone = new DateTimeZone(date_default_timezone_get());
        $singapore_date_time = new DateTime($date_time_str, $singapore_time_zone);

        // Convert Singapore time to Indian Standard Time (IST)
        $time_zone = $ci->session->userdata('time_zone');
        if (!empty($time_zone)) {
            $india_time_zone = new DateTimeZone($time_zone);
            $singapore_date_time->setTimezone($india_time_zone);

            if ($is_time) {
                $date_time_str = $singapore_date_time->format('H:i:s');
            } else {
                $date_time_str = $singapore_date_time->format('Y-m-d H:i:s');
            }
        }
        return $date_time_str;
    }
    return false;
}

function getServerTimeZone($time_str, $is_date_time = false) {
    $ci = &get_instance();

    $user_time_zone = $ci->session->userdata('time_zone');
    if (!empty($user_time_zone)) {
        $user_time_zone_obj = new DateTimeZone($user_time_zone);
        $time = new DateTime($time_str, $user_time_zone_obj);

        $server_time_zone = new DateTimeZone(date_default_timezone_get());
        $time->setTimezone($server_time_zone);
        if ($is_date_time) {
            $time_str = $time->format('Y-m-d H:i:s');
        } else {
            $time_str = $time->format('h:i a');
        }
    }

    return $time_str;
}

function getTimeBaseOnTimeZoneFromTimeSatmp($timestamp){
    $date = new DateTime("@$timestamp");
    $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
    $date_time = $date->format('Y-m-d H:i:s');
    return $date_time;
}

function creteServerFileLink($serverPath) {
    if (!empty($serverPath)) {
        $link_arr = explode('/', $serverPath);
        unset($link_arr[0]);
        unset($link_arr[1]);
        unset($link_arr[2]);
        unset($link_arr[3]);
        $link_arr[4] = base_url() . 'upload';
        return implode('/', $link_arr);
    }
    return false;
}

function getUserSettings($field = '') {
    $ci = &get_instance();
    $user_id = $ci->session->userdata('id');
    if (!empty($user_id)) {
        $uerSettings = $ci->db->get_where(tbl_user_settings, array('user_id' => $user_id))->row_array();
        if (!empty($field)) {
            return isset($uerSettings[$field]) && !empty($uerSettings[$field]) ? $uerSettings[$field] : '';
        }
        return $uerSettings;
    }
}

function create_template_message_for_chat($chat_id) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    if ($ci->data['user_data']['type'] == 'user') {
        $user_id = $ci->data['user_data']['id'];
    }
    if ($ci->data['user_data']['type'] == 'member') {
        $user_id = $ci->data['user_data']['added_by'];
    }
    $html = '<div>';
    if ($user_id > 0) {
        $message_info = $ci->CMS_model->get_result(tbl_chat_logs, 'id = ' . $chat_id, null, 1);
        $json_decoded_msg = !empty($message_info['message']) ? json_decode($message_info['message'], 1) : '';

        if (!empty($json_decoded_msg)) {
            $template_name = $json_decoded_msg['template']['name'];
            if (!empty($template_name)) {
                $where = 'name = "' . $template_name . '" AND user_id = ' . $user_id;
                $template_info = $ci->CMS_model->get_result(tbl_templates, $where, null, 1);
                $temp_description = '';

                if (!empty($template_info['description'])) {
                    $temp_description = json_decode($template_info['description'], 1);
                    $components = isset($json_decoded_msg['template']['components']) && !empty($json_decoded_msg['template']['components']) ? $json_decoded_msg['template']['components'] : '';

                    if (!empty($temp_description)) {
                        //pr($temp_description,1);
                        foreach ($temp_description as $ktdesc => $vtdesc) {
                            if ($vtdesc['type'] == 'HEADER') {
                                if (isset($vtdesc['format']) && $vtdesc['format'] == 'TEXT') {
                                    $temp_text = $vtdesc['text'];
                                    if (strpos($vtdesc['text'], '{{1}}') != false) {
                                        $param = isset($components[$ktdesc]['parameters']) && !empty($components[$ktdesc]['parameters']) ? $components[$ktdesc]['parameters'] : '';

                                        if (!empty($param)) {
                                            foreach ($param as $Kp => $Vp) {
                                                if ($Vp['type'] == 'text') {
                                                    if (isset($Vp['text']) && !empty($Vp['text'])) {
                                                        $temp_text = str_replace('{{1}}', $Vp['text'], $temp_text);
                                                    }
                                                    $html .= '<b>' . $temp_text . '</b>';
                                                }
                                            }
                                        }
                                    } else {
                                        $html .= '<b>' . $temp_text . '</b>';
                                    }
                                } else if (isset($vtdesc['format']) && $vtdesc['format'] == 'VIDEO') {
                                    $param = isset($components[$ktdesc]['parameters']) && !empty($components[$ktdesc]['parameters']) ? $components[$ktdesc]['parameters'] : '';
                                    if (!empty($param)) {
                                        foreach ($param as $Kp => $Vp) {
                                            if ($Vp['type'] == 'video' && isset($Vp['video']['link']) && !empty($Vp['video']['link'])) {
                                                $file_type = substr($Vp['video']['link'], strrpos($Vp['video']['link'], '.') + 1);
                                                $html .= '<video controls width="100%"><source src="' . $Vp['video']['link'] . '"></video>';
                                            }
                                        }
                                    }
                                } else if (isset($vtdesc['format']) && $vtdesc['format'] == 'IMAGE') {
                                    $param = isset($components[$ktdesc]['parameters']) && !empty($components[$ktdesc]['parameters']) ? $components[$ktdesc]['parameters'] : '';
                                    if (!empty($param)) {
                                        foreach ($param as $Kp => $Vp) {
                                            if ($Vp['type'] == 'image' && isset($Vp['image']['link']) && !empty($Vp['image']['link'])) {
                                                $file_type = substr($Vp['image']['link'], strrpos($Vp['image']['link'], '.') + 1);
                                                $html .= '<img src="' . $Vp['image']['link'] . '" width="100%px" /><br/>';
                                            }
                                        }
                                    }
                                } else if (isset($vtdesc['format']) && $vtdesc['format'] == 'DOCUMENT') {
                                    $param = isset($components[$ktdesc]['parameters']) && !empty($components[$ktdesc]['parameters']) ? $components[$ktdesc]['parameters'] : '';
                                    if (!empty($param)) {
                                        foreach ($param as $Kp => $Vp) {
                                            if ($Vp['type'] == 'document' && isset($Vp['document']['link']) && !empty($Vp['document']['link'])) {

                                                $link_arr = explode('/', $Vp['document']['link']);
                                                $data = get_headers($Vp['document']['link'], true);
                                                $file_size = isset($data['Content-Length']) ? (int) $data['Content-Length'] : '';
                                                $file_size_in = !empty($file_size) ? ' - ' . conver_file_size($file_size) : '';

                                                $parsedUrl = parse_url($Vp['document']['link']);
                                                $path = $parsedUrl['path'];
                                                $pathInfo = pathinfo($path);
                                                $file_type = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                                                $file_name = isset($pathInfo['basename']) ? $pathInfo['basename'] : '';

                                                //$file_name = substr($Vp['document']['link'], strrpos($Vp['document']['link'], '.') + 1);

                                                $html .= '<a href="' . $Vp['document']['link'] . '" target="_blank" class="download-file" data-link="' . $Vp['document']['link'] . '" data-filename="' . $file_name . '" ><div class="d-flex preview"><div class="file-type"></div><small><span>' . $file_name . '</span><br/><span>' . strtoupper($file_type) . $file_size_in . '</span></small></div></a>';
                                            }
                                        }
                                    }
                                }
                            } else if ($vtdesc['type'] == 'BODY') {
                                $temp_text = $vtdesc['text'];
                                if (strpos($vtdesc['text'], '{{1}}') != false) {
                                    $param = isset($components[$ktdesc]['parameters']) && !empty($components[$ktdesc]['parameters']) ? $components[$ktdesc]['parameters'] : '';

                                    if (!empty($param)) {
                                        foreach ($param as $Kp => $Vp) {
                                            $temp_text = str_replace('{{' . ($Kp + 1) . '}}', $Vp['text'], $temp_text);
                                        }
                                    }
                                }
                                $html .= '<br/>' . preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $temp_text);
                            } else if ($vtdesc['type'] == 'CAROUSEL') {
                                $carousel_html = '<div id="carousel-' . $chat_id . '" class="carousel slide" data-bs-ride="carousel"><div class="carousel-inner">';

                                $cards = $vtdesc['cards'];
                                foreach ($cards as $cdsK => $cdsV) {
                                    $carousel_html .= '<div class="carousel-item ' . ($cdsK == 0 ? 'active' : '') . '">';
                                    $card_components = $cdsV['components'];
                                    $param = isset($components[$ktdesc]['cards']) && !empty($components[$ktdesc]['cards']) ? $components[$ktdesc]['cards'] : '';
                                    if (!empty($card_components)) {
                                        $component_card = isset($param[$cdsK]) && !empty($param[$cdsK]) ? $param[$cdsK]['components'] : '';
                                        foreach ($card_components as $card_compK => $card_comp) {
                                            $card_param = isset($component_card[$card_compK]) && isset($component_card[$card_compK]['parameters']) && !empty($component_card[$card_compK]['parameters']) ? $component_card[$card_compK]['parameters'] : '';

                                            if ($card_comp['type'] == 'HEADER') {
                                                $header_parm = isset($card_param[0]) && !empty($card_param[0]) ? $card_param[0] : '';
                                                $link = !empty($header_parm) && isset($header_parm['image']['link']) && !empty($header_parm['image']['link']) ? $header_parm['image']['link'] : $card_comp['example']['header_handle'][0];
                                                if (isset($header_parm['type']) && $header_parm['type'] == 'video' && !empty($link)) {
                                                    $carousel_html .= '<video controls class="d-block w-100"><source src="' . $link . '"></video>';
                                                } else if (isset($header_parm['type']) && $header_parm['type'] == 'image' && !empty($link)) {
                                                    $carousel_html .= '<img class="d-block w-100" src="' . $link . '" />';
                                                }
                                            } else if ($card_comp['type'] == 'BODY') {
                                                $card_text = $card_comp['text'];
                                                if (strpos($card_text, '{{1}}') != false) {
                                                    $body_parm = $card_param;
                                                    if (!empty($body_parm)) {
                                                        foreach ($body_parm as $Kp => $Vp) {
                                                            $card_text = str_replace('{{' . ($Kp + 1) . '}}', $Vp['text'], $card_text);
                                                        }
                                                    }
                                                }
                                                $card_text_str = preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $card_text);
                                                $carousel_html .= '<div class="carousel-caption d-none d-sm-block"><p>' . $card_text_str . '</p></div>';
                                            } else if ($card_comp['type'] == 'BUTTONS') {
                                                foreach ($card_comp['buttons'] as $kbtn => $btn) {
                                                    if ($btn['type'] == 'QUICK_REPLY') {
                                                        $carousel_html .= '<p class="text-primary text-center mt-2 mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-left"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg> ';
                                                        $carousel_html .= $btn['text'] . '</p>';
                                                    }
                                                    if ($btn['type'] == 'URL') {
                                                        $carousel_html .= '<p class="text-primary text-center mt-2 mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg> ';
                                                        $carousel_html .= $btn['text'] . '</p>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $carousel_html .= '</div>';
                                }
                                $carousel_html .= '</div><a class="carousel-control-prev" href="#carousel-' . $chat_id . '" role="button" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></a>';
                                $carousel_html .= '<a class="carousel-control-next" href="#carousel-' . $chat_id . '" role="button" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></a></div>';

                                $html .= $carousel_html;
                            } else if ($vtdesc['type'] == 'FOOTER') {
                                $temp_text = $vtdesc['text'];
                                $html .= '<br/><small class="text-muted" >' . $temp_text . '</small>';
                            } else if ($vtdesc['type'] == 'BUTTONS') {
                                foreach ($vtdesc['buttons'] as $kbtn => $btn) {
                                    if ($btn['type'] == 'QUICK_REPLY') {
                                        $html .= '<p class="text-primary text-center mt-2 mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-left"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg> ';
                                        $html .= $btn['text'] . '</p>';
                                    }
                                    if ($btn['type'] == 'URL') {
                                        $html .= '<p class="text-primary text-center mt-2 mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg> ';
                                        $html .= $btn['text'] . '</p>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $html .= '</div>';
    return $html;
}

function startUploadSession($file_arr) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    $user_id = $ci->session->userdata('id');
    $where = 'user_id = ' . $ci->db->escape($user_id);
    $user_cread = $ci->CMS_model->get_result(tbl_user_settings, $where, '', 1);
    if (!empty($file_arr) && is_array($file_arr) && !empty($user_cread)) {
        $app_id = isset($user_cread['app_id']) && !empty($user_cread['app_id']) ? $user_cread['app_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';
        if (!empty($app_id) && !empty($permanent_access_token)) {
            $file_arr['access_token'] = $permanent_access_token;
            $file_arr['app_id'] = $app_id;
            $CURL_response = curlUploadSession($file_arr);
            if (!empty($CURL_response)) {
                $res_arr = json_decode($CURL_response, 1);
                if (isset($res_arr['id']) && !empty($res_arr['id'])) {
                    $file_arr['upd_session_id'] = $res_arr['id'];
                    $uploadResponse = startUploadMedia($file_arr);
                    return $uploadResponse;
                    exit();
                    //pr($uploadResponse, 1);
                }
            }
            return $CURL_response;
        }
    }
}

function curlUploadSession($data) {
    $url = "https://graph.facebook.com/v20.0/" . $data['app_id'] . "/uploads?";
    unset($data['app_id']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data['source'] = new CURLFile($data['file_tmp_path'], $data['file_type'], $data['file_name']);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $ch_result = curl_exec($ch);
    curl_close($ch);
    return $ch_result;
}

function startUploadMedia($data) {
    $url = "https://graph.facebook.com/v20.0/" . $data['upd_session_id'];
    unset($data['app_id']);
    unset($data['upd_session_id']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $file = fopen($data['file_tmp_path'], 'rb');
    $fileSize = filesize($data['file_tmp_path']);

    $headers = [
        'Authorization: OAuth ' . $data['access_token'],
        "file_offset: 0",
        "Content-Length: " . $fileSize,
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, fread($file, $fileSize));

    $ch_result = curl_exec($ch);
    curl_close($ch);
    return $ch_result;
}

function upload_file_on_server($file_arr, $filename) {
    $ci = &get_instance();
    $user_id = $ci->session->userdata('id');
    if (!empty($user_id) && !empty($file_arr) && !empty($filename)) {
        $path = 'upload/users_media/';
        if (!file_exists($path)) {
            mkdir($path, 0777);
            $path .= $user_id . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777);
            }
        } else {
            $path .= $user_id . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777);
            }
        }

        /* if (is_dir($path)) {
          $fileExtensions_arr = ['mp4', '3gp','pdf','jpg', 'jpeg', 'png'];
          $files = scandir($path);
          if(!empty($files)){
          foreach ($files as $file) {
          $filePath = $path . '/' . $file;
          if (is_file($filePath)) {
          $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
          if (in_array($fileExtension, $fileExtensions_arr)) {
          unlink($filePath);
          }
          }
          }
          }
          } */


        $fileInfo = pathinfo($file_arr[$filename]['name']);
        $imgname = '';
        if (!empty($fileInfo) && isset($fileInfo['filename']) && !empty($fileInfo['extension'])) {
            $randname = str_replace([' ', '.'], '_', $fileInfo['filename']);
            //if (!empty($fileInfo) && isset($fileInfo['extension'])) {
            //$randname = time() . $user_id . '.' . $fileInfo['extension'];

            $randname = $randname.'.'.$fileInfo['extension'];
            $fileExist = $path . $randname;
            if (file_exists($fileExist)) {
                unlink($fileExist);
            }
            $config = array(
                'upload_path' => $path,
                'file_name' => $randname
            );
            $config['allowed_types'] = "gif|jpg|png|jpeg|mp4|3gp|pdf";
            #Load the upload library
            $ci->load->library('upload');
            $ci->upload->initialize($config);
            if ($ci->upload->do_upload($filename)) {
                $img_data = $ci->upload->data();
                $imgname = base_url() . $path . $randname;
            } else {
                $imgname = '';
            }
        }
        return $imgname;
    }
}

function downloadImageByMediaId($data) {

    $url = "https://graph.facebook.com/v20.0/" . $data['media_id'] . "?access_token=" . $data['access_token'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Verify the host

    $ch_result = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return 'cURL Error: ' . $error_msg;
    }

    curl_close($ch);
    return $ch_result;
}

function create_meta_template($data) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');
    $user_id = $ci->session->userdata('id');
    $where = 'user_id = ' . $ci->db->escape($user_id);
    $user_cread = $ci->CMS_model->get_result(tbl_user_settings, $where, '', 1);
    if (!empty($user_cread)) {
        $business_account_id = isset($user_cread['business_account_id']) && !empty($user_cread['business_account_id']) ? $user_cread['business_account_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($business_account_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $business_account_id . "/message_templates";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            //curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                "Authorization: Bearer " . $permanent_access_token,
                "Content-Type: application/json",
                "Content-Length: " . strlen($data)
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $ch_result = curl_exec($ch);

            curl_close($ch);
            return $ch_result;
            /* if (curl_errno($ch)) {
              return curl_error($ch);
              } else {

              } */
        }
    }
}

function send_contact($json_data, $phone_number, $user_id) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');

    $where = 'user_id = ' . $ci->db->escape($user_id);
    $user_cread = $ci->CMS_model->get_result(tbl_user_settings, $where, '', 1);

    if (empty($json_data)) {
        $json_data = '{"name":{"formatted_name":"Rashmikant Rathod","first_name":"Rashmikant"},"phones":[{"phone":"+917990416847","type":"Mobile","wa_id":"917894561230"}]}';
        $phone_number = '919510482966';
    }

    if (!empty($user_cread) && !empty($phone_number)) {
        $phone_number_id = isset($user_cread['phone_number_id']) && !empty($user_cread['phone_number_id']) ? $user_cread['phone_number_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($phone_number_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $phone_number_id . "/messages";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $constact_arr = array(
                "messaging_product" => "whatsapp",
                "to" => '+' . $phone_number,
                "type" => "contacts",
            );

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $constact_arr["contacts"] = array(json_decode($json_data, 1));

            $headers = [
                "Authorization: Bearer " . $permanent_access_token,
                "Content-Type: application/json"
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($constact_arr));
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                file_put_contents('Z_send_contact_err.txt', json_encode($ch), FILE_APPEND | LOCK_EX);
                return curl_error($ch);
            }
            file_put_contents('Z_send_contact_response.txt', json_encode($ch_result), FILE_APPEND | LOCK_EX);
            curl_close($ch);
            return $ch_result;
        }
    } else {
        file_put_contents('Z_send_contact_err.txt', 'something wrong. contact sending failed', FILE_APPEND | LOCK_EX);
        exit();
    }
}

function curlSendTemplate($data) {
    $phone_number_id = isset($data['from_phone_number_id']) && !empty($data['from_phone_number_id']) ? $data['from_phone_number_id'] : '';
    $permanent_access_token = isset($data['access_token']) && !empty($data['access_token']) ? $data['access_token'] : '';
    $to = isset($data['to']) && !empty($data['to']) ? '+' . $data['to'] : '';

    if (!empty($phone_number_id) && !empty($permanent_access_token) && !empty($to)) {

        unset($data['from_phone_number_id']);
        unset($data['access_token']);
        unset($data['to']);

        $url = "https://graph.facebook.com/v20.0/" . $phone_number_id . "/messages";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $temp_data = array(
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            //"to" => +919510482966,
            "type" => "template",
        );

        $template = array_merge($temp_data, $data['components']);
        $template_json = json_encode($template);
        file_put_contents('z_curl_send_template.txt', $template_json . PHP_EOL, FILE_APPEND | LOCK_EX);

        $headers = [
            'Authorization: Bearer ' . $permanent_access_token,
            "Content-Type: application/json",
            "Content-Length: " . strlen($template_json)
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $template_json);

        $ch_result = curl_exec($ch);
        if (curl_errno($ch)) {
            return json_encode(array('error' => curl_error($ch)));
        }
        curl_close($ch);
        return $ch_result;
    }
}

function create_template_body($input_arr, $contact_arr, $return_defualt_data = false) {
    //pr($input_arr);
    //pr($contact_arr, 1);

    $ci = &get_instance();
    $ci->load->model('CMS_model');

    $message_decode = [];
    if (!empty($input_arr) && !empty($contact_arr)) {
        $template_id = isset($input_arr['template_id']) && !empty($input_arr['template_id']) ? $input_arr['template_id'] : '';

        if (!empty($template_id)) {
            $where_temp = 'id =' . $template_id . ' AND is_deleted = 0';
            $template_arr = $ci->CMS_model->get_result(tbl_templates, $where_temp, '', 1);
            if (!empty($template_arr)) {
                $temp_description = !empty($template_arr['description']) ? json_decode($template_arr['description'], 1) : '';
                $contact = isset($contact_arr['contact']) && !empty($contact_arr['contact']) ? $contact_arr['contact'] : $contact_arr['phone_number_full'];
                if (!empty($temp_description) && !empty($contact)) {
                    $message_arr = array(
                        "name" => $template_arr['name'],
                        "language" => array(
                            "code" => $template_arr['temp_language'],
                        ),
                    );

                    $header_select_value = isset($input_arr['default_select_header_value']) && !empty($input_arr['default_select_header_value']) ? $input_arr['default_select_header_value'] : '';
                    $header_value = isset($input_arr['header_value']) && !empty($input_arr['header_value']) ? $input_arr['header_value'] : '';
                    $default_select_value = isset($input_arr['default_select_value']) && !empty($input_arr['default_select_value']) ? $input_arr['default_select_value'] : '';

                    $default_value = isset($input_arr['default_value']) && !empty($input_arr['default_value']) ? $input_arr['default_value'] : '';
                    $temp_media = isset($input_arr['temp_media']) && !empty($input_arr['temp_media']) ? $input_arr['temp_media'] : '';
                    $temp_btn_url = isset($input_arr['temp_btn_url']) && !empty($input_arr['temp_btn_url']) ? $input_arr['temp_btn_url'] : '';
                    $card_media = isset($input_arr['card_media']) && !empty($input_arr['card_media']) ? $input_arr['card_media'] : '';

                    $components_arr = $component_header = $component_body = $component_buttons = array();
                    $message_string = '';
                    $user_values = array();
                    foreach ($temp_description as $index => $des) {
                        if (isset($des['type']) && $des['type'] == 'HEADER') {
                            if (isset($des['format']) && !empty($des['format'])) {
                                if ($des['format'] == 'TEXT') {
                                    if (isset($des['example']) && !empty($des['example'])) {
                                        if (isset($des['example']['header_text']) && !empty($des['example']['header_text'])) {
                                            $header_text = $des['example']['header_text'][0];
                                            $message_string .= str_replace('{{1}}', '@#' . !empty($header_select_value) ? $header_select_value : 'None' . '#@', $des['text']) . '<br/>';
                                            if (!empty($header_select_value)) {
                                                if (!empty($contact_arr[$header_select_value])) {
                                                    $hText = $contact_arr[$header_select_value];
                                                } else {
                                                    if (!empty($header_value)) {
                                                        $hText = $header_value;
                                                    } else {
                                                        $hText = $header_text;
                                                    }
                                                }
                                            } else {
                                                if (!empty($header_value)) {
                                                    $hText = $header_value;
                                                } else {
                                                    $hText = $header_text;
                                                }
                                            }
                                            $component_header[] = array(
                                                'type' => 'text',
                                                'text' => $hText
                                            );
                                            $user_values[] = $hText;
                                        }
                                    }
                                } else if ($des['format'] == 'DOCUMENT') {
                                    $path = base_url() . 'upload/users_media/'.$template_arr['user_id'].'/';
                                    $filename = str_replace($path, '', $temp_media);
                                    $file_arr = explode('.', $filename);
                                    $lastKey = array_key_last($file_arr); // Get the last key
                                    unset($file_arr[$lastKey]);
                                    $file_name = implode('.', $file_arr);
                                    $component_header[] = array(
                                        'type' => strtolower($des['format']),
                                        strtolower($des['format']) => array(
                                            'link' => $temp_media,
                                            'filename' => str_replace('_', ' ', $file_name)
                                        )
                                    );
                                } else {
                                    $component_header[] = array(
                                        'type' => strtolower($des['format']),
                                        strtolower($des['format']) => array(
                                            'link' => $temp_media,
                                        )
                                    );
                                }
                            }
                            if (!empty($component_header)) {
                                $components_arr['components'][$index] = array('type' => 'header', 'parameters' => $component_header);
                            }
                        } else if (isset($des['type']) && $des['type'] == 'BODY') {
                            if (isset($des['example']) && !empty($des['example'])) {
                                if (isset($des['example']['body_text']) && !empty($des['example']['body_text'])) {
                                    $body_text = $des['example']['body_text'][0];
                                    $body_message = $des['text'];
                                    foreach ($body_text as $bTextK => $bTextV) {
                                        $msg_column = isset($default_select_value[$bTextK]) && !empty($default_select_value[$bTextK]) ? $default_select_value[$bTextK] : 'None';
                                        $body_message = str_replace('{{' . ($bTextK + 1) . '}}', '@#' . $msg_column . '#@', $body_message);
                                        if (!empty($default_select_value[$bTextK])) {
                                            if (!empty($contact_arr[$default_select_value[$bTextK]])) {
                                                $bTextV = $contact_arr[$default_select_value[$bTextK]];
                                            } else {
                                                if (!empty($default_value[$bTextK])) {
                                                    $bTextV = $default_value[$bTextK];
                                                } else {
                                                    $bTextV = $body_text[$bTextK];
                                                }
                                            }
                                        } else {
                                            if (!empty($default_value[$bTextK])) {
                                                $bTextV = $default_value[$bTextK];
                                            } else {
                                                $bTextV = $body_text[$bTextK];
                                            }
                                        }
                                        $component_body[] = array(
                                            'type' => 'text',
                                            'text' => $bTextV
                                        );
                                        $user_values[] = $bTextV;
                                    }
                                    $message_string .= $body_message;

                                    array_splice($default_value, 0, count($body_text));
                                    if (!empty($default_select_value)) {
                                        array_splice($default_select_value, 0, count($body_text));
                                    }

                                    if (!empty($component_body)) {
                                        $components_arr['components'][$index] = array('type' => 'body', 'parameters' => $component_body);
                                    }
                                }
                            }
                        } else if (isset($des['type']) && $des['type'] == 'CAROUSEL') {
                            $cards = $des['cards'];
                            if (!empty($cards)) {
                                $carousel = [];
                                foreach ($cards as $cardK => $cardV) {
                                    $cardComponents = $cardV['components'];
                                    $card_component = $card_component_header = $card_component_body = $card_component_buttons = array();

                                    foreach ($cardComponents as $cardCompK => $cardCompV) {
                                        if ($cardCompV['type'] == 'HEADER') {
                                            $card_component_header[] = array(
                                                'type' => strtolower($cardCompV['format']),
                                                strtolower($cardCompV['format']) => array(
                                                    'link' => $card_media[$cardK],
                                                )
                                            );
                                            if (!empty($card_component_header)) {
                                                $card_component[] = array('type' => 'header', 'parameters' => $card_component_header);
                                            }
                                        } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BODY') {
                                            if (isset($cardCompV['example']) && !empty($cardCompV['example'])) {
                                                if (isset($cardCompV['example']['body_text']) && !empty($cardCompV['example']['body_text'])) {
                                                    $card_body_text = $cardCompV['example']['body_text'][0];
                                                    foreach ($card_body_text as $CbTextK => $CbTextV) {
                                                        if (!empty($default_select_value[$CbTextK])) {
                                                            if (!empty($contact_arr[$default_select_value[$CbTextK]])) {
                                                                $CbTextV = $contact_arr[$default_select_value[$CbTextK]];
                                                            } else {
                                                                $CbTextV = $default_value[$CbTextK];
                                                            }
                                                        } else {
                                                            $CbTextV = $default_value[$CbTextK];
                                                        }
                                                        $card_component_body[] = array(
                                                            'type' => 'text',
                                                            'text' => $CbTextV
                                                        );
                                                        $user_values[] = $CbTextV;
                                                    }
                                                    array_splice($default_value, 0, count($card_body_text));
                                                    if (!empty($default_select_value)) {
                                                        array_splice($default_select_value, 0, count($card_body_text));
                                                    }
                                                    if (!empty($card_component_body)) {
                                                        $card_component[] = array('type' => 'body', 'parameters' => $card_component_body);
                                                    }
                                                }
                                            }
                                        } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BUTTONS') {
                                            $cardbuttons = $cardCompV['buttons'];
                                            foreach ($cardbuttons as $cbtnk => $cbtn) {
                                                if ($cbtn['type'] == 'URL') {
                                                    $payload = isset($temp_btn_url[$cardK]) && !empty($temp_btn_url[$cardK]) ? $temp_btn_url[$cardK] : '';
                                                    if (strpos($cbtn['url'], '{{1}}') != false) {
                                                        $url = !empty($payload) ? $payload : $cbtn['example'][0];
                                                        $card_component_buttons = array(
                                                            "type" => 'button',
                                                            "sub_type" => "url",
                                                            "index" => ($cbtnk),
                                                            "parameters" => array(
                                                                array(
                                                                    'type' => 'payload',
                                                                    'payload' => $url
                                                                )
                                                            )
                                                        );
                                                    }
                                                }
                                                if (!empty($card_component_buttons)) {
                                                    $card_component[] = $card_component_buttons;
                                                }
                                            }
                                        }
                                    }
                                    if (!empty($card_component)) {
                                        $carousel[] = array('card_index' => $cardK, 'components' => $card_component);
                                        $components_arr['components'][$index] = array('type' => 'carousel', 'cards' => $carousel);
                                    }
                                }
                            }
                        } else if (isset($des['type']) && $des['type'] == 'BUTTONS') {
                            $buttons = $des['buttons'];
                            foreach ($buttons as $btnk => $btn) {
                                if ($btn['type'] == 'URL') {
                                    $payload = isset($temp_btn_url[$btnk]) && !empty($temp_btn_url[$btnk]) ? $temp_btn_url[$btnk] : '';
                                    if (strpos($btn['url'], '{{1}}') != false) {
                                        $url = empty($payload) ? $payload : $btn['example'][0];

                                        $component_buttons = array(
                                            "type" => 'button',
                                            "sub_type" => "url",
                                            "index" => ($btnk),
                                            "parameters" => array(
                                                array(
                                                    'type' => 'payload',
                                                    'payload' => $url
                                                )
                                            )
                                        );
                                    }
                                }
                                if (!empty($component_buttons)) {
                                    $components_arr['components'][$index] = $component_buttons;
                                }
                            }
                        }
                    }
                    $message_decode['template'] = array_merge($message_arr, $components_arr);
                    $message_decode['message'] = $message_string;
                    if ($return_defualt_data) {
                        $message_decode['user_values'] = $user_values;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
    return $message_decode;
}

function image_watermark($url, $text) {
    $ci = &get_instance();
    if (!empty($url) && !empty($text)) {
        $img = imagecreatefromjpeg($url);

        if ($img === false) {
            return array('status' => true, 'url' => $url, 'watermark_url' => $url, 'error' => 'failed to add text on image');
        }

        $color = imagecolorallocate($img, 255, 0, 0);

        $issue_date = date('d-m-Y');
        $font = "assets/fonts/Arial.ttf";

        // Check if font file exists
        if (!file_exists($font)) {
            imagedestroy($img);
            return array('status' => true, 'url' => $url, 'watermark_url' => $url, 'error' => 'failed to add text on image');
        }

        $imagesize = getimagesize($url);
        $width = $imagesize[0];
        $height = $imagesize[1];

        if ($width >= 500 && $width <= 1000) {
            imagettftext($img, 25, 0, 50, 400, $color, $font, $text);
            imagettftext($img, 20, 0, 50, 500, $color, $font, $issue_date);
        } else if ($width > 1000) {
            imagettftext($img, 30, 0, 50, 400, $color, $font, $text);
            imagettftext($img, 25, 0, 50, 700, $color, $font, $issue_date);
        } else {
            imagettftext($img, 17, 0, 50, 100, $color, $font, $text);
            imagettftext($img, 13, 0, 50, 130, $color, $font, $issue_date);
        }

        $user_id = $ci->session->userdata('id');
        $directory = 'upload/users_media/' . $user_id . '/';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $new_name = time() . ".jpeg";
        $filePath = $directory . $new_name;

        // Save the modified image
        if (imagejpeg($img, $filePath)) {
            return array('status' => true, 'url' => $url, 'watermark_url' => base_url() . $filePath);
        } else {
            return array('status' => true, 'url' => $url, 'watermark_url' => $url, 'error' => 'failed to add text on image');
        }

        // Clean up
        imagedestroy($img);
    }
}

function getMetaAccountDetails($user_cread) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');

    if (!empty($user_cread)) {
        $business_account_id = isset($user_cread['business_account_id']) && !empty($user_cread['business_account_id']) ? $user_cread['business_account_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($business_account_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $business_account_id . "/?fields=name,business_verification_status,currency,status,country&access_token=" . $permanent_access_token;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                return ['error' => $error_message];
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                return json_decode($ch_result, true);
            } else {
                return ['error' => 'HTTP Error ' . $http_code, 'response' => $ch_result];
            }
        } else {
            redirect('/');
        }
    } else {
        redirect('/');
    }
}

function getMetaPhoneDetails($user_cread) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');

    if (!empty($user_cread)) {
        $business_account_id = isset($user_cread['business_account_id']) && !empty($user_cread['business_account_id']) ? $user_cread['business_account_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($business_account_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $business_account_id . "/phone_numbers/?access_token=" . $permanent_access_token;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                return ['error' => $error_message];
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                return json_decode($ch_result, true);
            } else {
                return ['error' => 'HTTP Error ' . $http_code, 'response' => $ch_result];
            }
        } else {
            redirect('/');
        }
    } else {
        redirect('/');
    }
}

function getMessageDetails($user_cread) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');

    if (!empty($user_cread)) {
        $granularity = isset($user_cread['granularity']) && !empty($user_cread['granularity']) ? $user_cread['granularity'] : 'DAY';
        
        if(isset($user_cread['start_date'])&& !empty($user_cread['start_date']) && isset($user_cread['end_date'])&& !empty($user_cread['end_date'])){
            $start = $user_cread['start_date'];
            $end = $user_cread['end_date'];
        }else{
            $endDateTime = date('Y-m-d').' 18:30:00';
            
            $current_date = new DateTime($endDateTime);
            $current_date->modify('-30 days');
            $startDateTime = $current_date->format('Y-m-d H:i:s');
            
            $start = strtotime($startDateTime);
            $end = strtotime($endDateTime);
        }
        
        
        $business_account_id = isset($user_cread['business_account_id']) && !empty($user_cread['business_account_id']) ? $user_cread['business_account_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($business_account_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $business_account_id . "?fields=analytics.start(".$start.").end(".$end.").granularity(".$granularity.")&access_token=" . $permanent_access_token;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                return ['error' => $error_message];
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                return json_decode($ch_result, true);
            } else {
                return ['error' => 'HTTP Error ' . $http_code, 'response' => $ch_result];
            }
        } else {
            redirect('/');
        }
    } else {
        redirect('/');
    }
}

function getConversionCost($user_cread) {
    $ci = &get_instance();
    $ci->load->model('CMS_model');

    if (!empty($user_cread)) {
        $granularity = isset($user_cread['granularity']) && !empty($user_cread['granularity']) ? $user_cread['granularity'] : 'DAILY';
        
        if(isset($user_cread['start_date'])&& !empty($user_cread['start_date']) && isset($user_cread['end_date'])&& !empty($user_cread['end_date'])){
            $start = $user_cread['start_date'];
            $end = $user_cread['end_date'];
        }else{
            $endDateTime = date('Y-m-d').' 18:30:00';
            
            $current_date = new DateTime($endDateTime);
            $current_date->modify('-60 days');
            $startDateTime = $current_date->format('Y-m-d H:i:s');
            
            $start = strtotime($startDateTime);
            $end = strtotime($endDateTime);
        }
        
        $business_account_id = isset($user_cread['business_account_id']) && !empty($user_cread['business_account_id']) ? $user_cread['business_account_id'] : '';
        $permanent_access_token = isset($user_cread['permanent_access_token']) && !empty($user_cread['permanent_access_token']) ? $user_cread['permanent_access_token'] : '';

        if (!empty($business_account_id) && !empty($permanent_access_token)) {
            $url = "https://graph.facebook.com/v20.0/" . $business_account_id . "?fields=conversation_analytics.start(".$start.").end(".$end.").granularity(".$granularity.")&access_token=" . $permanent_access_token;
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                return ['error' => $error_message];
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                return json_decode($ch_result, true);
            } else {
                return ['error' => 'HTTP Error ' . $http_code, 'response' => $ch_result];
            }
        } else {
            redirect('/');
        }
    } else {
        redirect('/');
    }
}

function get_json_api_data($message = '', $business_account_id = '') {
    $ci = &get_instance();
    if ($business_account_id != '' && $message != '') {
        $ci->db->where(['business_account_id' => $business_account_id]);
        $ci->db->limit('1');
        $user_setting_data = $ci->db->get(tbl_user_settings)->row_array();
        $response = array();
        $permanent_access_token = '';
        if (isset($user_setting_data) && !empty($user_setting_data)) {
            $user_id = $user_setting_data['user_id'];
            $permanent_access_token = $user_setting_data['permanent_access_token'];
            $message = trim($message);

            $url = "https://jsonplaceholder.typicode.com/users?username=" . $message;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ch_result = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                $res = json_decode($ch_result, true);
                $result_text = 'User not found!';
                if (isset($res) && !empty($res)) {
                    $result = isset($res[0]) && !empty($res[0]) ? $res[0] : array();
                    if (isset($result) && !empty($result)) {
                        $result_text = "Name        : " . $result['name'];
                        $result_text .= "\nEmail         : " . $result['email'];
                        $result_text .= "\nUsername : " . $result['username'];
                        $result_text .= "\nPhone        : " . $result['phone'];
                        $result_text .= "\nAddress     : " . $result['address']['street'] . ", " . $result['address']['city'];
                        $result_text .= "\nWebsite     : " . $result['website'];
                        $result_text .= "\nCompany  : " . $result['company']['name'];
                    }
                }
                $response['message'] = $result_text;
                $response['permanent_access_token'] = $permanent_access_token;
                return $response;
            } else {
                return ['error' => 'HTTP Error ' . $http_code, 'response' => $ch_result];
            }
        }
    }
}

function count_user_replied_contacts() {
    $ci = &get_instance();

    if ($ci->session->userdata('type') == 'user') {
        $user_id = $ci->session->userdata('id');
    }
    if ($ci->session->userdata('type') == 'member') {
        $user_id = $ci->session->userdata('added_by');
    }

    $ci->db->select('count(phone_number) as total_contacts');
    $ci->db->where('from_user', 1);
    $ci->db->where('user_id', $user_id);
    $ci->db->group_by('phone_number');
    $res_data = $ci->db->get(tbl_chat_logs . ' l')->num_rows();
    return $res_data;
}
