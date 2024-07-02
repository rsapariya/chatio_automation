<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inquiries extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Inquiries_model', 'CMS_model']);
        $this->data = get_admin_data();
        $this->load->library('Excel');
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $this->template->set('title', 'Inquiries');
        $this->template->load('default_home', 'Inquiries/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_inquiries($type = '') {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Inquiries_model->get_all_inquiries('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->Inquiries_model->get_all_inquiries(null, $user_id);
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        $name = '';
        $id = $this->uri->segment(3);
        $inquiry_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($inquiry_id)) {
            $where = 'id = ' . $this->db->escape($inquiry_id);
            $check_inquiry = $this->CMS_model->get_result(tbl_inquiries, $where);
            if ($check_inquiry) {
                $this->data['inquiry_datas'] = $check_inquiry[0];
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $where = 'is_deleted = 0';
        $this->data['inquiry_types'] = $this->CMS_model->get_result(tbl_inquiry_types, $where);
        $this->data['automations'] = $this->Inquiries_model->get_automations($user_id);
        $this->template->load('default_home', 'Inquiries/edit', $this->data);
    }

    public function save() {
        $unique_str = '';
        if ($this->input->post()) {
            $inquiry_id = base64_decode($this->input->post('inquiry_id'));
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                $url = base_url() . 'inquiries/add';
                if (is_numeric($inquiry_id)) {
                    $id = base64_encode($inquiry_id);
                    $url = base_url() . 'inquiries/edit/' . $id;
                }
                redirect($url);
            } else {
                $where = array('id' => $inquiry_id);
                $automation_id = $this->input->post('automation_id');
                $name = $this->input->post('name');
                $update_array = [
                    'name' => $name,
                    'phone_number' => $this->input->post('phone_number'),
                    'phone_number_full' => ltrim($this->input->post('phone_number_full'), '+'),
                    'inquiry_type' => $this->input->post('inquiry_type'),
                    'automation_id' => $automation_id
                ];

                $user_id = 0;
                if ($this->data['user_data']['type'] == 'user') {
                    $user_id = $this->data['user_data']['id'];
                }
                date_default_timezone_set("Asia/Calcutta");
                if (is_numeric($inquiry_id)) {
                    $updated_inquiry_id = $inquiry_id;
                    $where_delete = array('inquiry_id' => $inquiry_id);
                    $this->CMS_model->delete_data(tbl_inquiry_logs, $where_delete);
                    $this->CMS_model->update_record(tbl_inquiries, $where, $update_array);
                } else {
                    $update_array['user_id'] = $user_id;
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $updated_inquiry_id = $this->CMS_model->insert_data(tbl_inquiries, $update_array);
                }

                if ($automation_id != '' && $updated_inquiry_id != '') {
                    $where_a = 'id = ' . $this->db->escape($automation_id);
                    $check_automation = $this->CMS_model->get_result(tbl_automations, $where_a);
                    $dates = array();
                    if (isset($check_automation[0]) && !empty($check_automation[0])) {
                        $details = (isset($check_automation[0]['details'])) ? json_decode($check_automation[0]['details'], true) : array();
                        if (!empty($details)) {
                            $date = date('Y-m-d H:i:s');
                            foreach ($details as $key => $detail) {
                                if (is_numeric($detail)) {
                                    $automation_template_detail = create_template_message($automation_id, $key, $detail, $name);
                                    $dates[] = array(
                                        'user_id' => $user_id,
                                        'inquiry_id' => $updated_inquiry_id,
                                        'automation_id' => $automation_id,
                                        'automation_template_id' => $detail,
                                        'temp_param' => json_encode($automation_template_detail),
                                        'notification_date' => $date,
                                        'created_at' => date('Y-m-d H:i:s')
                                    );
                                } else {
                                    $date_details = explode(' ', $detail);
                                    if (isset($date_details[1]) && ($date_details[1] == 'Minutes' || $date_details[1] == 'Hours')) {
                                        $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                    } else {
                                        $trigger_time = ($key > 1) ? $check_automation[0]['trigger_time'] : date('H:i:s');
                                        $date = date('Y-m-d', strtotime($date)) . '' . $trigger_time;
                                        $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($dates)) {
                        $this->CMS_model->insert_batch(tbl_inquiry_logs, $dates);
                    }
                }
                if (is_numeric($inquiry_id)) {
                    $this->session->set_flashdata('success_msg', 'Inquiry updated successfully !');
                } else {
                    $this->session->set_flashdata('success_msg', 'Inquiry created successfully!');
                }
                redirect('inquiries');
            }
        }
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function add_multiple() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->template->set('title', 'Add Mutliple Inquiries');
        $this->data['automations'] = $this->Inquiries_model->get_automations($user_id);
        $this->template->load('default_home', 'Inquiries/add_multiple', $this->data);
    }

    public function save_multiple() {
        $up_data = array();
        $automation_id = 0;
        if ($this->input->post()) {
            $automation_id = $this->input->post('automation_id');
        }
        if (isset($_FILES["inquiries_file"]["name"])) {
            $path = DEFAULT_ADMIN_INQUIRY_UPLOAD_PATH;
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('inquiries_file')) {
                $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                redirect('inquiries/add_multiple');
            } else {
                $up_data = array('upload_data' => $this->upload->data());
            }
            if (!empty($up_data['upload_data']['file_name'])) {
                $import_xls_file = $up_data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;
            $allDataInSheet = array();
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            } catch (Exception $e) {
                $this->session->set_flashdata('error_msg', 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                redirect('inquiries/add_multiple');
            }
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array('name', 'phone_number', 'inquiry_type');
            $makeArray = array('name' => 'name', 'phone_number' => 'phone_number', 'inquiry_type' => 'inquiry_type');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    $value = !empty($value) ? trim($value) : $value;
                    if (!empty($value) && in_array($value, $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[$value] = $key;
                    }
                }
            }
            $data = array_diff_key($makeArray, $SheetDataKey);
            if (empty($data)) {
                $flag = 1;
            }
            $user_id = 0;
            if ($this->data['user_data']['type'] == 'user') {
                $user_id = $this->data['user_data']['id'];
            }
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $name = filter_var(($allDataInSheet[$i][$SheetDataKey['name']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $phone_number = filter_var(($allDataInSheet[$i][$SheetDataKey['phone_number']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $inquiry_type = filter_var(($allDataInSheet[$i][$SheetDataKey['inquiry_type']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $inquiry_type_id = 0;
                    if (!empty($phone_number) && !empty($name) && !empty($inquiry_type)) {
                        if (!empty($inquiry_type)) {
                            $where = 'name Like "%' . strtolower(trim($inquiry_type)) . '%" and is_deleted = 0';
                            $exiting_inquiry_type = $this->CMS_model->get_result(tbl_inquiry_types, $where);
                            if (!empty($exiting_inquiry_type)) {
                                $inquiry_type_id = $exiting_inquiry_type[0]['id'];
                            } else {
                                $insertInquiryData = array(
                                    'name' => $inquiry_type,
                                    'created_at' => date('Y-m-d H:i:s')
                                );
                                $inquiry_type_id = $this->CMS_model->insert_data(tbl_inquiry_types, $insertInquiryData);
                            }
                        }
                        $insertData = array(
                            'user_id' => $user_id,
                            'name' => !empty($name) ? trim($name) : '',
                            'phone_number' => !empty($phone_number) ? substr(trim($phone_number), -10) : '',
                            'phone_number_full' => !empty($phone_number) ? $phone_number : '',
                            'inquiry_type' => $inquiry_type_id,
                            'automation_id' => $automation_id,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $updated_inquiry_id = $this->CMS_model->insert_data(tbl_inquiries, $insertData);
                        if ($automation_id != '' && $updated_inquiry_id != '') {
                            $where_a = 'id = ' . $this->db->escape($automation_id);
                            $check_automation = $this->CMS_model->get_result(tbl_automations, $where_a);
                            $dates = array();
                            if (isset($check_automation[0]) && !empty($check_automation[0])) {
                                $details = (isset($check_automation[0]['details'])) ? json_decode($check_automation[0]['details'], true) : array();
                                if (!empty($details)) {
                                    date_default_timezone_set("Asia/Calcutta");
                                    $date = date('Y-m-d H:i:s');
                                    foreach ($details as $key => $detail) {
                                        if (is_numeric($detail)) {
                                            $automation_template_detail = create_template_message($automation_id, $key, $detail, $name);
                                            $dates[] = array(
                                                'user_id' => $user_id,
                                                'inquiry_id' => $updated_inquiry_id,
                                                'automation_id' => $automation_id,
                                                'automation_template_id' => $detail,
                                                'temp_param' => json_encode($automation_template_detail),
                                                'notification_date' => $date,
                                                'created_at' => date('Y-m-d H:i:s')
                                            );
                                        } else {
                                            $date_details = explode(' ', $detail);
                                            if (isset($date_details[1]) && ($date_details[1] == 'Minutes' || $date_details[1] == 'Hours')) {
                                                $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                            } else {
                                                $trigger_time = ($key > 1) ? $check_automation[0]['trigger_time'] : date('H:i:s');
                                                $date = date('Y-m-d', strtotime($date)) . '' . $trigger_time;
                                                $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                            }
                                        }
                                    }
                                }
                            }
                            if (!empty($dates)) {
                                $this->CMS_model->insert_batch(tbl_inquiry_logs, $dates);
                            }
                        }
                    }
                    $this->session->set_flashdata('success_msg', 'Inqruies added successfully!');
                }
                redirect('inquiries');
            } else {
                $this->session->set_flashdata('error_msg', 'Please import correct file!');
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Please import file!');
        }
        redirect('inquiries/add_multiple');
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $inquiry_id) {
        $inquiry_id = base64_decode($inquiry_id);
        $where = 'id = ' . $inquiry_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }

        $check_inquiry = $this->CMS_model->get_result(tbl_inquiries, $where);
        if ($check_inquiry) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'Inquiry successfully deleted!');
            } elseif ($action == 'block') {
                $update_array = array(
                    'is_active' => 0
                );
                $this->session->set_flashdata('success_msg', 'Inquiry successfully deactivated!');
            } elseif ($action == 'activate') {
                $update_array = array(
                    'is_active' => 1
                );
                $this->session->set_flashdata('success_msg', 'Inquiry successfully activated!');
            }
            $this->CMS_model->update_record(tbl_inquiries, $where, $update_array);
            if ($action == 'delete') {
                $where_delete = array('inquiry_id' => $inquiry_id);
                $this->CMS_model->delete_data(tbl_inquiry_logs, $where_delete);
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }

        redirect('inquiries');
    }

    function create_template_message($automation_id, $temp_seq, $template_id, $name = '') {
        $components = create_template_message($automation_id, $temp_seq, $template_id, $name);
        pr($components);
        echo "<br/> ---- ";
        echo json_encode($components);
    }

}
