<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Tags_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @author : RR
     */
    function index() {
        $this->template->set('title', 'Tags');
        $this->template->load('default_home', 'Tags/index', $this->data);
    }

    function get_tags() {
        $final['recordsTotal'] = $this->Tags_model->get_all_tags('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->Tags_model->get_all_tags();
        echo json_encode($final);
    }

    public function manage($id = '') {
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        if (is_numeric($id)) {
            $where = 'id = ' . $this->db->escape($id);
            $this->data['tag'] = $this->CMS_model->get_result(tbl_tags, $where, '', 1);
        }
        $this->load->view("Tags/manage", $this->data);
    }

    public function save() {


        $this->form_validation->set_rules('tag', 'Tag', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $response = array('status' => false, 'msg' => validation_errors());
        } else {
            $user_id = $this->data['user_data']['id'];
            $id = $this->input->post('id');
            $tag = $this->input->post('tag');

            $data = array(
                'user_id' => $user_id,
                'tag' => $tag
            );

            $where = 'tag = ' . $this->db->escape($tag) . ' AND user_id = ' . $user_id;
            $is_exist = $this->CMS_model->get_result(tbl_tags, $where, '', 1);

            if (!empty($id)) {
                $id = base64_decode($id);
                if (is_numeric($id)) {
                    if (!empty($is_exist) && $is_exist['id'] != $id) {
                        $response = array('status' => false, 'msg' => 'Tag already exist!');
                    } else {
                        $this->CMS_model->update_record(tbl_tags, array('id' => $id), $data);
                        $response = array('status' => true);
                    }
                } else {
                    $response = array('status' => false, 'msg' => 'Something went wrong!');
                }
            } else {
                if (!empty($is_exist)) {
                    $response = array('status' => false, 'msg' => 'Tag already exist!');
                } else {
                    $this->CMS_model->insert_data(tbl_tags, $data);
                    $response = array('status' => true);
                }
            }
        }
        echo json_encode($response);
    }

    function is_assign() {
        $user_id = $this->data['user_data']['id'];
        $id = $this->input->post('id');

        $where = 'id = ' . $this->db->escape($id);
        $tagArr = $this->CMS_model->get_result(tbl_tags, $where, '', 1);
        
        if (!empty($tagArr)) {
            $tag = $tagArr['tag'];
            $Cwhere = 'user_id = ' . $user_id . ' AND is_deleted != 1';
            $client = $this->CMS_model->get_result(tbl_clients, $Cwhere, 'group_ids');
            if(!empty($client)){
                foreach($client as $c){
                    $tags = explode(',', $c['group_ids']);
                    if(in_array($tag, $tags)){
                        echo json_encode(array('status' => true));
                        exit();
                    }
                }
            }
        }
        echo json_encode(array('status' => false));
        exit();
    }

    public function action($action, $id) {
        $where = 'id = ' . $this->db->escape($id);
        $check_user = $this->CMS_model->get_result(tbl_tags, $where);
        if ($check_user) {
            if ($action == 'delete') {
                $where = 'id = ' . $id;
                /* $update_array = array(
                  'is_deleted' => 1
                  ); */
                $this->CMS_model->delete_data(tbl_tags, $where);
                $this->session->set_flashdata('success_msg', 'Tag successfully deleted!');
            }
            /* elseif ($action == 'block') {
              $update_array = array(
              'is_active' => 0
              );
              $this->session->set_flashdata('success_msg', 'Tag successfully deactivated!');
              } elseif ($action == 'activate') {
              $update_array = array(
              'is_active' => 1
              );
              $this->session->set_flashdata('success_msg', 'Client successfully activated!');
              }
              $this->CMS_model->update_record(tbl_clients, $where, $update_array);
             */
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('tag');
    }

}
