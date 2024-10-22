<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model', 'Indiamart_inquiries_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $this->template->set('title', 'Users');
        $this->template->load('default_home', 'Users/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_users() {
        $final['recordsTotal'] = $this->User_model->get_all_users('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->User_model->get_all_users();
        echo json_encode($final);
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function clients($user_id) {
        $this->data['user_id'] = $user_id;
        $this->template->set('title', 'Clients');
        $this->template->load('default_home', 'Users/clients', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_user_clients($user_id) {
        $user_id = base64_decode($user_id);
        $final['recordsTotal'] = $this->User_model->get_all_user_customers('count', '', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->User_model->get_all_user_customers(null, '', $user_id);
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
            $check_users = $this->CMS_model->get_result(tbl_users, $where);
            if ($check_users) {
                $this->data['user_datas'] = $check_users[0];
            } else {
                show_404();
            }
        }
        $this->template->load('default_home', 'Users/edit', $this->data);
    }

    function check_unique_user($email) {
        $where = 'email = ' . $this->db->escape($email) . ' AND is_deleted = 0 AND type="user"';
        $check_user = $this->CMS_model->get_result(tbl_users, $where, null, 1);
        if (!empty($check_user)) {
            $this->form_validation->set_message('check_unique_user', 'User already Exists!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function save() {
        $unique_str = '';
        if ($this->input->post()) {
            $user_id = base64_decode($this->input->post('user_id'));
            $email = '';
            if (is_numeric($user_id)) {
                $where = 'id = ' . $this->db->escape($user_id);
                $check_users = $this->CMS_model->get_result(tbl_users, $where);
                if ($check_users) {
                    $check_users = $check_users[0];
                    $email = $check_users['email'];
                }
            }
            if ($email == "") {
                $unique_str = "|callback_check_unique_user[" . $this->input->post('email') . "]";
            } else {
                if ($email != $this->input->post('email')) {
                    $unique_str = "|callback_check_unique_user[" . $this->input->post('email') . "]";
                }
            }
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required' . $unique_str);
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            if (is_numeric($user_id)) {
                
            } else {
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                $url = base_url() . 'users/add';
                if (is_numeric($user_id)) {
                    $id = base64_encode($user_id);
                    $url = base_url() . 'users/edit/' . $id;
                }
                redirect($url);
            } else {
                $where = array('id' => $user_id);
                $update_array = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'phone_number_full' => ltrim($this->input->post('phone_number_full'), '+'),
                    'type' => ($this->input->post('type') != '') ? $this->input->post('type') : 'user',
                    'waba_access' => $this->input->post('waba_access') == 'on' ? 1 : 0,
                    'crm_lead_access' => $this->input->post('crm_lead_access') == 'on' ? 1 : 0,
                ];
                if (is_numeric($user_id)) {
                    $this->CMS_model->update_record(tbl_users, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'User updated successfully !');
                } else {
                    $password = $this->input->post('password');
                    $update_array['password'] = $this->encrypt->encode($password);
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_users, $update_array);
                    $this->session->set_flashdata('success_msg', 'User created successfully!');
                }
                redirect('users');
            }
        }
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $user_id) {
        $where = 'id = ' . $this->db->escape($user_id);
        $check_user = $this->CMS_model->get_result(tbl_users, $where);
        $check_user_settings = $this->CMS_model->get_result(tbl_user_settings, $where);
        if ($check_user) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'User successfully deleted!');
            } elseif ($action == 'block') {
                $update_array = array(
                    'is_active' => 0
                );
                $this->session->set_flashdata('success_msg', 'User successfully deactivated!');
            } elseif ($action == 'activate') {
                $update_array = array(
                    'is_active' => 1
                );
                $this->session->set_flashdata('success_msg', 'User successfully activated!');
            }
            $this->CMS_model->update_record(tbl_users, $where, $update_array);
            if ($check_user_settings) {
                $where_user = 'user_id = ' . $this->db->escape($user_id);
                $this->CMS_model->update_record(tbl_user_settings, $where_user, $update_array);
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('users');
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function settings($user_id = '') {
        if (!empty($user_id)) {
            $user_id = base64_decode($user_id);
        } else {
            if ($this->data['user_data']['type'] == 'user') {
                $user_id = $this->data['user_data']['id'];
            }
        }

        if (is_numeric($user_id)) {
            $where = 'id = ' . $this->db->escape($user_id);
            $check_users = $this->CMS_model->get_result(tbl_users, $where);
            if ($check_users) {
                $this->data['user_datas'] = $check_users[0];
            } else {
                show_404();
            }
            $where = 'user_id = ' . $this->db->escape($user_id);
            $this->data['user_settings'] = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1, null, 'id', 'desc');
            $this->data['templates'] = get_user_meta_templates($user_id);
            $this->data['time_zone'] = get_time_zone();
            
            $tgwhere = 'user_id = ' . $this->db->escape($user_id);
            $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id', null, null, 'id', 'DESC');
            if ($this->session->userdata('type') == 'user' && $this->data['user_settings']) {
                if ($this->data['user_settings']['message_on_inquiry']) {
                    $where = 'user_id = ' . $this->db->escape($user_id) . ' AND template_for = "inquiry_template"';
                    $inquiry_template_arr = $this->CMS_model->get_result(tbl_default_templates, $where, null, 1);

                    if (!empty($inquiry_template_arr)) {
                        $this->data['user_settings']['inquiry_temp'] = $inquiry_template_arr;
                    }
                }
            }
        }
        //pr($this->data, 1);
        $this->template->set('title', 'Settings');
        $this->template->load('default_home', 'Users/settings', $this->data);
    }

    function unique_access_token($permanent_access_token, $setting_id) {
        $where = 'permanent_access_token = ' . $this->db->escape($permanent_access_token) . ' AND is_deleted = 0';
        if (!empty($setting_id)) {
            $where .= ' AND id != ' . $setting_id;
        }
        $check_permanent_access_token = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1);
        if (!empty($check_permanent_access_token)) {
            $this->form_validation->set_message('unique_access_token', 'Permanent Access Token already Exists!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function unique_phone_id($phone_number_id, $setting_id) {
        $where = 'phone_number_id = ' . $this->db->escape($phone_number_id) . ' AND is_deleted = 0';
        if (is_numeric($setting_id)) {
            $where .= ' AND id != ' . $this->db->escape($setting_id);
        }
        $check_phone_number_id = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1);

        if (!empty($check_phone_number_id)) {
            $this->form_validation->set_message('unique_phone_id', 'Phone Number ID already Exists!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function unique_business_id($business_account_id, $setting_id) {
        $where = 'business_account_id = ' . $this->db->escape($business_account_id) . ' AND is_deleted = 0';
        if (is_numeric($setting_id)) {
            $where .= ' AND id != ' . $this->db->escape($setting_id);
        }
        $check_business_account_id = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1);
        if (!empty($check_business_account_id)) {
            $this->form_validation->set_message('unique_business_id', 'Business Account ID already Exists!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function settings_save() {

        if ($this->input->post()) {
            $user_id = base64_decode($this->input->post('user_id'));
            $setting_id = base64_decode($this->input->post('setting_id'));
            $access_token = '';
            $permanent_access_token = '';
            $api_token = '';
            if ($this->session->userdata('type') == 'admin') {
                $url = base_url() . 'users/settings/' . base64_encode($user_id);
            } else {
                $url = base_url() . 'users/settings/';
            }


            if (is_numeric($setting_id)) {
                $id = base64_encode($user_id);
                $where = 'id = ' . $this->db->escape($setting_id);
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1, null, 'id', 'desc');
                if ($user_settings) {
                    $access_token = $user_settings['access_token'];
                    $permanent_access_token = $user_settings['permanent_access_token'];
                    $api_token = $user_settings['api_token'];
                }
            }

            $this->form_validation->set_rules('time_zone', 'Time Zone', 'trim|required');

            if ($this->data['user_data']['waba_access'] == 1) {
                $this->form_validation->set_rules('permanent_access_token', 'Permanent Access Token', 'trim|required');
                $this->form_validation->set_rules('phone_number_id', 'Phone Number ID', 'trim|required|callback_unique_phone_id[' . $setting_id . ']');
                $this->form_validation->set_rules('business_account_id', 'Business Account ID', 'trim|required|callback_unique_business_id[' . $setting_id . ']');
            }
            if ($this->session->userdata('type') == 'user') {
                $message_on_inquiry = $forward_inquiry_details = 0;
                if (!empty($this->input->post('message_on_inquiry'))) {
                    $message_on_inquiry = 1;
                    $inquiry_template = $this->input->post('inquiry_template');
                    if (empty($inquiry_template)) {
                        $this->form_validation->set_rules('inquiry_template', 'Inquiry template', 'trim|required');
                    }
                }

                $forward_text = 0;
                if (!empty($this->input->post('forward_text'))) {
                    $forward_text = 1;
                    $forward_to = $this->input->post('forward_to');
                    if (empty($forward_to)) {
                        $this->form_validation->set_rules('forward_to', 'Whatsapp No', 'trim|required');
                    }
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
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
                
                
                
                
                $time_zone = $this->input->post('time_zone');

                $where = array('id' => $setting_id);
                $update_array = [
                    'app_id' => $this->input->post('app_id'),
                    'access_token' => $this->input->post('access_token'),
                    'instance_id' => $this->input->post('instance_id'),
                    'permanent_access_token' => $this->input->post('permanent_access_token'),
                    'phone_number_id' => $this->input->post('phone_number_id'),
                    'business_account_id' => $this->input->post('business_account_id'),
                    'crm_key' => ($this->input->post('crm_key') != '') ? $this->input->post('crm_key') : '',
                    'tradeindia_user_id' => ($this->input->post('tradeindia_user_id') != '') ? $this->input->post('tradeindia_user_id') : '',
                    'tradeindia_profile_id' => ($this->input->post('tradeindia_profile_id') != '') ? $this->input->post('tradeindia_profile_id') : '',
                    'tradeindia_key' => ($this->input->post('tradeindia_key') != '') ? $this->input->post('tradeindia_key') : '',
                    'exportersindia_key' => ($this->input->post('exportersindia_key') != '') ? $this->input->post('exportersindia_key') : '',
                    'exportersindia_email' => ($this->input->post('exportersindia_email') != '') ? $this->input->post('exportersindia_email') : '',
                    'user_id' => $user_id,
                    'default_tags' => !empty($tags) ? implode(',', $tags) : '',
                ];

                if ($this->session->userdata('type') == 'user') {
                    $update_array['message_on_inquiry'] = $message_on_inquiry;
                    $update_array['forward_inquiry'] = !empty($this->input->post('forward_inquiry')) ? 1 : 0;
                    $update_array['forward_text'] = $forward_text;
                    $update_array['forward_to'] = !empty($this->input->post('forward_to')) ? $this->input->post('forward_to') : '';
                }
                if (empty($api_token)) {
                    $update_array['api_token'] = generate_api_key();
                }
                if (!empty($time_zone)) {
                    $update_array['time_zone'] = $time_zone;
                    $this->session->set_userdata('time_zone', $time_zone);
                }
                if (is_numeric($setting_id)) {
                    $this->CMS_model->update_record(tbl_user_settings, $where, $update_array);
                    if ($message_on_inquiry == 1 && !empty($inquiry_template)) {

                        $inquiry_temp_data = array(
                            'template_for' => 'inquiry_template',
                            'template_id' => $inquiry_template,
                        );

                        $dwhere = 'user_id = ' . $user_id . ' AND template_for = "inquiry_template"';
                        $inquiry_temp = $this->CMS_model->get_result(tbl_default_templates, $dwhere, null, 1);

                        $template_default_select_value = $this->input->post('default_select_value');
                        $template_default_value = $this->input->post('default_value');
                        $temp_media_array = $this->input->post('temp_media');
                        $default_temp_media_array = $this->input->post('default_temp_media');
                        if (empty($temp_media_array[1])) {
                            $temp_media_array = $default_temp_media_array;
                        }

                        $template_values = array();
                        if (!empty($template_default_value)) {
                            foreach ($template_default_value as $keyV => $valueV) {
                                if (!empty($valueV)) {
                                    foreach ($valueV as $keyS => $valueS) {
                                        if (!empty($valueS)) {
                                            $template_values[$keyV][$keyS] = $valueS;
                                        } else {
                                            $template_values[$keyV][$keyS] = $template_default_select_value[$keyV][$keyS] . '_field';
                                        }
                                    }
                                } else {
                                    $template_values[$keyV] = array();
                                }
                            }
                        }

                        $inquiry_temp_data['template_values'] = !empty($template_values) ? json_encode($template_values) : '';
                        $inquiry_temp_data['template_media'] = !empty($temp_media_array) ? json_encode($temp_media_array) : '';

                        //pr($inquiry_temp_data, 1);
                        if (empty($inquiry_temp)) {
                            $inquiry_temp_data['user_id'] = $user_id;
                            $this->CMS_model->insert_data(tbl_default_templates, $inquiry_temp_data);
                        } else {
                            $this->CMS_model->update_record(tbl_default_templates, 'id =' . $inquiry_temp['id'], $inquiry_temp_data);
                        }
                    }
                    $this->session->set_flashdata('success_msg', 'User Settings updated successfully!');
                } else {
                    $update_array['created'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_user_settings, $update_array);
                    $this->session->set_flashdata('success_msg', 'User Settings successfully!');
                }
                redirect($url);
            }
        }
    }

    public function download_indiamart_data() {
        $this->load->library('Excel');
        $user_id = '';
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        if (is_numeric($user_id)) {
            $inquiry_logs = $this->Indiamart_inquiries_model->get_indiamart_inquiries_logs($user_id);
            if (isset($inquiry_logs) && !empty($inquiry_logs)) {
                //                $fileName = 'leads_' . date('YmdHis') . '.xlsx';
                //
                //                $object = new PHPExcel();
                //                $object->setActiveSheetIndex(0);
                //                $table_columns = array("Query ID", "Query Type", "Query Time", "Name", "Mobile", "Alternative Mobile", "Phone", "Alternative Phone", "Email", "Alternative Email", "Subject", "Company", "Address", "City", "State", "Pincode", "Country", "Product Name", "Mcat Name", "Call Duration", "Message", "Receiver Mobile");
                //
                //                $column = 0;
                //                foreach ($table_columns as $field) {
                //                    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                //                    $column++;
                //                }
                //                $excel_row = 2;
                foreach ($inquiry_logs as $inquiry_log) {
                    $response_array = isset($inquiry_log['response']) && !empty($inquiry_log['response']) ? json_decode($inquiry_log['response']) : array();
                    if (isset($response_array) && !empty($response_array)) {
                        foreach ($response_array as $response) {
                            $response = (array) $response;
                            $where = 'query_id = ' . $this->db->escape($response['UNIQUE_QUERY_ID']) . ' AND user_id = ' . $this->db->escape($user_id) . ' AND inquiry_id = ' . $this->db->escape($inquiry_log['inquiry_id']);
                            $indiamart_customer_lead = $this->CMS_model->get_result(tbl_indiamart_customer_leads, $where, null, 1, null, 'id', 'desc');
                            $insert_array = array(
                                'user_id' => $user_id,
                                'inquiry_id' => $inquiry_log['inquiry_id'],
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
                            } else {
                                echo 'exist : ' . $indiamart_customer_lead['query_id'];
                                //                                $this->CMS_model->update_record(tbl_indiamart_customer_leads, array('id' => $indiamart_customer_lead['id']), $insert_array);
                            }
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $response['UNIQUE_QUERY_ID']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $response['QUERY_TYPE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $response['QUERY_TIME']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $response['SENDER_NAME']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $response['SENDER_MOBILE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $response['SENDER_MOBILE_ALT']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $response['SENDER_PHONE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $response['SENDER_PHONE_ALT']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $response['SENDER_EMAIL']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $response['SENDER_EMAIL_ALT']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $response['SUBJECT']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $response['SENDER_COMPANY']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $response['SENDER_ADDRESS']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $response['SENDER_CITY']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $response['SENDER_STATE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $response['SENDER_PINCODE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $response['SENDER_COUNTRY_ISO']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $response['QUERY_PRODUCT_NAME']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $response['QUERY_MCAT_NAME']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $response['CALL_DURATION']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $response['QUERY_MESSAGE']);
                            //                            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $response['RECEIVER_MOBILE']);
                        }
                    }
                }
                $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename=' . $fileName);
                $object_writer->save('php://output');
            }
        }
    }

}
