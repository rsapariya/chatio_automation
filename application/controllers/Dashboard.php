<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Admin_model', 'User_model', 'CMS_model', 'Chatlogs_model']);
        $this->data = get_admin_data();
    }

    public function index() {
        $this->template->set('title', 'Dashboard');

        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        if ($this->data['user_data']['type'] == 'admin') {
            $users = $this->CMS_model->get_result(tbl_users, array('is_deleted' => '0'));
        } else {
            $where = array('is_deleted' => '0', 'user_id' => $user_id);

            $users = $this->CMS_model->get_result(tbl_clients, $where);
            $templates = $this->CMS_model->get_result(tbl_templates, $where);
            $this->data['templates_cnt'] = count($templates);

            $unsread_message = $this->Chatlogs_model->get_unread_messages_count();
            //echo $this->db->last_query(); exit();
            $this->data['unread_message'] = !empty($unsread_message) && !empty($unsread_message['unread_message']) ? $unsread_message['unread_message'] : 0;
        }

        $this->data['users_cnt'] = count($users);
        $this->template->load('default_home', 'Dashboard/dashboard', $this->data);
    }

    public function edit() {
        $this->template->set('title', 'Edit Profile');

        $this->template->load('default_home', 'Dashboard/profile_edit', $this->data);
    }

    public function save_profile() {
        $unique_str = '';
        $url = base_url() . 'edit_profile';
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
                $unique_str = '|is_unique[' . tbl_users . '.email]';
            } else {
                if ($email != $this->input->post('email')) {
                    $unique_str = '|is_unique[' . tbl_users . '.email]';
                }
            }
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required' . $unique_str, array('is_unique' => 'This %s already exists.'));
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
                $where = array('id' => $user_id);
                $update_array = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'type' => ($this->input->post('type') != '') ? $this->input->post('type') : 'user',
                ];
                if (is_numeric($user_id)) {
                    if (!empty($password)) {
                        $update_array['password'] = $this->encrypt->encode($password);
                    }
                    $this->CMS_model->update_record(tbl_users, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'User updated successfully !');
                }
                redirect($url);
            }
        }
    }

    public function waba_status() {
        $this->template->set('title', 'WABA Status');

        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $where = 'user_id = ' . $this->db->escape($user_id);
        $user_cread = $this->CMS_model->get_result(tbl_user_settings, $where, '', 1);
        if (!empty($user_cread)) {
            $account_details = getMetaAccountDetails($user_cread);
            $this->data['account_details'] = !empty($account_details) && !isset($account_details['error']) ? $account_details : '';
            $phone_details = getMetaPhoneDetails($user_cread);
            $this->data['phone_details'] = !empty($phone_details) && !isset($phone_details['error']) ? $phone_details['data'] : '';
            $message_details = getMessageDetails($user_cread);
            $message_details_analytics = !empty($message_details) && isset($message_details['analytics']) ? $message_details['analytics']: '';
            $this->data['message_details'] = isset($message_details_analytics['data_points']) && !empty($message_details_analytics['data_points']) ? array_reverse($message_details_analytics['data_points']) : '';
            $conversion_cost = getConversionCost($user_cread);
            $conversion_cost_analytics = !empty($conversion_cost) && isset($conversion_cost['conversation_analytics']['data'][0]) ? $conversion_cost['conversation_analytics']['data'][0]: '';
            $this->data['conversion_cost'] = isset($conversion_cost_analytics['data_points']) && !empty($conversion_cost_analytics['data_points']) ? $conversion_cost_analytics['data_points'] : '';
            
        }

        $this->template->load('default_home', 'Dashboard/waba_status', $this->data);
    }

    public function upload_file() {
        $filename = $this->input->post('filename');
        $dynamic_text = $this->input->post('dynamic_text');

        if (!empty($_FILES) && !empty($filename)) {
            $url = upload_file_on_server($_FILES, $filename);
            if (!empty($url)) {
                if (!empty($dynamic_text)) {
                    $response = image_watermark($url, $dynamic_text);
                } else {
                    $response = array('status' => true, 'url' => $url, 'watermark_url' => $url);
                }
            } else {
                $response = array('status' => false);
            }
        } else {
            $response = array('status' => false);
        }
        echo json_encode($response);
        exit();
    }

    public function image_watermark() {
        $text = $this->input->post('text');
        $url = $this->input->post('url');
        if (!empty($text) && !empty($url)) {
            $response = image_watermark($url, $text);
            echo json_encode($response);
            exit();
        }
    }

    public function check_drawflow() {
        $this->load->view('Dashboard/drawflow');
    }

    public function save_drawflow_data() {
        $drawflow = $this->input->post('drawflow');
        if (isset($drawflow['Home'])) {
            $data = $drawflow['Home']['data'];
            $json = json_encode($data);
            $update_array['user_id'] = $this->session->userdata('id');
            $update_array['data'] = $json;
            $id = $this->CMS_model->insert_data('chatboat', $update_array);
            if (!empty($id)) {
                $name = 'df' . $id;
                $this->CMS_model->update_record('chatboat', array('id' => $id), array('name' => $name));
            }
        }
        redirect('dashboard/check_drawflow');
    }

    public function generate_drawflow_data_flow() {
        $check_drawflow = $this->CMS_model->get_result('chatboat', array('flow_generated' => 0));
        if (isset($check_drawflow) && !empty($check_drawflow)) {
            foreach ($check_drawflow as $drawflow) {
                $user_id = $drawflow['user_id'];
                $chatboat_id = $drawflow['id'];
                $json_datas = isset($drawflow['data']) && !empty($drawflow['data']) ? json_decode($drawflow['data'], true) : array();
                $send_data = array();
                if (isset($json_datas) && !empty($json_datas)) {
                    $i = 1;
                    foreach ($json_datas as $key => $json_data) {
                        if ($json_data['name'] == 'Send Media') {
                            $send_data[] = array(
                                'chatboat_order' => $i,
                                'user_id' => $user_id,
                                'chatboat_id' => $chatboat_id,
                                'type' => 'media',
                                'value' => $json_data['data']['name']
                            );
                        } elseif ($json_data['name'] == 'Text') {
                            $send_data[] = array(
                                'chatboat_order' => $i,
                                'user_id' => $user_id,
                                'chatboat_id' => $chatboat_id,
                                'type' => 'text',
                                'value' => $json_data['data']['name']
                            );
                        } elseif ($json_data['name'] == 'templates') {
                            $send_data[] = array(
                                'chatboat_order' => $i,
                                'user_id' => $user_id,
                                'chatboat_id' => $chatboat_id,
                                'type' => 'template',
                                'value' => $json_data['data']['channel']
                            );
                        }
                        $i++;
                    }
                    if (isset($send_data) && !empty($send_data)) {
                        $this->db->insert_batch('chatboat_datas', $send_data);
                        $this->CMS_model->update_record('chatboat', array('id', $chatboat_id), array('flow_generated' => 1));
                    }
                }
            }
        }
    }

}
