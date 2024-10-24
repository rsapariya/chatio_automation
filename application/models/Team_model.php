<?php

class Team_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->data = get_admin_data();
        $this->user_id = $this->data['user_data']['id'];
    }

    /**
     * @author : RR
     */
    public function get_members($count = null) {
        $start = $this->input->get('start');
        $columns = ['u.id', 'u.name', 'u.email', 'u.phone_number','u.is_blocked', 'u.last_login','u.last_ip'];
        $this->db->select('u.*', false);
        $this->db->where(['u.added_by' => $this->user_id, 'u.type' => 'member']);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('u.name LIKE "%' . $keyword['value'] . '%" OR u.email LIKE "%' . $keyword['value'] . '%"  OR u.last_ip LIKE "%' . $keyword['value'] . '%" OR u.phone_number LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_users . ' u')->result_array();
        else:
            $res_data = $this->db->get(tbl_users . ' u')->num_rows();
        endif;
        return $res_data;
    }
    
}