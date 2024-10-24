<?php

class Tags_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->data = get_admin_data();
        $this->user_id = $this->data['user_data']['id'];
    }

    /**
     * @author : RR
     */
    public function get_all_tags($count = null) {
        $start = $this->input->get('start');
        $columns = ['t.id', 't.tag'];
        $this->db->select('t.*', false);
        $this->db->where(['t.user_id' => $this->user_id]);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('t.tag LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_tags . ' t')->result_array();
        else:
            $res_data = $this->db->get(tbl_tags . ' t')->num_rows();
        endif;
        return $res_data;
    }

    public function is_contain_contacts($user_id, $tag, $return = '') {
        $this->db->select('id');
        $this->db->from(tbl_clients);
        $this->db->where('user_id', $user_id);
        $this->db->where("FIND_IN_SET('$tag', group_ids) != 0");
        $query = $this->db->get();
        if(empty($return)){
            if ($query->num_rows() > 0) {
                return true;
            }
        }else if($return == 'data'){
            return $query->result_array();
        }
        
        return false;
    }
}
