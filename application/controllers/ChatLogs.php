<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class ChatLogs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Chatlogs_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of ChatLog list.
     * @author : RR
     */
    public function index() {

        $this->template->set('title', 'Text Logs');
        $this->template->load('default_home', 'Chatlogs/index', $this->data);
    }

    public function getlogs() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Chatlogs_model->get_logs('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $records = $this->Chatlogs_model->get_logs(null, $user_id);
        if (!empty($records)) {
            foreach ($records as $rdk => $rd) {
                $tz_date = '';
                if (!empty($rd['created']) && $rd['created'] != '0000-00-00 00:00:00') {
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['created']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['created'] = !empty($tz_date) ? date('d M Y', strtotime($tz_date)) . '<br/>' . date('h:i a', strtotime($tz_date)) : '';
            }
        }
        $final['data'] = $records;

        echo json_encode($final);
    }

    public function api_log() {
        $this->template->set('title', 'API Logs');
        $this->template->load('default_home', 'Chatlogs/api_log', $this->data);
    }

    public function getApilogs() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Chatlogs_model->get_apilogs('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data = $this->Chatlogs_model->get_apilogs(null, $user_id);

        if (!empty($data)) {
            foreach ($data as $kd => $vd) {
                $final['data'][$kd]['template_name'] = $final['data'][$kd]['template_body'] = '';
                if (!empty($vd['message'])) {
                    $message_arr = json_decode($vd['message'], 1);
                    $template_name = $template_body = '';
                    if (isset($message_arr['template']) && !empty($message_arr['template'])) {
                        $template_name = isset($message_arr['template']['name']) && !empty($message_arr['template']['name']) ? $message_arr['template']['name'] : '';
                        //$template_body = isset($message_arr['template']['name']) && !empty($message_arr['template']['name']) ? $message_arr['template']['name'] : '';
                    }
                    $final['data'][$kd]['template_name'] = $template_name;
                    $final['data'][$kd]['template_body'] = $template_body;
                }

                $tz_create_date = '';
                if (!empty($vd['created']) && $vd['created'] != '0000-00-00 00:00:00') {
                    $create_date = date('Y-m-d H:i:s', strtotime($vd['created']));
                    $tz_create_date = getTimeBaseOnTimeZone($create_date);
                }
                $final['data'][$kd]['created'] = !empty($tz_create_date) ? date('d M Y', strtotime($tz_create_date)) . ' ' . date('h:i a', strtotime($tz_create_date)) : '';

                $tz_deliver_time = '';
                if (!empty($vd['deliver_time']) && $vd['deliver_time'] != '0000-00-00 00:00:00') {
                    $deliver_time = date('Y-m-d H:i:s', strtotime($vd['deliver_time']));
                    $tz_deliver_time = getTimeBaseOnTimeZone($deliver_time);
                }
                $final['data'][$kd]['deliver_time'] = !empty($tz_deliver_time) ? date('d M Y', strtotime($tz_deliver_time)) . ' ' . date('h:i a', strtotime($tz_deliver_time)) : '';

                $tz_read_time = '';
                if (!empty($vd['read_time']) && $vd['read_time'] != '0000-00-00 00:00:00') {
                    $read_time = date('Y-m-d H:i:s', strtotime($vd['read_time']));
                    $tz_read_time = getTimeBaseOnTimeZone($read_time);
                }
                $final['data'][$kd]['read_time'] = !empty($tz_read_time) ? date('d M Y', strtotime($tz_read_time)) . ' ' . date('h:i a', strtotime($tz_read_time)) : '';
            }
        }
        echo json_encode($final);
    }

    public function createExcel() {
        $response = array();
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $source = $this->input->post('source');
        $data_arr = array(
            'query_time' => $this->input->post('query_time'),
            'user_id' => $user_id
        );
        $logs_details = $this->Chatlogs_model->get_filtered_leads($data_arr);

        if (isset($logs_details) && !empty($logs_details)) {
            $fileName = date('Ymdhis') . '_textlog.xlsx';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Phone Number');
            $sheet->setCellValue('D1', 'Message');
            $sheet->setCellValue('E1', 'Created');
            $rows = 2;
            foreach ($logs_details as $key => $val) {
                $sheet->setCellValue('A' . $rows, ++$key);
                $sheet->setCellValue('B' . $rows, $val['from_profile_name']);
                $sheet->setCellValue('C' . $rows, $val['phone_number']);
                $sheet->getStyle('C' . $rows)->getNumberFormat()->setFormatCode('000000000000');
                $sheet->setCellValue('D' . $rows, $val['message']);
                $sheet->setCellValue('E' . $rows, $val['created']);
                $rows++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save("upload/" . $fileName);
            $response = array(
                'status' => true,
                'link' => base_url() . "/upload/" . $fileName
            );
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Invalid request. Please try again!'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function createApiLogExcel() {
        $response = array();
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $source = $this->input->post('source');
        $data_arr = array(
            'query_time' => $this->input->post('query_time'),
            'user_id' => $user_id
        );
        $logs_details = $this->Chatlogs_model->get_filtered_api($data_arr);

        if (isset($logs_details) && !empty($logs_details)) {
            $fileName = date('Ymdhis') . '_textlog.xlsx';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Phone Number');
            $sheet->setCellValue('C1', 'Message Type');
            $sheet->setCellValue('D1', 'Status');
            $sheet->setCellValue('E1', 'Created');
            $sheet->setCellValue('F1', 'Deliver Time');
            $sheet->setCellValue('G1', 'Read Time');
            $rows = 2;
            foreach ($logs_details as $key => $val) {
                $sheet->setCellValue('A' . $rows, ++$key);
                $sheet->setCellValue('B' . $rows, $val['phone_number']);
                $sheet->getStyle('B' . $rows)->getNumberFormat()->setFormatCode('000000000000');
                $sheet->setCellValue('C' . $rows, $val['message_type']);
                $sheet->setCellValue('D' . $rows, $val['message_status']);
                $sheet->setCellValue('E' . $rows, $val['created']);
                $sheet->setCellValue('F' . $rows, $val['deliver_time']);
                $sheet->setCellValue('G' . $rows, $val['read_time']);
                $rows++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save("upload/" . $fileName);
            $response = array(
                'status' => true,
                'link' => base_url() . "/upload/" . $fileName
            );
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Invalid request. Please try again!'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function chat_live() {
        $this->template->set('title', 'Chat Live');
        if ($this->data['user_data']['type'] == 'member') {
            $user_id = $this->data['user_data']['added_by'];
        } else {
            $user_id = $this->data['user_data']['id'];
        }

        $contacts = $this->get_contacts();
        
        $this->data['contacts'] = $this->load->view('Chatlogs/contact-list', array('contacts' => $contacts), TRUE);
        //$this->data['contacts'] = $contacts;
        $this->data['total_contact'] = count_user_replied_contacts();
        $this->data['listed_contact'] = count($contacts);
        $this->template->load('default_home', 'Chatlogs/chat_live', $this->data);
    }

    function get_contacts($filter = [], $count= '') {
        
        $customers = $this->Chatlogs_model->get_customers($filter, $count);
        echo $this->db->last_query();
        exit();
        if(empty($count) && !empty($customers)){
            foreach ($customers as $key => $cust) {
                $customers[$key]['message_type'] = '';
                $customers[$key]['message'] = '';
                $date = !empty($cust['created']) ? getTimeBaseOnTimeZone($cust['created']) : '';
                $time = '';
                if (!empty($date)) {
                    $diff = date_diff(date_create(date('Y-m-d', strtotime($date))), date_create(date('Y-m-d')));
                    if ($diff->days == 0) {
                        $time = !empty($date) ? date('h:i a', strtotime($date)) : '';
                    } else if ($diff->days == 1) {
                        $time = 'Yesterday';
                    } else if ($diff->days > 1 && $diff->days < 7) {
                        $time = date('l', strtotime($date));
                    } else {
                        $time = date('Y/m/d', strtotime($date));
                    }
                }
                $customers[$key]['created'] = $time;
                $last_message_arr = $this->Chatlogs_model->get_last_messages($cust['phone_number']);
                if (!empty($last_message_arr)) {
                    $customers[$key]['message_type'] = !empty($last_message_arr['message_type']) ? $last_message_arr['message_type'] : '';
                    $customers[$key]['message'] = !empty($last_message_arr['message']) ? $last_message_arr['message'] : '';
                }
                /*$unsread_message = $this->Chatlogs_model->get_unread_messages_count($cust['phone_number']);
                //echo $this->db->last_query();
                if (!empty($unsread_message)) {
                    $customers[$key]['unread_message'] = !empty($unsread_message['unread_message']) ? $unsread_message['unread_message'] : '';
                }*/
            }
            
        }
        return $customers;
    }

    public function contact_filter() {
        
        $offset = $this->input->post('start');
        $filter = array(
            'search' => $this->input->post('search'),
            'limit' => $this->input->post('limit'),
            'start' => $offset,
        );
        
        //$active_contact = $this->input->post('active');
        $contacts = $this->get_contacts($filter);
        
        //$data['contacts'] = $this->load->view('Chatlogs/contact-list', array('contacts' => $contacts, 'active_contact' => $active_contact,'offset' => $offset), TRUE);
        $data['contacts'] = $contacts;
        $data['total_contact'] =  $this->get_contacts($filter, true);
        echo json_encode($data);
    }
    
    public function load_more(){
        $offset = $this->input->post('start');
        $filter = array(
            'limit' => $this->input->post('limit'),
            'start' => $offset,
        );
        $data['contacts'] = $this->get_contacts($filter);
        $data['total_contact'] =  $this->get_contacts($filter, true);
        echo json_encode($data);
    }

    public function get_chat() {
       //$this->load->add_package_path(FCPATH.'application/vendor/romainrg/ratchet_client');
        //$this->load->library('ratchet_client');
        //$this->load->remove_package_path(FCPATH.'application/vendor/romainrg/ratchet_client');
        
        
        $user_id = $this->session->userdata('id');

        $contact = $this->input->post('contact');
        $offset = !empty($this->input->post('offset')) ? $this->input->post('offset') : 0;

        $chatArr = $this->Chatlogs_model->get_messages($contact, $offset);

        $chatData = [];
        $last_message = $this->Chatlogs_model->get_last_sent_message($contact);
        $diff = 0;
        if (!empty($last_message)) {
            $diff = differenceInHours($last_message['created'], date('Y-m-d H:i:s'));
        }

        $allow_send_msg = $diff >= 24 ? false : true;

        if (!empty($chatArr)) {
            $lastChat = $chatArr[0];
            $this->Chatlogs_model->update_user_message_status($lastChat['id'], $contact);

            $chatData['chat'] = $chatArr;
            
            // Run server
            //$this->ratchet_client->run();
            echo json_encode(array('chat' => $this->load->view('Chatlogs/chat', $chatData, true), 'allow_send_msg' => $allow_send_msg));
            exit();
        }

        echo false;
    }

    public function get_latest_chat() {
        $contact = $this->input->post('contact');
        $id = $this->input->post('id');

        $chatArr = $this->Chatlogs_model->get_latest_chat($contact, $id);
        if (!empty($chatArr)) {

            $lastChat = $chatArr[0];
            $this->Chatlogs_model->update_user_message_status($lastChat['id'], $contact);

            $chatData['chat'] = $chatArr;
            echo json_encode(array('chat' => $this->load->view('Chatlogs/chat', $chatData, true)));
            exit();
        } else {
            echo json_encode(array('chat' => ''));
            exit();
        }
    }

    public function get_contact_info() {
        $contact = $this->input->post('contact');
        $user_id = $this->session->userdata('id');

        $is_already_assign = $this->Chatlogs_model->get_assigned_member_info($contact);
        if (empty($is_already_assign)) {
            $where = 'type = "member" AND is_blocked="0" AND added_by =' . $this->db->escape($user_id);
            $data['members'] = $this->CMS_model->get_result(tbl_users, $where, 'id,name');
        }
        $contact_info = $this->db->get_where(tbl_clients, array('phone_number_full' => $contact))->row_array();
        $contact_arr = array('phone_number' => $contact);
        if (!empty($contact_info)) {
            $contact_arr['contact_id'] = $contact_info['id'];
            $contact_arr['name'] = $contact_info['name'];
            $contact_arr['tags'] = $contact_info['group_ids'];
            $contact_arr['is_subscribed'] = $contact_info['is_subscribed'];
            $member_info = $this->db->get_where(tbl_assigned_member, array('assigned_to' => $contact))->row_array();
            if (!empty($member_info)) {
                $contact_arr['member_id'] = $member_info['member_id'];
            }
        } else {
            $this->db->select('*');
            $this->db->from(tbl_chat_logs);
            $this->db->where('phone_number', $contact);
            $this->db->where('from_profile_name !=', '');
            $this->db->group_by('from_profile_name');
            $contact_info = $this->db->get()->row_array();
            $contact_arr['name'] = !empty($contact_info) ? $contact_info['from_profile_name'] : '';
        }
        $data['contact'] = $contact_arr;

        $tag_where = 'user_id =' . $this->db->escape($user_id);
        $tags = $this->CMS_model->get_result(tbl_tags, $tag_where, 'id as value,tag as name');

        echo json_encode(array('contact' => $this->load->view('Chatlogs/contact-info', $data, true), 'tags' => $tags));
    }

    public function delete_logs() {
        $ids = $this->input->post();

        if (!empty($ids)) {
            $id = implode(',', $ids);
            $this->CMS_model->delete_multiple('id', json_decode($id), tbl_chat_logs);
            $response = array(
                'status' => true,
            );
        } else {
            $response = array(
                'status' => false,
            );
        }
        echo json_encode($response);
        exit;
    }

    public function view_message() {
        $id = base64_decode($this->input->get('id'));
        $where = 'id = ' . $this->db->escape($id);
        $info = $this->CMS_model->get_result(tbl_chat_logs, $where, 'message', 1);

        echo json_encode(!empty($info['message']) ? $info['message'] : '');
    }

    public function view_api_message() {
        $id = base64_decode($this->input->get('id'));
        $message = create_template_message_for_chat($id);

        echo json_encode($message);
    }

    public function send_message() {
        $msg = $this->input->post('msg');
        $send_text = false;
        $send_image = false;
        $send_audio = false;
        $send_video = false;
        $send_document = false;
        $is_file = false;

        $message_type = '';

        if (!empty($msg)) {
            $send_text = true;
            $message_type = 'text';
        } else if (!empty($_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'image/jpg' || $_FILES['file']['type'] == 'image/jpeg')) {
            $send_image = $is_file = true;
            $message_type = 'image';
        } else if (!empty($_FILES['file']['type'] == 'video/mp4' || $_FILES['file']['type'] == 'video/3gp')) {
            $send_video = $is_file = true;
            $message_type = 'video';
        } else if (!empty($_FILES['file']['type'] == 'video/mp3' || $_FILES['file']['type'] == 'video/ogg')) {
            $send_audio = $is_file = true;
            $message_type = 'audio';
        } else {
            $send_document = $is_file = true;
            $message_type = 'document';
        }


        $contact = $this->input->post('contact');
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        if ($this->data['user_data']['type'] == 'member') {
            $user_id = $this->data['user_data']['added_by'];
        }


        $where = 'user_id = ' . $user_id;
        $waba_credential = $this->CMS_model->get_result(tbl_user_settings, $where, '', 1);

        if (!empty($waba_credential)) {
            $permanent_access_token = $waba_credential['permanent_access_token'];
            $phone_number_id = $waba_credential['phone_number_id'];

            $whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $phone_number_id,
                'access_token' => $permanent_access_token,
            ]);

            $insertData = array(
                'user_id' => $user_id,
                'from_user' => 0,
                'phone_number' => $contact,
                'message_type' => $message_type,
            );

            $wa_response = '';
            if ($send_text) {
                $insertData['message'] = $msg;
                $wa_response = $whatsapp_cloud_api->sendTextMessage('+' . $contact, $msg);
            }
            $upload_data = '';
            if ($is_file) {
                $path = CHAT_UPLOAD_PATH;
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx|xls|jpg|png|jpeg|pdf|mp4|3gp|mp3|ogg';
                $config['remove_spaces'] = TRUE;
                $temp = explode(".", $_FILES["file"]["name"]);
                $extenstion = end($temp);
                $temp[0] = 'file-' . date('YmdHis');
                $_FILES["file"]["name"] = $temp[0] . '.' . $extenstion;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    if (!empty($upload_data)) {
                        $fileLink = creteServerFileLink($upload_data['full_path']);
                        $document_name = $upload_data['file_name'];
                        if (!empty($fileLink)) {
                            $insertData['media'] = $fileLink;
                            $link_id = new LinkID($fileLink);
                            try {
                                if ($send_image) {
                                    $wa_response = $whatsapp_cloud_api->sendImage('+' . $contact, $link_id);
                                }
                                if ($send_document) {
                                    $wa_response = $whatsapp_cloud_api->sendDocument('+' . $contact, $link_id, $document_name, '');
                                }
                                if ($send_video) {
                                    $wa_response = $whatsapp_cloud_api->sendVideo('+' . $contact, $link_id, '');
                                }
                                if ($send_audio) {
                                    $wa_response = $whatsapp_cloud_api->sendAudio('+' . $contact, $link_id);
                                }
                            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                                $responseData = $e->responseData();
                                echo json_encode(array('status' => false, 'error' => 'Something went wrong!'));
                                exit();
                            }
                        }
                    } else {
                        echo json_encode(array('status' => false, 'error' => 'Something went wrong!'));
                        exit();
                    }
                } else {
                    echo json_encode(array('status' => false, 'error' => $this->upload->display_errors()));
                    exit();
                }
            }
            if (!empty($wa_response)) {
                $Exresponse = new ResponseException($wa_response);
                $responseData = $Exresponse->responseData();
                if (isset($responseData['messages'][0])) {
                    $insertData['message_id'] = $responseData['messages'][0]['id'];
                    /* $time_zone = $this->session->userdata('time_zone');
                      //pr($this->session->userdata(), 1);
                      if (!empty($time_zone)) {
                      date_default_timezone_set($time_zone);
                      } */
                    $insertData['created'] = date('Y-m-d H:i:s');
                    $insertData['send_by'] = $this->data['user_data']['id'];
                    $this->CMS_model->insert_data(tbl_chat_logs, $insertData);
                    echo json_encode(array('status' => true));
                    exit();
                }
            }
        }
        echo json_encode(array('status' => false, 'error' => 'Something went wrong!'));
        exit();
    }

    public function get_templates($contact) {
        $data['contact'] = $contact;
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED" AND is_deleted = 0';
        $data['templates'] = $templates = $this->CMS_model->get_result(tbl_templates, $where);
        $this->load->view("Chatlogs/send-template", $data);
    }

    public function send_template() {
        $post = $this->input->post();
        $this->form_validation->set_rules('template_id', 'Template', 'trim|required');
        if (isset($post['temp_media'])) {
            if (empty($post['temp_media'])) {
                $this->form_validation->set_rules('temp_media', 'Media URL', 'trim|required', array('required' => 'Please select media file.'));
            }
        }
        if (isset($post['card_media'])) {
            $custom_media_message = '';
            foreach ($post['card_media'] as $cmK => $cmV) {
                if (empty($cmV)) {
                    $custom_media_message .= '<p>Please select media file for card ' . ($cmK + 1) . '.</p>';
                    $this->form_validation->set_rules('card_media', 'Media URL', 'trim|required', array('required' => $custom_media_message));
                }
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $return = array('status' => false, 'error' => validation_errors());
        } else {

            $template_id = $this->input->post('template_id');
            $contact = $this->input->post('contact');

            $input_arr = array(
                'template_id' => $template_id,
                'default_select_header_value' => isset($post['default_select_header_value']) && !empty($post['default_select_header_value']) ? $post['default_select_header_value'] : '',
                'header_value' => isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '',
                'default_select_value' => isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '',
                'default_value' => isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '',
                'temp_media' => isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '',
                'temp_btn_url' => isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '',
                'card_media' => isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '',
            );

            $contact_info = $this->CMS_model->get_result(tbl_clients, 'phone_number_full =' . $contact, '', 1);
            if (empty($contact_info)) {
                $contact_info['contact'] = $contact;
            }
            $reponse = create_template_body($input_arr, $contact_info);

            if (!empty($reponse)) {
                unset($reponse['message']);
                $template_info['components'] = $reponse;
                $send_by = '';
                if ($this->session->userdata('type') == 'user') {
                    $user_id = $this->session->userdata('id');
                }
                if ($this->session->userdata('type') == 'member') {
                    $user_id = $this->session->userdata('added_by');
                    $send_by = $this->session->userdata('id');
                }

                $user_settings = $this->CMS_model->get_result(tbl_user_settings, 'user_id =' . $user_id, '', 1);
                if (!empty($user_settings)) {
                    $curl_data = array(
                        'from_phone_number_id' => $user_settings['phone_number_id'],
                        'access_token' => $user_settings['permanent_access_token'],
                        'to' => isset($contact_info['phone_number_full']) && !empty($contact_info['phone_number_full']) ? $contact_info['phone_number_full'] : $contact,
                    );

                    $curl_data = array_merge($curl_data, $template_info);

                    $wa_reposne = curlSendTemplate($curl_data);
                    if (!empty($wa_reposne)) {
                        $response_arr = json_decode($wa_reposne, 1);
                        if (isset($response_arr['error'])) {
                            return false;
                        } else {
                            $chat_log = array(
                                'user_id' => $user_id,
                                'from_user' => 0,
                                'phone_number' => $contact,
                                'message_type' => 'template',
                                'message_id' => $response_arr['messages'][0]['id'],
                                'message_status' => $response_arr['messages'][0]['message_status'],
                                'message' => json_encode($reponse),
                                'created' => date('Y-m-d H:i:s'),
                                'api_response' => $wa_reposne,
                                'send_by' => $send_by,
                            );
                            $id = $this->CMS_model->insert_data(tbl_chat_logs, $chat_log);
                            if (!empty($id)) {
                                $message = create_template_message_for_chat($id);
                                $return = array('status' => true, 'message' => $message);
                            } else {
                                $return = array('status' => false, 'error' => 'Something went wrong!');
                            }
                        }
                    } else {
                        $return = array('status' => false, 'error' => 'Something went wrong!');
                    }
                } else {
                    $return = array('status' => false, 'error' => 'Something went wrong!');
                }
            } else {
                $return = array('status' => false, 'error' => 'Something went wrong!');
            }
        }
        echo json_encode($return);
    }

    public function get_assigned_member() {
        $contact = $this->input->post('contact');
        $member = $this->Chatlogs_model->get_assigned_member_info($contact);
        if (!empty($member)) {
            $member['status'] = true;
            echo json_encode($member);
            exit();
        }
        echo json_encode(array('status' => false));
        exit();
    }

    public function save_contact() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $response = array('status' => false, 'msg' => validation_errors());
        } else {
            $user_id = $this->session->userdata('id');
            $phone_number = $this->input->post('phone_number');
            $is_subscribed = $this->input->post('is_subscribed');
            $name = $this->input->post('name');
            $member_id = $this->input->post('member_id');

            $tags_json = $this->input->post('tags');
            $tags = [];
            if (!empty($tags_json)) {
                $tags_arr = json_decode($tags_json, true);
                if (!empty($tags_arr)) {
                    foreach ($tags_arr as $key => $rt) {
                        $tags[$key] = trim($rt['name']);
                    }
                }
            }
            $contact_arr = [
                'name' => $name,
                'user_id' => $user_id,
                'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                'phone_number' => substr($phone_number, -10),
                'phone_number_full' => $phone_number,
                'is_subscribed' => $is_subscribed == 'unsubscribed' ? '0' : '1'
            ];
            if (!empty($member_id)) {
                $data = array(
                    'assigned_to' => $phone_number,
                    'member_id' => $member_id,
                    'name' => $name,
                    'created' => date('Y-m-d H:i:s'),
                );
                $this->CMS_model->insert_data(tbl_assigned_member, $data);

                $where = 'id = ' . $this->db->escape($member_id);
                $member = $this->CMS_model->get_result(tbl_users, $where, 'name', 1);

                $response = array('member' => $member['name']);
            }
            $contact_id = $this->input->post('contact_id');
            if (!empty($contact_id)) {
                $this->CMS_model->update_record(tbl_clients, 'id=' . $contact_id, $contact_arr);
                $response['msg'] = 'Contact Updated successfully.';
            } else {
                $this->CMS_model->insert_data(tbl_clients, $contact_arr);
                $response['msg'] = 'Contact saved successfully.';
            }
            $response['status'] = true;
        }
        echo json_encode($response);
        exit();
    }

    public function remove_assigned_member() {
        $contact = $this->input->post('contact');
        $member = $this->CMS_model->get_result(tbl_assigned_member, array('assigned_to' => $contact), '', 1);
        if (!empty($member)) {
            $where = 'id = ' . $this->db->escape($member['id']);
            $this->CMS_model->delete_data(tbl_assigned_member, $where);
            $response = array('status' => true);
        } else {
            $response = array('status' => false, 'error' => 'data not found!');
        }
        echo json_encode($response);
        exit();
    }

    public function is_subscribed() {
        $contact = $this->input->post('contact');
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }
        $contact_info = $this->db->get_where(tbl_clients, array('user_id' => $user_id, 'phone_number_full' => $contact))->row_array();
        if (!empty($contact)) {
            echo json_encode(array('status' => true, 'is_subscribed' => $contact_info['is_subscribed']));
            exit();
        }
        echo json_encode(array('status' => false));
    }

    public function update_subscribe_status() {
        $contact = $this->input->post('contact');
        $status = $this->input->post('status') == 'subscribed' ? '0' : '1';
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }
        $contact_info = $this->db->get_where(tbl_clients, array('user_id' => $user_id, 'phone_number_full' => $contact))->row_array();
        if (!empty($contact_info)) {
            $this->db->where('id', $contact_info['id']);
            $this->db->update(tbl_clients, array('is_subscribed' => $status));
            //echo $this->db->last_query();
            echo json_encode(array('status' => true));
            exit();
        }
        echo json_encode(array('status' => false));
    }

    public function _auth($datas = null) {
        // Here you can verify everything you want to perform user login.

        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null) {
        // Here you can do everyting you want, each time message is received
        echo 'Hey ! I\'m an EVENT callback' . PHP_EOL;
    }

}
