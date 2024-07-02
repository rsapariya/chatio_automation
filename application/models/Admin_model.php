<?php

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get user data
     * @param : @user_id
     * @author : HPA
     */
    public function get_user_data($user_id) {
        $this->db->where('id', $user_id);
        $users = $this->db->get(tbl_users);
        return $users->row_array();
    }

    /**
     * @uses : this function is used to get user data email and password wise
     * @param : @email,@password 
     * @author : HPA
     */
    public function get_user($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('is_deleted', 0);
        $users = $this->db->get(tbl_users);
        $user_detail = $users->result_array();
        if (count($user_detail) == 1) {
            $db_password = $this->encrypt->decode($user_detail[0]['password']);
            if ($db_password == $password) {
                return $user_detail;
            } else {
                return array();
            }
        }
        return array();
    }

}
