<?php

defined('BASEPATH') OR exit('No direct script access allowed');
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
        $final['data'] = $this->Chatlogs_model->get_logs(null, $user_id);
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

    public function chat_live() {
        //$this->load->add_package_path(FCPATH . 'vendor/takielias/codeigniter-websocket');
        //$this->load->library('Codeigniter_websocket');
        //$this->load->remove_package_path(FCPATH . 'vendor/takielias/codeigniter-websocket');
        
        //$this->codeigniter_websocket->set_callback('auth', array($this, '_auth'));
       // $this->codeigniter_websocket->set_callback('event', array($this, '_event'));
        //$this->codeigniter_websocket->run();
        
        $this->template->set('title', 'Chat Live');
        $this->data['customers'] = $this->Chatlogs_model->get_customers();
        $this->template->load('default_home', 'Chatlogs/chat_live', $this->data);
    }

    public function contact_filter() {
        $filter = $this->input->post('search');
        $customers = $this->Chatlogs_model->get_customers($filter);
        foreach ($customers as $key => $cust) {
            $customers[$key]['created'] = !empty($cust['created']) ? date('h:i a', strtotime($cust['created'])) : '';
        }
        $data['customers'] = $customers;
        $data['count'] = !empty($customers) ? count($customers) : 0;
        echo json_encode($data);
    }

    public function get_chat() {
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
            $contact = $this->input->post('contact');
            $where = 'message_id != "" AND phone_number = ' . $contact . ' AND user_id = ' . $user_id;
            $chatArr = $this->CMS_model->get_result(tbl_chat_logs, $where);
            $chatData = [];
            if (!empty($chatArr)) {
                //pr($chatArr, 1);
                foreach ($chatArr as $chat) {
                    $date = date('Y-m-d', strtotime($chat['created']));
                    $chatData['chat'][$date][] = $chat;
                }
                echo json_encode($this->load->view('Chatlogs/chat', $chatData, true));
            }
            echo false;
        }
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

    public function send_message() {
        if ($this->data['user_data']['type'] == 'user') {
            $msg = $this->input->post('msg');
            $contact = $this->input->post('contact');
            $user_id = $this->data['user_data']['id'];

            $where = 'user_id = ' . $user_id;
            $waba_credential = $this->CMS_model->get_result(tbl_user_settings, $where, '', 1);

            if (!empty($waba_credential)) {
                $permanent_access_token = $waba_credential['permanent_access_token'];
                $phone_number_id = $waba_credential['phone_number_id'];

                $whatsapp_cloud_api = new WhatsAppCloudApi([
                    'from_phone_number_id' => $phone_number_id,
                    'access_token' => $permanent_access_token,
                ]);

                try {
                    $wa_response = $whatsapp_cloud_api->sendTextMessage('+' . $contact, $msg);
                    if (!empty($wa_response)) {
                        $Exresponse = new ResponseException($wa_response);
                        $responseData = $Exresponse->responseData();
                        if (isset($responseData['messages'][0])) {
                            $responseData = array(
                                'user_id' => $user_id,
                                'from_user' => 0,
                                'phone_number' => $contact,
                                'message_type' => 'text',
                                'message_id' => $responseData['messages'][0]['id'],
                                'message' => $msg,
                                'created' => date('Y-m-d H:i:s')
                            );
                            $this->CMS_model->insert_data(tbl_chat_logs, $responseData);
                            echo json_encode(array('status' => true));
                            exit();
                        }
                    }
                } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                    $responseData = $e->responseData();
                    echo json_encode(array('status' => false));
                    exit();
                }
            }
            echo json_encode(array('status' => false));
            exit();
        }
        echo json_encode(array('status' => false));
        exit();
    }
    
    
    public function _auth($datas = null)
    {
        // Here you can verify everything you want to perform user login.

        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null)
    {
        // Here you can do everyting you want, each time message is received
        echo 'Hey ! I\'m an EVENT callback' . PHP_EOL;
    }

}
