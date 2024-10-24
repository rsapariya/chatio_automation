<?php

class ReplyResponse_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_reply_responses($count = null, $user_id = 0) {
        
        //$columns = ['rr.id',  'rr.name', 'rr.mobile_number','rr.response', 'rr.created'];
        $columns = ['rr.id',  'rr.from_profile_name', 'rr.phone_number','rr.message', 'rr.created'];
        
        $select = 'rr.id,@a:=@a+1 AS test_id,rr.from_profile_name,rr.phone_number,rr.message,rr.created AS created_at,rr.user_id';
        $this->db->select($select, false);
        $keyword = $this->input->post('search');
        $trigger_text = $this->input->post('trigger_text');
        $query_time = $this->input->post('query_time');
        if (!empty($keyword['value'])) {
            $this->db->having('rr.message LIKE "%' . $keyword['value'] . '%" OR rr.from_profile_name LIKE "%' . $keyword['value'] . '%" OR rr.phone_number LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%"');
        }
        if (!empty($trigger_text)) {
            $this->db->where('rr.message', $trigger_text);
        }
        $where_in = array('button_reply', 'list_reply');
        $this->db->where_in('rr.message_type', $where_in);
        //$this->db->where('rr.message_type', 'button_reply');
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(rr.created) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(rr.created) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;

        $order = $this->input->post('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }

        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_chat_logs . ' rr')->result_array();
        else:
            $res_data = $this->db->get(tbl_chat_logs . ' rr')->num_rows();
        endif;
        return $res_data;
    }

    public function get_filtered_reply_responses($trigger_text = '', $query_time = '', $user_id = 0) {
        $select = 'rr.from_profile_name,rr.phone_number,rr.message,DATE_FORMAT(rr.created,"%d %b %Y %l:%i %p") AS created_at';
        $this->db->select($select, false);

        if (!empty($trigger_text)) {
            $this->db->where('rr.message', $trigger_text);
        }
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(rr.created) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(rr.created) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
//            $where_user = ' (rr.user_id = 0 OR rr.user_id = ' . $user_id . ') ';
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $where_in = array('button_reply', 'list_reply');
        $this->db->where_in('rr.message_type', $where_in);
        //$this->db->where('rr.message_type', 'button_reply');
        $this->db->order_by('rr.created', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' rr')->result_array();
        return $res_data;
    }

    public function get_all_response_text($user_id) {
        $this->db->select('DISTINCT rr.message', false);
        if ($user_id > 0):
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $this->db->where('rr.message_type', 'button_reply');
        $res_data = $this->db->get(tbl_chat_logs . ' rr')->result_array();
        return $res_data;
    }

}
