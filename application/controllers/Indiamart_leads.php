<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Indiamart_leads extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['CMS_model', 'Indiamart_inquiries_model']);
        $this->data = get_admin_data();
        if ($this->data['user_data']['type'] == 'user') {
            if(empty($this->data['user_data']['crm_lead_access'])){
                $this->session->set_flashdata('error_msg', '<br/>You have not access for this page');
                redirect('dashboard');  
            }
        }
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->template->set('title', 'CRM Leads');
        $this->data['cities'] = $this->Indiamart_inquiries_model->get_indiamart_inquiries_unqiue_data($user_id, 'city');
        $this->data['categories'] = $this->Indiamart_inquiries_model->get_indiamart_inquiries_unqiue_data($user_id, 'mcat_name');
        $this->data['running_inquiries'] = $this->Indiamart_inquiries_model->get_running_inquiries($user_id);
        $this->data['ti_cities'] = $this->Indiamart_inquiries_model->get_indiamart_inquiries_unqiue_data($user_id, 'city','tradeindia');
        $this->data['ei_cities'] = $this->Indiamart_inquiries_model->get_indiamart_inquiries_unqiue_data($user_id, 'city','exportersindia');
        
        $this->template->load('default_home', 'Indiamart_Leads/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_indiamart_leads() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Indiamart_inquiries_model->get_all_leads('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $records = $this->Indiamart_inquiries_model->get_all_leads(null, $user_id);
        
        if(!empty($records)){
            foreach ($records as $rdk =>$rd){
                $tz_date = '';
                if(!empty($rd['query_time']) && $rd['query_time'] != '0000-00-00 00:00:00'){
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['query_time']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['query_time'] =  !empty($tz_date) ? date('d M Y', strtotime($tz_date)).'<br/>'.date('h:i a', strtotime($tz_date)): '';
            }
        }
        $final['data'] = $records;
        
        echo json_encode($final);
    }

    public function list_tradeindia_leads() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Indiamart_inquiries_model->get_tradeindia_leads('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $records = $this->Indiamart_inquiries_model->get_tradeindia_leads(null, $user_id);
        if(!empty($records)){
            foreach ($records as $rdk =>$rd){
                $tz_date = '';
                if(!empty($rd['query_time']) && $rd['query_time'] != '0000-00-00 00:00:00'){
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['query_time']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['query_time'] =  !empty($tz_date) ? date('d M Y', strtotime($tz_date)).'<br/>'.date('h:i a', strtotime($tz_date)): '';
            }
        }
        $final['data'] = $records;
        echo json_encode($final);
    }

    public function list_exportersindia_leads(){
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Indiamart_inquiries_model->get_exportersindia_leads('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $records = $this->Indiamart_inquiries_model->get_exportersindia_leads(null, $user_id);
        if(!empty($records)){
            foreach ($records as $rdk =>$rd){
                $tz_date = '';
                if(!empty($rd['query_time']) && $rd['query_time'] != '0000-00-00 00:00:00'){
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['query_time']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['query_time'] =  !empty($tz_date) ? date('d M Y', strtotime($tz_date)).'<br/>'.date('h:i a', strtotime($tz_date)): '';
            }
        }
        $final['data'] = $records;
        echo json_encode($final);

    }


    public function start_grabbing() {
        if (!empty($user_id)) {
            $user_id = base64_decode($user_id);
        } else {
            if ($this->data['user_data']['type'] == 'user') {
                $user_id = $this->data['user_data']['id'];
            }
        }
        if (is_numeric($user_id)) {
            $insert_array = [
                'last_cron_day' => 365,
                'last_run_at' => null,
                'user_id' => $user_id,
                'status' => 'pending',
                'created' => date('Y-m-d H:i:s')
            ];
            $this->CMS_model->insert_data(tbl_indiamart_inquiries, $insert_array);
            $this->session->set_flashdata('success_msg', 'Started Indiamart API successfully!');
        }
        $url = base_url() . 'users/settings';
        redirect($url);
    }

    public function send_leads_message() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('lead_ids', 'Leads ID', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            $resposne = array();
            if ($this->form_validation->run() == FALSE) {
                $resposne = array(
                    'status' => false,
                    'message' => validation_errors()
                );
            } else {
                if (!empty($user_id)) {
                    $user_id = base64_decode($user_id);
                } else {
                    if ($this->data['user_data']['type'] == 'user') {
                        $user_id = $this->data['user_data']['id'];
                    }
                }
                if (is_numeric($user_id)) {
                    $lead_ids = $this->input->post('lead_ids');
                    $insert_array = [
                        'lead_ids' => $lead_ids,
                        'message' => $this->input->post('message'),
                        'user_id' => $user_id,
                        'status' => 'pending',
                        'created' => date('Y-m-d H:i:s')
                    ];
                    $this->CMS_model->insert_data(tbl_indiamart_leads_message, $insert_array);
                    $resposne = array(
                        'status' => true,
                    );
                }
            }
            echo json_encode($resposne);
            die;
        }
    }

    public function get_cron_percentage() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $res = get_lead_percentage($user_id);
        echo $res;
        die;
    }

    
    public function message_logs(){
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->template->set('title', 'CRM Message Logs');
        $this->template->load('default_home', 'Indiamart_Leads/message_logs', $this->data);

    }
    public function get_message_logs() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Indiamart_inquiries_model->get_all_logs('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->Indiamart_inquiries_model->get_all_logs(null, $user_id);
       
        //pr($final['data'], true);
        foreach($final['data'] as $index => $data){
            
            $final[$index]['created'] = !empty($data['created']) && $data['created'] !== '0000-00-00 00:00:00' ? getTimeBaseOnTimeZone($data['created']) : '';
            $final[$index]['deliver_time'] = !empty($data['deliver_time']) && $data['deliver_time'] !== '0000-00-00 00:00:00' ? getTimeBaseOnTimeZone($data['deliver_time']) : '';
            $final[$index]['read_time'] = !empty($data['read_time']) && $data['read_time'] !== '0000-00-00 00:00:00' ? getTimeBaseOnTimeZone($data['read_time']) : '';
        }
        echo json_encode($final);
    }
    public function get_view_data(){
        $id = base64_decode($this->input->get('id'));
        $type = $this->input->get('type');
        $where = 'id = ' . $this->db->escape($id);
        $info = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, $type, 1);

        echo json_encode(!empty($info[$type]) ? $info[$type] : '');
    }

    public function createExcel() {
        $response = array();
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $source = $this->input->post('source');
        $data_arr =  array(
            'city' => $this->input->post('city'),
            'query_time' => $this->input->post('query_time'),
            'leads_source' => $source,
            'user_id' => $user_id
        );
        if($source == 'indiamart'){
            $data_arr['mcat_name'] = $this->input->post('mcat_name');
        }
        $leads_details = $this->Indiamart_inquiries_model->get_filtered_leads($data_arr);
        
        if (isset($leads_details) && !empty($leads_details)) {
            $fileName = date('Ymdhis') . '_'.$source.'_leads.xlsx';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Phone Number');
            $sheet->setCellValue('D1', 'Lead Source');
            $sheet->setCellValue('E1', 'Subject');
            $sheet->setCellValue('F1', 'Company');
            $sheet->setCellValue('G1', 'City');
            $sheet->setCellValue('H1', 'State');
            $sheet->setCellValue('I1', 'Product Name');
            if($source == 'indiamart'){
                $sheet->setCellValue('J1', 'Category');
                $sheet->setCellValue('K1', 'Message');
                $sheet->setCellValue('L1', 'Query Time');
            }else{
                $sheet->setCellValue('J1', 'Message');
                $sheet->setCellValue('K1', 'Query Time');
            }


            $rows = 2;
            foreach ($leads_details as $key => $val) {
                $sheet->setCellValue('A' . $rows, ++$key);
                $sheet->setCellValue('B' . $rows, $val['name']);
                $sheet->setCellValue('C' . $rows, $val['mobile']);
                $sheet->getStyle('C' . $rows)->getNumberFormat()->setFormatCode('000000000000');
                $sheet->setCellValue('D' . $rows, $val['leads_source']);
                $sheet->setCellValue('E' . $rows, $val['subject']);
                $sheet->setCellValue('F' . $rows, $val['company']);
                $sheet->setCellValue('G' . $rows, $val['city']);
                $sheet->setCellValue('H' . $rows, $val['state']);
                $sheet->setCellValue('I' . $rows, $val['product_name']);
                if($source == 'indiamart'){
                    $sheet->setCellValue('J' . $rows, $val['mcat_name']);
                    $sheet->setCellValue('K' . $rows, $val['message']);
                    $sheet->setCellValue('L' . $rows, $val['query_time']);
                }else{
                    $sheet->setCellValue('J' . $rows, $val['message']);
                    $sheet->setCellValue('K' . $rows, $val['query_time']);
                }
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

}

?>