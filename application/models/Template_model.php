<?php

class Template_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_templates($count = null, $type = '', $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['t.id', 't.description', 't.created_at', 't.is_deleted'];
        if ($type == 'automation') {
            $columns = ['t.id', 't.name', 't.automation_image', 't.created_at', 't.is_deleted'];
        }
        $select = 't.id,@a:=@a+1 AS test_id,t.description,t.created_at,t.is_deleted,t.type,t.user_id,t.automation_image,t.name,t.temp_id,t.temp_language,t.temp_status,t.custom_type';
        if (!empty($type) && $user_id > 0):
            $select = 't.id,t.description,t.created_at,t.is_deleted,t.type,t.user_id,ut.template_id as is_default,t.automation_image,t.name,t.temp_id,t.temp_language,t.temp_status,t.custom_type';
            $this->db->join('user_default_templates ut', 't.id=ut.template_id and ut.type = "' . $type . '" and ut.user_id = ' . $user_id, 'left');
            $where_temp = '((t.temp_id IS NULL) OR (t.temp_id IS NOT NULL AND t.temp_status = "APPROVED"))';
            $this->db->where($where_temp);
        endif;
        $this->db->select($select, false);
        $this->db->where(['t.is_deleted' => 0]);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $having = 't.description LIKE "%' . $keyword['value'] . '%" OR t.type LIKE "%' . $keyword['value'] . '%" OR t.created_at LIKE "%' . $keyword['value'] . '%"';
            if ($type == 'automation') {
                $having .= ' OR t.name LIKE "%' . $keyword['value'] . '%"';
            }
            $this->db->having($having, NULL);
        }
        if (!empty($type)):
            $this->db->where('t.type', $type);
        endif;
        $where_user = ' (t.user_id = 0 ) ';
        if ($user_id > 0):
            $where_user = ' (t.user_id = 0 OR t.user_id = ' . $user_id . ') ';
        endif;
        $this->db->where($where_user);
        
        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        
        if (!empty($type) && $user_id > 0):
            $this->db->group_by('t.id');
        endif;
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_templates . ' t')->result_array();
        else:
            $res_data = $this->db->get(tbl_templates . ' t')->num_rows();
        endif;
        return $res_data;
    }

}
