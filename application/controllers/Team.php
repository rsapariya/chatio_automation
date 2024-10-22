<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Team_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @author : RR
     */
    function index() {
        $this->template->set('title', 'Team');
        $this->template->load('default_home', 'Team/index', $this->data);
    }

    function get_team() {
        $final['recordsTotal'] = $this->Team_model->get_members('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $records = $this->Team_model->get_members();
        if(!empty($records)){
            foreach ($records as $rdk =>$rd){
                $tz_date = '';
                if(!empty($rd['last_login']) && $rd['last_login'] != '0000-00-00 00:00:00'){
                    $create_date = date('Y-m-d H:i:s', strtotime($rd['last_login']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$rdk]['last_login'] =  !empty($tz_date) ? date('d M Y', strtotime($tz_date)).' '.date('h:i a', strtotime($tz_date)): '';
            }
        }
        $final['data'] = $records;
        
        echo json_encode($final);
    }

    public function manage($id = '') {
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        if (is_numeric($id)) {
            $where = 'id = ' . $this->db->escape($id);
            $this->data['team'] = $this->CMS_model->get_result(tbl_users, $where, '', 1);
        }
        $this->load->view("Team/manage", $this->data);
    }

    public function save() {
        $mid = $this->input->post('id');
        if (!empty($mid)) {
            $id = base64_decode($mid);
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if (empty($id)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $response = array('status' => false, 'msg' => validation_errors());
        } else {
            $user_id = $this->data['user_data']['id'];

            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $phone_number = $this->input->post('phone_number');

            $data = array(
                'type' => 'member',
                'added_by' => $user_id,
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone_number,
                'created_at' => date('Y-m-d H:i:s'),
            );
            if(empty($id)){
                $where = 'email = ' . $this->db->escape($email);
            }else{
                $where = 'email = ' . $this->db->escape($email).' AND id != '.$id;
            }
            $is_exist = $this->CMS_model->get_result(tbl_users, $where, '', 1);

            if (empty($is_exist)) {
                if (!empty($id)) {
                    if (is_numeric($id)) {
                        $this->CMS_model->update_record(tbl_users, array('id' => $id), $data);
                        $response = array('status' => true);
                    } else {
                        $response = array('status' => false, 'msg' => 'Something went wrong!');
                    }
                } else {
                    $data['password'] = $this->encrypt->encode($password);
                    $this->CMS_model->insert_data(tbl_users, $data);
                    $response = array('status' => true);
                }
            } else {
                $response = array('status' => false, 'msg' => 'Member already exist with this email!');
            }
        }
        echo json_encode($response);
        exit();
    }

    function change_status() {
        $user_id = $this->data['user_data']['id'];
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if (!empty($id)) {
            $id = base64_decode($id);
            if (is_numeric($id)) {
                $is_blocked = !empty($status) && $status == 1 ? 0 : 1;
                $this->CMS_model->update_record(tbl_users, array('id' => $id), array('is_blocked' => $is_blocked));
                $response = array('status' => true);
            } else {
                $response = array('status' => false, 'msg' => 'Something went wrong!');
            }
        } else {
            $response = array('status' => false, 'msg' => 'Something went wrong! ID not found!');
        }
        echo json_encode($response);
        exit();
    }
    
    public function is_assigned(){
        $id = $this->input->post('id');
        $where = 'member_id = '.  base64_decode($id);
        $check_user = $this->CMS_model->get_result(tbl_assigned_member, $where, '', 1);
        if(!empty($check_user)){
            $response = array('status' => true);
        }else{
            $response = array('status' => false);
        }
        echo json_encode($response);
    }
    
    public function view($id) {
        $id = base64_decode($id);
        if (is_numeric($id)) {
            $where = 'member_id = ' . $this->db->escape($id);
            $this->data['assign_to'] = $this->CMS_model->get_result(tbl_assigned_member, $where);
            
            $wherem = 'id = ' . $this->db->escape($id);
            $this->data['member_info'] = $this->CMS_model->get_result(tbl_users, $wherem, '', 1);
        }
        $this->load->view("Team/view", $this->data);
    }
    
    public function remove_assigned_member(){
        $id = $this->input->post('id');
        $where = 'id = '.  base64_decode($id);
        $check_user = $this->CMS_model->get_result(tbl_assigned_member, $where, '', 1);
        if(!empty($check_user)){
            $this->CMS_model->delete_data(tbl_assigned_member, $where);
            $response = array('status' => true);
        }else{
            $response = array('status' => false, 'error' => 'Member not found!');
        }
        echo json_encode($response);
        exit();
    }
    
    public function delete(){
        $id = $this->input->post('id');
        $where = 'id = '.  base64_decode($id);
        $check_user = $this->CMS_model->get_result(tbl_users, $where);
        if ($check_user) {
            $this->CMS_model->delete_data(tbl_users, $where);
            $response = array('status' => true);
        }else{
            $response = array('status' => false, 'error' => 'Member not found!');
        }
        return $response;
    }
}
