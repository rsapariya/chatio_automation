<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

//use Netflie\WhatsAppCloudApi\WebHook;

class Webhook extends CI_Controller {

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

            $file = 'log_live.txt';
            file_put_contents($file, $data_content, FILE_APPEND | LOCK_EX);
            $message = '';
            if (isset($response['entry'][0]['changes'][0]['value']['messages'][0]['text'])) {
                $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
            } elseif (isset($response['entry'][0]['changes'][0]['value']['messages'][0]['interactive'])) {
                $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['title'];
            } elseif (isset($response['entry'][0]['changes'][0]['value']['messages'][0]['button'])) {
                $message = $response['entry'][0]['changes'][0]['value']['messages'][0]['button']['text'];
            }
            if ($message != '') {
                $this->phone_number = $response['entry'][0]['changes'][0]['value']['messages'][0]['from'];
                $this->business_account_id = $response['entry'][0]['id'];
                $this->from_phone_number_id = $response['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
                $reply_message_datas = $this->ReplyMessage_model->get_trigger_message_attachment($message, $this->business_account_id, $this->from_phone_number_id);
                file_put_contents('message_live.txt', json_encode($reply_message_datas), FILE_APPEND | LOCK_EX);
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
                                $attachment_name = isset($attachments_name[$key]) ? $attachments_name[$key] : '';
                                file_put_contents('message_live.txt', $key . '=>' . $attachment_name, FILE_APPEND | LOCK_EX);
                                $document_link = base_url() . '' . ATTACHMENT_IMAGE_UPLOAD_PATH . '' . $attachment;
                                $this->send_media($this->phone_number, $document_link, $attachment, $attachment_name);
                            }
                        }
                    }
                }
            }

            return $challenge;
        }
    }

    function send_media($phone_number = '', $document_link = '', $attachment = '', $attachment_name = '') {
        $image_type = substr($attachment, strrpos($attachment, '.') + 1);
        $phone_number = ($phone_number == '') ? $this->phone_number : $phone_number;
        $link_id = new LinkID($document_link);
        file_put_contents('data_live.txt', $link_id, FILE_APPEND | LOCK_EX);
        $attachment_name = ($attachment_name != '') ? $attachment_name : $attachment;
        if ($this->whatsapp_app_cloud_api) {
            if ($image_type == 'pdf') {
                $response = $this->whatsapp_app_cloud_api->sendDocument($phone_number, $link_id, $attachment_name, '');
            } elseif ($image_type == 'mp4') {
                $response = $this->whatsapp_app_cloud_api->sendVideo($phone_number, $link_id, $attachment_name, '');
            } else {
                $response = $this->whatsapp_app_cloud_api->sendImage($phone_number, $link_id);
            }
        }
    }

    function send_template() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->from_phone_number_id,
            'access_token' => $this->access_token,
        ]);
        $this->whatsapp_app_cloud_api->sendTemplate($this->phone_number, 'hello_world', 'en_US');
    }

    function send_api_response($message = 'Bret') {
        $business_account_id = '104482029413792';
        $res = get_json_api_data($message, $business_account_id);
        pr($res);
    }

    function send_chatboat_response($message = 'Media') {
        $business_account_id = '213697808496918';
        $res = $this->ReplyMessage_model->get_trigger_chatboat_message($message, $business_account_id);
    }

}
