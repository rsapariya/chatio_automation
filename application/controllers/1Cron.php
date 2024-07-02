<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model', 'Inquiries_model', 'Recurring_model']);
    }

    public function send_notifications() {
        date_default_timezone_set("Asia/Calcutta");
        $clients = $this->User_model->getClients();
        $url = "https://thebrandingmonk.com/api/send?";
        $group_url = "https://thebrandingmonk.com/api/send_group?";
        if (isset($clients) && !empty($clients)) {
            foreach ($clients as $client) {
                $where_settings = ' user_id = ' . $client['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $trigger_time = $user_settings['trigger_time'];
                    $time = date('H:i');
                    $instance_id = $user_settings['instance_id'];
                    $access_token = $user_settings['access_token'];
                    $where_random_type = '';
                    $where_birthday_default = $where_anniversary_default = '';
                    $check_birthday_default_template = $check_anniversary_default_template = '';
                    $send_birthday = $send_anniversary = false;
                    if (date('d-m', strtotime($client['cust_birth_date'])) == date('d-m')) {
                        $where_random_type = 'birthday';
                        $where_birthday_default = ' user_id = ' . $client['user_id'] . ' and type="birthday"';
                        $check_birthday_default_template = $this->CMS_model->get_result(tbl_user_templates, $where_birthday_default, null, 1);
                        $send_birthday = true;
                    }
                    if (date('d-m', strtotime($client['cust_anniversary_date'])) == date('d-m')) {
                        $where_random_type = 'anniversary';
                        $where_anniversary_default = ' user_id = ' . $client['user_id'] . ' and type="anniversary"';
                        $check_anniversary_default_template = $this->CMS_model->get_result(tbl_user_templates, $where_anniversary_default, null, 1);
                        $send_anniversary = true;
                    }
                    if (strtotime($time) == strtotime(date('H:i', strtotime($trigger_time)))) {
                        $default_birthday_template = $default_anniversary_template = array();
                        if (isset($check_birthday_default_template) && !empty($check_birthday_default_template)) {
                            $where_birthday_template = ' id = ' . $check_birthday_default_template['template_id'];
                            $default_birthday_template = $this->CMS_model->get_result(tbl_templates, $where_birthday_template, null, 1);
                        } else {
                            $default_birthday_template = $this->User_model->get_random_template($client['user_id'], 'birthday');
                        }

                        if (isset($check_anniversary_default_template) && !empty($check_anniversary_default_template)) {
                            $where_anniversary_template = ' id = ' . $check_anniversary_default_template['template_id'];
                            $default_anniversary_template = $this->CMS_model->get_result(tbl_templates, $where_anniversary_template, null, 1);
                        } else {
                            $default_anniversary_template = $this->User_model->get_random_template($client['user_id'], 'anniversary');
                        }

                        if ($send_birthday) {
                            if (isset($default_birthday_template) && !empty($default_birthday_template)) {
                                $description = $default_birthday_template['description'];
                                if (strstr($description, '||name||')) {
                                    $description = str_replace('||name||', $client['name'], $description);
                                } else if (strstr($description, '||Name||')) {
                                    $description = str_replace('||Name||', $client['name'], $description);
                                }

                                $curl_post_data = array(
                                    'type' => 'text',
                                    'message' => $description,
                                    'instance_id' => $instance_id,
                                    'access_token' => $access_token,
                                    'number' => $client['phone_number_full'],
                                );
                                $fields_string = http_build_query($curl_post_data);
                                $url = $url . $fields_string;
                                $response_json = $this->curl_api($url);
                                if (isset($response_json) && !empty($response_json)) {
                                    $response = json_decode($response_json, true);
                                    if (isset($response['status']) && $response['status'] != 'error') {
                                        echo "\n\n Message successfully sent to :" . $client['name'] . ' on number : ' . $client['phone_number_full'];
                                    } else {
                                        echo $response['message'];
                                    }
                                }

                                $groups = array();
                                if (isset($client['group_ids']) && !empty($client['group_ids'])) {
                                    $groups = explode(',', $client['group_ids']);
                                }
                                if (!empty($groups)) {
                                    foreach ($groups as $group) {
                                        $curl_post_data = array(
                                            'group_id' => $group,
                                            'type' => 'text',
                                            'message' => $description,
                                            'instance_id' => $instance_id,
                                            'access_token' => $access_token,
                                        );
                                        $fields_string = http_build_query($curl_post_data);
                                        $group_url_new = $group_url . $fields_string;
                                        $response_json = $this->curl_api($group_url_new);
                                        if (isset($response_json) && !empty($response_json)) {
                                            $response = json_decode($response_json, true);
                                            if (isset($response['status']) && $response['status']) {
                                                echo "\n\n Message successfully sent to :" . $client['name'] . ' on group : ' . $group;
                                            }
                                        }
                                        sleep(2);
                                    }
                                }
                            }
                            sleep(2);
                        }
                        if ($send_anniversary) {
                            if (isset($default_anniversary_template) && !empty($default_anniversary_template)) {
                                $description = $default_anniversary_template['description'];
                                if (strstr($description, '||name||')) {
                                    $description = str_replace('||name||', $client['name'], $description);
                                } else if (strstr($description, '||Name||')) {
                                    $description = str_replace('||Name||', $client['name'], $description);
                                }

                                $curl_post_data = array(
                                    'type' => 'text',
                                    'message' => $description,
                                    'instance_id' => $instance_id,
                                    'access_token' => $access_token,
                                    'number' => $client['phone_number_full'],
                                );
                                $fields_string = http_build_query($curl_post_data);
                                $url = $url . $fields_string;
                                $response_json = $this->curl_api($url);
                                if (isset($response_json) && !empty($response_json)) {
                                    $response = json_decode($response_json, true);
                                    if (isset($response['status']) && $response['status'] != 'error') {
                                        echo "\n\n Message successfully sent to :" . $client['name'] . ' on number : ' . $client['phone_number_full'];
                                    } else {
                                        echo $response['message'];
                                    }
                                }

                                $groups = array();
                                if (isset($client['group_ids']) && !empty($client['group_ids'])) {
                                    $groups = explode(',', $client['group_ids']);
                                }
                                if (!empty($groups)) {
                                    foreach ($groups as $group) {
                                        $curl_post_data = array(
                                            'group_id' => $group,
                                            'type' => 'text',
                                            'message' => $description,
                                            'instance_id' => $instance_id,
                                            'access_token' => $access_token,
                                        );
                                        $fields_string = http_build_query($curl_post_data);
                                        $group_url_new = $group_url . $fields_string;
                                        $response_json = $this->curl_api($group_url_new);
                                        if (isset($response_json) && !empty($response_json)) {
                                            $response = json_decode($response_json, true);
                                            if (isset($response['status']) && $response['status']) {
                                                echo "\n\n Message successfully sent to :" . $client['name'] . ' on group : ' . $group;
                                            }
                                        }
                                        sleep(2);
                                    }
                                }
                            }
                            sleep(2);
                        }
                    }
                }
            }
        }
    }

    public function send_inquiry_notifications() {
        date_default_timezone_set("Asia/Calcutta");
        $Inquiries = $this->Inquiries_model->getInquiryLogs();
        $url = "https://thebrandingmonk.com/api/send?";
        if (isset($Inquiries) && !empty($Inquiries)) {
            foreach ($Inquiries as $Inquiry) {
                $where_settings = ' user_id = ' . $Inquiry['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $trigger_time = $Inquiry['notification_date'];
                    $time = date('Y-m-d H:i:s');
                    $instance_id = $user_settings['instance_id'];
                    $access_token = $user_settings['access_token'];

                    if (strtotime($time) >= strtotime(date('H:i', strtotime($trigger_time)))) {
//                    if (strtotime($time) >= strtotime($trigger_time)) {
                        $description = $Inquiry['description'];
                        if (strstr($description, '||name||')) {
                            $description = str_replace('||name||', $Inquiry['inquiry'], $description);
                        }
                        if (!empty($Inquiry['automation_image'])) {
                            $media_url = base_url() . '' . DEFAULT_IMAGE_UPLOAD_PATH . '' . $Inquiry['automation_image'];
                            $curl_post_data = array(
                                'number' => $Inquiry['phone_number_full'],
                                'type' => 'media',
                                'message' => $description,
                                'instance_id' => $instance_id,
                                'access_token' => $access_token,
                                'media_url' => $media_url,
                                'filename ' => $Inquiry['automation_image'],
                            );
                        } else {
                            $curl_post_data = array(
                                'type' => 'text',
                                'message' => $description,
                                'instance_id' => $instance_id,
                                'access_token' => $access_token,
                                'number' => $Inquiry['phone_number_full'],
                            );
                        }
                        $fields_string = http_build_query($curl_post_data);
                        $url = $url . $fields_string;
                        $response_json = $this->curl_api($url);
                        if (!empty($response_json)) {
                            $response = json_decode($response_json, true);
                            if (isset($response['status']) && $response['status']) {
                                $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('is_sent' => 1));
                                echo "\n\n Message successfully sent to Inquiry : " . $Inquiry['inquiry'] . ", Automation : " . $Inquiry['automation'] . ", Template : " . $Inquiry['template'] . " on number : " . $Inquiry['phone_number_full'];
                            }
                        }
                        sleep(2);
                    }
                }
            }
        }
    }

    public function send_recurring_notifications() {
        $Recurrings = $this->Recurring_model->get_recurrings();
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $url = "https://thebrandingmonk.com/api/send?";
        if (isset($Recurrings) && !empty($Recurrings)) {
            foreach ($Recurrings as $Recurring) {
                $where_settings = ' user_id = ' . $Recurring['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $trigger_time = $Recurring['trigger_time'];
                    $time = date('H:i');
                    $instance_id = $user_settings['instance_id'];
                    $access_token = $user_settings['access_token'];

                    $rucurring_send = false;
                    if ($Recurring['trigger_type'] == 'daily') {
                        $rucurring_send = true;
                    } elseif ($Recurring['trigger_type'] == 'weekly') {
                        $weekly_day = $days[date('w')];
                        if ($weekly_day == $Recurring['weekly_day']) {
                            $rucurring_send = true;
                        }
                    } elseif ($Recurring['trigger_type'] == 'monthly') {
                        $month_date = date('d');
                        if ($month_date == $Recurring['monthly_date']) {
                            $rucurring_send = true;
                        }
                    } elseif ($Recurring['trigger_type'] == 'yearly') {
                        $yearly_date = date('d-m');
                        if (date('d-m', strtotime($Recurring['yearly_date'])) == $yearly_date) {
                            $rucurring_send = true;
                        }
                    }
                    if ($rucurring_send) {
                        if (strtotime($time) == strtotime($trigger_time)) {
                            $description = $Recurring['description'];
                            if (strstr($description, '||name||')) {
                                $description = str_replace('||name||', $Recurring['name'], $description);
                            }

                            $curl_post_data = array(
                                'type' => 'text',
                                'message' => $description,
                                'instance_id' => $instance_id,
                                'access_token' => $access_token,
                                'number' => $Recurring['phone_number_full'],
                            );
                            $fields_string = http_build_query($curl_post_data);
                            $url = $url . $fields_string;
                            $response_json = $this->curl_api($url);
                            if (!empty($response_json)) {
                                $response = json_decode($response_json, true);
                                if (isset($response['status']) && $response['status']) {
                                    $this->CMS_model->update_record(tbl_recurrings, array('id' => $Recurring['id']), array('last_trigger_on' => date('Y-m-d H:i:s')));
                                    echo "\n\n Message successfully sent to Recurring : " . $Recurring['name'] . " on number : " . $Recurring['phone_number_full'];
                                }
                            }
                            sleep(2);
                        }
                    }
                }
            }
        }
    }

    public function curl_api($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, trim($fields_string));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $ch_result;
    }

    public function get_files() {
        $response = array();
        if (isset($_POST)) {
            $this->form_validation->set_data($_POST);
            $this->form_validation->set_rules('instance_id', 'Intance ID', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            $this->form_validation->set_rules('access_token', 'Access Token', 'trim|required');
            if ($this->form_validation->run() == TRUE) {
                if (isset($_FILES) && !empty($_FILES)) {
                    if (isset($_FILES['upload_file']['name']) && !empty($_FILES['upload_file']['name'])) {
                        $path = API_IMAGE_UPLOAD_PATH;
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|png|pdf';
                        $config['max_size'] = '500';
                        $config['overwrite'] = TRUE;
                        $this->upload->initialize($config);
                        $this->load->library('upload', $config);
                        $image_name = date('Ymdhis') . '_template_' . $_FILES['upload_file']['name'];
                        $_FILES['upload_file']['name'] = $image_name;

                        if (!$this->upload->do_upload('upload_file')) {
                            $error = array('error' => $this->upload->display_errors());
                            $upload_error = isset($error['error']) ? $error['error'] : 'Invalid File';
                            $response = array(
                                'success' => false,
                                'message' => $upload_error
                            );
                        } else {
                            $uploaded_image_path = base_url() . '' . API_IMAGE_UPLOAD_PATH . '' . $image_name;

                            $curl_post_data = array(
                                'number' => $_POST['phone_number'],
                                'type' => 'media',
                                'message' => 'test message',
                                'instance_id' => $_POST['instance_id'],
                                'access_token' => $_POST['access_token'],
                                'media_url' => $uploaded_image_path,
                                'filename' => $image_name,
                            );
                            $url = "https://thebrandingmonk.com/api/send?";
                            $fields_string = http_build_query($curl_post_data);
                            $url = $url . $fields_string;
                            $response_json = $this->curl_api($url);
                            if (isset($response_json) && !empty($response_json)) {
                                $api_response = json_decode($response_json, true);
                                if (isset($api_response['status']) && $api_response['status'] != 'error') {
                                    $message = "File successfully sent on number : " . $_POST['phone_number'];
                                    $response = array(
                                        'success' => true,
                                        'message' => $message
                                    );
                                } else {
                                    $response = array(
                                        'success' => false,
                                        'message' => $api_response['message']
                                    );
                                }
                            }
                        }
                    }
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'There is no file.'
                    );
                }
            } else {
                $message = validation_errors();
                $response = array(
                    'success' => false,
                    'message' => (!empty($message)) ? $message : 'There is no data.'
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'There is no post data.'
            );
        }
        echo json_encode($response);
        die;
    }

}
