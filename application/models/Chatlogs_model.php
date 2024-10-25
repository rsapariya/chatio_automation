<?php

class Chatlogs_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_logs($count = null, $user_id = 0)
    {
        $start = $this->input->get('start');
        $columns = ['cl.id', 'cl.from_profile_name', 'cl.phone_number', 'cl.message', 'cl.created'];
        $select = 'cl.id,cl.from_profile_name,cl.phone_number,cl.message,cl.created AS created,cl.user_id';
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

        if ($user_id > 0) :
            $where_user = ' (cl.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;

        $this->db->where('cl.from_user != 0');
        $this->db->where('cl.message_type',"text");
        
        $order = $this->input->post('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }

        if (is_null($count)) :
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_chat_logs . ' cl')->result_array();
        else :
            $res_data = $this->db->get(tbl_chat_logs . ' cl')->num_rows();
        endif;
        return $res_data;
    }

    public function get_apilogs($count = null, $user_id = 0)
    {
        $start = $this->input->get('start');
        $columns = ['cl.id', 'cl.phone_number', 'cl.message', 'cl.message_status', 'cl.created', 'cl.deliver_time', 'cl.read_time'];
        $select = 'cl.id,cl.phone_number,cl.message,cl.created,cl.message_status,cl.deliver_time,cl.read_time,cl.user_id';
        $this->db->select($select, false);
        $this->db->where(['cl.is_deleted' => 0]);
        $this->db->where('cl.api_data != ""');
        $query_time = $this->input->post('query_time');


        $keyword = $this->input->post('search');
        if (!empty($keyword['value'])) {
            $this->db->having('cl.phone_number LIKE "%' . $keyword['value'] . '%" OR created LIKE "%' . $keyword['value'] . '%" OR cl.message LIKE "%' . $keyword['value'] . '%"', NULL);
        }

        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(cl.created) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(cl.created) <=', date('Y-m-d', strtotime($times[1])));
        }

        if ($user_id > 0) :
            $where_user = ' (cl.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;

        $order = $this->input->post('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }

        if (is_null($count)) :
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_chat_logs . ' cl')->result_array();
        else :
            $res_data = $this->db->get(tbl_chat_logs . ' cl')->num_rows();
        endif;
        return $res_data;
    }

    public function get_filtered_reply_responses($trigger_text = '', $query_time = '', $user_id = 0)
    {
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
        if ($user_id > 0) :
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $this->db->order_by('rr.id', 'desc');
        $res_data = $this->db->get(tbl_button_reply_logs . ' rr')->result_array();
        return $res_data;
    }

    public function get_all_response_text($user_id)
    {
        $this->db->select('DISTINCT rr.response', false);
        if ($user_id > 0) :
            $where_user = ' (rr.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
        $res_data = $this->db->get(tbl_button_reply_logs . ' rr')->result_array();
        return $res_data;
    }

    public function get_filtered_leads($data_arr = [])
    {
        if (!empty($data_arr)) {

            $this->db->select('l.id,l.from_profile_name,l.phone_number,l.message,l.created', false);

            if (!empty($data_arr['query_time'])) {
                $times = explode('-', $data_arr['query_time']);
                $this->db->where('DATE(l.created) >=', date('Y-m-d', strtotime($times[0])));
                $this->db->where('DATE(l.created) <=', date('Y-m-d', strtotime($times[1])));
            }
            if (!empty($data_arr['user_id'])) {
                $this->db->where('l.user_id =', $data_arr['user_id']);
            }
            $this->db->where('l.message_type', 'text');


            $this->db->order_by('l.created', 'desc');
            $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
            return $res_data;
        }
    }

    public function get_filtered_api($data_arr = [])
    {
        if (!empty($data_arr)) {

            $this->db->select('l.id,l.phone_number,l.message,l.message_status,l.created,l.deliver_time,l.read_time,l.message_type', false);

            if (!empty($data_arr['query_time'])) {
                $times = explode('-', $data_arr['query_time']);
                $this->db->where('DATE(l.created) >=', date('Y-m-d', strtotime($times[0])));
                $this->db->where('DATE(l.created) <=', date('Y-m-d', strtotime($times[1])));
            }
            if (!empty($data_arr['user_id'])) {
                $this->db->where('l.user_id =', $data_arr['user_id']);
            }
            $this->db->where(['l.is_deleted' => 0]);
            $this->db->where('l.api_data != ""');

            $this->db->order_by('l.created', 'desc');
            $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
            return $res_data;
        }
    }


    public function get_customers($filter = [],$count= false)
    {
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
            $member_id = $this->session->userdata('id');
        } else {
            $user_id = $this->session->userdata('id');
        }
        
        //$last_msg = 'select m.message from '.tbl_chat_logs.' as m where m.phone_number = l.phone_number AND m.from_user=1 ORDER BY m.id DESC LIMIT 1';
        //$message_type = 'select mt.message_type from '.tbl_chat_logs.' as mt where mt.phone_number = l.phone_number AND mt.from_user=1 ORDER BY mt.id DESC LIMIT 1';
        //$unread_message = 'select count(um.id) from '.tbl_chat_logs.' as um where um.phone_number = l.phone_number AND um.user_id = l.user_id AND um.from_user=1 AND (um.message_status IS NULL OR um.message_status != "read") ORDER BY um.id DESC';
        
	//$this->db->select('c.id,l.phone_number, l.from_profile_name, MAX(l.created) as created, c.name, ('.$last_msg.') as message, ('.$message_type.') as message_type, ('.$unread_message.') as unread_message', false);
        $this->db->select('c.id,l.phone_number, l.from_profile_name, MAX(l.created) as created, c.name');
        $this->db->select('COUNT(DISTINCT CASE WHEN l.from_user = 1 AND l.user_id = "'.$user_id.'" AND (l.message_status IS NULL OR l.message_status != "read") THEN l.id END) AS unread_message', false);
        if ($this->session->userdata('type') == 'member') {
            $this->db->join(tbl_assigned_member . ' a', 'a.assigned_to = l.phone_number');
            $this->db->where('a.member_id =', $member_id);
        }
        $this->db->join(tbl_clients . ' c', 'c.phone_number_full = l.phone_number','left');
        $this->db->where('l.user_id =', $user_id);

        if (!empty($filter) && isset($filter['search']) && !empty($filter['search'])) {
            $this->db->having('l.from_profile_name LIKE "%' . $filter['search'] . '%" OR l.phone_number LIKE "%' . $filter['search'] . '%" OR c.name LIKE "%' . $filter['search'] . '%"', NULL);
        }
        $this->db->group_by('l.phone_number');
        $this->db->having('SUM(l.from_user = 1) > 0', NULL, FALSE);
        
        
        if(empty($count) && empty($filter['search'])){
            $limit = isset($filter['limit']) && !empty($filter['limit']) ? $filter['limit'] : 7;
            $start = isset($filter['start']) && !empty($filter['start']) ? $filter['start'] : 0;
            if(!empty($start) && !empty($limit)){
                $this->db->limit($limit,$start);
            }else{
                $this->db->limit($limit);
            }
        }
        $this->db->order_by('created', 'desc');
        if(!empty($count)){
            $res_data = $this->db->get(tbl_chat_logs . ' l')->num_rows();
        }else{
            $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
        }
        
        return $res_data;
    }

    public function get_messages($contact, $offset = 0, $limit = 10)
    {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $this->db->select('l.*');
        $this->db->where('l.user_id =', $user_id);
        $this->db->where('l.phone_number =', $contact);
        $this->db->where('l.message_id != ""');
        $this->db->limit($limit, $offset);
        $this->db->order_by('id', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
        return $res_data;
    }
	
	public function get_last_messages($contact)
    {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $this->db->select('l.message, l.message_type');
        $this->db->where('l.user_id =', $user_id);
        $this->db->where('l.phone_number =', $contact);
        $this->db->where('l.from_user', 1);
        $this->db->order_by('id', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->row_array();
        return $res_data;
    }
	
	public function get_unread_messages_count($contact = '')
    {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $this->db->select('count(l.id) as unread_message');
        $this->db->where('l.user_id =', $user_id);
        if(!empty($contact)){
                $this->db->where('l.phone_number =', $contact);
        }
        $this->db->where('l.from_user', 1);
	
        $this->db->group_start();
        $this->db->where('message_status IS NULL', null, false); // Check if message_status IS NULL
        $this->db->or_where('message_status !=', 'read'); // OR message_status != 'read'
        $this->db->group_end();
        
        
        $this->db->order_by('id', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->row_array();
        return $res_data;
    }
	
	

    public function get_latest_chat($contact, $id)
    {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }

        $this->db->select('l.*');
        $this->db->where('l.user_id =', $user_id);
        $this->db->where('l.phone_number =', $contact);
        $this->db->where('l.message_id != ""');
        $this->db->where('l.id >', $id);
        $this->db->order_by('id', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
        return $res_data;
    }


    public function get_last_sent_message($contact)
    {
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }
        $this->db->select('l.phone_number, l.from_profile_name, l.created', false);
        $this->db->where('l.user_id =', $user_id);
        $this->db->where('l.from_user = 1');
        $this->db->where('l.phone_number =', $contact);
        $this->db->order_by('created', 'desc');
        $res_data = $this->db->get(tbl_chat_logs . ' l')->row_array();
        return $res_data;
    }

    public function get_assigned_member_info($contact)
    {
        $user_id = $this->session->userdata('id');
        $this->db->select('a.*, u.name', false);
        $this->db->join(tbl_users . ' u', 'u.id = a.member_id');
        $this->db->where('a.assigned_to =', $contact);
        $this->db->where('u.added_by =', $user_id);
        $res_data = $this->db->get(tbl_assigned_member . ' a')->row_array();
        return $res_data;
    }
	
    public function update_user_message_status($id, $contact){
            if ($this->session->userdata('type') == 'user') {
                $user_id = $this->session->userdata('id');
            }
            if ($this->session->userdata('type') == 'member') {
                $user_id = $this->session->userdata('added_by');
            }

            $this->db->where('user_id', $user_id);
            $this->db->where('from_user', 1);
            $this->db->where('phone_number', $contact);
            $this->db->group_start();
            $this->db->where('message_status IS NULL', null, false); 
            $this->db->or_where('message_status !=', 'read'); 
            $this->db->group_end();
            $this->db->where('id <=', $id);
            $this->db->update(tbl_chat_logs, array('message_status' => 'read'));
    }
    
    public function get_unsaved_contacts(){
        $this->db->select('l.from_profile_name, l.phone_number, l.user_id,u.default_tags');
        $this->db->from(tbl_chat_logs.' l');
        $this->db->join(tbl_user_settings . ' u', 'u.user_id = l.user_id');
        $this->db->join(tbl_clients.' c', 'l.phone_number = c.phone_number_full', 'left');
        $this->db->where('l.from_user', 1);
        $this->db->where('c.phone_number_full IS NULL');
        $this->db->where('u.default_tags IS NOT NULL');
        $this->db->group_by('l.phone_number, l.user_id');
        $this->db->order_by('l.id','ASC');
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }
    
}