<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_users($count = null, $wher = null) {
        $start = $this->input->get('start');
        $columns = ['u.id', 'u.name', 'u.email', 'u.phone_number_full', 'u.type', 'u.created_at', 'u.is_deleted'];
        $this->db->select('u.id,@a:=@a+1 AS test_id,u.name,u.phone_number,u.phone_number_full,DATE_FORMAT(u.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,u.is_deleted,u.email,u.is_blocked,u.type', false);
        $this->db->where(['u.is_deleted' => 0]);
        $this->db->where_in('u.type', array('admin', 'user'));
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('u.email LIKE "%' . $keyword['value'] . '%" OR u.name LIKE "%' . $keyword['value'] . '%" OR u.type LIKE "%' . $keyword['value'] . '%"  OR u.phone_number LIKE "%' . $keyword['value'] . '%" OR u.phone_number_full LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%"', NULL);
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

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_user_customers($count = null, $wher = null, $id = null) {
        if (empty($id)) {
            $id = $this->session->userdata('id');
        }
        $start = $this->input->get('start');
        $columns = ['u.id', 'u.name', 'u.email', 'u.phone_number_full', 'u.birth_date', 'u.anniversary_date', 'u.created_at', 'u.is_deleted'];
        $this->db->select('u.id,@a:=@a+1 AS test_id,u.name,u.phone_number,u.phone_number_full,DATE_FORMAT(u.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,u.is_deleted,u.email,DATE_FORMAT(u.birth_date,"%d %b %Y")AS cust_birth_date,DATE_FORMAT(u.anniversary_date,"%d %b %Y")AS cust_anniversary_date', false);
        $this->db->where(['u.is_deleted' => 0, 'user_id' => $id]);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('u.email LIKE "%' . $keyword['value'] . '%" OR u.name LIKE "%' . $keyword['value'] . '%" OR u.phone_number LIKE "%' . $keyword['value'] . '%" OR u.phone_number_full LIKE "%' . $keyword['value'] . '%"  OR cust_birth_date LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%" OR cust_anniversary_date LIKE "%' . $keyword['value'] . '%"', NULL);
        }

        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_clients . ' u')->result_array();
        else:
            $res_data = $this->db->get(tbl_clients . ' u')->num_rows();
        endif;
        return $res_data;
    }

    public function getClients($id = null) {
        $date = date('Y-m-d');
        $this->db->select('u.id,u.name,u.phone_number,u.phone_number_full,u.email,DATE_FORMAT(u.birth_date,"%d %b %Y")AS cust_birth_date,DATE_FORMAT(u.anniversary_date,"%d %b %Y")AS cust_anniversary_date,u.user_id,u.group_ids as group_ids', false);
        $this->db->where(['u.is_deleted' => 0]);
        $where = ' ((DAY(u.birth_date) = DAY(now()) and MONTH(u.birth_date) = MONTH(now()) ) OR (DAY(u.anniversary_date) = DAY(now()) and MONTH( u.anniversary_date) = MONTH(now())))';
        $this->db->where($where);
        if (!empty($id)) {
            $this->db->where('u.id', $id);
        }
        $this->db->order_by('u.id', 'desc');
        $res_data = $this->db->get(tbl_clients . ' u')->result_array();
        return $res_data;
    }

    public function get_random_template($user_id, $type) {
        $this->db->select('*', false);
        $this->db->where('t.type', $type);
        $where_user = ' (t.user_id = 0 OR t.user_id = ' . $user_id . ') ';
        $this->db->where($where_user);
        $this->db->order_by('RAND()');
        $this->db->limit('1');
        $res_data = $this->db->get(tbl_templates . ' t')->row_array();
        return $res_data;
    }

    public function get_access_details_and_credentials($userid){
        $this->db->select('*', false);
        $this->db->where('t.type', $type);
        $this->db->join(tbl_indiamart_customer_leads . ' cl', 'cl.id = l.lead_id');
        $res_data = $this->db->get(tbl_templates . ' t')->row_array();
    }

}
