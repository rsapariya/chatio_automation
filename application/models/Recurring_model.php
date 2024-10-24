<?php

class Recurring_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_recurrings($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['i.id', 'c.name','c.phone_number_full','i.description', 'i.created_at', 'i.is_deleted'];
        $select = 'i.id,@a:=@a+1 AS test_id,c.name,c.phone_number_full,i.description,DATE_FORMAT(i.trigger_time,"%l:%i %p") AS trigger_time,DATE_FORMAT(i.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,i.is_deleted,i.user_id,i.trigger_type,i.weekly_day,i.monthly_date,i.yearly_date,i.template_id,t.name as template_name';
        $this->db->select($select, false);
        $this->db->where(['i.is_deleted' => 0]);
        $this->db->join(tbl_templates . ' t', 'i.template_id = t.id', 'left');
        $this->db->join(tbl_clients . ' c', 'i.client_id = c.id', 'left');
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('c.name LIKE "%' . $keyword['value'] . '%" OR c.phone_number_full LIKE "%' . $keyword['value'] . '%" OR i.description LIKE "%' . $keyword['value'] . '%" OR trigger_time LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%" OR i.phone_number LIKE "%' . $keyword['value'] . '%" OR i.phone_number_full LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if ($user_id > 0):
            $where_user = ' (i.user_id = 0 OR i.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_recurrings . ' i')->result_array();
        else:
            $res_data = $this->db->get(tbl_recurrings . ' i')->num_rows();
        endif;
        return $res_data;
    }

    public function get_recurrings() {
        //date_default_timezone_set("Asia/Calcutta");
        $select = 'i.*,DATE_FORMAT(i.trigger_time,"%H:%i") as trigger_time,t.temp_language,t.name as template';
        $this->db->select($select, false);
        $this->db->join(tbl_templates . ' t', 't.id=i.template_id', 'left');
        $this->db->where(['i.is_deleted' => 0, 'DATE_FORMAT(i.trigger_time,"%H:%i")' => date('H:i')]);
        $this->db->order_by('id', 'ASC');
        $res_data = $this->db->get(tbl_recurrings . ' i')->result_array();
        return $res_data;
    }
    
    public function get_recurring_logs($count= null){
        if($this->session->userdata('type') == 'user'){
            $user_id = $this->session->userdata('id');
        }
        if($this->session->userdata('type') == 'member'){
            $user_id = $this->session->userdata('added_by');
        }
        
        $columns = ['c.name','c.phone_number_full','r.trigger_type', 'r.trigger_time', 'l.created', 'l.message_status'];
        $select = 'l.id,,c.name,c.phone_number_full,r.trigger_type,r.trigger_time,l.created,l.message_status,l.user_id';
        $this->db->select($select, false);
        $this->db->where(['l.user_id' => $user_id, 'l.recurring_id >' => 0]);
        $this->db->join(tbl_recurrings . ' r', 'r.id = l.recurring_id');
        $this->db->join(tbl_clients . ' c', 'r.client_id = c.id', 'left');
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('c.name LIKE "%' . $keyword['value'] . '%" OR c.phone_number_full LIKE "%' . $keyword['value'] . '%" OR r.trigger_type LIKE "%' . $keyword['value'] . '%" OR l.message_status LIKE "%' . $keyword['value'] . '%" OR l.created LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if(is_null($count)){
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_chat_logs . ' l')->result_array();
        }else{
            $res_data = $this->db->get(tbl_chat_logs . ' l')->num_rows();
        }
        return $res_data;
    }

}
