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
        $select = 'a.id,@a:=@a+1 AS test_id,a.id as automation_id,a.name,DATE_FORMAT(a.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,a.is_deleted,a.user_id,count(i.id) as total_inquiries,DATE_FORMAT(a.trigger_time,"%l:%i %p") AS trigger_time';
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

}
