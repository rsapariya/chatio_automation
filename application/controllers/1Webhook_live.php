<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;

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
//                file_put_contents('Z_MessageText_live.txt', 'Message ----------' . json_encode($response['entry'][0]['changes'][0]['value']['messages'][0]), FILE_APPEND | LOCK_EX);
                $message = '';
                if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['text'])) {
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['button'])) {
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0]['interactive'])) {
                    $type = $response['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['type'];
                    $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['interactive'][$type]['title'];
                } else if (empty($message) && isset($response['entry'][0]['changes'][0]['value']['messages'][0])) {
                    $ttype = $response['entry'][0]['changes'][0]['value']['messages'][0]['type'];
                    if (isset($response['entry'][0]['changes'][0]['value']['messages'][0][$ttype]['text']))
                        $message = $response['entry'][0]['changes'][0]['value']['messages'][0][$ttype]['text'];
                }
                file_put_contents('Z_message_live.txt', '------' . $message, FILE_APPEND | LOCK_EX);
                if ($message != '') {
                    $this->phone_number = $response['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                    $this->business_account_id = $response['entry'][0]['id'];
                    $this->from_phone_number_id = $response['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                    $reply_message_datas = $this->ReplyMessage_model->get_trigger_message_attachment($message, $this->business_account_id, $this->from_phone_number_id);
                    file_put_contents('Z_message_live.txt', json_encode($reply_message_datas), FILE_APPEND | LOCK_EX);
                    if (isset($reply_message_datas) && !empty($reply_message_datas)) {
                        $this->access_token = !empty($reply_message_datas['permanent_access_token']) ? $reply_message_datas['permanent_access_token'] : '';
                        $attachments = !empty($reply_message_datas['attachments']) ? json_decode($reply_message_datas['attachments']) : '';
                        $attachments_name = !empty($reply_message_datas['attachments_name']) ? (array) json_decode($reply_message_datas['attachments_name']) : '';
                        if (!empty($attachments)) {
                            if ($this->from_phone_number_id != '' && $this->access_token != '') {
                                $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                                    'from_phone_number_id' => $this->from_phone_number_id,
                                    'access_token' => $this->access_token,
                                ]);
                                foreach ($attachments as $key => $attachment) {
                                    if (is_numeric($attachment)) {
                                        $reply_template_datas = $this->ReplyMessage_model->get_list_templates($reply_message_datas['user_id'], $attachment, true);
                                        $this->send_list_template($this->phone_number, $reply_template_datas);
                                    } else {
                                        $attachment_name = isset($attachments_name[$key]) ? $attachments_name[$key] : '';
                                        $document_link = base_url() . '' . ATTACHMENT_IMAGE_UPLOAD_PATH . '' . $attachment;
                                        file_put_contents('Z_message_live.txt', '\n-----------' . $key . '=>' . $document_link, FILE_APPEND | LOCK_EX);
                                        $this->send_media($this->phone_number, $document_link, $attachment, $attachment_name);
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

    public function send_media($phone_number = '', $document_link = '', $attachment = '', $attachment_name = '') {
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
        file_put_contents('Z_data_live.txt', $image_type . ' =>' . $attachment . '=>' . $document_link, FILE_APPEND | LOCK_EX);
        if ($this->whatsapp_app_cloud_api) {
            if ($image_type == 'pdf') {
                $response = $this->whatsapp_app_cloud_api->sendDocument($phone_number, $link_id, $attachment_name, '');
            } elseif ($image_type == 'mp4') {
                $response = $this->whatsapp_app_cloud_api->sendVideo($phone_number, $link_id);
            } else {
                $response = $this->whatsapp_app_cloud_api->sendImage($phone_number, $link_id);
            }
        }
        return $response;
    }

    public function send_list_template($phone_number = '', $list_details = array()) {
        $response = '';
        if (empty($phone_number)) {
            $phone_number = '919537800320';
            $list_details = json_decode('{"id":"149","name":"Hello","custom_type":"list","description":"{\"header_text\":\"Our Flower Selection\",\"body_text\":\"This is our current flower selection.\",\"footer_text\":\"Select the one in which you are interested.\",\"actions\":{\"1\":{\"title\":\"Red Rose\",\"description\":\"10 Fresh Roses\"},\"2\":{\"title\":\"While Lilies\",\"description\":\"3 Fresh lilies\"},\"3\":{\"title\":\"Tulips\",\"description\":\"5 Fresh Tulips\"}}}","user_id":"16"}', true);
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => '108268772303530',
                'access_token' => 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze',
            ]);
        }
        if (isset($list_details) && !empty($list_details)) {
            $desc = json_decode($list_details['description'], true);
            if (!empty($desc)) {
                $header_text = isset($desc['header_text']) ? $desc['header_text'] : '';
                $body_text = isset($desc['body_text']) ? $desc['body_text'] : '';
                $footer_text = isset($desc['footer_text']) ? $desc['footer_text'] : '';
                $action_title = isset($desc['action_title']) && !empty($desc['action_title']) ? $desc['action_title'] : 'Options';
                $actions = isset($desc['actions']) ? $desc['actions'] : '';
                if (!empty($actions)) {
                    $rows = array();
                    foreach ($actions as $key => $action) {
                        $rows[$key] = new Row($key, $action['title'], $action['description']);
                    }
                    $sections = [new Section('Actions', $rows)];
                    $action = new Action($action_title, $sections);
//                    file_put_contents('Z_template_live.txt', '------------------' . json_encode($sections), FILE_APPEND | LOCK_EX);
//                    file_put_contents('Z_template_live.txt', '------------------' . json_encode($action), FILE_APPEND | LOCK_EX);
                    $response = $this->whatsapp_app_cloud_api->sendList(
                            $phone_number,
                            $header_text,
                            $body_text,
                            $footer_text,
                            $action
                    );
                }
            }
        }
        return $response;
    }

    public function send_template() {
        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => '108268772303530',
            'access_token' => 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze',
        ]);
        $this->whatsapp_app_cloud_api->sendTemplate('919537800320', 'hello_world', 'en_US');
    }

}
