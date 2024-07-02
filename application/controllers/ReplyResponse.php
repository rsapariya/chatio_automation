<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReplyResponse extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['ReplyResponse_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->template->set('title', 'Reply Responses');
        $this->data['responses'] = $this->ReplyResponse_model->get_all_response_text($user_id);
        $this->template->load('default_home', 'ReplyResponse/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_reply_responses() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->ReplyResponse_model->get_all_reply_responses('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->ReplyResponse_model->get_all_reply_responses(null, $user_id);
        $records = $final['data'];
        $final['data'] = $records;
        echo json_encode($final);
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $reply_message_id) {
        $reply_message_id = base64_decode($reply_message_id);
        $where = 'id = ' . $reply_message_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $check_reply_message = $this->CMS_model->get_result(tbl_button_reply_logs, $where);
        if ($check_reply_message) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'Reply Message successfully deleted!');
            }
            $this->CMS_model->update_record(tbl_button_reply_logs, $where, $update_array);
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('replyMessage');
    }

    public function createExcel() {
        $response = array();
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $trigger_text = $this->input->post('trigger_text');
        $query_time = $this->input->post('query_time');
        $check_reply_messages = $this->ReplyResponse_model->get_filtered_reply_responses($trigger_text, $query_time, $user_id);

        if (isset($check_reply_messages) && !empty($check_reply_messages)) {
            $fileName = date('Ymdhis') . '_button_response.xlsx';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Phone Number');
            $sheet->setCellValue('D1', 'Button Reply');
            $sheet->setCellValue('E1', 'Replied On');

            $rows = 2;
            foreach ($check_reply_messages as $key => $val) {
                $sheet->setCellValue('A' . $rows, ++$key);
                $sheet->setCellValue('B' . $rows, $val['name']);
                $sheet->setCellValue('C' . $rows, $val['mobile_number']);
                $sheet->getStyle('C' . $rows)->getNumberFormat()->setFormatCode('000000000000');
                $sheet->setCellValue('D' . $rows, $val['response']);
                $sheet->setCellValue('E' . $rows, $val['created_at']);
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

    public function delete_responses(){
        $ids = $this->input->post();

        if(!empty($ids)){
            $id = implode(',',$ids);
            $this->CMS_model->delete_multiple('id',json_decode($id),tbl_button_reply_logs);
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

}
