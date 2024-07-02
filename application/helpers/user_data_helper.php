<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function get_admin_data() {
    $ci = & get_instance();
    $ci->load->model('Admin_model');
    $controller = $ci->router->fetch_class();
    $action = $ci->router->fetch_method();
    $allowed_action = array('login');
    $nonallowed_admin_action = array('clients');
    $nonallowed_user_action = array('users');
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
    $ci = & get_instance();
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
    $CI = & get_instance();
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
    $ci = & get_instance();
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
        'name' => 'Full Name'
    );
    $value = '';
    if ($param_match != 'name_field') {
        $value = $param_match;
    }
    $custom_text .= '<span><select class="form-control-sm default_select_value col-3" name="default_select_value[' . $temp_add_seq . '][]" id="default_select_value_' . $temp_add_seq . '">';
    if (isset($option_array) && !empty($option_array)) {
        foreach ($option_array as $key => $val) {
            $custom_text .= '<option value="' . $key . '">' . $val . '</option>';
        }
    }
    $custom_text .= '</select></span>';
    $custom_text .= '<strong>OR</strong>';
    $custom_text .= '<span><input type="text" class="form-control-sm default_value col-3" placeholder="Default Value" name="default_value[' . $temp_add_seq . '][]" id="default_value_' . $temp_add_seq . '" value="' . $value . '"/></span>';
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
    $ci = & get_instance();
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
                                                $text_value = $name;
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

function get_user_meta_templates() {
    $ci = & get_instance();
    $ci->load->model('CMS_model');
    $user_id = $ci->session->userdata('id');
    $where = 'user_id = ' . $ci->db->escape($user_id) . 'AND temp_status = "APPROVED" AND temp_id IS NOT NULL AND is_deleted IS NOT NULL';
    $fields = 'id, name, temp_language';
    $template_arr = $ci->CMS_model->get_result(tbl_templates, $where, $fields);
    return $template_arr;
}

function get_meta_template_details($user_id, $template_id, $seq = 0) {
    $ci = & get_instance();
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

function conver_file_size($size)
{
    if ($size < 1024) {
        return "{$size} bytes";
    } elseif ($size < 1048576) {
        $size_kb = round($size/1024);
        return "{$size_kb} KB";
    } else {
        $size_mb = round($size/1048576, 1);
        return "{$size_mb} MB";
    }
}

function get_time_zone(){
    $ci = & get_instance();
    $ci->load->model('CMS_model');
    $fields = 'country_name, time_zone';
    $time_zone_arr = $ci->db->get(tbl_country_time_zone)->result_array();
    return $time_zone_arr;
}


?>