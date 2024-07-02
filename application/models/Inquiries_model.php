<?php

class Inquiries_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_inquiries($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['i.id', 'i.name', 'i.created_at as created_at', 'i.is_deleted'];
        $select = 'i.id,@a:=@a+1 AS test_id,i.name,DATE_FORMAT(i.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,i.is_deleted,i.user_id,i.phone_number_full,i.phone_number,it.name as inquiry_type_name,a.name as automation_name,i.automation_id';
        $this->db->select($select, false);
        $this->db->join(tbl_inquiry_types . ' it', 'it.id=i.inquiry_type', 'left');
        $this->db->join(tbl_automations . ' a', 'a.id=i.automation_id');
        $this->db->where(['i.is_deleted' => 0]);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('i.name LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%" OR i.phone_number LIKE "%' . $keyword['value'] . '%" OR i.phone_number_full LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        $where_user = ' (i.user_id = 0 ) ';
        if ($user_id > 0):
            $where_user = ' (i.user_id = 0 OR i.user_id = ' . $user_id . ') ';
        endif;
        $this->db->where($where_user);

        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_inquiries . ' i')->result_array();
        else:
            $res_data = $this->db->get(tbl_inquiries . ' i')->num_rows();
        endif;
        return $res_data;
    }

    public function get_automations($user_id = 0, $id = null) {
        $select = 'a.id,a.name';
        if (!empty($id)) {
            $select = 'a.*';
        }
        $this->db->select($select, false);
        if ($user_id > 0):
            $where_user = ' (a.user_id = 0 OR a.user_id = ' . $user_id . ')';
        endif;
        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }
        $this->db->where($where_user);
        $this->db->where(['a.is_deleted' => 0]);
        if (!empty($id)) {
            $res_data = $this->db->get(tbl_automations . ' a')->row_array();
        } else {
            $res_data = $this->db->get(tbl_automations . ' a')->result_array();
        }
        return $res_data;
    }

    public function getInquiryLogs($one_record = false) {
        date_default_timezone_set("Asia/Calcutta");
        $date = date('Y-m-d H:i:s');
        $this->db->select('il.*,i.name as inquiry,i.inquiry_type,a.name as automation,t.name as template,t.temp_id,t.temp_category,t.temp_language,t.description,t.automation_image,i.phone_number_full', false);
        $this->db->where(['i.is_deleted' => 0, 'a.is_deleted' => 0, 'is_sent' => '0']);
        $this->db->join(tbl_inquiries . ' i', 'i.id=il.inquiry_id');
        $this->db->join(tbl_inquiry_types . ' it', 'it.id=i.inquiry_type');
        $this->db->join(tbl_automations . ' a', 'a.id=i.automation_id');
        $this->db->join(tbl_templates . ' t', 't.id=il.automation_template_id AND t.type = "automation"');
        $where = ' il.notification_date <= "' . $date . '"';
        $this->db->where($where);
        $this->db->order_by('il.id', 'desc');
        if ($one_record) {
            $res_data = $this->db->get(tbl_inquiry_logs . ' il')->row_array();
        } else {
            $res_data = $this->db->get(tbl_inquiry_logs . ' il')->result_array();
        }
        return $res_data;
    }

}
