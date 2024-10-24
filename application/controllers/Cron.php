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
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

//class Cron_official extends CI_Controller {
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model', 'Inquiries_model', 'Recurring_model', 'Campaigns_model', 'Chatlogs_model']);
    }

    public function send_notifications() {
        //date_default_timezone_set("Asia/Calcutta");
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
        //$server_timezone = date_default_timezone_get();
        //echo "The server's default timezone is: " . $server_timezone.' time is '.date('H:i');

        $Inquiries = $this->Inquiries_model->getInquiryLogs();
        if (!empty($Inquiries)) {
            foreach ($Inquiries as $inq) {
                $where_settings = ' user_id = ' . $inq['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    //$trigger_time = $user_settings['trigger_time'];
                    $inquiry_id = $inq['inquiry_id'];

                    $where_inquiry = ' id = ' . $inquiry_id;
                    $inquiry_info = $this->CMS_model->get_result(tbl_inquiries, $where_inquiry, null, 1);

                    $phone_number = $inquiry_info['phone_number_full'];

                    $post_data = array(
                        'from_phone_number_id' => $user_settings['phone_number_id'],
                        'access_token' => $user_settings['permanent_access_token'],
                        'to' => $phone_number,
                    );

                    $components = json_decode($inq['temp_param'], TRUE);

                    $post_data['components'] = $components;
                    $response_json = curlSendTemplate($post_data);

                    if (isset($response_json) && !empty($response_json)) {
                        $response_arr = json_decode($response_json, 1);
                        if (isset($response_arr['messages'][0]['id']) && !empty($response_arr['messages'][0]['id'])) {
                            $chat_log = array(
                                'user_id' => $inq['user_id'],
                                'from_user' => 0,
                                'phone_number' => $phone_number,
                                'message_type' => 'template',
                                'message' => json_encode($components),
                                'message_id' => $response_arr['messages'][0]['id'],
                                'message_status' => 'accepted',
                                'api_response' => $response_json,
                                'inquiry_log_id' => $inq['id']
                            );
                            $this->CMS_model->insert_data(tbl_chat_logs, $chat_log);

                            $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $inq['id']), array('is_sent' => 1, 'sent_at' => date('Y-m-d H:i:s')));
                            echo "Message successfully sent for inquiry log  : " . $inquiry_id . " on number : " . $phone_number . PHP_EOL;
                        } else {
                            $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $inq['id']), array('error_response' => $response_json));
                            echo "Message sending failed for inquiry log  : " . $inquiry_id . " on number : " . $phone_number . PHP_EOL;
                        }
                    }
                }
            }
        }



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
                $contact_info = $this->CMS_model->get_result(tbl_clients, 'id=' . $Recurring['client_id'], null, 1);

                if (!empty($contact_info)) {
                    $user_id = $Recurring['user_id'];
                    $phone_number = $contact_info['phone_number_full'];

                    $where_settings = ' user_id = ' . $user_id;
                    $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                    if (!empty($user_settings)) {
                        //$trigger_time = $Recurring['trigger_time'];

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
                                'to' => $phone_number,
                            );

                            $chat_log = array(
                                'user_id' => $user_id,
                                'from_user' => 0,
                                'phone_number' => $phone_number,
                            );

                            if (!empty($Recurring['template_id']) && $Recurring['template_id'] != 'other') {
                                //$post_data['template'] = $Recurring['template'];
                                //$post_data['language'] = $Recurring['temp_language'];
                                $components['template'] = json_decode($Recurring['temp_param'], TRUE);

                                $chat_log['message_type'] = 'template';
                                $chat_log['message'] = json_encode($components);

                                $post_data['components'] = $components;
                                $response_json = curlSendTemplate($post_data);
                            } else {
                                $description = $Recurring['description'];
                                if (strstr($description, '||name||')) {
                                    $description = str_replace('||name||', $Recurring['name'], $description);
                                }
                                $chat_log['message_type'] = 'text';
                                $chat_log['message'] = $description;

                                $urlContain = extract_url($description);

                                $components = array(
                                    'type' => 'text',
                                    'text' => array('body' => $description, 'preview_url' => !empty($urlContain) ? true : false),
                                );
                                $post_data['components'] = $components;
                                $response_json = curlSendTemplate($post_data);
                            }

                            if (isset($response_json) && !empty($response_json)) {
                                $response_arr = json_decode($response_json, 1);
                                if (isset($response_arr['messages'][0]['id']) && !empty($response_arr['messages'][0]['id'])) {
                                    $chat_log['message_id'] = $response_arr['messages'][0]['id'];
                                    $chat_log['message_status'] = 'accepted';
                                    $chat_log['api_response'] = $response_json;
                                    $chat_log['recurring_id'] = $Recurring['id'];
                                    $chat_log['created'] = date('Y-m-d H:i:s');
                                    $this->CMS_model->insert_data(tbl_chat_logs, $chat_log);

                                    $this->CMS_model->update_record(tbl_recurrings, array('id' => $Recurring['id']), array('last_trigger_on' => date('Y-m-d H:i:s')));
                                    echo "Message successfully sent to Recurring : " . $Recurring['name'] . " on number : " . $Recurring['phone_number_full'] . PHP_EOL;
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

    public function update_campaign_status() {
        $where = ' status  = "in_progress" OR  status  = "draft"';
        $campaign = $this->CMS_model->get_result(tbl_campaigns, $where);

        if (!empty($campaign)) {
            foreach ($campaign as $c) {
                $campaign_info = $this->Campaigns_model->campaign_details($c['id']);

                if (!empty($campaign_info)) {
                    $total = $campaign_info['contacts'];

                    $sent = $campaign_info['sent_messages'];
                    $delivered = $campaign_info['delivered_messages'];
                    $read = $campaign_info['read_messages'];
                    $failed = $campaign_info['failed_messages'];
                    $accepted = $campaign_info['accepted_messages'];
                    $status = '';

                    if ($total == $failed) {
                        $status = 'failed';
                    } else if ($total == $delivered || $total == $read || $total == $accepted || $total == ($failed + $delivered + $read + $accepted)) {
                        $status = 'delivered';
                    }
                    if (!empty($status)) {
                        $this->CMS_model->update_record(tbl_campaigns, array('id' => $campaign_info['id']), array('status' => $status));
                        echo 'UPDATED => campaign: ' . $campaign_info['id'] . ' Status : ' . $status . '<br/>';
                    }
                }
            }
        }
    }

    public function forward_message() {
        $messages = $this->db->limit(50)->get(tbl_forward_messages)->result_array();

        if (!empty($messages)) {
            foreach ($messages as $m) {
                if (!empty($m['message'])) {
                    $message_arr = json_decode($m['message'], 1);
                    $forward_response = curlSendTemplate($message_arr);
                    if (!empty($forward_response)) {
                        $fr_arr = json_decode($forward_response, 1);
                        if (isset($fr_arr['messages'][0]['message_status']) && $fr_arr['messages'][0]['message_status'] == 'accepted') {
                            echo 'Message ' . $m['id'] . ' sent to ' . $fr_arr['contacts'][0]['wa_id'] . ' at ' . date('H:i:s Y-m-d') . PHP_EOL;
                            if ($this->db->delete(tbl_forward_messages, array('id' => $m['id']))) {
                                echo 'Message ' . $m['id'] . ' removed  at ' . date('H:i:s Y-m-d') . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    }

    public function download_media() {
        $media_arr = $this->db->limit(50)->get(tbl_temp_media)->result_array();
        if (!empty($media_arr)) {
            foreach ($media_arr as $media_arr) {
                if (!empty($media_arr['user_id']) && !empty($media_arr['media_id'])) {
                    $user_id = $media_arr['user_id'];
                    $user_info = $this->db->get_where(tbl_user_settings, array('user_id' => $user_id))->row_array();
                    if (!empty($user_info)) {
                        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                            'from_phone_number_id' => $user_info['phone_number_id'],
                            'access_token' => $user_info['permanent_access_token'],
                        ]);

                        $mime_arr = explode('/', $media_arr['mime_type']);

                        $extension = strtolower($mime_arr[1]);
                        if ($media_arr['media_type'] == 'document' && $extension == 'plain') {
                            $extension = 'txt';
                        }
                        if ($media_arr['media_type'] == 'document' && ($extension == 'vnd.openxmlformats' || $extension == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                            $extension = 'xlxs';
                        }
                        if ($media_arr['media_type'] == 'document' && $extension == 'vnd.openxmlformats-officedocument.wordprocessingml.document') {
                            $extension = 'docx';
                        }
                        if ($media_arr['media_type'] == 'document' && $extension == 'vnd.openxmlformats-officedocument.presentationml.presentation') {
                            $extension = 'pptx';
                        }

                        $filename = time() . '.' . $extension;
                        $directory = 'upload/users_media/' . $user_id . '/';
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                            $directory .= 'received/';
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }
                        } else {
                            $directory .= 'received/';
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }
                        }
                        $output_file = $directory . $filename;

                        $response = $this->whatsapp_app_cloud_api->downloadMedia($media_arr['media_id']);
                        file_put_contents($output_file, $response->body());

                        if (file_exists($output_file)) {
                            $media_url = base_url() . $output_file;
                            $udp = $this->CMS_model->update_record(tbl_chat_logs, array('id' => $media_arr['chat_id']), array('media' => $media_url));
                            if (!empty($udp)) {
                                $this->CMS_model->delete_data(tbl_temp_media, 'id=' . $media_arr['id']);
                            }
                        }
                        return false;
                    }
                }
            }
        }
        return false;
    }

    public function update_reponse() {
        $responses = $this->db->limit(250)->get(tbl_wh_response)->result_array();
        if (!empty($responses)) {
            $update_chat_log = $update_campaign_log = [];
            foreach ($responses as $r) {
                if (isset($r['message_id']) && !empty($r['message_id'])) {
                    $message_id = $r['message_id'];
                    $crow = $this->db->get_where(tbl_chat_logs, array('message_id' => $message_id));
                    $chat_log_exists = $crow->row_array();
                    if (!empty($chat_log_exists)) {
                        $update_chat_log[] = array(
                            'id' => $chat_log_exists['id'],
                            'message_status' => $r['message_status'],
                            'sent_time' => $r['sent_time'],
                            'deliver_time' => $r['deliver_time'],
                            'read_time' => $r['read_time'],
                        );
                    }
                    $row = $this->db->get_where(tbl_campaign_queue, array('message_id' => $message_id));
                    $campaign_log_exists = $row->row_array();
                    if (!empty($campaign_log_exists)) {
                        $update_campaign_log[] = array(
                            'id' => $campaign_log_exists['id'],
                            'message_status' => $r['message_status'],
                            'sent_time' => $r['sent_time'],
                            'deliver_time' => $r['deliver_time'],
                            'read_time' => $r['read_time'],
                        );
                    }
                    $this->db->delete(tbl_wh_response, array('message_id' => $message_id));
                }
            }
            if (!empty($update_chat_log)) {
                $this->db->update_batch(tbl_chat_logs, $update_chat_log, 'id');
                if ($this->db->affected_rows() > 0) {
                    echo "Records updated successfully in chat_log.";
                } else {
                    echo "No records updated.";
                }
            }
            if (!empty($update_campaign_log)) {
                $this->db->update_batch(tbl_campaign_queue, $update_campaign_log, 'id');
                if ($this->db->affected_rows() > 0) {
                    echo "Records updated successfully in campaign_queue.";
                } else {
                    echo "No records updated.";
                }
            }
        }
    }

    public function save_new_contact() {
        $contacts = $this->Chatlogs_model->get_unsaved_contacts();
        if (!empty($contacts)) {
            $unsaved = [];
            foreach ($contacts as $key => $con) {
                $phone_number_full = $con['phone_number'];
                $unsaved[$key] = array(
                    'name' => $con['from_profile_name'],
                    'phone_number_full' => $phone_number_full,
                    'user_id' => $con['user_id'],
                    'group_ids' => $con['default_tags'],
                );
                
                $isValid = $this->isValidNumber($phone_number_full);
                if (!empty($isValid)) {
                    $unsaved[$key]['phone_number'] = $isValid['phone_number'];
                }
            }
            
            if(!empty($unsaved)){
                if($this->db->insert_batch(tbl_clients, $unsaved)){
                    echo count($unsaved).' contacts saved.';
                }
            }
        }
    }

    function isValidNumber($phoneNumber) {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $parsedNumber = $phoneUtil->parse('+' . $phoneNumber, null);
            $isValid = $phoneUtil->isValidNumber($parsedNumber);
            if (!empty($isValid) && !empty($parsedNumber)) {
                $return['country_code'] = $parsedNumber->getCountryCode();
                $return['phone_number'] = $parsedNumber->getNationalNumber();
                return $return;
            } else {
                return false;
            }
        } catch (NumberParseException $e) {
            return false;
        }
    }

}
