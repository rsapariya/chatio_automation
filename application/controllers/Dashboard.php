<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Admin_model', 'User_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    public function index() {
        $this->template->set('title', 'Dashboard');
        $where = array('is_deleted' => '0');
        $where_tem = array('is_deleted' => '0');
        if ($this->data['user_data']['type'] == 'admin') {
            $where_tem['user_id'] = 0;
        } elseif ($this->data['user_data']['type'] == 'user') {
            $where_tem['user_id'] = $where['user_id'] = $this->data['user_data']['id'];
        }
         if ($this->data['user_data']['type'] == 'admin') {
            $users = $this->CMS_model->get_result(tbl_users, $where);
        }else{
            $users = $this->CMS_model->get_result(tbl_clients, $where);
        }
        $templates = $this->CMS_model->get_result(tbl_templates, $where_tem);
        $this->data['users_cnt'] = count($users);
        $this->data['templates_cnt'] = count($templates);
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

}
