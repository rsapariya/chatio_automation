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
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;

//use Netflie\WhatsAppCloudApi\WebHook;

class Webhook_live extends CI_Controller {

    public function __construct() {
        parent::__construct();
//        $this->from_phone_number_id = '108268772303530';
//        $this->phone_number = '919537800320';
//        $this->access_token = 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze';
//        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
//            'from_phone_number_id' => $this->from_phone_number_id,
//            'access_token' => $this->access_token,
//        ]);
        $this->from_phone_number_id = '';
        $this->phone_number = '';
        $this->access_token = '';
        $this->business_account_id = '';
        $this->load->model(['CMS_model', 'ReplyMessage_model']);
    }

    public function index() {
        $my_verify_token = "zresthine_technologies";

        $challenge = isset($_REQUEST['hub_challenge']) ? $_REQUEST['hub_challenge'] : '';
        $verify_token = isset($_REQUEST['hub_verify_token']) ? $_REQUEST['hub_verify_token'] : '';

        if ($my_verify_token === $verify_token) {
            echo $challenge;
            exit;
        }

        $data_content = file_get_contents("php://input");
        $response = json_decode($data_content, true);


        if (isset($response) && !empty($response)) {
            http_response_code(200);
            header("Content-Type: application/json");
            echo '{success:true}';

            file_put_contents('Z_log_live.txt', $data_content, FILE_APPEND | LOCK_EX);

            $error_message = '';
            if (isset($response['entry'][0]['changes'][0]['value']['statuses'][0]['errors'])) {
                $error_message = $response['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['title'];
            }
            if (empty($error_message)) {
                $message = '';
                $save_record = false;
                $save_chat_record = false;
                $save_chat_record_type = 'text';
                $from_message_id = $reply_message_id = '';
                $from_message_id = $response['entry'][0]['changes'][0]['value']['messages'][0]['id'];
                $reply_message_id = $response['entry'][0]['changes'][0]['value']['messages'][0]['context']['id'];
                if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['text'])) {
                    $save_chat_record_type = 'text';
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];

                    $where_log_settings = ' message_id = "' . $from_message_id . '"';
                    $message_exists = $this->CMS_model->get_result(tbl_chat_logs, $where_log_settings, null, 1);
                    if (empty($message_exists)) {
                        $save_chat_record = true;
                    }
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['button'])) {
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
                    $save_chat_record = true;
                    //$save_record = true;
                    $save_chat_record_type = 'button_reply';
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['interactive'])) {
                    $type = $response['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['type'];
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['interactive'][$type]['title'];
                    if ($type == 'button_reply' || $type = 'list_reply') {
                        //$save_record = true;
                        $save_chat_record = true;
                    }
                    $save_chat_record_type = $type;
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['statuses'])) {
                    $wamid = $response['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
                    $status = $response['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
                    $timestamp = $response['entry'][0]['changes'][0]['value']['statuses'][0]['timestamp'];

                    $row = $this->db->get_where(tbl_chat_logs, array('message_id' => $wamid));
                    $customer_lead = $row->row_array();

                    file_put_contents('Z_update_log.txt', 'wamid :' . $wamid . '=== ??  timestamp :' . $timestamp, FILE_APPEND | LOCK_EX);

                    $updateStatus = array(
                        'message_status' => $status
                    );

                    if (!empty($customer_lead)) {
                        if ($status == 'sent') {
                            $updateStatus['sent_time'] = date('Y-m-d H:i:s', $timestamp);
                        }
                        if ($status == 'delivered') {
                            $updateStatus['deliver_time'] = date('Y-m-d H:i:s', $timestamp);
                        }

                        if ($status == 'read') {
                            $updateStatus['read_time'] = date('Y-m-d H:i:s', $timestamp);
                        }
                        $this->CMS_model->update_record(tbl_chat_logs, array('id' => $customer_lead['id']), $updateStatus);
                    }
                }

                $profile_name = '';
                file_put_contents('Z_message_live.txt', '#=>------' . $message . '===?? $from_message_id :' . $from_message_id . '===?? $save_chat_record_type :' . $save_chat_record_type, FILE_APPEND | LOCK_EX);
                $chat_logs = array();
                if ($message != '') {
                    $this->phone_number = $response['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                    $this->business_account_id = $response['entry'][0]['id'];
                    $this->from_phone_number_id = $response['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                    $profile_name = $response['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
                    $user_datas = $this->ReplyMessage_model->get_user_details($this->business_account_id);
                    /* if ($save_record) {
                      date_default_timezone_set("Asia/Calcutta");
                      $save_button_reply = array(
                      'user_id' => isset($user_datas['user_id']) ? $user_datas['user_id'] : 0,
                      'response' => $message,
                      'mobile_number' => $this->phone_number,
                      'name' => $profile_name,
                      'api_response' => $data_content,
                      'created' => date('Y-m-d H:i:s')
                      );
                      //$this->CMS_model->insert_data(tbl_button_reply_logs, $save_button_reply);
                      } */
                    if ($save_chat_record) {
                        $chat_logs = array(
                            'user_id' => isset($user_datas['user_id']) ? $user_datas['user_id'] : 0,
                            'from_user' => '1',
                            'from_profile_name' => $profile_name,
                            'phone_number' => $this->phone_number,
                            'message_type' => $save_chat_record_type,
                            'message_id' => $from_message_id,
                            'reply_message_id' => $reply_message_id,
                            'message' => $message,
                            'api_response' => $data_content,
                            'created' => date('Y-m-d H:i:s')
                        );
                        $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                    }
                    $reply_message_datas = $this->ReplyMessage_model->get_trigger_message_attachment($message, $this->business_account_id);
                    if (isset($reply_message_datas) && !empty($reply_message_datas)) {
                        $this->access_token = !empty($reply_message_datas['permanent_access_token']) ? $reply_message_datas['permanent_access_token'] : '';
                        $attachments = !empty($reply_message_datas['attachments']) ? json_decode($reply_message_datas['attachments']) : '';
                        $attachments_caption = !empty($reply_message_datas['attachments_caption']) ? json_decode($reply_message_datas['attachments_caption'], 1) : '';


                        $attachments_name = !empty($reply_message_datas['attachments_name']) ? (array) json_decode($reply_message_datas['attachments_name']) : '';
                        $user_id = isset($user_datas['user_id']) ? $user_datas['user_id'] : 0;
                        if (!empty($attachments)) {
                            if ($this->from_phone_number_id != '' && $this->access_token != '') {
                                $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                                    'from_phone_number_id' => $this->from_phone_number_id,
                                    'access_token' => $this->access_token,
                                ]);
                                foreach ($attachments as $key => $attachment) {
                                    $is_send_template = false;

                                    $chat_logs = array(
                                        'user_id' => isset($user_datas['user_id']) ? $user_datas['user_id'] : 0,
                                        'from_user' => '0',
                                        'from_profile_name' => $profile_name,
                                        'phone_number' => $this->phone_number,
                                        'created' => date('Y-m-d H:i:s')
                                    );

                                    if (is_numeric($attachment)) {
                                        $reply_list_template_datas = $this->ReplyMessage_model->get_list_templates($reply_message_datas['user_id'], $attachment, true);
                                        $reply_meta_template_datas = $this->ReplyMessage_model->get_meta_templates($reply_message_datas['user_id'], $attachment, true);
                                        if (!empty($reply_list_template_datas) && empty($reply_meta_template_datas)) {
                                            $this->send_custom_template($this->phone_number, $reply_list_template_datas);
                                            $is_send_template = true;
                                        }
                                        if (!empty($reply_meta_template_datas) && empty($reply_list_template_datas)) {
                                            $components = create_template_message($reply_meta_template_datas['id'], $key, $attachment, $profile_name, 'reply_message', $user_id);
                                            $post_data = array(
                                                'to' => $this->phone_number,
                                                'template' => $reply_meta_template_datas['name'],
                                                'language' => $reply_meta_template_datas['temp_language'],
                                            );
                                            $post_data['components'] = (!empty($components)) ? $components : array();
                                            $response_json = $this->send_template($post_data);

                                            $temp_message["template"] = array(
                                                "name" => $reply_meta_template_datas['name'],
                                                "language" => array("code" => $reply_meta_template_datas['temp_language']),
                                                "components" => $post_data['components']
                                            );
                                            $chat_logs['message_type'] = 'template';
                                            $chat_logs['message'] = json_encode($temp_message);
                                            $chat_logs['api_response'] = json_encode($response_json);

                                            $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                                        }
                                    } else {
                                        $attachment_name = isset($attachments_name[$key]) ? $attachments_name[$key] : '';
                                        $document_link = base_url() . '' . ATTACHMENT_IMAGE_UPLOAD_PATH . '' . $attachment;
                                        $attach_caption = isset($attachments_caption[$key]) ? $attachments_caption[$key] : '';
                                        //file_put_contents('Z_reply_data.txt', 'key=>' . $attach_caption, FILE_APPEND | LOCK_EX);
                                        $image_type = substr($attachment, strrpos($attachment, '.') + 1);
                                        if ($image_type == 'png' || $image_type == 'jpeg' || $image_type == 'jpg') {
                                            $message_type = 'image';
                                        } else if ($image_type == 'aac' || $image_type == 'amr' || $image_type == 'mp3' || $image_type == 'm4a' || $image_type == 'ogg') {
                                            $message_type = 'audio';
                                        } else if ($image_type == '3gp' || $image_type == 'mp4') {
                                            $message_type = 'video';
                                        } else {
                                            $message_type = 'document';
                                        }


                                        $chat_logs['message_type'] = $message_type;
                                        $chat_logs['media'] = $document_link;
                                        $chat_logs['message'] = $attach_caption;

                                        $response = $this->send_media($this->phone_number, $document_link, $attachment, $attachment_name, $attach_caption);
                                        sleep(1);
                                        $chat_logs['api_response'] = json_encode($response);
                                        $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                                    }
                                }
                            }
                        }
                    }
                }
                return $challenge;
            } else {
                file_put_contents('Z_error_message_live.txt', '\n----------- Error  =>' . $error_message, FILE_APPEND | LOCK_EX);
            }
        }
    }

    public function send_media($phone_number = '', $document_link = '', $attachment = '', $attachment_name = '', $caption = '') {
        if (empty($phone_number)) {
            $phone_number = '919537800320';
            $document_link = 'https://official.thebrandingmonk.com/upload/message_attach_image/2023_11_21_19_12_25_20240129033140.mp4';
            $attachment = '2023_11_21_19_12_25_20240129033140.mp4';
            $attachment_name = '2023_11_21_19_12_25_20240129033140.mp4';
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => '108268772303530',
                'access_token' => 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze',
            ]);
        }

        $image_type = substr($attachment, strrpos($attachment, '.') + 1);
        $phone_number = ($phone_number == '') ? $this->phone_number : $phone_number;
        $link_id = new LinkID($document_link);
        $attachment_name = ($attachment_name != '') ? $attachment_name : $attachment;
        if ($this->whatsapp_app_cloud_api) {
            try {

                if ($image_type == 'png' || $image_type == 'jpeg' || $image_type == 'jpg') {
                    $response = $this->whatsapp_app_cloud_api->sendImage($phone_number, $link_id, $caption);
                } elseif ($image_type == 'aac' || $image_type == 'amr' || $image_type == 'mp3' || $image_type == 'm4a' || $image_type == 'ogg') {
                    $response = $this->whatsapp_app_cloud_api->sendAudio($phone_number, $link_id);
                } elseif ($image_type == 'mp4' || $image_type == '3gp') {
                    $response = $this->whatsapp_app_cloud_api->sendVideo($phone_number, $link_id, $caption);
                } else {
                    $response = $this->whatsapp_app_cloud_api->sendDocument($phone_number, $link_id, $attachment_name, $caption);
                }
                if (!empty($response)) {
                    $Exresponse = new ResponseException($response);
                    $responseData = $Exresponse->responseData();
                }
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                $responseData = $e->responseData();
            }
        }
        return $responseData;
    }

    public function send_custom_template($phone_number = '', $list_details = array()) {
        $response = $responseData = '';
        if (empty($phone_number)) {
            $phone_number = '919537800320';
//            $list_details = json_decode('{"id":"41","name":"BTN Custom Template","custom_type":"button","description":"{\"header_text\":\"Hey\",\"body_text\":\"Please reply to us with your opinion\",\"footer_text\":\"Which Noodles is delicious?\",\"action_title\":\"Please Choose\",\"actions\":{\"1\":{\"title\":\"Meggi\"},\"2\":{\"title\":\"Yuppie\"},\"3\":{\"title\":\"Top Ramen\"}}}","user_id":"16"}', true);
            $list_details = json_decode('{"id":"98","name":"Green Tick Eligibility","custom_type":"text","description":"{\"text_details\":\"Hey, How may i help you?\"}","user_id":"16"}', true);
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => '106293179239424',
                'access_token' => 'EAADbkbJS1AsBOZBZClTsTytZBgYlbwA50b3efNjwX7dLRL4mGEi3Yu8IqkXod7sPdUhct5xBCF0HXFaSdtvyMiQz8L6uaQNLWvUitgoXdWd8ovFwtCKdI0oQMGgD4UvCZBWfBeUOfBZBaPZCEv3epH4zbZCq2OHex1ZBG0fwVTNlpZC85xRuE7jtjZCLYVun0elPwSAt4le2ZBE8I1RtHmv',
            ]);
        }
        if (isset($list_details) && !empty($list_details)) {

            $desc = json_decode($list_details['description'], true);
            if (!empty($desc)) {
                $chat_logs = array(
                    'user_id' => isset($list_details['user_id']) ? $list_details['user_id'] : 0,
                    'from_user' => '0',
                    'phone_number' => $phone_number,
                    'message_type' => $list_details['custom_type'],
                    //'message_id' => $response_message_id,
                    'created' => date('Y-m-d H:i:s')
                );



                if ($list_details['custom_type'] == 'text') {
                    $text_details = isset($desc['text_details']) ? $desc['text_details'] : '';
                    if (!empty($text_details)) {
                        //$response_json = $this->whatsapp_app_cloud_api->sendTextMessage($phone_number, $text_details);

                        $chat_logs['message'] = $text_details;

                        try {
                            $response = $this->whatsapp_app_cloud_api->sendTextMessage($phone_number, $text_details);
                            if (!empty($response)) {
                                $Exresponse = new ResponseException($response);
                                $responseData = $Exresponse->responseData();
                                $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                            }
                        } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                            $responseData = $e->responseData();
                        }

                        /* $response_message_id = '';
                          if (isset($response_json) && !empty($response_json)) {
                          if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
                          $body = $response_json->body();
                          $response_body = (!empty($body)) ? json_decode($body, true) : array();
                          $response_message_id = $response_body['messages'][0]['id'];
                          $chat_logs = array(
                          'user_id' => isset($list_details['user_id']) ? $list_details['user_id'] : 0,
                          'from_user' => '0',
                          'phone_number' => $phone_number,
                          'message_type' => $list_details['custom_type'],
                          'message_id' => $response_message_id,
                          'message' => $text_details,
                          'created' => date('Y-m-d H:i:s')
                          );
                          $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                          }
                          } */
                    }
                } else {
                    $header_text = isset($desc['header_text']) ? $desc['header_text'] : '';
                    $body_text = isset($desc['body_text']) ? $desc['body_text'] : '';
                    $footer_text = isset($desc['footer_text']) ? $desc['footer_text'] : '';
                    $action_title = isset($desc['action_title']) && !empty($desc['action_title']) ? $desc['action_title'] : 'Options';
                    $actions = isset($desc['actions']) ? $desc['actions'] : '';
                    if (!empty($actions)) {
                        $chat_logs['message'] = json_encode($desc);

                        if ($list_details['custom_type'] == 'list') {
                            $rows = array();
                            foreach ($actions as $key => $action) {
                                $rows[$key] = new Row($key, $action['title'], $action['description']);
                            }
                            $sections = [new Section('Actions', $rows)];
                            $action = new Action($action_title, $sections);

                            try {
                                $response = $this->whatsapp_app_cloud_api->sendList(
                                        $phone_number, $header_text, $body_text, $footer_text, $action
                                );

                                if (!empty($response)) {
                                    $Exresponse = new ResponseException($response);
                                    $responseData = $Exresponse->responseData();
                                    $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                                }
                            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                                $responseData = $e->responseData();
                            }
                        } else if ($list_details['custom_type'] == 'button') {
                            $rows = array();
                            foreach ($actions as $key => $action) {
                                $rows[$key] = new Button($key, $action['title']);
                            }
                            $action = new ButtonAction($rows);

                            try {
                                $response = $this->whatsapp_app_cloud_api->sendButton(
                                        $phone_number, $body_text, $action, $header_text, $footer_text
                                );
                                if (!empty($response)) {
                                    $Exresponse = new ResponseException($response);
                                    $responseData = $Exresponse->responseData();
                                    $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                                }
                            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                                $responseData = $e->responseData();
                            }
                        }
                    }
                }
            }

            $chat_logs['api_response'] = !empty($responseData) ? json_encode($responseData) : '';
            $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
        }

        return $responseData;
    }

    public function send_template($post_array = array()) {
        $response = '';
        if (!empty($post_array)) {
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
            try {
                $response = $this->whatsapp_app_cloud_api->sendTemplate($post_array['to'], $post_array['template'], $post_array['language'], $components);
                if (!empty($response)) {
                    $Exresponse = new ResponseException($response);
                    $responseData = $Exresponse->responseData();
                }
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                $responseData = $e->responseData();
            }
            return $responseData;
        }
    }

}
