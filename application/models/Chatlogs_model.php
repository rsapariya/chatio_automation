<?php

class Chatlogs_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_logs($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['cl.id', 'cl.from_profile_name', 'cl.phone_number','cl.message', 'cl.created'];
        $select = 'cl.id,cl.from_profile_name,cl.phone_number,cl.message,DATE_FORMAT(cl.created,"%d %b %Y <br> %l:%i %p") AS created,cl.user_id';
        $this->db->select($select, false);
        $this->db->where(['cl.is_deleted' => 0]);
        $query_time = $this->input->post('query_time');


        $keyword = $this->input->post('search');
        if (!empty($keyword['value'])) {
            $this->db->having('cl.from_profile_name LIKE "%' . $keyword['value'] . '%" OR cl.phone_number LIKE "%' . $keyword['value'] . '%" OR created LIKE "%' . $keyword['value'] . '%" OR cl.message LIKE "%' . $keyword['value'] . '%"', NULL);
        }

        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(cl.created) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(cl.created) <=', date('Y-m-d', strtotime($times[1])));
        }

        if ($user_id > 0):
            $where_user = ' (cl.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        
        $this->db->where('cl.from_user != 0');

        $order = $this->input->post('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }
        
        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_chat_logs.' cl')->result_array();
        else:
            $res_data = $this->db->get(tbl_chat_logs.' cl')->num_rows();
        endif;
        return $res_data;
    }

    public function get_filtered_reply_responses($trigger_text = '', $query_time = '', $user_id = 0) {
        $select = 'rr.name,rr.mobile_number,rr.response,DATE_FORMAT(rr.created,"%d %b %Y %l:%i %p") AS created_at';
        $this->db->select($select, false);

        if (!empty($trigger_text)) {
            $this->db->where('rr.response', $trigger_text);
        }
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(rr.created) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(rr.created) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $this->db->order_by('rr.id', 'desc');
        $res_data = $this->db->get(tbl_button_reply_logs . ' rr')->result_array();
        return $res_data;
    }

    public function get_all_response_text($user_id) {
        $this->db->select('DISTINCT rr.response', false);
        if ($user_id > 0):
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $res_data = $this->db->get(tbl_button_reply_logs . ' rr')->result_array();
        return $res_data;
    }

    public function get_filtered_leads($data_arr = []) {
        if(!empty($data_arr)){

            $this->db->select('l.id,l.from_profile_name,l.phone_number,l.message,l.created', false);

            if (!empty($data_arr['query_time'])) {
                $times = explode('-', $data_arr['query_time']);
                $this->db->where('DATE(l.created) >=', date('Y-m-d', strtotime($times[0])));
                $this->db->where('DATE(l.created) <=', date('Y-m-d', strtotime($times[1])));
            }
            if(!empty($data_arr['user_id'])){
                $this->db->where('l.user_id =', $data_arr['user_id']);
            }
            
            $this->db->order_by('l.created', 'desc');
            $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
            return $res_data;
            
        }
        
    }
    
    public function get_customers($filter = ''){
        $user_id = $this->session->userdata('id');
        $this->db->select('l.phone_number, l.from_profile_name, MAX(l.created) as created', false);
        $this->db->where('l.user_id =', $user_id);
        if(!empty($filter)){
            $this->db->having('l.from_profile_name LIKE "%' .$filter . '%" OR l.phone_number LIKE "%' . $filter . '%"', NULL);
        }
        $this->db->group_by('l.phone_number');
        $this->db->order_by('created', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
        return $res_data;
    }
    
    


}
