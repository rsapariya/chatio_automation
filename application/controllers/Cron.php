<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

//class Cron_official extends CI_Controller {
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model', 'Inquiries_model', 'Recurring_model']);
    }

    public function send_notifications() {
        date_default_timezone_set("Asia/Calcutta");
        $clients = $this->User_model->getClients();
        if (isset($clients) && !empty($clients)) {
            foreach ($clients as $client) {
                $where_settings = ' user_id = ' . $client['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $trigger_time = $user_settings['trigger_time'];
                    $time = date('H:i');

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

                    $post_data = array(
                        'from_phone_number_id' => $user_settings['phone_number_id'],
                        'access_token' => $user_settings['permanent_access_token'],
                        'to' => $client['phone_number_full'],
                    );

//                    $trigger_time = $time;
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

                                $post_data['message'] = $description;

                                $response_json = $this->send_message($post_data);
                                if (isset($response_json) && !empty($response_json)) {
                                    if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
                                        echo "\n\n Message successfully sent to :" . $client['name'] . ' on number : ' . $client['phone_number_full'];
                                    }
                                }

                                $groups = array();
                                if (isset($client['group_ids']) && !empty($client['group_ids'])) {
                                    $groups = explode(',', $client['group_ids']);
                                }
                                if (!empty($groups)) {
                                    foreach ($groups as $group) {
                                        $post_data['to'] = $group;
//                                        $response_json = $this->send_message($post_data);
                                        if (isset($response_json) && !empty($response_json)) {
                                            echo "\n\n Message successfully sent to :" . $client['name'] . ' on group : ' . $group;
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

                                $post_data['message'] = $description;

                                $response_json = $this->send_message($post_data);
                                if (isset($response_json) && !empty($response_json)) {
                                    if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
                                        echo "\n\n Message successfully sent to :" . $client['name'] . ' on number : ' . $client['phone_number_full'];
                                    }
                                }

                                $groups = array();
                                if (isset($client['group_ids']) && !empty($client['group_ids'])) {
                                    $groups = explode(',', $client['group_ids']);
                                }
                                if (!empty($groups)) {
                                    foreach ($groups as $group) {
                                        $post_data['to'] = $group;
//                                        $response_json = $this->send_message($post_data);
                                        if (isset($response_json) && !empty($response_json)) {
                                            echo "\n\n Message successfully sent to :" . $client['name'] . ' on group : ' . $group;
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
//        date_default_timezone_set("Asia/Calcutta");
//        $Inquiries = $this->Inquiries_model->getInquiryLogs();
//        if (isset($Inquiries) && !empty($Inquiries)) {
//            foreach ($Inquiries as $Inquiry) {
//                $where_settings = ' user_id = ' . $Inquiry['user_id'];
//                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
//                if (isset($user_settings) && !empty($user_settings)) {
//                    $trigger_time = $Inquiry['notification_date'];
//                    $time = date('Y-m-d H:i:s');
//
//                    $post_data = array(
//                        'from_phone_number_id' => $user_settings['phone_number_id'],
//                        'access_token' => $user_settings['permanent_access_token'],
//                        'to' => $Inquiry['phone_number_full'],
//                        'template' => $Inquiry['template'],
//                        'language' => $Inquiry['temp_language'],
//                    );
//
////                    $trigger_time = $time; // Need to comment
////                    if (strtotime($time) >= strtotime(date('H:i', strtotime($trigger_time)))) {
//                    if (strtotime($time) >= strtotime(date('Y-m-d H:i:s', strtotime($trigger_time)))) {
//                        $components = $Inquiry['temp_param'];
//                        $post_data['components'] = (!empty($components)) ? json_decode($components, TRUE) : array();
//                        $response_json = $this->send_template($post_data);
//                        if (isset($response_json) && !empty($response_json)) {
//                            $response_array = array(
//                                'status' => $response_json->httpStatusCode(),
//                                'body' => $response_json->body()
//                            );
//                            $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('error_response' => json_encode($response_array), 'sent_at' => date('Y-m-d H:i:s')));
//                            if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
//                                $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('is_sent' => 1));
//                                echo "\n\n Message successfully sent to Inquiry : " . $Inquiry['inquiry'] . ", Automation : " . $Inquiry['automation'] . ", Template : " . $Inquiry['template'] . " on number : " . $Inquiry['phone_number_full'];
//                            } else {
//                                $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('is_sent' => 2));
//                                echo "\n\n Error in Inquiry : " . $Inquiry['inquiry'] . ", Automation : " . $Inquiry['automation'] . ", Template : " . $Inquiry['template'] . " on number : " . $Inquiry['phone_number_full'];
//                            }
//                        }
//                        sleep(2);
//                    }
//                }
//            }
//        }
    }

    public function send_template($post_array = array()) {
        $response = '';
        if (!empty($post_array)) {
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $post_array['from_phone_number_id'],
                'access_token' => $post_array['access_token'],
            ]);
            $component_header = $component_body = $component_buttons = array();
            $components = '';
            if (isset($post_array['components']) && !empty($post_array['components'])) {
                if (isset($post_array['components']['HEADER']['parameters'])) {
                    $component_header = (array) $post_array['components']['HEADER']['parameters'];
                }
                if (isset($post_array['components']['BODY']['parameters'])) {
                    $component_body = (array) $post_array['components']['BODY']['parameters'];
                }
                if (isset($post_array['components']['BUTTONS'])) {
                    foreach ($post_array['components']['BUTTONS'] as $button) {
                        $component_buttons[] = $button;
                    }
                }
            }
            $components = new Component($component_header, $component_body, $component_buttons);
            $response = $this->whatsapp_app_cloud_api->sendTemplate($post_array['to'], $post_array['template'], $post_array['language'], $components);
            return $response;
        }
    }

    public function send_recurring_notifications() {
        $Recurrings = $this->Recurring_model->get_recurrings();
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        if (isset($Recurrings) && !empty($Recurrings)) {
            foreach ($Recurrings as $Recurring) {
                $where_settings = ' user_id = ' . $Recurring['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $trigger_time = $Recurring['trigger_time'];
                    $time = date('H:i');

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
                        $post_data = array(
                            'from_phone_number_id' => $user_settings['phone_number_id'],
                            'access_token' => $user_settings['permanent_access_token'],
                            'to' => $Recurring['phone_number_full'],
                        );

                        if (strtotime($time) == strtotime($trigger_time)) {
                            if (!empty($Recurring['template_id']) && $Recurring['template_id'] != 'other') {
                                $post_data['template'] = $Recurring['template'];
                                $post_data['language'] = $Recurring['temp_language'];

                                $components = $Recurring['temp_param'];
                                $post_data['components'] = json_decode($components, TRUE);
                                $response_json = $this->send_template($post_data);
                            } else {
                                $description = $Recurring['description'];
                                if (strstr($description, '||name||')) {
                                    $description = str_replace('||name||', $Recurring['name'], $description);
                                }
                                $post_data['message'] = $description;
                                $response_json = $this->send_message($post_data);
                            }

                            if (isset($response_json) && !empty($response_json)) {
                                if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
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

    public function send_message($post_array = array()) {
        if (!empty($post_array)) {
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $post_array['from_phone_number_id'],
                'access_token' => $post_array['access_token'],
            ]);
            $response = $this->whatsapp_app_cloud_api->sendTextMessage($post_array['to'], $post_array['message']);
            return $response;
        }
    }

    public function send_media($post_array = array()) {
        if (!empty($post_array)) {
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $post_array['from_phone_number_id'],
                'access_token' => $post_array['access_token'],
            ]);

            $image_type = substr($post_array['filename'], strrpos($post_array['filename'], '.') + 1);
            $link_id = new LinkID($post_array['media_url']);

            if ($image_type == 'pdf') {
                $response = $this->whatsapp_app_cloud_api->sendDocument($post_array['to'], $link_id, $post_array['filename'], '');
            } else {
                $response = $this->whatsapp_app_cloud_api->sendImage($post_array['to'], $link_id);
            }
        }
    }

}
