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

class Campaign extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model']);
        $this->data = get_admin_data();
        $this->load->library('Excel');
    }

    
    public function index() {
        $user_id = $this->data['user_data']['id'];
        //$this->data['contact_id'] = $id;
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND is_deleted = 0';
        $this->data['automation'] = $this->CMS_model->get_result(tbl_automations, $where);
        $this->load->view("Campaign/index", $this->data);
    }

   
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
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }

        if (!empty($this->input->post('file_name'))) {
            $post = $this->input->post();
            $inputFileName = $post['file_name'];
            unset($post['file_name']);
            $group_ids_json = $post['group_ids'];
            unset($post['group_ids']);
            $is_contry_code_added = $post['is_contry_code_added'];
            unset($post['is_contry_code_added']);
            $country_code = $post['countrycode'];
            unset($post['countrycode']);


            $map_arr = $post;
            /* $required_field = array('name', 'phone_number');
              $diff_arra = array_diff($required_field, $map_arr);

              $validation = true;
              $res_message = '';
              if (in_array('name', $diff_arra)) {
              $validation = false;
              $res_message .= '<p>Name value must be assign to any related field.</p>';
              }
              if (in_array('phone_number', $diff_arra)) {
              $validation = false;
              $res_message .= '<p>Phone Number value must be assign to any related field.</p>';
              }
              if ($validation) { */
            $tags = [];
            if (!empty($group_ids_json)) {
                $arr_group_id = json_decode($group_ids_json, true);
                if (!empty($arr_group_id)) {
                    foreach ($arr_group_id as $key => $rt) {
                        $tags[$key] = trim($rt['name']);
                    }
                }
            }
            if (!empty($inputFileName)) {
                $allDataInSheet = array();
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    //$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $worksheet = $objPHPExcel->getActiveSheet();
                    $allDataInSheet = $worksheet->toArray(null, true, true, true);

                    $columns = $allDataInSheet[1];
                    //$index = array_keys($columns);

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
                    if (!empty($rowsWithData)) {
                        $count = 1;
                        foreach ($rowsWithData as $rwdk => $rwdv) {
                            $finalData[$count] = array(
                                'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                                'user_id' => $user_id,
                                'created_at' => date('Y-m-d H:i:s')
                            );

                            foreach ($map_arr as $mark => $marv) {
                                if ($marv == 'birth_date' || $marv == 'anniversary_date') {
                                    $finalData[$count][$marv] = date('Y-m-d', strtotime($rwdv[$mark]));
                                } elseif ($marv == 'phone_number') {
                                    if ($is_contry_code_added == 'no' && !empty($country_code)) {
                                        $finalData[$count][$marv] = $rwdv[$mark];
                                        $finalData[$count]['phone_number_full'] = $country_code . $rwdv[$mark];
                                    } else {
                                        $finalData[$count][$marv] = filter_var(trim($rwdv[$mark]), FILTER_SANITIZE_SPECIAL_CHARS);
                                        $finalData[$count]['phone_number_full'] = filter_var(trim($rwdv[$mark]), FILTER_SANITIZE_SPECIAL_CHARS);
                                    }
                                } else {
                                    $finalData[$count][$marv] = filter_var(trim($rwdv[$mark]), FILTER_SANITIZE_SPECIAL_CHARS);
                                }
                            }
                            $count++;
                        }
                        if (!empty($finalData)) {
                            if ($this->db->insert_batch(tbl_clients, $finalData)) {
                                if (file_exists($inputFileName)) {
                                    unlink($inputFileName);
                                }
                                $response = array(
                                    'status' => true,
                                    'error' => 'Contacts added successfully!'
                                );
                                echo json_encode($response);
                                exit();
                            } else {
                                $response = array(
                                    'status' => false,
                                    'error' => 'Something went wrong!'
                                );
                                echo json_encode($response);
                                exit();
                            }
                        }
                    }
                } catch (Exception $e) {
                    $response = array(
                        'status' => false,
                        'error' => $e->getMessage()
                    );
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            $response = array(
                'status' => false,
                'error' => 'Please choose file to upload.'
            );
            echo json_encode($response);
            exit();
        }
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

    public function manage_country_code() {
        $this->load->view("Clients/manage_country_code");
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
                        try {
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
                            exit();
                        } catch (Exception $e) {
                            $response = array(
                                'status' => false,
                                'error' => $e->getMessage()
                            );
                            echo json_encode($response);
                            exit();
                        }
                    } catch (Exception $e) {
                        $response = array(
                            'status' => false,
                            'error' => $e->getMessage()
                        );
                        echo json_encode($response);
                        exit();
                    }
                }
            }
        }
    }

    /* =======================
     * Send Templates
      ======================== */

    public function get_templates($contact_id) {
        $data['contact_id'] = base64_decode($contact_id);
        $user_id = $this->data['user_data']['id'];
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED"';
        $data['templates'] = $templates = $this->CMS_model->get_result(tbl_templates, $where);
        $this->load->view("Clients/send-template", $data);
    }

    public function get_template_preview($template_id) {
        $template_id = base64_decode($template_id);

        $seq = 1;
        $where = 'id = ' . $template_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $template_response = array();
        $response = '';
        if ($user_id > 0) {
            $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
            $check_existing_template = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
            if (isset($check_existing_template) && !empty($check_existing_template)) {

                $this->data['template_datas'] = $check_existing_template;
                if ($seq > 0) {
                    $this->data['update_option'] = true;
                    $this->data['temp_add_seq'] = $seq;
                }
                $response = $this->load->view('Clients/template_preview', $this->data, TRUE);
                $template_response['name'] = $check_existing_template['name'];
                $template_response['response'] = $response;
            }
        }
        echo json_encode($template_response);
        die;
    }

    public function check_field_value() {
        $id = base64_decode($this->input->post('contact_id'));
        $field = $this->input->post('field');
        $where = 'id = "' . $id . '"';
        $isEmpty = $this->CMS_model->get_result(tbl_clients, $where, $field, 1);
        if (empty($isEmpty[$field])) {
            echo json_encode(array("status" => false, "error" => '<b>' . $field . '</b> is empty. please provide default value.'));
            exit();
        }
        echo json_encode(array("status" => true));
        exit();
    }

    public function send_template() {
        $template_id = $this->input->post('template_id');
        $post = $this->input->post();

        $default_value = isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '';
        $default_select_value = isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '';
        $header_value = isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '';
        $temp_media = isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '';
        $temp_btn_url = isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '';

        $empty_field = false;
        $empty_field_arr = [];
        if (!empty($header_value)) {
            if (empty($header_value)) {
                $empty_field = true;
                array_push($empty_field_arr, '#header_value');
            }
        }
        if (!empty($default_value)) {
            foreach ($default_value as $dk => $dv) {
                if (empty($dv) && $default_select_value[$dk] == 'null') {
                    $empty_field = true;
                    array_push($empty_field_arr, '#default_value_' . ($dk + 1));
                }
            }
        }
        if (!empty($temp_media)) {
            foreach ($temp_media as $tk => $tv) {
                if (empty($tv)) {
                    $empty_field = true;
                    array_push($empty_field_arr, '#temp_media' . ($dk + 1));
                }
            }
        }

        if (empty($empty_field)) {
            $contact_id = base64_decode($post['contact_id']);
            $contact_info = $this->CMS_model->get_result(tbl_clients, 'id =' . $contact_id, '', 1);

            $where_settings = ' user_id = ' . $contact_info['user_id'];
            $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);

            $template = $this->CMS_model->get_result(tbl_templates, 'id =' . $template_id, '', 1);

            $component_header = $component_body = $component_buttons = array();
            $description = !empty($template['description']) ? json_decode($template['description'], 1) : '';
            if (!empty($description)) {
                $template_name = $template['name'];
                $template_language = $template['temp_language'];

                $message_arr = array(
                    'name' => $template_name,
                    'language' => array('code' => $template_language)
                );
                $chat_logs = array(
                    'user_id' => $contact_info['user_id'],
                    'from_user' => 0,
                    'phone_number' => $contact_info['phone_number_full'],
                    'message_type' => 'template',
                );
                //pr($description, 1);
                $components_arr = [];

                foreach ($description as $kdes => $des) {
                    if (isset($des['type']) && $des['type'] == 'HEADER') {
                        if (isset($des['format']) && !empty($des['format'])) {
                            if ($des['format'] == 'TEXT') {
                                if (!empty($header_value)) {
                                    $component_header[] = array(
                                        'type' => 'text',
                                        'text' => $header_value
                                    );
                                }
                            } else {
                                $component_header[] = array(
                                    'type' => strtolower($des['format']),
                                    strtolower($des['format']) => array(
                                        'link' => $temp_media[1],
                                    )
                                );
                                $chat_logs['media'] = $temp_media[1];
                            }
                        }
                        if (!empty($component_header)) {
                            $components_arr['components'][$kdes] = array('type' => 'header', 'parameters' => $component_header);
                        }
                    } else if (isset($des['type']) && $des['type'] == 'BODY') {
                        if (!empty($default_value)) {
                            foreach ($default_value as $dk => $dv) {
                                if (empty($dv) && $default_select_value[$dk] !== 'null') {
                                    $dv = $contact_info[$default_select_value[$dk]];
                                }
                                $component_body[] = array(
                                    'type' => 'text',
                                    'text' => $dv
                                );
                            }
                            if (!empty($component_body)) {
                                $components_arr['components'][$kdes] = array('type' => 'body', 'parameters' => $component_body);
                            }
                        }
                    } else if (isset($des['type']) && $des['type'] == 'FOOTER') {
                        $footer_text = $des['text'];
                        $components_arr['footer']['parameters'] = array(
                            'type' => strtolower($des['type']),
                            'text' => $footer_text
                        );
                    } else if (isset($des['type']) && $des['type'] == 'BUTTONS') {
                        $buttons = $des['buttons'];
                        foreach ($buttons as $btnk => $btn) {
                            if ($btn['type'] == 'URL') {
                                $payload = isset($temp_btn_url[1]) && !empty($temp_btn_url[1]) ? $temp_btn_url[1] : '';
                                if (strpos($btn['url'], '{{1}}') != false) {
                                    $url = empty($payload) ? $payload : $btn['example'][0];

                                    $component_buttons[] = array(
                                        "type" => 'button',
                                        "sub_type" => "url",
                                        "index" => ($btnk),
                                        "parameters" => array(
                                            array(
                                                'type' => 'payload',
                                                'payload' => $url
                                            )
                                        )
                                    );
                                }
                            }
                        }
                        if (!empty($component_buttons)) {
                            $components_arr['components'][$kdes] = array('type' => 'buttons', 'parameters' => $component_buttons);
                        }
                    }
                }
                $components = new Component($component_header, $component_body, $component_buttons);

                $message_decode['template'] = array_merge($message_arr, $components_arr);
                $chat_logs['message'] = json_encode($message_decode);

                try {

                    $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                        'from_phone_number_id' => $user_settings['phone_number_id'],
                        'access_token' => $user_settings['permanent_access_token'],
                    ]);

                    $wa_response = $this->whatsapp_app_cloud_api->sendTemplate($contact_info['phone_number_full'], $template_name, $template_language, $components);
                    if (!empty($wa_response)) {
                        $Exresponse = new ResponseException($wa_response);
                        $responseData = $Exresponse->responseData();

                        $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                        $chat_logs['message_status'] = !empty($responseData) && isset($responseData['messages'][0]['message_status']) ? $responseData['messages'][0]['message_status'] : '';

                        $return = array(
                            'status' => true
                        );
                    }
                } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                    $responseData = $e->responseData();
                    $return = array(
                        'status' => false,
                        'error' => $responseData['error']['message']
                    );
                }
                $chat_logs['api_response'] = !empty($responseData) ? json_encode($responseData) : '';
                $chat_logs['created'] = date('Y-m-d H:i:s');
                $this->CMS_model->insert_data(tbl_chat_logs, $chat_logs);
            }
        } else {
            $return = array(
                'status' => false,
                'fields' => json_encode($empty_field_arr)
            );
        }
        echo json_encode($return);
        exit();
    }

    public function campaigns() {
        
    }
    
    public function new_campaign() {
        $user_id = $this->data['user_data']['id'];
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED"';
        $data['templates'] = $templates = $this->CMS_model->get_result(tbl_templates, $where);
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id');
        $this->template->load('default_home', 'Clients/create_campaign', $data);
    }
    
    
    
    
    
    
    public function automation($id) {
        $this->data['team'] = '';
        $user_id = $this->data['user_data']['id'];
        $this->data['contact_id'] = $id;
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND is_deleted = 0';
        $this->data['automation'] = $this->CMS_model->get_result(tbl_automations, $where);
        $this->load->view("Clients/manage", $this->data);
    }

    public function add_automation() {
        if ($this->input->post()) {
            $contact_id = base64_decode($this->input->post('contact_id'));
            $automation_id = $this->input->post('automation_id');

            $where = 'id = ' . $contact_id;
            $is_exist = $this->CMS_model->get_result(tbl_clients, $where, '', 1);
            if (!empty($is_exist)) {
                $au_where = 'id = ' . $automation_id;
                $automation = $this->CMS_model->get_result(tbl_automations, $au_where, '', 1);

                /*
                  if (!empty($automation)) {
                  $au_details = !empty($automation['details']) ? json_decode($automation['details'], 1) : '';
                  $delay = $interval = 0;
                  $interval_str = '';
                  $trigger_time = date('H:i', strtotime($automation['trigger_time']));
                  if (!empty($au_details)) {
                  $i = 1;
                  $au_data = array(
                  'user_id' => $automation['user_id'],
                  'automation_id' => $automation_id
                  );

                  foreach ($au_details as $dt) {
                  if (str_contains($dt, 'Minutes') || str_contains($dt, 'Hours') || str_contains($dt, 'Days') || str_contains($dt, 'Weeks')) {
                  $delay = $dt;
                  $minutes = $hours = $days = $weeks = 0;
                  if (str_contains($dt, 'Minutes')) {
                  $minutes = (int) trim(str_replace('Minutes', '', $dt));
                  }
                  if (str_contains($dt, 'Hours')) {
                  $hours = (int) trim(str_replace('Hours', '', $dt));
                  }
                  if (str_contains($dt, 'Days')) {
                  $days = (int) trim(str_replace('Days', '', $dt));
                  }
                  if (str_contains($dt, 'Weeks')) {
                  $weeks = (int) trim(str_replace('Weeks', '', $dt));
                  }

                  $interval_str = 'P';
                  if (!empty($weeks)) {
                  $interval_str .= $weeks . 'W';
                  }
                  if (!empty($days)) {
                  $interval_str .= $days . 'D';
                  }
                  if (!empty($hours) || !empty($minutes)) {
                  $interval_str .= 'T';
                  if (!empty($hours)) {
                  $interval_str .= $hours . 'H';
                  }
                  if (!empty($minutes)) {
                  $interval_str .= $minutes . 'M';
                  }
                  }



                  //$interval_str = 'P' . (!empty($weeks) ? ($weeks * 7) . 'D' : '') . (!empty($days) ? $days . 'D' : '') . 'T' . (!empty($hours) ? $hours . 'H' : '') . (!empty($minutes) ? $minutes . 'M' : '');
                  } else {
                  $templates = create_template_message($automation_id, $i, $dt, '', '', $automation['user_id']);
                  //pr($templates);
                  if (!empty($templates) && isset($templates['BODY']['parameters']) && !empty($templates['BODY']['parameters'])) {
                  $parameters = $templates['BODY']['parameters'];
                  foreach ($parameters as $prmk => $prm) {
                  if (strpos($prm['text'], '_field')) {
                  $field = str_replace('_field', '', $prm['text']);
                  $templates['BODY']['parameters'][$prmk]['text'] = $is_exist[$field];
                  }
                  }
                  }
                  $au_data['automation_template_id'] = $dt;
                  $au_data['temp_param'] = json_encode($templates);


                  $dateTime = new DateTime($trigger_time);
                  if (!empty($interval_str)) {
                  try {
                  $interval = new DateInterval($interval_str);
                  $dateTime->add($interval);
                  } catch (Exception $e) {
                  echo 'Error: ' . $e->getMessage();
                  }
                  }

                  $trigger_time = $dateTime->format('Y-m-d H:i');
                  }
                  $i++;
                  }
                  }
                  } else {
                  $response = array(
                  'status' => false,
                  'error' => 'Something went wrong!'
                  );
                  }

                 */

                //$template = create_template_message($automation_id, 1, );
                if (!empty($automation)) {
                    $update_array['automation_id'] = $automation_id;
                    $this->CMS_model->update_record(tbl_clients, $where, $update_array);
                    echo json_encode(array('status' => true));
                    exit();
                }
            }
        }
        $response = array(
            'status' => false,
            'error' => 'Data not found!'
        );
        echo json_encode($response);
        exit();
    }

    public function remove_automation($id) {
        $where = 'id = ' . base64_decode($id);
        $is_exist = $this->CMS_model->get_result(tbl_clients, $where);
        if (!empty($is_exist)) {
            $update_array['automation_id'] = '';
            $this->CMS_model->update_record(tbl_clients, $where, $update_array);
            $response = array('status' => true);
        } else {
            $response = array('status' => false, 'error' => 'Data not found!');
        }
        echo json_encode($response);
        exit();
    }

}
