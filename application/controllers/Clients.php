<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model']);
        $this->data = get_admin_data();
        $this->load->library('Excel');
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $this->template->set('title', 'Clients');
        $this->template->load('default_home', 'Clients/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_clients() {
        $final['recordsTotal'] = $this->User_model->get_all_user_customers('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->User_model->get_all_user_customers();
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        $name = '';
        $id = $this->uri->segment(3);
        $user_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($user_id)) {
            $where = 'id = ' . $this->db->escape($user_id);
            $check_users = $this->CMS_model->get_result(tbl_clients, $where);

            if ($check_users) {
                $this->data['user_datas'] = $check_users[0];
            } else {
                show_404();
            }
        }
        //pr($this->data['user_datas'], 1);
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id');

        $unique_str = '';
        $this->template->load('default_home', 'Clients/edit', $this->data);
    }

    public function save() {
        $unique_str = '';
        //pr($this->input->post(), 1);
        if ($this->input->post()) {
            $user_id = base64_decode($this->input->post('user_id'));
            $email = '';
            if (is_numeric($user_id)) {
                $where = 'id = ' . $this->db->escape($user_id);
                $check_users = $this->CMS_model->get_result(tbl_clients, $where);
                if ($check_users) {
                    $check_users = $check_users[0];
                    $email = $check_users['email'];
                }
            }

            if ($email == "") {
                $unique_str = '|is_unique[' . tbl_clients . '.email]';
            } else {
                if ($email != $this->input->post('email')) {
                    $unique_str = '|is_unique[' . tbl_clients . '.email]';
                }
            }
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
//            $this->form_validation->set_rules('email', 'Email', 'trim|required' . $unique_str, array('is_unique' => 'This %s already exists.'));
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            $this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required');
            $this->form_validation->set_rules('anniversary_date', 'Anniversary Date', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                $url = base_url() . 'contacts/add';
                if (is_numeric($user_id)) {
                    $id = base64_encode($user_id);
                    $url = base_url() . 'contacts/edit/' . $id;
                }
                redirect($url);
            } else {
//                pr($this->input->post(), 1);
                $where = array('id' => $user_id);
                $group_ids_json = $this->input->post('group_ids');
                $tags = [];
                if (!empty($group_ids_json)) {
                    $arr_group_id = json_decode($group_ids_json, true);
                    if (!empty($arr_group_id)) {
                        foreach ($arr_group_id as $key => $rt) {
                            $tags[$key] = trim($rt['name']);
                        }
                    }
                }
                $update_array = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                    'phone_number' => $this->input->post('phone_number'),
                    'phone_number_full' => ltrim($this->input->post('phone_number_full'), '+'),
                    'birth_date' => date('Y-m-d', strtotime($this->input->post('birth_date'))),
                    'anniversary_date' => date('Y-m-d', strtotime($this->input->post('anniversary_date'))),
                ];
                $dynamic_columns = $this->input->post('column');
                if (!empty($dynamic_columns)) {
                    $column = 1;
                    foreach ($dynamic_columns as $key => $dc) {
                        $update_array['column' . $column++] = $dc;
                    }
                }
                if (is_numeric($user_id)) {
                    $this->CMS_model->update_record(tbl_clients, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Contact updated successfully !');
                } else {
                    $update_array['user_id'] = $this->session->userdata('id');
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_clients, $update_array);
                    $this->session->set_flashdata('success_msg', 'Contact created successfully!');
                }
                redirect('contacts');
            }
        }
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function add_multiple() {
        $this->template->set('title', 'Add Mutliple Clients');
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id');
        $this->template->load('default_home', 'Clients/add_multiple', $this->data);
    }

    public function save_multiple() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => false,
                'error' => validation_errors()
            );
            echo json_encode($response);
            exit();
        } else {
            $up_data = array();
            $group_ids_json = $this->input->post('group_ids');

            $tags = [];
            if (!empty($group_ids_json)) {
                $arr_group_id = json_decode($group_ids_json, true);
                if (!empty($arr_group_id)) {
                    foreach ($arr_group_id as $key => $rt) {
                        $tags[$key] = trim($rt['name']);
                    }
                }
            }

            $inputFileName = $this->input->post('file_name');
            $allDataInSheet = array();
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            } catch (Exception $e) {
                $response = array(
                    'status' => false,
                    'error' => $e->getMessage()
                );
                echo json_encode($response);
                exit();
            }
        }





        if (isset($_FILES["client_file"]["name"])) {
            $path = DEFAULT_ADMIN_UPLOAD_PATH;
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('client_file')) {
                $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                redirect('contacts/add_multiple');
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
                redirect('contacts/add_multiple');
            }
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array('name', 'email', 'phone_number', 'birth_date', 'anniversary_date');
            $makeArray = array('name' => 'name', 'email' => 'email', 'phone_number' => 'phone_number', 'birth_date' => 'birth_date', 'anniversary_date' => 'anniversary_date');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (!empty($value) && in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
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
                    $name = filter_var(trim($allDataInSheet[$i][$SheetDataKey['name']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = filter_var(trim($allDataInSheet[$i][$SheetDataKey['email']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $phone_number = filter_var(trim($allDataInSheet[$i][$SheetDataKey['phone_number']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $birth_date = filter_var(trim($allDataInSheet[$i][$SheetDataKey['birth_date']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $anniversary_date = filter_var(trim($allDataInSheet[$i][$SheetDataKey['anniversary_date']]), FILTER_SANITIZE_SPECIAL_CHARS);
                    $insertData[] = array(
                        'user_id' => $user_id,
                        'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                        'name' => $name,
                        'email' => $email,
                        'phone_number' => substr($phone_number, -10),
                        'phone_number_full' => $phone_number,
                        'birth_date' => date('Y-m-d', strtotime($birth_date)),
                        'anniversary_date' => date('Y-m-d', strtotime($anniversary_date)),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
                if ($this->db->insert_batch(tbl_clients, $insertData)) {
                    $this->session->set_flashdata('success_msg', 'Contacts added successfully!');
                } else {
                    $this->session->set_flashdata('error_msg', 'Something went wrong!');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Please import correct file!');
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Please import file!');
        }
        redirect('contacts/add_multiple');
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $user_id) {
        $where = 'id = ' . $this->db->escape($user_id);
        $check_user = $this->CMS_model->get_result(tbl_clients, $where);
        if ($check_user) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'Contact successfully deleted!');
            } elseif ($action == 'block') {
                $update_array = array(
                    'is_active' => 0
                );
                $this->session->set_flashdata('success_msg', 'Contact successfully deactivated!');
            } elseif ($action == 'activate') {
                $update_array = array(
                    'is_active' => 1
                );
                $this->session->set_flashdata('success_msg', 'Contact successfully activated!');
            }
            $this->CMS_model->update_record(tbl_clients, $where, $update_array);
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('contacts');
    }

    public function map_columns() {
        if (isset($_FILES["client_file"]["name"])) {
            $path = DEFAULT_ADMIN_UPLOAD_PATH;
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('client_file')) {
                $response = array(
                    'status' => false,
                    'error' => $this->upload->display_errors()
                );
                echo json_encode($response);
                exit();
            } else {
                $up_data = array('upload_data' => $this->upload->data());
                
                if (!empty($up_data['upload_data']['file_name'])) {
                    $import_xls_file = $up_data['upload_data']['file_name'];

                    $data['file_name'] = $inputFileName = $path . $import_xls_file;
                    $allDataInSheet = array();
                    try {
                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFileName);


                        $worksheet = $objPHPExcel->getActiveSheet();
                        $allDataInSheet = $worksheet->toArray(null, true, true, true);
                        $data['sheetsdata']['columns'] = $columns = $allDataInSheet[1];
                        $data['sheetsdata']['index'] = array_keys($columns);
                        $startRow = 2;
                        $endRow = $worksheet->getHighestRow();
                        $rowsWithData = [];
                        for ($row = $startRow; $row <= $endRow; $row++) {
                            $hasData = false;
                            $rowData = $allDataInSheet[$row];
                            foreach ($rowData as $rdv) {
                                if (!empty($rdv)) {
                                    $hasData = true;
                                    break;
                                }
                            }
                            if ($hasData) {
                                foreach ($rowData as $rdk => $rdv) {
                                    $rowsWithData[$row][$rdk] = $rdv;
                                }
                            }
                        }
                        $data['sheetsdata']['rows'] = $rowsWithData;
                        echo json_encode(array('status' => true, 'success' => $this->load->view('Clients/map', $data, true)));
                    } catch (Exception $e) {
                        $response = array(
                            'status' => false,
                            'error' => $e->getMessage()
                        );
                    }
                }
            }
        }
    }

}
