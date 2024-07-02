<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class Whatsapp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->from_phone_number_id = '108268772303530';
        $this->access_token = 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze';
        $this->phone_number = '919081377555';
        $this->load->model(['User_model', 'CMS_model', 'Inquiries_model', 'Recurring_model']);
    }

    public function send_message() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->from_phone_number_id,
            'access_token' => $this->access_token,
        ]);

        $whatsapp_cloud_api->sendTextMessage($this->phone_number, 'Hey Hemadri! I\'m using WhatsApp Cloud API.');
    }

    public function send_template_message() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->from_phone_number_id,
            'access_token' => $this->access_token,
        ]);

        $whatsapp_cloud_api->sendTemplate($this->phone_number, 'hello_world', 'en_US'); // Language is optional
//        $component_header = [];
//
//        $component_body = [
//            [
//                'type' => 'text',
//                'text' => '*Pratik*',
//            ],
//            [
//                "type" => "text",
//                "text" => "Ear Phone"
//            ],
//            [
//                "type" => "text",
//                "text" => "10"
//            ],
//            [
//                "type" => "text",
//                "text" => "Surat"
//            ]
//        ];
//
//        $component_buttons = [];
//
//        $components = new Component($component_header, $component_body, $component_buttons);
//        $whatsapp_cloud_api->sendTemplate($this->phone_number, 'system_cod_order_verification_new', 'en_US', $components); // Language is optional
    }

}

?>