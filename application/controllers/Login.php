<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use Netflie\WhatsAppCloudApi\Request\BusinessProfileRequest;

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Admin_model', 'User_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    public function index() {
        if ($this->session->userdata('id') != '') {
            redirect('dashboard');
        } else {
            $this->template->set('title', 'Login');
            $this->template->load('default_login', 'login', $this->data);
        }
    }

    public function login_post() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Email', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == TRUE) {
                $email = $this->input->post('username');
                $password = $this->input->post('password');
//                echo $password . "<br/>";
//                echo $this->encrypt->encode($password);
//                die;
                $user_detail = $this->Admin_model->get_user($email, $password);
                if (count($user_detail) == 1) {
                    $user = $user_detail[0];
                    if ($user['is_blocked'] != 1) {
                        $login_update = array(
                            'last_ip' => $_SERVER['REMOTE_ADDR'],
                            'last_login' => date('Y-m-d H:i:s')
                        );
                        $this->CMS_model->update_record(tbl_users, array('id' => $user['id']), $login_update);

                        $profile_url = '';
                        if ($user['type'] == 'user') {
                            $profile_url = $this->get_user_wa_profile($user['id']);
                        }
                        if ($user['type'] == 'member') {
                            $userInfo = $this->CMS_model->get_result(tbl_users, array('id' => $user['added_by']), '', 1);
                            $user['waba_access'] = !empty($userInfo) ? $userInfo['waba_access'] : $user['waba_access'];
                            $time_zone = $this->CMS_model->get_result(tbl_user_settings, array('user_id' => $user['added_by']), '', 1);
                            $user['time_zone'] = !empty($time_zone) ? $time_zone['time_zone'] : '';
                        }
                        
                        $user['wa_profile_image_url'] = $profile_url;
                        unset($user['password']);
                        $session_array = $user;
                        $this->session->set_userdata($session_array);
                        redirect('dashboard', $this->data);
                    } else {
                        $this->session->set_flashdata('error_msg', 'Your account is blocked.');
                        redirect('login');
                    }
                } else {
                    $this->session->set_flashdata('error_msg', 'Invalid Login Crdentials.');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect('login');
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function dashboard() {
        $this->template->set('title', 'Dashboard');
        $this->template->load('default_login', 'dashboard', $this->data);
    }

    public function register() {
        if ($this->session->userdata('id') != '') {
            redirect('dashboard');
        } else {
            $this->template->set('title', 'Register');
            $this->template->load('default_login', 'register', $this->data);
        }
    }

    public function register_post() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[' . tbl_users . '.email]', array('is_unique' => 'This %s already exists.'));
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
            if ($this->form_validation->run() == TRUE) {
                $password = $this->input->post('password');
                $insert_data = array(
                    'email' => $this->input->post('email'),
                    'password' => $this->encrypt->encode($password),
                    'name' => $this->input->post('name'),
                    'phone_number' => $this->input->post('phone_number'),
                    'type' => 'user',
                );
                $id = $this->CMS_model->insert_data(tbl_users, $insert_data);
                if (!empty($id)) {
                    $insert_settings = array(
                        'user_id' => $id,
                        'api_token' => generate_api_key()
                    );
                    $user_setting_id = $this->CMS_model->insert_data(tbl_user_settings, $insert_settings);
                    $user = $this->Admin_model->get_user_data($id);
                    unset($user['password']);
                    $session_array = $user;
                    $this->session->set_userdata($session_array);
                    $this->session->set_flashdata('success_msg', 'Register Successfully.');
                    redirect('dashboard', $this->data);
                }
            } else {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect('register');
            }
        }
    }

    public function forgot_password() {
        if ($this->session->userdata('id') != '') {
            redirect('dashboard');
        } else {
            $this->template->set('title', 'Forgot Password');
            $this->template->load('default_login', 'forgot_password', $this->data);
        }
    }

    public function get_user_wa_profile($user_id) {

        $where = ' user_id = ' . $user_id;
        $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1);
        if (!empty($user_settings)) {
            $whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $user_settings['phone_number_id'],
                'access_token' => $user_settings['permanent_access_token'],
            ]);
            try {
                $wa_response = $whatsapp_cloud_api->businessProfile('email,profile_picture_url,websites');
                if (!empty($wa_response)) {
                    $Exresponse = new ResponseException($wa_response);
                    $responseData = $Exresponse->responseData();
                    if (!empty($responseData) && isset($responseData['data'])) {
                        if (!empty($responseData['data'])) {
                            if (isset($responseData['data'][0])) {
                                return isset($responseData['data'][0]['profile_picture_url']) && !empty($responseData['data'][0]['profile_picture_url']) ? $responseData['data'][0]['profile_picture_url'] : '';
                            }
                        }
                    }
                    return false;
                }
            } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
                $responseData = $e->responseData();
            }
            return false;
        }
        return false;
    }

}
