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
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

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
        $records = $this->User_model->get_all_user_customers();
        if (!empty($records)) {
            foreach ($records as $rdk => $rd) {
                $tz_date = '';
                if (!empty($rd['created_at']) && $rd['created_at'] != '0000-00-00 00:00:00') {
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['created_at']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['created_at'] = !empty($tz_date) ? date('d M Y', strtotime($tz_date)) . '<br/>' . date('h:i a', strtotime($tz_date)) : '';
            }
        }
        $final['data'] = $records;
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        //$name = '';
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
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id', null, null, 'id', 'DESC');
        //$unique_str = '';
        $this->template->load('default_home', 'Clients/edit', $this->data);
    }

    public function save() {
        //$unique_str = '';
        //pr($this->input->post(), 1);
        if ($this->input->post()) {
            $contact_id = base64_decode($this->input->post('user_id'));
            if ($this->session->userdata('type') == 'user') {
                $user_id = $this->session->userdata('id');
            }
            if ($this->session->userdata('type') == 'member') {
                $user_id = $this->session->userdata('added_by');
            }
            //$email = '';
            $phone_number_full = ltrim($this->input->post('phone_number_full'), '+');
            if (is_numeric($contact_id)) {
                $where = 'id = ' . $this->db->escape($contact_id);
                $check_users = $this->CMS_model->get_result(tbl_clients, $where);
                if ($check_users) {
                    $check_users = $check_users[0];
                    //$email = $check_users['email'];
                }
                $phoneValidation = '';
            }

            /* if ($email == "") {
              $unique_str = '|is_unique[' . tbl_clients . '.email]';
              } else {
              if ($email != $this->input->post('email')) {
              $unique_str = '|is_unique[' . tbl_clients . '.email]';
              }
              }
              $this->form_validation->set_rules('email', 'Email', 'trim|required' . $unique_str, array('is_unique' => 'This %s already exists.'));
              $this->form_validation->set_rules('email', 'Email', 'trim|required');
              $this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required');
              $this->form_validation->set_rules('anniversary_date', 'Anniversary Date', 'trim|required');
             */

            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            
            if (empty($contact_id)) {
                $is_deleted = 0;
                $is_exist = $this->db->get_where(tbl_clients, array('phone_number_full' => $phone_number_full, 'user_id' => $user_id))->row_array();
                
                if (!empty($is_exist) && $is_exist['is_deleted'] == 1) {
                    $contact_id = $is_exist['id'];
                    $is_deleted = 1;
                } else {
                    $this->form_validation->set_rules('phone_number_full', 'Phone', 'trim|required|callback_unique_number[' . $this->input->post('phone_number_full') . ']');
                }
            }
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                $url = base_url() . 'contacts/add';
                if (is_numeric($contact_id)) {
                    $id = base64_encode($contact_id);
                    $url = base_url() . 'contacts/edit/' . $id;
                }
                redirect($url);
            } else {
//                pr($this->input->post(), 1);
                $where = array('id' => $contact_id);
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
                $birthdate = $this->input->post('birth_date');
                $anniversary_date = $this->input->post('anniversary_date');

                $update_array = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                    'phone_number' => $this->input->post('phone_number'),
                    'phone_number_full' => $phone_number_full,
                    'birth_date' => !empty($birthdate) ? date('Y-m-d', strtotime($birthdate)) : '',
                    'anniversary_date' => !empty($anniversary_date) ? date('Y-m-d', strtotime($anniversary_date)) : '',
                    'is_deleted' => 0
                ];
                
                $dynamic_columns = $this->input->post('column');
                if (!empty($dynamic_columns)) {
                    $column = 1;
                    foreach ($dynamic_columns as $key => $dc) {
                        $update_array['column' . $column++] = $dc;
                    }
                }
                if (is_numeric($contact_id)) {
                    $this->CMS_model->update_record(tbl_clients, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Contact updated successfully !');
                } else {
                    $update_array['user_id'] = $user_id;
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_clients, $update_array);
                    $this->session->set_flashdata('success_msg', 'Contact created successfully!');
                }
                redirect('contacts');
            }
        }
    }

    public function unique_number($phone) {
        $phone_number_full = ltrim($phone, '+');

        $user_id = $this->session->userdata('id');
        $where = ' user_id = ' . $user_id . ' and phone_number_full=' . $phone_number_full;
        $is_exist = $this->CMS_model->get_result(tbl_clients, $where, null, 1);
        if (!empty($is_exist)) {
            $this->form_validation->set_message('unique_number', 'This number is already exists!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function add_multiple() {
        $this->template->set('title', 'Add Mutliple Clients');
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id', null, null, 'id', 'DESC');
        $this->template->load('default_home', 'Clients/add_multiple', $this->data);
    }

    public function save_multiple() {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }

        if (!empty($this->input->post('file_name')) && !empty($user_id)) {
            $post = $this->input->post();
            $inputFileName = $post['file_name'];
            unset($post['file_name']);

            $group_ids_json = $post['group_ids'];
            unset($post['group_ids']);
            $is_contry_code_added = $post['is_contry_code_added'];
            unset($post['is_contry_code_added']);
            $country_code = $post['countrycode'];
            unset($post['countrycode']);
            $skip_or_merge = $post['skip_or_merge'];
            unset($post['skip_or_merge']);

            $map_arr = $post;

            $tags = [];
            if (!empty($group_ids_json)) {
                $arr_group_id = json_decode($group_ids_json, true);
                if (!empty($arr_group_id)) {
                    foreach ($arr_group_id as $key => $rt) {
                        $tags[$key] = trim($rt['name']);
                    }
                }
            }

            try {
                ini_set('memory_limit', '512M');
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setReadDataOnly(true);

                $objPHPExcel = $objReader->load($inputFileName);
                $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();

                $chunkSize = 1000;

                // Initialize arrays for processing
                $rowsWithData = [];
                for ($startRow = 2; $startRow <= $highestRow; $startRow += $chunkSize) {
                    $endRow = min($startRow + $chunkSize - 1, $highestRow);
                    $range = 'A' . $startRow . ':Z' . $endRow; // Adjust the columns as needed
                    $worksheet = $objPHPExcel->getActiveSheet()->rangeToArray($range, null, true, true, true);

                    foreach ($worksheet as $row) {
                        if (array_filter($row)) {
                            $rowsWithData[] = $row; // Store rows with data
                        }
                    }
                }

                if (!empty($rowsWithData)) {
                    $count = 1;
                    $finalData = $updateData = [];
                    $contact_arr = $contact_exist_arr = $contact_duplicate_arr = $contact_invalid_arr = [];

                    $user_existing_contacts = $this->getExistingContacts($user_id);
                    //pr($user_existing_contacts, 1);
                    foreach ($rowsWithData as $rwdk => $rwdv) {
                        $contact_data = array(
                            'group_ids' => !empty($tags) ? implode(',', $tags) : '',
                            'user_id' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $phone_number_full = '';
                        $checked = 0;
                        foreach ($map_arr as $mark => $marv) {
                            if ($marv == 'birth_date' || $marv == 'anniversary_date') {
                                $contact_data[$marv] = date('Y-m-d', strtotime($rwdv[$mark]));
                            } elseif ($marv == 'phone_number') {
                                if ($is_contry_code_added == 'no' && !empty($country_code)) {
                                    $phone_number_full = $country_code . $rwdv[$mark];
                                } else {
                                    $phone_number_full = filter_var(trim($rwdv[$mark]), FILTER_SANITIZE_SPECIAL_CHARS);
                                }
                                $isValid = $this->isValidNumber($phone_number_full);
                                if (!empty($isValid)) {
                                    $contact_data[$marv] = $isValid['phone_number'];
                                    $contact_data['phone_number_full'] = $isValid['country_code'] . $isValid['phone_number'];
                                } else {
                                    $contact_invalid_arr[] = $rwdv[$mark];
                                    $checked = 1;
                                }
                                if (empty($checked) && !empty($contact_arr) && in_array($phone_number_full, $contact_arr)) {
                                    $contact_duplicate_arr[] = $phone_number_full;
                                    $checked = 1;
                                }
                            } else {
                                $contact_data[$marv] = filter_var(trim($rwdv[$mark]), FILTER_SANITIZE_SPECIAL_CHARS);
                            }
                        }
                        $contact_arr[] = $phone_number_full;
                        if (empty($checked)) {
                            if (isset($user_existing_contacts[$phone_number_full])) {
                                $existin_contact = $user_existing_contacts[$phone_number_full];
                                $contact_tag_string = !empty($existin_contact['group_ids']) ? $existin_contact['group_ids'] : '';
                                $contact_tag_arr = !empty($contact_tag_string) ? explode(',', $contact_tag_string) : '';
                                $tag_diff = [];
                                if (!empty($tags)) {
                                    if (!empty($contact_tag_arr)) {
                                        $tag_diff = array_diff($tags, $contact_tag_arr);
                                    } else {
                                        $tag_diff = $tags;
                                    }
                                }
                                if (!empty($tag_diff)) {
                                    $contact_tag_string .= !empty($contact_tag_string) ? ',' . implode(',', $tag_diff) : implode(',', $tag_diff);
                                }
                                $contact_data['group_ids'] = $contact_tag_string;

                                if ($skip_or_merge == 'merge') {
                                    $updateData[$count] = $contact_data;
                                    $updateData[$count]['id'] = $existin_contact['id'];
                                } else {
                                    if (!empty($tag_diff)) {
                                        $updateData[$count] = array(
                                            'id' => $existin_contact['id'],
                                            'group_ids' => $contact_tag_string
                                        );
                                    }
                                }
                                $contact_exist_arr[] = $phone_number_full;
                            } else {
                                $finalData[$count] = $contact_data;
                            }
                        }
                        $count++;
                    }
                    if (!empty($updateData)) {
                        $this->db->update_batch(tbl_clients, $updateData, 'id');
                    }

                    if (!empty($finalData)) {
                        if ($this->db->insert_batch(tbl_clients, $finalData)) {
                            if (file_exists($inputFileName)) {
                                unlink($inputFileName);
                            }
                            $response = array(
                                'status' => true,
                                'error' => 'Contacts added successfully!',
                                'total_records' => !empty($contact_arr) ? count($contact_arr) : 0,
                                'duplicate_records' => !empty($contact_duplicate_arr) ? count($contact_duplicate_arr) : 0,
                                'exist_records' => !empty($contact_exist_arr) ? count($contact_exist_arr) : 0,
                                'invalid_records' => !empty($contact_invalid_arr) ? count($contact_invalid_arr) : 0,
                                'saved_records' => count($finalData),
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
                    } else {
                        $response = array(
                            'status' => true,
                            'error' => 'Contacts added successfully!',
                            'total_records' => !empty($contact_arr) ? count($contact_arr) : 0,
                            'duplicate_records' => !empty($contact_duplicate_arr) ? count($contact_duplicate_arr) : 0,
                            'exist_records' => !empty($contact_exist_arr) ? count($contact_exist_arr) : 0,
                            'invalid_records' => !empty($contact_invalid_arr) ? count($contact_invalid_arr) : 0,
                            'saved_records' => count($finalData),
                        );
                        echo json_encode($response);
                        exit();
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
        } else {
            $response = array(
                'status' => false,
                'error' => 'Please choose file to upload.'
            );
            echo json_encode($response);
            exit();
        }
    }

    private function getExistingContacts($user_id) {
        $contacts_arr = $this->CMS_model->get_result(tbl_clients, ' user_id = ' . $user_id);
        $existing_contacts = [];
        foreach ($contacts_arr as $contact) {
            $existing_contacts[$contact['phone_number_full']] = $contact;
        }
        return $existing_contacts;
    }

    function isValidNumber($phoneNumber) {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $parsedNumber = $phoneUtil->parse('+' . $phoneNumber, null);
            $isValid = $phoneUtil->isValidNumber($parsedNumber);
            if (!empty($isValid) && !empty($parsedNumber)) {
                $return['country_code'] = $parsedNumber->getCountryCode();
                $return['phone_number'] = $parsedNumber->getNationalNumber();
                return $return;
            } else {
                return false;
            }
        } catch (NumberParseException $e) {
            return false;
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
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED" AND is_deleted = 0';
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

    public function get_single_template_preview($template_id, $seq = '') {
        $template_id = base64_decode($template_id);
        $seq = base64_decode($seq);

        $template_response = array();
        $response = '';
        if (!empty($template_id)) {
            if ($this->session->userdata('type') == 'user') {
                $user_id = $this->session->userdata('id');
                $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
            } else if ($this->session->userdata('type') == 'member') {
                $user_id = $this->session->userdata('added_by');
                $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
            } else {
                $where = ' id=' . $template_id;
            }

            if (!empty($seq)) {
                $this->data['seq'] = $seq;
            }

            $check_existing_template = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
            if (isset($check_existing_template) && !empty($check_existing_template)) {
                $this->data['template_datas'] = $check_existing_template;
                $response = $this->load->view('Clients/single_template_preview', $this->data, TRUE);
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
        $card_media = isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '';
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
        if (isset($post['temp_media']) && empty($post['temp_media'])) {
            $empty_field = true;
            array_push($empty_field_arr, '#temp_media');
        }

        if (!empty($card_media)) {
            foreach ($card_media as $cmk => $cmv) {
                if (empty($cmv)) {
                    $empty_field = true;
                    array_push($empty_field_arr, '#card_media_' . ($cmk + 1));
                }
            }
        }

        if (empty($empty_field)) {
            $contact_id = base64_decode($post['contact_id']);
            $contact_info = $this->CMS_model->get_result(tbl_clients, 'id =' . $contact_id, '', 1);

            $where_settings = ' user_id = ' . $contact_info['user_id'];
            $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);

            $template = $this->CMS_model->get_result(tbl_templates, 'id =' . $template_id, '', 1);

            //$response = create_template_body($post, $contact_info);
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
                $components_arr = [];
                foreach ($description as $kdes => $des) {
                    $new_default_value = $default_value;
                    $new_default_select_value = $default_select_value;
                    $new_temp_btn_url = $temp_btn_url;
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
                                        'link' => $temp_media,
                                    )
                                );
                                $chat_logs['media'] = $temp_media;
                            }
                        }
                        if (!empty($component_header)) {
                            $components_arr['components'][$kdes] = array('type' => 'header', 'parameters' => $component_header);
                        }
                    } else if (isset($des['type']) && $des['type'] == 'BODY') {
                        if (isset($des['example']) && !empty($des['example'])) {
                            if (isset($des['example']['body_text']) && !empty($des['example']['body_text'])) {
                                $body_text = $des['example']['body_text'][0];
                                foreach ($body_text as $bTextK => $bTextV) {
                                    if (!empty($new_default_select_value[$bTextK])) {
                                        if (!empty($contact_info[$new_default_select_value[$bTextK]])) {
                                            $bTextV = $contact_info[$new_default_select_value[$bTextK]];
                                        } else {
                                            $bTextV = $new_default_value[$bTextK];
                                        }
                                    } else {
                                        $bTextV = $new_default_value[$bTextK];
                                    }
                                    $component_body[] = array(
                                        'type' => 'text',
                                        'text' => $bTextV
                                    );
                                }
                                array_splice($new_default_value, 0, count($body_text));
                                array_splice($default_select_value, 0, count($body_text));
                                if (!empty($component_body)) {
                                    $components_arr['components'][$kdes] = array('type' => 'body', 'parameters' => $component_body);
                                }
                            }
                        }
                    } else if (isset($des['type']) && $des['type'] == 'CAROUSEL') {
                        $cards = $des['cards'];
                        $card_arr = [];
                        if (!empty($cards)) {
                            $carousel = [];
                            foreach ($cards as $cardK => $cardV) {
                                $card_component = $card_component_header = $card_component_body = $card_component_buttons = array();
                                $cardComponents = $cardV['components'];
                                foreach ($cardComponents as $cardCompK => $cardCompV) {
                                    if ($cardCompV['type'] == 'HEADER') {
                                        $card_component_header[] = array(
                                            'type' => strtolower($cardCompV['format']),
                                            strtolower($cardCompV['format']) => array(
                                                'link' => $card_media[$cardK],
                                            )
                                        );
                                        if (!empty($card_component_header)) {
                                            $card_component[] = array('type' => 'header', 'parameters' => $card_component_header);
                                        }
                                    } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BODY') {
                                        if (isset($cardCompV['example']) && !empty($cardCompV['example'])) {
                                            if (isset($cardCompV['example']['body_text']) && !empty($cardCompV['example']['body_text'])) {
                                                $card_body_text = $cardCompV['example']['body_text'][0];
                                                foreach ($card_body_text as $CbTextK => $CbTextV) {
                                                    if (!empty($new_default_select_value[$CbTextK])) {
                                                        if (!empty($contact_info[$new_default_select_value[$CbTextK]])) {
                                                            $CbTextV = $contact_info[$new_default_select_value[$CbTextK]];
                                                        } else {
                                                            $CbTextV = $new_default_value[$CbTextK];
                                                        }
                                                    } else {
                                                        $CbTextV = $new_default_value[$CbTextK];
                                                    }
                                                    $card_component_body[] = array(
                                                        'type' => 'text',
                                                        'text' => $CbTextV
                                                    );
                                                }
                                                array_splice($new_default_value, 0, count($body_text));
                                                array_splice($default_select_value, 0, count($body_text));
                                                if (!empty($card_component_body)) {
                                                    $card_component[] = array('type' => 'body', 'parameters' => $card_component_body);
                                                }
                                            }
                                        }
                                    } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BUTTONS') {
                                        $cardbuttons = $cardCompV['buttons'];
                                        foreach ($cardbuttons as $cbtnk => $cbtn) {
                                            if ($cbtn['type'] == 'URL') {
                                                $payload = isset($new_temp_btn_url[$cardK]) && !empty($new_temp_btn_url[$cardK]) ? $temp_btn_url[$cardK] : '';
                                                if (strpos($cbtn['url'], '{{1}}') != false) {
                                                    $url = !empty($payload) ? $payload : $cbtn['example'][0];
                                                    $card_component_buttons = array(
                                                        "type" => 'button',
                                                        "sub_type" => "url",
                                                        "index" => ($cbtnk),
                                                        "parameters" => array(
                                                            array(
                                                                'type' => 'payload',
                                                                'payload' => $url
                                                            )
                                                        )
                                                    );
                                                }
                                            }
                                            if (!empty($card_component_buttons)) {
                                                $card_component[] = $card_component_buttons;
                                            }
                                        }
                                    }
                                }

                                if (!empty($card_component)) {
                                    $carousel[] = array('card_index' => $cardK, 'components' => $card_component);
                                    $components_arr['components'][$kdes] = array('type' => 'carousel', 'cards' => $carousel);
                                }
                            }
                        }
                        /* } else if (isset($des['type']) && $des['type'] == 'FOOTER') {
                          $footer_text = $des['text'];
                          $components_arr['footer']['parameters'] = array(
                          'type' => strtolower($des['type']),
                          'text' => $footer_text
                          ); */
                    } else if (isset($des['type']) && $des['type'] == 'BUTTONS') {
                        $buttons = $des['buttons'];
                        foreach ($buttons as $btnk => $btn) {
                            if ($btn['type'] == 'URL') {
                                $payload = isset($temp_btn_url[$btnk]) && !empty($temp_btn_url[$btnk]) ? $temp_btn_url[$btnk] : '';
                                if (strpos($btn['url'], '{{1}}') != false) {
                                    $url = !empty($payload) ? $payload : $btn['example'][0];

                                    $component_buttons = array(
                                        "type" => 'button',
                                        "sub_type" => "url",
                                        "index" => ($btnk),
                                        "parameters" => array(
                                            array(
                                                'type' => 'payload',
                                                'payload' => $payload
                                            )
                                        )
                                    );
                                }
                            }
                        }
                        if (!empty($component_buttons)) {
                            $components_arr['components'][$kdes] = $component_buttons;
                        }
                    }
                }
                $components = new Component($component_header, $component_body, $component_buttons);

                $message_decode['template'] = array_merge($message_arr, $components_arr);
                $template_info['components'] = $message_decode;

                $curl_data = array(
                    'from_phone_number_id' => $user_settings['phone_number_id'],
                    'access_token' => $user_settings['permanent_access_token'],
                    'to' => $contact_info['phone_number_full'],
                );

                $curl_data = array_merge($curl_data, $template_info);
                $chat_logs['message'] = json_encode($message_decode);
                try {
                    $responseData = curlSendTemplate($curl_data);
                    $responseData = json_decode($responseData, 1);
                    if (!empty($responseData)) {
                        $chat_logs['message_id'] = !empty($responseData) && isset($responseData['messages'][0]['id']) ? $responseData['messages'][0]['id'] : '';
                        $chat_logs['message_status'] = !empty($responseData) && isset($responseData['messages'][0]['message_status']) ? $responseData['messages'][0]['message_status'] : '';

                        $return = array(
                            'status' => true
                        );
                    }
                } catch (Exception $e) {
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

    public function change_status() {
        $id = base64_decode($this->input->post('id'));
        $status = $this->input->post('status');
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }
        $where = ' user_id = ' . $user_id . ' and id=' . $id;
        $is_exist = $this->CMS_model->get_result(tbl_clients, $where, null, 1);
        if (!empty($is_exist)) {
            $update_array['is_subscribed'] = $status == 'subscribed' ? '0' : '1';
            $this->CMS_model->update_record(tbl_clients, $where, $update_array);
            $return = array('status' => true);
        } else {
            $return = array('status' => false);
        }
        echo json_encode($return);
    }

    public function delete_contact($id) {
        if (!empty($id)) {
            $contact_info = $this->db->get_where(tbl_clients, array('id' => $id))->row_array();
            if (!empty($contact_info)) {
                $this->CMS_model->delete_data(tbl_clients, 'id=' . $id);
                $response = array(
                    'status' => true,
                );
            } else {
                $response = array(
                    'status' => false,
                    'error' => 'Data not found!'
                );
            }
        } else {
            $response = array(
                'status' => false,
                'error' => 'Data not found!'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function delete_contacts() {
        $ids = $this->input->post();

        if (!empty($ids)) {
            $id = implode(',', $ids);
            $this->CMS_model->delete_multiple('id', json_decode($id), tbl_clients);
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

    public function campaigns() {
        $user_id = $this->data['user_data']['id'];
        //$this->data['contact_id'] = $id;
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND is_deleted = 0';
        $this->data['automation'] = $this->CMS_model->get_result(tbl_automations, $where);
        $this->load->view("Clients/manage", $this->data);
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
                if (!empty($automation)) {

                    $au_details = !empty($automation['details']) ? json_decode($automation['details'], 1) : '';

                    $delay = $interval = 0;
                    $interval_str = '';

                    $trigger_time = date('H:i', strtotime($automation['trigger_time']));
                    $dateTime = new DateTime($trigger_time);
                    $notification_date_time = $dateTime->format('Y-m-d H:i');
                    if (!empty($au_details)) {
                        //$i = 1;
                        if ($this->session->userdata('type') == 'user') {
                            $user_id = $this->session->userdata('id');
                        }
                        if ($this->session->userdata('type') == 'member') {
                            $user_id = $this->session->userdata('added_by');
                        }

                        $inquiry_arr = array(
                            'name' => !empty($is_exist['name']) ? $is_exist['name'] : '',
                            'user_id' => $user_id,
                            'send_to' => $contact_id,
                            'inquiry_type' => 1,
                            'phone_number' => !empty($is_exist['phone_number']) ? $is_exist['phone_number'] : '',
                            'phone_number_full' => !empty($is_exist['phone_number_full']) ? $is_exist['phone_number_full'] : '',
                            'automation_id' => $automation_id,
                        );
                        $inquiry_id = $this->CMS_model->insert_data(tbl_inquiries, $inquiry_arr);

                        $selected_values = isset($automation['selected_values']) && !empty($automation['selected_values']) ? json_decode($automation['selected_values'], 1) : '';
                        $default_value = isset($automation['template_values']) && !empty($automation['template_values']) ? json_decode($automation['template_values'], 1) : '';
                        $template_media = isset($automation['template_media']) && !empty($automation['template_media']) ? json_decode($automation['template_media'], 1) : '';
                        $template_button_url = isset($automation['template_button_url']) && !empty($automation['template_button_url']) ? json_decode($automation['template_button_url'], 1) : '';
                        $inquiry_log = [];
                        foreach ($au_details as $dtkey => $dt) {
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

                                $interval = new DateInterval($interval_str);
                                $dateTime = $dateTime->add($interval);
                                //$interval_str = 'P' . (!empty($weeks) ? ($weeks * 7) . 'D' : '') . (!empty($days) ? $days . 'D' : '') . 'T' . (!empty($hours) ? $hours . 'H' : '') . (!empty($minutes) ? $minutes . 'M' : '');
                            } else {
                                $notification_date_time = $dateTime->format('Y-m-d H:i');

                                $input_arr['template_id'] = $dt;
                                $input_arr['default_select_header_value'] = isset($selected_values[$dtkey]) && !empty($selected_values[$dtkey]) ? $selected_values[$dtkey] : '';
                                $input_arr['header_value'] = isset($selected_values[$dtkey]) && !empty($selected_values[$dtkey]) ? $selected_values[$dtkey] : '';
                                $input_arr['default_select_value'] = isset($selected_values[$dtkey]) && !empty($selected_values[$dtkey]) ? $selected_values[$dtkey] : '';
                                $input_arr['default_value'] = isset($default_value[$dtkey]) && !empty($default_value[$dtkey]) ? $default_value[$dtkey] : '';
                                $input_arr['temp_media'] = isset($template_media[$dtkey]) && !empty($template_media[$dtkey]) ? $template_media[$dtkey] : '';
                                $input_arr['temp_btn_url'] = isset($template_button_url[$dtkey]) && !empty($template_button_url[$dtkey]) ? $template_button_url[$dtkey] : '';
                                $input_arr['card_media'] = isset($template_media[$dtkey]) && !empty($template_media[$dtkey]) ? $template_media[$dtkey] : '';

                                $response_arr = create_template_body($input_arr, $is_exist);
                                $temp_param['template'] = !empty($response_arr) && isset($response_arr) ? $response_arr['template'] : '';
                                $inquiry_log[] = array(
                                    'user_id' => $user_id,
                                    'inquiry_id' => $inquiry_id,
                                    'automation_id' => $automation_id,
                                    'automation_template_id' => $dt,
                                    'temp_param' => json_encode($temp_param),
                                    'notification_date' => getServerTimeZone($notification_date_time, true)
                                );
                                //pr($response_arr);

                                /* $templates = create_template_message($automation_id, $i, $dt, '', '', $automation['user_id']);
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
                                  } */

                                //$trigger_time = $dateTime->format('Y-m-d H:i');
                            }
                        }
                        if (!empty($inquiry_log)) {
                            $this->CMS_model->insert_batch(tbl_inquiry_logs, $inquiry_log);
                            echo json_encode(array('status' => true));
                            exit();
                        }
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'error' => 'Something went wrong!'
                    );
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
