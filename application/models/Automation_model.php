<?php

class Automation_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_automations($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['a.id', 'a.name', 'a.trigger_time', 'a.id', 'total_inquiries', 'a.created_at'];
        $select = 'a.id,@a:=@a+1 AS test_id,a.id as automation_id,a.name,a.created_at AS created_at,a.is_deleted,a.user_id,count(i.id) as total_inquiries,DATE_FORMAT(a.trigger_time,"%l:%i %p") AS trigger_time';
        $this->db->select($select, false);
        $this->db->where(['a.is_deleted' => 0]);
        $this->db->join(tbl_inquiries . ' i', 'i.automation_id = a.id', 'left');
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('a.name LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%" OR trigger_time LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        $where_user = ' (a.user_id = 0 ) ';
        if ($user_id > 0):
            $where_user = ' (a.user_id = 0 OR a.user_id = ' . $user_id . ') ';
        endif;
        $this->db->where($where_user);

        $this->db->group_by('a.id');
        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_automations . ' a')->result_array();
        else:
            $res_data = $this->db->get(tbl_automations . ' a')->num_rows();
        endif;
        return $res_data;
    }

    public function get_automation_templates($user_id = 0, $id = null) {
        $select = 'a.id,a.name';
        if (!empty($id)) {
            $select = 'a.*';
        }
        $this->db->select($select, false);
        if ($user_id > 0):
            $where_user = ' (a.user_id = 0 OR a.user_id = ' . $user_id . ') AND (type = "automation" AND custom_type IS NULL)';
        endif;
        $where = ' (a.temp_id IS NOT NULL)';
        $this->db->where($where);
        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }
        $this->db->where($where_user);
        $this->db->where(['a.is_deleted' => 0]);
        $this->db->order_by('a.name', 'ASC');
        if (!empty($id)) {
            $res_data = $this->db->get(tbl_templates . ' a')->row_array();
        } else {
            $res_data = $this->db->get(tbl_templates . ' a')->result_array();
        }
        return $res_data;
    }
    
    public function get_automations(){
        date_default_timezone_set("Asia/Calcutta");
        $select = 'a.*,DATE_FORMAT(a.trigger_time,"%H:%i") as trigger_time';
        $this->db->select($select, false);
        $this->db->where(['a.is_deleted' => 0, 'a.user_id' => $this->session->userdata('id')]);
        //$this->db->where(['a.is_deleted' => 0, 'DATE_FORMAT(a.trigger_time,"%H:%i")' => date('H:i')]);
        $this->db->order_by('a.id', 'ASC');
        $res_data = $this->db->get(tbl_automations . ' a')->result_array();
        return $res_data;
    }
    
    public function get_automation_logs($count = null) {
        if($this->session->userdata('type') == 'user'){
            $user_id = $this->session->userdata('id');
        }
        if($this->session->userdata('type') == 'member'){
            $user_id = $this->session->userdata('added_by');
        }
        $columns = ['il.id','c.name','c.phone_number_full','il.notification_date','a.name', 't.name','cl.message_status','il.sent_at'];
        
        $select = 'il.id, c.name,c.phone_number_full,il.notification_date,a.name as automation_name,t.name as template_name,cl.message_status,il.sent_at,il.is_sent, il.error_response';
        $this->db->select($select, false);
        $this->db->where(['il.user_id' => $user_id]);
        $this->db->where('il.automation_id !=', '');
        $this->db->join(tbl_automations . ' a', 'a.id = il.automation_id', 'left');
        $this->db->join(tbl_templates . ' t', 't.id = il.automation_template_id', 'left');
        $this->db->join(tbl_inquiries . ' i', 'i.id = il.inquiry_id');
        $this->db->join(tbl_clients . ' c', 'c.id = i.send_to');
        $this->db->join(tbl_chat_logs . ' cl', 'cl.inquiry_log_id = il.id', 'left');
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('c.name LIKE "%' . $keyword['value'] . '%" OR c.phone_number_full LIKE "%' . $keyword['value'] . '%" OR a.name LIKE "%' . $keyword['value'] . '%" OR il.notification_date LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)){
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_inquiry_logs . ' il')->result_array();
        }else{
            $res_data = $this->db->get(tbl_inquiry_logs . ' il')->num_rows();
        }
        return $res_data;
    }

}
