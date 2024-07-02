<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

class Indiamart_cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['CMS_model', 'Indiamart_inquiries_model']);
        $this->glusr_crm_key = '';
    }

    public function get_all_inquiries() {
        $response_daily_json = '';
        $call_running_inquiry = false;
        $daily_inquiry = $this->Indiamart_inquiries_model->get_daily_inquiries();
        $url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2?";
        if (isset($daily_inquiry) && !empty($daily_inquiry)) {

            foreach($daily_inquiry as $daily_inquiry){
                $where = 'user_id = ' . $this->db->escape($daily_inquiry['user_id']);
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1, null, 'id', 'desc');

                $startDate = Date('d-M-Y', strtotime('-1 days'));
                $toDate = Date('d-M-Y');
                

                $this->glusr_crm_key = (isset($user_settings) && !empty($user_settings)) ? $user_settings['crm_key'] : '';
                if (!empty($this->glusr_crm_key)) {
                    $curl_post_data = array(
                        'glusr_crm_key' => $this->glusr_crm_key,
                    );
                    $fields_string = http_build_query($curl_post_data);
                    $url = $url . $fields_string;
                    $CURL_response = $this->curl_api($url);
                    if (!empty($CURL_response)) {
                        $response_json = json_decode($CURL_response);
                        if (!empty($response_json)) {
                            if ($response_json->STATUS == 'SUCCESS') {
                                $json_response = $response_json->RESPONSE;
                                $response_array = isset($json_response) && !empty($json_response) ? $json_response : array();
                                if (isset($response_array) && !empty($response_array)) {
                                    foreach ($response_array as $response) {
                                        $response = (array) $response;
                                        $where = 'query_id = ' . $this->db->escape($response['UNIQUE_QUERY_ID']) . ' AND user_id = ' . $this->db->escape($daily_inquiry['user_id']);
                                        $indiamart_customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');
                                        $insert_array = array(
                                            'user_id' => $daily_inquiry['user_id'],
                                            'inquiry_id' => $daily_inquiry['inquiry_id'],
                                            'query_id' => $response['UNIQUE_QUERY_ID'],
                                            'query_type' => $response['QUERY_TYPE'],
                                            'query_time' => $response['QUERY_TIME'],
                                            'name' => $response['SENDER_NAME'],
                                            'mobile' => $response['SENDER_MOBILE'],
                                            'alternative_mobile' => $response['SENDER_MOBILE_ALT'],
                                            'phone' => $response['SENDER_PHONE'],
                                            'alternative_phone' => $response['SENDER_PHONE_ALT'],
                                            'email' => $response['SENDER_EMAIL'],
                                            'alternative_email' => $response['SENDER_EMAIL_ALT'],
                                            'subject' => $response['SUBJECT'],
                                            'company' => $response['SENDER_COMPANY'],
                                            'address' => $response['SENDER_ADDRESS'],
                                            'city' => $response['SENDER_CITY'],
                                            'state' => $response['SENDER_STATE'],
                                            'pincode' => $response['SENDER_PINCODE'],
                                            'country' => $response['SENDER_COUNTRY_ISO'],
                                            'product_name' => $response['QUERY_PRODUCT_NAME'],
                                            'mcat_name' => $response['QUERY_MCAT_NAME'],
                                            'call_duration' => $response['CALL_DURATION'],
                                            'message' => $response['QUERY_MESSAGE'],
                                            'receiver_mobile' => $response['RECEIVER_MOBILE']
                                        );
                                        if (empty($indiamart_customer_lead)) {
                                            $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);
                                        }
                                    }
                                }
                                $logs = array(
                                    'inquiry_id' => $daily_inquiry['id'],
                                    'fromdate' => date('Y-m-d', strtotime($startDate)),
                                    'todate' => date('Y-m-d', strtotime($toDate)),
                                    'response' => json_encode($json_response),
                                );
                                $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);
                                $update_array['last_run_at'] = Date('Y-m-d H:i:s');
                                $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $daily_inquiry['id']), $update_array);
                            } else {
                                echo '<br/><br/> message : ' . $response_json->MESSAGE;
                            }
                        }
                    }
                } else {
                    $call_running_inquiry = true;
                    echo '<br/> Error : CRM Key is not Found.';
                }
            }
        } else {
            $call_running_inquiry = true;
        }
        if ($call_running_inquiry) {
            $response_json = '';
            $inquiry = $this->Indiamart_inquiries_model->get_running_inquiries();
            

            if (isset($inquiry) && !empty($inquiry)) {
                foreach($inquiry as $inquiry){
                    $where = 'user_id = ' . $this->db->escape($inquiry['user_id']);
                    $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1, null, 'id', 'desc');

                    $from = (isset($inquiry['last_cron_day']) && !empty($inquiry['last_cron_day'])) ? $inquiry['last_cron_day'] : 365;
                    $to = ($from - 6);
                    $to = ($to < 0) ? 0 : $to;
                    $startDate = Date('d-M-Y', strtotime('-' . $from . ' days'));
                    $toDate = Date('d-M-Y', strtotime('-' . $to . ' days'));

                    $this->glusr_crm_key = (isset($user_settings) && !empty($user_settings)) ? $user_settings['crm_key'] : '';
                    if (!empty($this->glusr_crm_key)) {
                        $curl_post_data = array(
                            'glusr_crm_key' => $this->glusr_crm_key,
                            'start_time' => $startDate,
                            'end_time' => $toDate,
                        );
                        $fields_string = http_build_query($curl_post_data);
                        $url = $url . $fields_string;
                        $CURL_response = $this->curl_api($url);
                        if (!empty($CURL_response)) {
                            $response_json = json_decode($CURL_response);
                            if (!empty($response_json)) {
                                if ($response_json->STATUS == 'SUCCESS') {
                                    $json_response = $response_json->RESPONSE;
                                    $response_array = isset($json_response) && !empty($json_response) ? $json_response : array();
                                    if (isset($response_array) && !empty($response_array)) {
                                        foreach ($response_array as $response) {
                                            $response = (array) $response;
                                            $where = 'query_id = ' . $this->db->escape($response['UNIQUE_QUERY_ID']) . ' AND user_id = ' . $this->db->escape($inquiry['user_id']);
                                            $indiamart_customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');
                                            $insert_array = array(
                                                'user_id' => $inquiry['user_id'],
                                                'inquiry_id' => $inquiry['id'],
                                                'query_id' => $response['UNIQUE_QUERY_ID'],
                                                'query_type' => $response['QUERY_TYPE'],
                                                'query_time' => $response['QUERY_TIME'],
                                                'name' => $response['SENDER_NAME'],
                                                'mobile' => $response['SENDER_MOBILE'],
                                                'alternative_mobile' => $response['SENDER_MOBILE_ALT'],
                                                'phone' => $response['SENDER_PHONE'],
                                                'alternative_phone' => $response['SENDER_PHONE_ALT'],
                                                'email' => $response['SENDER_EMAIL'],
                                                'alternative_email' => $response['SENDER_EMAIL_ALT'],
                                                'subject' => $response['SUBJECT'],
                                                'company' => $response['SENDER_COMPANY'],
                                                'address' => $response['SENDER_ADDRESS'],
                                                'city' => $response['SENDER_CITY'],
                                                'state' => $response['SENDER_STATE'],
                                                'pincode' => $response['SENDER_PINCODE'],
                                                'country' => $response['SENDER_COUNTRY_ISO'],
                                                'product_name' => $response['QUERY_PRODUCT_NAME'],
                                                'mcat_name' => $response['QUERY_MCAT_NAME'],
                                                'call_duration' => $response['CALL_DURATION'],
                                                'message' => $response['QUERY_MESSAGE'],
                                                'receiver_mobile' => $response['RECEIVER_MOBILE']
                                            );
                                            if (empty($indiamart_customer_lead)) {
                                                $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);
                                            }
                                        }
                                    }
                                    $logs = array(
                                        'inquiry_id' => $inquiry['id'],
                                        'fromdate' => date('Y-m-d', strtotime($startDate)),
                                        'todate' => date('Y-m-d', strtotime($toDate)),
                                        'response' => json_encode($json_response),
                                    );
                                    $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);
                                    $update_array = array('last_cron_day' => ($to - 1));
                                    if ($inquiry['status'] == 'pending') {
                                        $update_array['status'] = 'running';
                                    }
                                    if ($to <= 0) {
                                        $update_array['status'] = 'success';
                                    }
                                    $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $inquiry['id']), $update_array);
                                } else {
                                    $logs = array(
                                        'inquiry_id' => $inquiry['id'],
                                        'fromdate' => date('Y-m-d', strtotime($startDate)),
                                        'todate' => date('Y-m-d', strtotime($toDate)),
                                        'response' => json_encode($response_json),
                                    );
                                    $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);
    //                            $update_array['status'] = 'error';
    //                            $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $inquiry['id']), $update_array);
                                }
                                $update_array['last_run_at'] = Date('Y-m-d H:i:s');
                                $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $inquiry['id']), $update_array);
                            }
                        }
                    } else {
                        echo '<br/> Error : CRM Key is not Found.';
                    }
                }
            }
        }
    }

    



//    public function get_daily_inquiries() {
//        $response_json = '';
//        $inquiry = $this->Indiamart_inquiries_model->get_daily_inquiries();
//        pr($inquiry, 1);
//        if (isset($inquiry) && !empty($inquiry)) {
//            $where = 'user_id = ' . $this->db->escape($inquiry['user_id']);
//            $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1, null, 'id', 'desc');
//
//            $url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2?";
//            $startDate = Date('d-M-Y', strtotime('-1 days'));
//            $toDate = Date('d-M-Y');
//
//            $this->glusr_crm_key = (isset($user_settings) && !empty($user_settings)) ? $user_settings['crm_key'] : '';
//            if (!empty($this->glusr_crm_key)) {
//                $curl_post_data = array(
//                    'glusr_crm_key' => $this->glusr_crm_key,
//                );
//                $fields_string = http_build_query($curl_post_data);
//                $url = $url . $fields_string;
//                $CURL_response = $this->curl_api($url);
//                if (!empty($CURL_response)) {
//                    $response_json = json_decode($CURL_response);
//                    if (!empty($response_json)) {
//                        if ($response_json->STATUS == 'SUCCESS') {
//                            $response = $response_json->RESPONSE;
//                            $logs = array(
//                                'inquiry_id' => $inquiry['id'],
//                                'fromdate' => date('Y-m-d', strtotime($startDate)),
//                                'todate' => date('Y-m-d', strtotime($toDate)),
//                                'response' => json_encode($response),
//                            );
//                            $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);
//                            $update_array['last_run_at'] = Date('Y-m-d H:i:s');
//                            $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $inquiry['id']), $update_array);
//                        } else {
//                            echo '<br/> message : ' . $response_json->MESSAGE;
//                        }
//                    }
//                }
//            } else {
//                echo '<br/> Error : CRM Key is not Found.';
//            }
//        }
//    }

    public function get_lead_messages_official() {
        $response_json = '';
        $lead_messages = $this->Indiamart_inquiries_model->get_lead_messages();
        if (isset($lead_messages) && !empty($lead_messages)) {
            foreach ($lead_messages as $lead_message) {
                $leads = $this->Indiamart_inquiries_model->get_leads($lead_message['lead_ids']);
                if (isset($leads) && !empty($leads)) {
                    $post_data = array(
                        'from_phone_number_id' => $lead_message['phone_number_id'],
                        'access_token' => $lead_message['permanent_access_token'],
                    );
                    foreach ($leads as $key => $lead) {
                        $mobile = (isset($lead['mobile']) && !empty(isset($lead['mobile']))) ? str_replace('+', '', str_replace('-', '', $lead['mobile'])) : '';
                        $post_data['to'] = $mobile;
//                        $post_data['to'] = '919537800320';
                        $description = $lead_message['message'];
                        if (strstr($description, '||name||')) {
                            $description = str_replace('||name||', $lead['name'], $description);
                        } else if (strstr($description, '||Name||')) {
                            $description = str_replace('||Name||', $lead['name'], $description);
                        }
                        $post_data['message'] = $description;
                        $response_json = $this->send_message($post_data);
                        if (isset($response_json) && !empty($response_json)) {
                            $this->CMS_model->update_record(tbl_indiamart_leads_message, array('id' => $lead_message['id']), array('status' => 'success'));
                            echo "\n\n Message successfully sent to :" . $lead['name'] . ' on number : ' . $lead['mobile'];
                        }
                        sleep(2);
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

    public function get_lead_messages() {
        $url = "https://thebrandingmonk.com/api/send?";
        $response_json = '';
        $lead_messages = $this->Indiamart_inquiries_model->get_lead_messages();
        if (isset($lead_messages) && !empty($lead_messages)) {
            foreach ($lead_messages as $lead_message) {
                $instance_id = $lead_message['instance_id'];
                $access_token = $lead_message['access_token'];
                $leads = $this->Indiamart_inquiries_model->get_leads($lead_message['lead_ids']);

                if (isset($leads) && !empty($leads)) {
                    foreach ($leads as $key => $lead) {
                        $mobile = (isset($lead['mobile']) && !empty(isset($lead['mobile']))) ? str_replace('+', '', str_replace('-', '', $lead['mobile'])) : '';
//                        $mobile = '919537800320';
                        $description = $lead_message['message'];
                        if (strstr($description, '||name||')) {
                            $description = str_replace('||name||', $lead['name'], $description);
                        } else if (strstr($description, '||Name||')) {
                            $description = str_replace('||Name||', $lead['name'], $description);
                        }
                        $curl_post_data = array(
                            'type' => 'text',
                            'message' => $description,
                            'instance_id' => $instance_id,
                            'access_token' => $access_token,
                            'number' => $mobile,
                        );
//                        pr($curl_post_data);
                        $fields_string = http_build_query($curl_post_data);
                        $url = $url . $fields_string;
                        $response_json = $this->curl_api($url);
//                        echo '<br/>' . $url;
//                        pr($response_json);
                        if (isset($response_json) && !empty($response_json)) {
                            $response = json_decode($response_json, true);
                            if (isset($response['status']) && $response['status'] != 'error') {
                                $this->CMS_model->update_record(tbl_indiamart_leads_message, array('id' => $lead_message['id']), array('status' => 'success'));
                                echo "\n\n Message successfully sent to :" . $client['name'] . ' on number : ' . $client['phone_number_full'];
                            } else {
                                echo $response['message'];
                            }
                        }
                        sleep(2);
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

//    public function fetch_leads() {
//        $lead_inquiry = $this->Indiamart_inquiries_model->get_lead_rem_inquiries();
//        $user_id = 31;
//        if (isset($lead_inquiry) && !empty($lead_inquiry)) {
//            foreach ($lead_inquiry as $inquiry) {
//                $json_response = $inquiry['response'];
//                $response_array = isset($json_response) && !empty($json_response) ? (array) json_decode($json_response) : array();
//                if (isset($response_array) && !empty($response_array)) {
//                    foreach ($response_array as $response) {
//                        $response = (array) $response;
//                        $where = 'query_id = ' . $this->db->escape($response['UNIQUE_QUERY_ID']) . ' AND user_id = ' . $this->db->escape($user_id);
//                        $indiamart_customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');
//                        $insert_array = array(
//                            'user_id' => $user_id,
//                            'inquiry_id' => $inquiry['inquiry_id'],
//                            'query_id' => $response['UNIQUE_QUERY_ID'],
//                            'query_type' => $response['QUERY_TYPE'],
//                            'query_time' => $response['QUERY_TIME'],
//                            'name' => $response['SENDER_NAME'],
//                            'mobile' => $response['SENDER_MOBILE'],
//                            'alternative_mobile' => $response['SENDER_MOBILE_ALT'],
//                            'phone' => $response['SENDER_PHONE'],
//                            'alternative_phone' => $response['SENDER_PHONE_ALT'],
//                            'email' => $response['SENDER_EMAIL'],
//                            'alternative_email' => $response['SENDER_EMAIL_ALT'],
//                            'subject' => $response['SUBJECT'],
//                            'company' => $response['SENDER_COMPANY'],
//                            'address' => $response['SENDER_ADDRESS'],
//                            'city' => $response['SENDER_CITY'],
//                            'state' => $response['SENDER_STATE'],
//                            'pincode' => $response['SENDER_PINCODE'],
//                            'country' => $response['SENDER_COUNTRY_ISO'],
//                            'product_name' => $response['QUERY_PRODUCT_NAME'],
//                            'mcat_name' => $response['QUERY_MCAT_NAME'],
//                            'call_duration' => $response['CALL_DURATION'],
//                            'message' => $response['QUERY_MESSAGE'],
//                            'receiver_mobile' => $response['RECEIVER_MOBILE']
//                        );
//                        if (empty($indiamart_customer_lead)) {
//                            $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);
//                        }
//                    }
//                }
//            }
//        }
//    }
}
