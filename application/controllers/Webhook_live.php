<?php

defined('BASEPATH') or exit('No direct script access allowed');

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

            file_put_contents('Z_log_live.txt', $data_content . PHP_EOL, FILE_APPEND | LOCK_EX);

            $error_message = '';
            if (isset($response['entry'][0]['changes'][0]['value']['statuses'][0]['errors'])) {
                $error_message = $response['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]['title'];
            }
            if (empty($error_message)) {
                $message = $media_url = $caption = '';
                $save_record = false;
                $save_chat_record = $save_media = false;
                $save_chat_record_type = 'text';
                $from_message_id = $reply_message_id = '';
                $from_message_id = $response['entry'][0]['changes'][0]['value']['messages'][0]['id'];
                $reply_message_id = $response['entry'][0]['changes'][0]['value']['messages'][0]['context']['id'];

//                $send_api_response = false;
//                $send_chatboat_response = false;
                if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['text'])) {
                    $save_chat_record_type = 'text';
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
//                    $send_api_response = true;
//                    $send_chatboat_response = true;

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
                    $profile_name = $response['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];

                    $row = $this->db->get_where(tbl_wh_response, array('message_id' => $wamid));
                    $response_exists = $row->row_array();

                    //file_put_contents('Z_response_exist.txt', !empty($response_exists) ? 'yes' . $response_exists['id'] : 'no' . PHP_EOL, FILE_APPEND | LOCK_EX);
                    $wh_response = array(
                        'message_id' => $wamid,
                    );
                    if (!empty($response_exists)) {
                        if ($status == 'sent') {
                            $wh_response['sent_time'] = date('Y-m-d H:i:s', $timestamp);
                            if ($response_exists['message_status'] !== 'read' && $response_exists['message_status'] !== 'delivered') {
                                $wh_response['message_status'] = $status;
                            }
                        } else if ($status == 'delivered') {
                            $wh_response['deliver_time'] = date('Y-m-d H:i:s', $timestamp);
                            if ($response_exists['message_status'] !== 'read') {
                                $wh_response['message_status'] = $status;
                            }
                        } else if ($status == 'read') {
                            $wh_response['read_time'] = date('Y-m-d H:i:s', $timestamp);
                            $wh_response['message_status'] = $status;
                        } else if ($status == 'failed') {
                            $wh_response['message_status'] = $status;
                        }
                        //file_put_contents('Z_response_data.txt', 'update =>'.date('Y-m-d H:i:s').' id=>'.$response_exists['id'].' status=>'.$status.' time=>'.date('Y-m-d H:i:s', $timestamp).PHP_EOL, FILE_APPEND | LOCK_EX);
                        $this->CMS_model->update_record(tbl_wh_response, array('id' => $response_exists['id']), $wh_response);
                        //file_put_contents('Z_response_query.txt', $this->db->last_query().PHP_EOL, FILE_APPEND | LOCK_EX);
                    } else {

                        if ($status == 'sent') {
                            $wh_response['sent_time'] = date('Y-m-d H:i:s', $timestamp);
                        } else if ($status == 'delivered') {
                            $wh_response['deliver_time'] = date('Y-m-d H:i:s', $timestamp);
                        } else if ($status == 'read') {
                            $wh_response['read_time'] = date('Y-m-d H:i:s', $timestamp);
                            $wh_response['message_status'] = $status;
                        }
                        //file_put_contents('Z_response_data.txt', 'insert =>' . date('Y-m-d H:i:s') . ' status=>' . $status . ' time=>' . date('Y-m-d H:i:s', $timestamp) . PHP_EOL, FILE_APPEND | LOCK_EX);
                        $this->CMS_model->insert_data(tbl_wh_response, $wh_response);
                        //file_put_contents('Z_response_query.txt', $this->db->last_query().PHP_EOL, FILE_APPEND | LOCK_EX);
                    }


                    /*
                      $row = $this->db->get_where(tbl_chat_logs, array('message_id' => $wamid));
                      $message_exists = $row->row_array();

                      if (!empty($message_exists)) {
                      $updateStatus['from_profile_name'] = $profile_name;
                      if ($status == 'sent') {
                      $updateStatus['sent_time'] = date('Y-m-d H:i:s', $timestamp);
                      }
                      if ($status == 'delivered') {
                      $updateStatus['deliver_time'] = date('Y-m-d H:i:s', $timestamp);
                      $updateStatus['message_status'] = $status;
                      }

                      if ($status == 'read') {
                      $updateStatus['read_time'] = date('Y-m-d H:i:s', $timestamp);
                      $updateStatus['message_status'] = $status;
                      }

                      if ($status == 'failed') {
                      $updateStatus['message_status'] = $status;
                      }

                      $this->CMS_model->update_record(tbl_chat_logs, array('id' => $message_exists['id']), $updateStatus);
                      }

                      $camp_row = $this->db->get_where(tbl_campaign_queue, array('message_id' => $wamid));
                      $campaign_message_exists = $camp_row->row_array();

                      if (!empty($campaign_message_exists)) {

                      if ($status == 'delivered') {
                      $updateCampStatus['deliver_time'] = date('Y-m-d H:i:s', $timestamp);
                      $updateCampStatus['message_status'] = $status;
                      }
                      if ($status == 'read') {
                      $updateCampStatus['read_time'] = date('Y-m-d H:i:s', $timestamp);
                      $updateCampStatus['message_status'] = $status;
                      }
                      if ($status == 'failed') {
                      $updateCampStatus['message_status'] = $status;
                      }
                      $this->CMS_model->update_record(tbl_campaign_queue, array('id' => $campaign_message_exists['id']), $updateCampStatus);
                      } */
                } else if (isset($response['entry'][0]['changes'][0]['value']['messages'][0]['type'])) {
                    $res_arr = $response['entry'][0]['changes'][0]['value']['messages'][0];
                    if (!empty($res_arr['type'])) {
                        $type = $res_arr['type'];
                        $message = $res_arr[$type]['id'];
                        $mime_type = $res_arr[$type]['mime_type'];
                        $caption = isset($res_arr[$type]['caption']) && !empty($res_arr[$type]['caption']) ? $res_arr[$type]['caption'] : '';
                        $save_media = true;
                        $save_chat_record_type = $type;
                    }
                }

                $profile_name = '';

                $chat_logs = $media_arr = array();
                if ($message != '') {
                    $this->phone_number = $response['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                    $this->business_account_id = $response['entry'][0]['id'];
                    $this->from_phone_number_id = $response['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                    $profile_name = $response['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
                    $user_datas = $this->ReplyMessage_model->get_user_details($this->business_account_id);

                    $stop_message = strtolower($message);
                    if ($stop_message == 'stop' || $stop_message == 'stop promotion' || $stop_message == 'unsubscribe') {
                        $contact_row = $this->db->get_where(tbl_clients, array('phone_number_full' => $this->phone_number, 'user_id' => $user_datas['user_id']));
                        $contact_row_exists = $contact_row->result_array();
                        if (!empty($contact_row_exists)) {
                            foreach ($contact_row_exists as $contact_exist) {
                                $this->CMS_model->update_record(tbl_clients, array('id' => $contact_exist['id']), array('is_subscribed' => '0'));
                            }
                        }
                    }

                    if (!empty($user_datas)) {
                        //file_put_contents('Z_forward_message_message_recieved.txt', $message.PHP_EOL, FILE_APPEND | LOCK_EX);
                        if (isset($user_datas['forward_text']) && !empty($user_datas['forward_text']) && isset($user_datas['forward_to']) && !empty($user_datas['forward_to'])) {
                            //file_put_contents('Z_forward_message_test.txt', json_encode(array('from' =>$this->phone_number, 'user_id' => $user_datas['user_id'], 'forward_to' =>$user_datas['forward_to'])).PHP_EOL, FILE_APPEND | LOCK_EX);
                            $display_phone_number = $response['entry'][0]['changes'][0]['value']['metadata']['display_phone_number'];
                            $template_arr = $this->db->get_where(tbl_templates, array('name' => 'forward_1', 'user_id' => $user_datas['user_id']))->row_array();
                            if (!empty($template_arr)) {
                                $forward_data = array(
                                    'from_phone_number_id' => $this->from_phone_number_id,
                                    'access_token' => $user_datas['permanent_access_token'],
                                    'to' => $user_datas['forward_to'],
                                    'components' => array(
                                        'template' => array(
                                            'name' => 'forward_1',
                                            'language' => array(
                                                'code' => $template_arr['temp_language']
                                            ),
                                            'components' => array(
                                                array(
                                                    'type' => 'body',
                                                    'parameters' => array(
                                                        array(
                                                            'type' => 'text',
                                                            'text' => $display_phone_number
                                                        ),
                                                        array(
                                                            'type' => 'text',
                                                            'text' => !empty($profile_name) ? $profile_name : '-'
                                                        ),
                                                        array(
                                                            'type' => 'text',
                                                            'text' => $this->phone_number
                                                        ),
                                                        array(
                                                            'type' => 'text',
                                                            'text' => $message
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                );
                                $fid = $this->CMS_model->insert_data(tbl_forward_messages, array('message' => json_encode($forward_data)));
                                //file_put_contents('Z_forward_message_inserted.txt', $fid . PHP_EOL, FILE_APPEND | LOCK_EX);
                                //$forward_response = curlSendTemplate($forward_data);
                            }
                        }

                        if ($save_media && !empty($mime_type)) {
                            $media_arr = array(
                                'user_id' => $user_datas['user_id'],
                                'mime_type' => $mime_type,
                                'media_type' => $save_chat_record_type,
                                'media_id' => $message
                            );
                            $save_chat_record = true;

                            /* $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                              'from_phone_number_id' => $this->from_phone_number_id,
                              'access_token' => $user_datas['permanent_access_token'],
                              ]);

                              $mime_arr = explode('/', $mime_type);

                              $extension = strtolower($mime_arr[1]);
                              if ($save_chat_record_type == 'document' && $extension == 'plain') {
                              $extension = 'txt';
                              }
                              if ($save_chat_record_type == 'document' && $extension == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                              $extension = 'xlxs';
                              }
                              if ($save_chat_record_type == 'document' && $extension == 'vnd.openxmlformats-officedocument.wordprocessingml.document') {
                              $extension = 'docx';
                              }
                              if ($save_chat_record_type == 'document' && $extension == 'vnd.openxmlformats-officedocument.presentationml.presentation') {
                              $extension = 'pptx';
                              }

                              $filename = time() . '.' . $extension;
                              $directory = 'upload/users_media/' . $user_datas['user_id'] . '/';
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

                              $response = $this->whatsapp_app_cloud_api->downloadMedia($message);
                              file_put_contents($output_file, $response->body());

                              if (file_exists($output_file)) {
                              $save_chat_record = true;
                              $media_url = base_url() . $output_file;
                              }
                             */
                        }
                    }

                    if ($save_chat_record) {
                        //$emojiMessage = '';
                        //if ($type == 'button_reply' || $type = 'list_reply') {
                        $emojiMessage = $message;
                        //}
                        /* file_put_contents('Z_message.txt',$message.PHP_EOL, FILE_APPEND | LOCK_EX);


                          if ($save_chat_record_type == 'text') {

                          $url = extract_url($message);
                          if (!empty($url)) {
                          $url_msg = str_replace($url, '#URL#', $message);
                          $decode_message = json_decode('"' . $url_msg . '"');
                          $emojiMessage = str_replace('#URL#', $url, $decode_message);
                          } else {
                          $emojiMessage = json_decode('"' . $message . '"');
                          }

                          } */

                        $chat_logs = array(
                            'user_id' => isset($user_datas['user_id']) ? $user_datas['user_id'] : 0,
                            'from_user' => '1',
                            'from_profile_name' => $profile_name,
                            'phone_number' => $this->phone_number,
                            'message_type' => $save_chat_record_type,
                            'message_id' => $from_message_id,
                            'reply_message_id' => $reply_message_id,
                            'message' => !empty($save_media) ? $caption : $emojiMessage,
                            'media' => $media_url,
                            'api_response' => $data_content,
                            'created' => !empty($timestamp) ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s')
                        );
                        $chat_id = $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);

                        if (!empty($save_media) && !empty($media_arr)) {
                            $media_arr['chat_id'] = $chat_id;
                            $this->CMS_model->insert_data(tbl_temp_media, $media_arr);
                        }
                    }

//                    if ($send_api_response) {
//                        $from_phone_number = $response['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
//                        $API_message_data = get_json_api_data($message, $this->business_account_id);
//                        if (!empty($API_message_data)) {
//                            $this->access_token = !empty($API_message_data['permanent_access_token']) ? $API_message_data['permanent_access_token'] : '';
//                            $API_message_text = !empty($API_message_data['message']) ? $API_message_data['message'] : 'No data found!';
//                            if ($this->from_phone_number_id != '' && $this->access_token != '') {
//                                $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
//                                    'from_phone_number_id' => $this->from_phone_number_id,
//                                    'access_token' => $this->access_token,
//                                ]);
//                                $response = $this->whatsapp_app_cloud_api->sendTextMessage($from_phone_number, $API_message_text);
//                            }
//                        }
//                    }
//                    file_put_contents('Z_chatboat_log.txt', '\n' . $send_chatboat_response, FILE_APPEND | LOCK_EX);
//                    if ($send_chatboat_response) {
//                        $from_phone_number = $response['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
//                        $API_message_data = $this->ReplyMessage_model->get_trigger_chatboat_message($message, $this->business_account_id);
//                        file_put_contents('Z_chatboat_log.txt', '\n' . json_encode($API_message_data), FILE_APPEND | LOCK_EX);
//                        if (!empty($API_message_data)) {
//                            $this->access_token = !empty($API_message_data['permanent_access_token']) ? $API_message_data['permanent_access_token'] : '';
//                            $API_message_response_type = !empty($API_message_data['type']) ? $API_message_data['type'] : '';
//                            $API_message_response = !empty($API_message_data['value']) ? $API_message_data['value'] : '';
//                            if ($this->from_phone_number_id != '' && $this->access_token != '') {
//                                $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
//                                    'from_phone_number_id' => $this->from_phone_number_id,
//                                    'access_token' => $this->access_token,
//                                ]);
//                                if ($API_message_response_type == 'text') {
//                                    $response = $this->whatsapp_app_cloud_api->sendTextMessage($from_phone_number, $API_message_text);
//                                } elseif ($API_message_response_type == 'media') {
//                                    $link_id = new LinkID($API_message_response);
//                                    $this->whatsapp_app_cloud_api->sendImage($this->phone_number, $link_id);
//                                } elseif ($API_message_response_type == 'template') {
//                                    
//                                }
//                            }
//                        }
//                    }

                    $reply_message_arr = $this->ReplyMessage_model->get_trigger_messages($this->business_account_id);
                    if (!empty($reply_message_arr) && !empty($message)) {
                        $matched_keywords = array();
                        $this->access_token = !empty($reply_message_arr['permanent_access_token']) ? $reply_message_arr['permanent_access_token'] : '';
                        unset($reply_message_arr['permanent_access_token']);
                        $trigger_arr = isset($reply_message_arr) && !empty($reply_message_arr) ? $reply_message_arr : '';
                        if (!empty($trigger_arr)) {
                            foreach ($trigger_arr as $trigger) {
                                if (!key_exists($trigger['reply_id'], $matched_keywords)) {
                                    if (stripos($message, $trigger['reply_text']) !== false) {
                                        if ((empty($trigger['trigger_on']) && strtolower($trigger['reply_text']) == strtolower($message)) || strtolower($trigger['reply_text']) == strtolower($message)) {
                                            $matched_keywords[$trigger['reply_id']] = $trigger;
                                        } else if ($trigger['trigger_on'] == 'contains') {
                                            $matched_keywords[$trigger['reply_id']] = $trigger;
                                        }
                                    }
                                }
                            }
                            if (!empty($matched_keywords)) {
                                foreach ($matched_keywords as $matchedK) {
                                    $attachments = !empty($matchedK['attachments']) ? json_decode($matchedK['attachments']) : '';
                                    $attachments_caption = !empty($matchedK['attachments_caption']) ? json_decode($matchedK['attachments_caption'], 1) : '';

                                    $attachments_name = !empty($matchedK['attachments_name']) ? (array) json_decode($matchedK['attachments_name']) : '';
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
                                                    'created' => !empty($timestamp) ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s')
                                                );

                                                if (is_numeric($attachment)) {
                                                    $reply_list_template_datas = $this->ReplyMessage_model->get_list_templates($matchedK['user_id'], $attachment, true);
                                                    $reply_meta_template_datas = $this->ReplyMessage_model->get_meta_templates($matchedK['user_id'], $attachment, true);
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
                                                    if (!empty($response) && isset($response['messages'][0]['id']) && !empty($response['messages'][0]['id'])) {
                                                        $chat_logs['message_id'] = $response['messages'][0]['id'];
                                                    }
                                                    $chat_logs['api_response'] = json_encode($response);
                                                    $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                                                    sleep(1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    /*$reply_message_datas = $this->ReplyMessage_model->get_trigger_message_attachment($message, $this->business_account_id);
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
                                        'created' => !empty($timestamp) ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s')
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
                                        if (!empty($response) && isset($response['messages'][0]['id']) && !empty($response['messages'][0]['id'])) {
                                            $chat_logs['message_id'] = $response['messages'][0]['id'];
                                        }
                                        $chat_logs['api_response'] = json_encode($response);
                                        $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                                        sleep(1);
                                    }
                                }
                            }
                        }
                    }
                    */
                    sleep(1);
                }
                return $challenge;
            } else {
                $wamid = $response['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
                $status = $response['entry'][0]['changes'][0]['value']['statuses'][0]['status'];

                $updateStatus = array(
                    'message_status' => $status,
                    'error_message' => $error_message
                );
                $this->CMS_model->update_record(tbl_chat_logs, array('message_id' => $wamid), $updateStatus);

                $camp_row = $this->db->get_where(tbl_campaign_queue, array('message_id' => $wamid));
                $campaign_message_exists = $camp_row->row_array();
                if (!empty($campaign_message_exists)) {
                    $this->CMS_model->update_record(tbl_campaign_queue, array('id' => $campaign_message_exists['id']), array('message_status' => $status));
                }
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
            $user_id = isset($list_details['user_id']) ? $list_details['user_id'] : 0;
            if (!empty($desc) && !empty($user_id)) {
                $chat_logs = array(
                    'user_id' => $user_id,
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
                } else if ($list_details['custom_type'] == 'contacts') {
                    $responseData = send_contact($list_details['description'], $phone_number, $user_id);
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
                                        $phone_number,
                                        $header_text,
                                        $body_text,
                                        $footer_text,
                                        $action
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
                                        $phone_number,
                                        $body_text,
                                        $action,
                                        $header_text,
                                        $footer_text
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
                $chat_logs['api_response'] = !empty($responseData) ? json_encode($responseData) : '';
                $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
            }
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

    public function sendTextMessage() {
        $response = $this->whatsapp_app_cloud_api->sendTextMessage('919537800320', 'Hey, Hemadri');
    }

}
