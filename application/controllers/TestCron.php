<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

class TestCron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['CMS_model', 'Indiamart_inquiries_model', 'Tradeindia_inquiries_model', 'ReplyMessage_model']);
        $this->glusr_crm_key = '';
        $this->phone_number_id = '';
        $this->permanent_access_token = '';

        date_default_timezone_set('Asia/Kolkata');

        $this->tradeindia_user_id = '';
        $this->tradeindia_profile_id = '';
        $this->tradeindia_key = '';

        $this->exportersindia_key = '';
        $this->exportersindia_email = '';
    }

    public function get_all_inquiries() {

        $users_cred = $this->Indiamart_inquiries_model->get_allowed_crm_users_credential();
        if (!empty($users_cred)) {
            foreach ($users_cred as $uc) {
                $url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2?";

                $this->phone_number_id = $uc['phone_number_id'];
                $this->permanent_access_token = $uc['permanent_access_token'];

                $this->glusr_crm_key = !empty($uc['crm_key']) ? $uc['crm_key'] : '';

                if (!empty($this->glusr_crm_key)) {
                    $curl_post_data = array(
                        'glusr_crm_key' => $this->glusr_crm_key,
                    );
                    $date = date('Y-m-d h:m:s');
                    $fields_string = http_build_query($curl_post_data);
                    $url = $url . $fields_string;
                    $CURL_response = $this->curl_api($url);
                    //$CURL_response = '[{"STATUS":"SUCCESS"}]';


                    if (!empty($CURL_response)) {
                        $response_json = json_decode($CURL_response, true);
                        //$response_json = json_decode('{"STATUS":"SUCCESS"}', true);
                        if (!empty($response_json)) {
                            if ($response_json['STATUS'] == 'SUCCESS') {
                                //$json_response = json_decode('[{"UNIQUE_QUERY_ID":"2769560468","QUERY_TYPE":"W","QUERY_TIME":"2024-05-23 15:44:28","SENDER_NAME":"Shailesh Lathia","SENDER_MOBILE":"+91-9909505001","SENDER_EMAIL":"s.lathia@yahoo.com","SUBJECT":"Requirement for Loctite Industrial Sealant","SENDER_COMPANY":"Bharat Industries","SENDER_ADDRESS":"No. 906\/4, GIDC, Bharuch, Gujarat,         394116","SENDER_CITY":"Bharuch","SENDER_STATE":"Gujarat","SENDER_PINCODE":"394116","SENDER_COUNTRY_ISO":"IN","SENDER_MOBILE_ALT":"","SENDER_PHONE":"","SENDER_PHONE_ALT":"","SENDER_EMAIL_ALT":"bharat.chrome@gmail.com","QUERY_PRODUCT_NAME":"Loctite Industrial Sealant","QUERY_MESSAGE":"I am interested in buying Loctite Industrial Sealant. Kindly send me price and other details.<br>Packaging Size : 10 Gm To 250gm<br>Probable Requirement Type : Business Use<br>","QUERY_MCAT_NAME":"Loctite Adhesive","CALL_DURATION":"","RECEIVER_MOBILE":""}]', true);
                                $json_response = $response_json['RESPONSE'];
                                $response_array = isset($json_response) && !empty($json_response) ? $json_response : array();
                                $send_msg = sizeof($response_array);

                                $user_last_inquiry = $this->Indiamart_inquiries_model->get_inquiries($uc['user_id']);

                                $inquiry_log_id = '';
                                if (!empty($user_last_inquiry)) {
                                    $update_array['last_run_at'] = Date('Y-m-d H:i:s');
                                    $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $user_last_inquiry['id']), $update_array);
                                    $inquiry_log_id = $user_last_inquiry['id'];
                                } else {
                                    $inquiry_log = array(
                                        'user_id' => $uc['user_id'],
                                        'last_cron_day' => -1,
                                        'last_run_at' => date('Y-m-d H:i:s'),
                                        'status' => 'success',
                                        'created' => date('Y-m-d H:i:s')
                                    );
                                    $inquiry_log_id = $this->CMS_model->insert_data(tbl_indiamart_inquiries, $inquiry_log);
                                }

                                $logs = array(
                                    'inquiry_id' => $inquiry_log_id,
                                    'fromdate' => date('Y-m-d', strtotime($date)),
                                    'todate' => date('Y-m-d', strtotime($date)),
                                    'response' => json_encode($json_response),
                                );
                                $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);

                                if (isset($response_array) && !empty($response_array)) {

                                    foreach ($response_array as $response) {
                                        $response = (array) $response;
                                        $where = 'query_id = ' . $this->db->escape($response['UNIQUE_QUERY_ID']) . ' AND leads_source ="indiamart"   AND user_id = ' . $this->db->escape($uc['user_id']);
                                        $indiamart_customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');

                                        $insert_array = array(
                                            'user_id' => $uc['user_id'],
                                            'inquiry_id' => $uc['id'],
                                            'leads_source' => 'indiamart',
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
                                            $lastid = $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);

                                            if (!empty($uc['waba_access']) && !empty($this->phone_number_id) && !empty($this->permanent_access_token)) {
                                                $sender_number = !empty($response['SENDER_MOBILE']) ? str_replace('-', '', $response['SENDER_MOBILE']) : '';

                                                if (!empty($uc['message_on_inquiry'])) {
                                                    /* $respone_body = $this->send_inquiry_msg($uc['user_id'], $sender_number);
                                                      if (!empty($respone_body)) {

                                                      $respone_body['user_id'] = $uc['user_id'];
                                                      $respone_body['lead_id'] = $lastid;
                                                      $respone_body['customer_name'] = $response['SENDER_NAME'];
                                                      $respone_body['created'] = date('Y-m-d H:i:s');
                                                      $this->CMS_model->insert_data(tbl_lead_notify_log, $respone_body);
                                                      } */

                                                    $inuiry_msg_data = array(
                                                        'user_id' => $uc['user_id'],
                                                        'sender_mobile' => $sender_number,
                                                        'lead_id' => $lastid,
                                                        'sender_name' => $response['SENDER_NAME'],
                                                        'lead_source' => 'indiamart'
                                                    );

                                                    $respone_body = $this->send_inquiry_msg($inuiry_msg_data);
                                                }

                                                $receiver_mobile = isset($uc['phone_number']) && !empty($uc['phone_number']) ? $uc['phone_number'] : '';
                                                if (!empty($receiver_mobile) && !empty($uc['forward_inquiry'])) {
                                                    /* $notify_data = [
                                                      'source' => ' on indiamart',
                                                      'receiver_mobile' => $receiver_mobile,
                                                      'sender_mobile' => $response['SENDER_MOBILE'],
                                                      'sender_name' => $response['SENDER_NAME'],
                                                      'subject' => $response['SUBJECT'],
                                                      ]; */

                                                    $notify_data = [
                                                        $receiver_mobile,
                                                        $uc['user_id'],
                                                        [
                                                            $receiver_mobile . ' on indiamart',
                                                            $response['SENDER_NAME'],
                                                            $response['SENDER_MOBILE'],
                                                            $response['SUBJECT'],
                                                        ]
                                                    ];

                                                    $this->notify_receiver($notify_data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                echo '<br/><br/> message : ' . $response_json['MESSAGE'];
                            }
                        }
                    }
                }
            }
        }
    }

    public function get_tradeind_inquiries() {

        $credentials = $this->Indiamart_inquiries_model->get_allowed_crm_users_credential(crm_tradeindia);

        if (!empty($credentials)) {
            foreach ($credentials as $cred) {
                //tradeindia url
                $url = "https://www.tradeindia.com/utils/my_inquiry.html?";

                //tradeindia credentials
                $this->tradeindia_user_id = isset($cred['tradeindia_user_id']) && !empty($cred['tradeindia_user_id']) ? $cred['tradeindia_user_id'] : '';
                $this->tradeindia_profile_id = isset($cred['tradeindia_profile_id']) && !empty($cred['tradeindia_profile_id']) ? $cred['tradeindia_profile_id'] : '';
                $this->tradeindia_key = isset($cred['tradeindia_key']) && !empty($cred['tradeindia_key']) ? $cred['tradeindia_key'] : '';

                //meta credentials
                $this->phone_number_id = $cred['phone_number_id'];
                $this->permanent_access_token = $cred['permanent_access_token'];

                //receiver number
                $receiver_mobile = isset($cred['phone_number']) && !empty($cred['phone_number']) ? $cred['phone_number'] : '';


                if (!empty($this->tradeindia_user_id) && !empty($this->tradeindia_profile_id) && !empty($this->tradeindia_key)) {
                    $curl_post_data = array(
                        'userid' => $this->tradeindia_user_id,
                        'profile_id' => $this->tradeindia_profile_id,
                        'key' => $this->tradeindia_key,
                        'from_date' => date('Y-m-d'),
                        'to_date' => date('Y-m-d')
                    );

                    $fields_string = http_build_query($curl_post_data);
                    $url = $url . $fields_string;
                    $CURL_response = $this->curl_api($url);

                    if (!empty($CURL_response)) {
                        $response_json = json_decode($CURL_response);
                        if (!empty($response_json)) {

                            $inquiry_log_id = '';
                            $user_last_inquiry = $this->Indiamart_inquiries_model->get_inquiries($cred['user_id']);
                            if (!empty($user_last_inquiry)) {
                                $update_array['last_run_at'] = Date('Y-m-d H:i:s');
                                $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $user_last_inquiry['id']), $update_array);
                                $inquiry_log_id = $user_last_inquiry['id'];
                            } else {
                                $inquiry_log = array(
                                    'user_id' => $cred['user_id'],
                                    'last_cron_day' => -1,
                                    'last_run_at' => date('Y-m-d H:i:s'),
                                    'status' => 'success',
                                    'created' => date('Y-m-d H:i:s')
                                );
                                $inquiry_log_id = $this->CMS_model->insert_data(tbl_indiamart_inquiries, $inquiry_log);
                            }

                            $logs = array(
                                'inquiry_id' => $inquiry_log_id,
                                'fromdate' => date('Y-m-d'),
                                'todate' => date('Y-m-d'),
                                'response' => $CURL_response,
                            );
                            $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);

                            foreach ($response_json as $response) {
                                $response = (array) $response;
                                $where = 'query_id = ' . $this->db->escape($response['rfi_id']) . ' AND leads_source ="tradeindia"  AND user_id = ' . $this->db->escape($cred['user_id']);
                                $customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');

                                if (empty($customer_lead)) {
                                    $query_time = date('Y-m-d', strtotime($response['generated_date'])) . ' ' . date('H:i:s', strtotime($response['generated_time']));
                                    $sender_mobile = isset($response['sender_mobile']) && !empty($response['sender_mobile']) ? $response['sender_mobile'] : '';
                                    $sender_mobile_alt = isset($response['sender_other_mobiles']) && !empty($response['sender_other_mobiles']) ? $response['sender_other_mobiles'] : '';
                                    $sender_name = isset($response['sender_name']) && !empty($response['sender_name']) ? $response['sender_name'] : '';
                                    $sender_co = isset($response['sender_co']) && !empty($response['sender_co']) ? $response['sender_co'] : '';
                                    $subject = isset($response['subject']) && !empty($response['subject']) ? $response['subject'] : '';

                                    $insert_array = array(
                                        'user_id' => $cred['user_id'],
                                        'inquiry_id' => $cred['id'],
                                        'leads_source' => 'tradeindia',
                                        'query_id' => $response['rfi_id'],
                                        'query_type' => isset($response['inquiry_type']) && !empty($response['inquiry_type']) ? $response['inquiry_type'] : '',
                                        'name' => $sender_name,
                                        'mobile' => $sender_mobile,
                                        'alternative_mobile' => $sender_mobile_alt,
                                        'email' => isset($response['sender_email']) && !empty($response['sender_email']) ? $response['sender_email'] : '',
                                        'subject' => $subject,
                                        'address' => isset($response['address']) && !empty($response['address']) ? $response['address'] : '',
                                        'city' => isset($response['sender_city']) && !empty($response['sender_city']) ? $response['sender_city'] : '',
                                        'company' => $sender_co,
                                        'state' => isset($response['sender_state']) && !empty($response['sender_state']) ? $response['sender_state'] : '',
                                        'country' => isset($response['sender_country']) && !empty($response['sender_country']) ? $response['sender_country'] : '',
                                        'message' => isset($response['message']) && !empty($response['message']) ? $response['message'] : '',
                                        'product_name' => isset($response['product_name']) && !empty($response['product_name']) ? $response['product_name'] : '',
                                        'receiver_mobile' => isset($response['receiver_mobile']) && !empty($response['receiver_mobile']) ? $response['receiver_mobile'] : '',
                                        'query_time' => $query_time,
                                        'phone' => isset($response['landline_number']) && !empty($response['landline_number']) ? $response['landline_number'] : '',
                                    );
                                    $lastid = $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);

                                    if (!empty($cred['waba_access']) && !empty($this->phone_number_id) && !empty($this->permanent_access_token)) {

                                        $sdr_mobile = '';
                                        if (!empty($cred['message_on_inquiry']) && !empty($sender_mobile) || !empty($sender_mobile_alt)) {
                                            $sdr_mobile = !empty($sender_mobile) ? $sender_mobile : $sender_mobile_alt;

                                            $inuiry_msg_data = array(
                                                'user_id' => $cred['user_id'],
                                                'sender_mobile' => $sdr_mobile,
                                                'lead_id' => $lastid,
                                                'sender_name' => $sender_name,
                                                'lead_source' => 'tradeindia'
                                            );

                                            $respone_body = $this->send_inquiry_msg($inuiry_msg_data);
                                        }

                                        if (!empty($receiver_mobile) && $response['view_status'] == 'UNREAD' && !empty($cred['forward_inquiry'])) {
                                            $sendrname = '-';
                                            if (!empty($sender_name) || !empty($sender_co)) {
                                                $sendrname = !empty($sender_name) ? $sender_name : $sender_co;
                                            }
                                            /* $notify_data = [
                                              'source' => ' on tradeindia',
                                              'receiver_mobile' => $receiver_mobile,
                                              'sender_mobile' => !empty($sdr_mobile) ? $sdr_mobile : '-',
                                              'sender_name' => $sendrname,
                                              'subject' => !empty($subject) ? $subject : '-',
                                              ]; */

                                            $notify_data = [
                                                $receiver_mobile,
                                                $cred['user_id'],
                                                [
                                                    $receiver_mobile . ' on tradeindia',
                                                    $sendrname,
                                                    !empty($sdr_mobile) ? $sdr_mobile : '-',
                                                    !empty($subject) ? $subject : '-',
                                                ]
                                            ];
                                            $this->notify_receiver($notify_data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function get_exportersind_inquiries() {
        $credentials = $this->Indiamart_inquiries_model->get_allowed_crm_users_credential(crm_exportersindia);
        if (!empty($credentials)) {
            foreach ($credentials as $cred) {
                //tradeindia url
                $url = "https://my.exportersindia.com/api-inquiry-detail.php?";

                //tradeindia credentials
                $this->exportersindia_key = isset($cred['exportersindia_key']) && !empty($cred['exportersindia_key']) ? $cred['exportersindia_key'] : '';
                $this->exportersindia_email = isset($cred['exportersindia_email']) && !empty($cred['exportersindia_email']) ? $cred['exportersindia_email'] : '';

                //meta credentials
                $this->phone_number_id = $cred['phone_number_id'];
                $this->permanent_access_token = $cred['permanent_access_token'];

                //receiver number
                $receiver_mobile = isset($cred['phone_number']) && !empty($cred['phone_number']) ? $cred['phone_number'] : '';

                if (!empty($this->exportersindia_key) && !empty($this->exportersindia_email)) {
                    $curl_post_data = array(
                        'k' => $this->exportersindia_key,
                        'email' => $this->exportersindia_email,
                        'date_from' => date('Y-m-d')
                    );

                    $fields_string = http_build_query($curl_post_data);
                    $url = $url . $fields_string;
                    $CURL_response = $this->curl_api($url);

                    if (!empty($CURL_response)) {
                        $response_json = json_decode($CURL_response);
                        if (!empty($response_json)) {

                            $inquiry_log_id = '';
                            $user_last_inquiry = $this->Indiamart_inquiries_model->get_inquiries($cred['user_id']);
                            if (!empty($user_last_inquiry)) {
                                $update_array['last_run_at'] = Date('Y-m-d H:i:s');
                                $this->CMS_model->update_record(tbl_indiamart_inquiries, array('id' => $user_last_inquiry['id']), $update_array);
                                $inquiry_log_id = $user_last_inquiry['id'];
                            } else {
                                $inquiry_log = array(
                                    'user_id' => $cred['user_id'],
                                    'last_cron_day' => -1,
                                    'last_run_at' => date('Y-m-d H:i:s'),
                                    'status' => 'success',
                                    'created' => date('Y-m-d H:i:s')
                                );
                                $inquiry_log_id = $this->CMS_model->insert_data(tbl_indiamart_inquiries, $inquiry_log);
                            }


                            $logs = array(
                                'inquiry_id' => $inquiry_log_id,
                                'fromdate' => date('Y-m-d'),
                                'todate' => date('Y-m-d'),
                                'response' => $CURL_response,
                            );
                            $this->CMS_model->insert_data(tbl_indiamart_inquiry_logs, $logs);

                            foreach ($response_json as $response) {
                                $response = (array) $response;
                                if (isset($response['inq_id'])) {
                                    $where = 'query_id = ' . $this->db->escape($response['inq_id']) . ' AND leads_source ="exportersindia" AND user_id = ' . $this->db->escape($cred['user_id']);
                                    $customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');

                                    if (empty($customer_lead)) {
                                        $query_time = date('Y-m-d', strtotime($response['enq_date'])) . ' ' . date('H:i:s', strtotime($response['enq_date']));
                                        $sender_mobile = isset($response['mobile']) && !empty($response['mobile']) ? $response['mobile'] : '';
                                        $sender_mobile_alt = isset($response['alt_mobile']) && !empty($response['alt_mobile']) ? $response['alt_mobile'] : '';
                                        $sender_name = isset($response['name']) && !empty($response['name']) ? $response['name'] : '';
                                        $sender_co = isset($response['company']) && !empty($response['company']) ? $response['company'] : '';
                                        $subject = isset($response['subject']) && !empty($response['subject']) ? $response['subject'] : '';
                                        $city = isset($response['city']) && !empty($response['city']) ? $response['city'] : '';
                                        $state = isset($response['state']) && !empty($response['state']) ? $response['state'] : '';
                                        $product = isset($response['product']) && !empty($response['product']) ? $response['product'] : '';

                                        $insert_array = array(
                                            'user_id' => $cred['user_id'],
                                            'inquiry_id' => $cred['id'],
                                            'leads_source' => crm_exportersindia,
                                            'query_id' => $response['inq_id'],
                                            'query_type' => isset($response['inq_type']) && !empty($response['inq_type']) ? $response['inq_type'] : '',
                                            'name' => $sender_name,
                                            'mobile' => $sender_mobile,
                                            'alternative_mobile' => $sender_mobile_alt,
                                            'email' => isset($response['email']) && !empty($response['email']) ? $response['email'] : '',
                                            'alternative_email' => isset($response['alt_email']) && !empty($response['alt_email']) ? $response['alt_email'] : '',
                                            'subject' => $subject,
                                            'address' => isset($response['address']) && !empty($response['address']) ? $response['address'] : '',
                                            'city' => $city,
                                            'state' => $state,
                                            'country' => isset($response['country']) && !empty($response['country']) ? $response['country'] : '',
                                            'message' => isset($response['detail_req']) && !empty($response['detail_req']) ? $response['detail_req'] : '',
                                            'product_name' => $product,
                                            'query_time' => $query_time,
                                            'company' => $sender_co,
                                        );
                                        $lastid = $this->CMS_model->insert_data(tbl_indiamart_customer_leads, $insert_array);

                                        if (!empty($cred['waba_access']) && !empty($this->phone_number_id) && !empty($this->permanent_access_token)) {

                                            $sdr_mobile = '';
                                            $sender_mobile_alt = !empty($sender_mobile_alt) ? str_replace('-', '', $sender_mobile_alt) : '';
                                            if (!empty($sender_mobile) || !empty($sender_mobile_alt)) {
                                                $sdr_mobile = !empty($sender_mobile) ? str_replace('-', '', $sender_mobile) : $sender_mobile_alt;
                                                if (!empty($cred['message_on_inquiry'])) {
                                                    /* $respone_body = $this->send_inquiry_msg($cred['user_id'], $sdr_mobile);
                                                      if (!empty($respone_body)) {
                                                      $respone_body['user_id'] = $cred['user_id'];
                                                      $respone_body['lead_id'] = $lastid;
                                                      $respone_body['customer_name'] = $sender_name;
                                                      $respone_body['created'] = date('Y-m-d H:i:s');
                                                      $this->CMS_model->insert_data(tbl_lead_notify_log, $respone_body);
                                                      } */

                                                    $inuiry_msg_data = array(
                                                        'user_id' => $cred['user_id'],
                                                        'sender_mobile' => $sdr_mobile,
                                                        'lead_id' => $lastid,
                                                        'sender_name' => $sender_name,
                                                        'lead_source' => crm_exportersindia
                                                    );

                                                    $respone_body = $this->send_inquiry_msg($inuiry_msg_data);
                                                }
                                            }

                                            if (!empty($receiver_mobile) && !empty($cred['forward_inquiry'])) {
                                                $sendrname = '-';
                                                if (!empty($sender_name) || !empty($sender_co)) {
                                                    $sendrname = !empty($sender_name) ? $sender_name : $sender_co;
                                                }
                                                $notify_data = [
                                                    $receiver_mobile,
                                                    $cred['user_id'],
                                                    [
                                                        $receiver_mobile . ' on exportersindia',
                                                        $sendrname,
                                                        !empty($sdr_mobile) ? $sdr_mobile : '-',
                                                        !empty($subject) ? $subject : '-',
                                                    ]
                                                ];
                                                $this->notify_receiver($notify_data);
                                            }
                                        }
                                    }
                                }
                            }
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

    public function send_inquiry_msg($data = []) {
        if (empty($data)) {
            $data = array(
                'user_id' => 30,
                'sender_mobile' => '+919510482966',
                'sender_name' => 'Testing API',
                'lead_id' => '37627',
                'lead_source' => 'indiamart'
            );
        }

        //if (!empty($user_id) && !empty($sender_mobile)) {
        if (!empty($data)) {
            $user_id = $data['user_id'];
            $sender_mobile = $data['sender_mobile'];

            $chat_logs = array(
                'user_id' => $user_id,
                'from_user' => 0,
                'from_profile_name' => $data['sender_name'],
                'phone_number' => str_replace('+', '', $sender_mobile),
                'message_type' => 'lead_notify',
                'lead_id' => $data['lead_id'],
                'created' => date('Y-m-d H:i:s'),
            );

            $where_inquiry = 'user_id = ' . $user_id . ' AND template_for = "inquiry_template"';
            $inquiry_temp = $this->CMS_model->get_result(tbl_default_templates, $where_inquiry, null, 1);
            if (!empty($inquiry_temp)) {
                $where_settings = ' user_id = ' . $user_id;
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                $template_info = $this->CMS_model->get_result(tbl_templates, 'id =' . $inquiry_temp['template_id'], null, 1);
                if (!empty($user_settings)) {
                    if (!empty($inquiry_temp['template_id']) && !empty($template_info)) {
                        $post_data['template'] = $template_name = $template_info['name'];
                        $post_data['language'] = $template_language = $template_info['temp_language'];
                        $message_arr = array(
                            'template' => array(
                                'name' => $template_name,
                                'language' => array('code' => $template_language)
                            ),
                        );
                        $temp_param = $inquiry_temp['temp_param'];
                        $post_data['components'] = $temp_param;
                        $response = create_template_message($inquiry_temp['id'], 1, $inquiry_temp['template_id'], '', 'default_template', $user_id);
                        //pr($response);
                        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                            'from_phone_number_id' => $user_settings['phone_number_id'],
                            'access_token' => $user_settings['permanent_access_token'],
                        ]);

                        $component_header = $component_body = $component_buttons = array();
                        $components = '';
                        if (!empty($response)) {
                            if (isset($response['HEADER']['parameters'])) {
                                $component_header = (array) $response['HEADER']['parameters'];
                            }
                            if (isset($response['BODY']['parameters'])) {
                                $component_body = (array) $response['BODY']['parameters'];
                            }
                            if (isset($response['BUTTONS'])) {
                                foreach ($response['BUTTONS'] as $button) {
                                    $component_buttons[] = $button;
                                }
                            }
                        }
                        $components = new Component($component_header, $component_body, $component_buttons);
                        $components_arr = [];
                        if (!empty($component_header)) {
                            $components_arr = array('header' => array('parameters' => $component_header));
                        }
                        if (!empty($component_body)) {
                            $components_arr = array('body' => array('parameters' => $component_body));
                        }
                        if (!empty($component_buttons)) {
                            $components_arr = array('buttons' => array('parameters' => $component_buttons));
                        }
                        $message_arr = array_merge($message_arr, $components_arr);
                        $chat_logs['message'] = json_encode($message_arr);
                        try {
                            $wa_response = $this->whatsapp_app_cloud_api->sendTemplate($sender_mobile, $post_data['template'], $post_data['language'], $components);
                            if (!empty($wa_response)) {
                                $Exresponse = new ResponseException($wa_response);
                                $responseData = $Exresponse->responseData();
                                $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                                $chat_logs['message_status'] = !empty($responseData) && isset($responseData['messages'][0]['message_status']) ? $responseData['messages'][0]['message_status'] : '';
                            }
                        } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                            $responseData = $e->responseData();
                        }
                        $chat_logs['api_response'] = !empty($responseData) ? json_encode($responseData) : '';
                        $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
                        return true;
                    }
                }
            }
        }
    }

    public function notify_receiver($notify_data = []) {
        if (!empty($notify_data)) {

            $user_id = $notify_data[1];
            $cred = $this->CMS_model->get_result(tbl_user_settings, 'user_id =' . $user_id, null, 1);

            $this->phone_number_id = $cred['phone_number_id'];
            $this->permanent_access_token = $cred['permanent_access_token'];

            if (!empty($this->phone_number_id) && !empty($this->permanent_access_token)) {
                $fwhere = 'user_id = ' . $user_id . ' and name = "forward_1" and is_deleted != 1';
                $template_info = $this->CMS_model->get_result(tbl_templates, $fwhere, null, 1);

                $template_name = $template_info['name'];
                $template_language = $template_info['temp_language'];
                $template_format = json_decode($template_info['description'], 1);


                if (!empty($template_info)) {
                    $whatsapp_cloud_api = new WhatsAppCloudApi([
                        'from_phone_number_id' => $this->phone_number_id,
                        'access_token' => $this->permanent_access_token,
                    ]);

                    $component_header = $component_body = $component_buttons = [];

                    foreach ($template_format as $index => $value) {
                        if ($value['type'] == 'BODY') {
                            if (isset($value['example'])) {

                                if (isset($value['example']['body_text'][0])) {
                                    foreach ($value['example']['body_text'][0] as $idd => $dd) {
                                        $component_body[] = [
                                            'type' => 'text',
                                            'text' => $notify_data[2][$idd]
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    $components = new Component($component_header, $component_body, $component_buttons);
                    $wa_response = $whatsapp_cloud_api->sendTemplate('+91' . $notify_data[0], $template_name, $template_language, $components);
                    //$whatsapp_cloud_api->sendTemplate('+919510482966', $template_name, $template_language, $components);
                }
            }
        }
    }

    public function testing_msg() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => '218390051360010',
            'access_token' => 'EAAVZCbpuIeqkBOwDEnunMoiRqgS5s4TuwpuZBtee7QMdGSPST5kzKbohVF8Ldnbp6uIQwVElqsYtcXOzSAjCkkS7RpQBK1T59EkL4FomZCazxyTJg3Y1qtpNkt7a9JTjyBcnhuZC9mtRvNx6EstQTEFsw0oP9tfdQekb6ihXq5l3BNAfZBIebZBYj8Y67tksXG',
        ]);
        $component_header = [];
        $component_body = [
            [
                "type" => "text",
                "text" => "Rashmikant"
            ],
            [
                "type" => "text",
                "text" => "12PM"
            ]
        ];
        $component_buttons = [];
        $components = new Component($component_header, $component_body, $component_buttons);
        $whatsapp_cloud_api->sendTemplate('+919510482966', 'confirm_meeting', 'en', $components);
    }

    function send_template() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => '265225076680517',
            'access_token' => 'EAAZAZCieHoCEIBO6R1twZB7jVJWnC8riv0COplYt3LZCh2tg5lu8ZC6ccBLJpuk1KVhwpZAVJGVL2DnBDM94luP2bPAGJkBD5nBBq9dx3vCshRyMEbpdP0jm6JjJXjmNbi5vonnNwNCJwifrD3GQMm4jHZBmp7WRLh7nsaW7JHuMH9a0DkozQK2vV8qIDi11AcX',
        ]);
        $whatsapp_cloud_api->sendTemplate('+919537800320', 'hello_world', 'en_US');
    }

    function downloadMedia() {
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => '265225076680517',
            'access_token' => 'EAAZAZCieHoCEIBO6R1twZB7jVJWnC8riv0COplYt3LZCh2tg5lu8ZC6ccBLJpuk1KVhwpZAVJGVL2DnBDM94luP2bPAGJkBD5nBBq9dx3vCshRyMEbpdP0jm6JjJXjmNbi5vonnNwNCJwifrD3GQMm4jHZBmp7WRLh7nsaW7JHuMH9a0DkozQK2vV8qIDi11AcX',
        ]);

        $waimgId = 'wamid.HBgMOTE5NTEwNDgyOTY2FQIAEhggNzQ5MTcxQUU1MDFCQ0UyMTJCRjEwMTA1RTc4QzM0Q0EA';
        $whatsapp_cloud_api->sendTextMessage('+919510482966', $waimgId);
        $media_id = new MediaObjectID($waimgId);
        //pr($media_id, 1);
        $response = $whatsapp_cloud_api->downloadMedia($media_id->value());

        pr($response, 1);
        //$response = $whatsapp_cloud_api->downloadMedia('830796188464611');

        $Exresponse = new ResponseException($response);
        $body = $Exresponse->body();
        pr($body, 1);
    }

}
