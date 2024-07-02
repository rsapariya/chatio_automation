<?php

class ReplyMessage_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_reply_messages($count = null, $user_id = 0) {
        $start = $this->input->get('start');
//        $columns = ['rm.id', 'rm.reply_text', 'rm.messages', 'rm.attachments', 'rm.created_at', 'rm.is_deleted'];
        $columns = ['rm.id', 'reply_text', 'rm.attachments', 'rm.created_at', 'rm.is_deleted'];
        $select = 'rm.id,@a:=@a+1 AS test_id,rm.reply_text,GROUP_CONCAT(rm.reply_text SEPARATOR ",") AS reply_text,rm.messages,rm.attachments,DATE_FORMAT(rm.created_at,"%d %b %Y <br> %l:%i %p") AS created_at,rm.is_deleted,rm.user_id,rm.reply_id';
        $this->db->select($select, false);
        $this->db->where(['rm.is_deleted' => 0]);
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
//            $this->db->having('rm.reply_text LIKE "%' . $keyword['value'] . '%" OR rm.messages LIKE "%' . $keyword['value'] . '%" OR rm.created_at LIKE "%' . $keyword['value'] . '%"', NULL);
            $this->db->having('reply_text LIKE "%' . $keyword['value'] . '%" OR created_at LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if ($user_id > 0):
            $where_user = ' (rm.user_id = ' . $user_id . ') ';
            $this->db->where($where_user);
        endif;
//        $this->db->where('rm.reply_id is not NULL');
        $this->db->group_by('rm.reply_id');
        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_reply_messages . ' rm')->result_array();
        else:
            $res_data = $this->db->get(tbl_reply_messages . ' rm')->num_rows();
        endif;
        return $res_data;
    }

    public function get_user_details($business_account_id = '') {
        $res_data = array();
        $this->db->where(['business_account_id' => $business_account_id]);
        $this->db->limit('1');
        $user_setting_data = $this->db->get(tbl_user_settings)->row_array();
        return $user_setting_data;
    }

    public function get_trigger_message_attachment($message = '', $business_account_id = '') {
        $res_data = array();
        $this->db->where(['business_account_id' => $business_account_id]);
        $this->db->limit('1');
        $user_setting_data = $this->db->get(tbl_user_settings)->row_array();
        $permanent_access_token = '';
        if (isset($user_setting_data) && !empty($user_setting_data)) {
            $user_id = $user_setting_data['user_id'];
            $permanent_access_token = $user_setting_data['permanent_access_token'];

            $message = trim($message);
            $this->db->where(['is_deleted' => 0, 'reply_text' => $message]);
            if ($user_id > 0):
                $where_user = ' (user_id = ' . $user_id . ') ';
                $this->db->where($where_user);
            endif;
            $this->db->limit('1');
            $res_data = $this->db->get(tbl_reply_messages)->row_array();
            if (!empty($res_data) && !empty($permanent_access_token)) {
                $res_data['permanent_access_token'] = $permanent_access_token;
            }
        }
        return $res_data;
    }

    public function get_list_templates($user_id = 0, $id = null, $full_details = false) {
        $select = 'a.id,a.name,a.custom_type';
        if (!empty($id)) {
            $select = 'a.id,a.name,a.custom_type';
            if ($full_details) {
                $select = 'a.id,a.name,a.custom_type,a.description,a.user_id';
            }
        }
        $this->db->select($select, false);
        if ($user_id > 0):
//            $where_user = ' (a.user_id = 0 OR a.user_id = ' . $user_id . ') AND (type = "automation" AND custom_type IS NOT NULL)';
            $where_user = ' (a.user_id = ' . $user_id . ') AND (type = "automation" AND custom_type IS NOT NULL)';
            $this->db->where($where_user);
        endif;
        $where = ' (a.temp_id IS NULL)';
        $this->db->where($where);
        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }
        $this->db->where(['a.is_deleted' => 0]);
        $this->db->order_by('a.name', 'ASC');
        if (!empty($id)) {
            $res_data = $this->db->get(tbl_templates . ' a')->row_array();
        } else {
            $res_data = $this->db->get(tbl_templates . ' a')->result_array();
        }
        return $res_data;
    }

    public function get_meta_templates($user_id = 0, $id = null, $full_details = false) {
        $select = 'a.id,a.name';
        if (!empty($id)) {
            $select = 'a.*';
            if ($full_details) {
                $select = 'a.id,a.name,a.temp_language,a.description,a.user_id';
            }
        }
        $this->db->select($select, false);
        if ($user_id > 0):
//            $where_user = ' (a.user_id = 0 OR a.user_id = ' . $user_id . ') AND (type = "automation" AND custom_type IS NULL)';
            $where_user = ' (a.user_id = ' . $user_id . ') AND (type = "automation" AND custom_type IS NULL)';
            $this->db->where($where_user);
        endif;
        $where = ' (a.temp_id IS NOT NULL)';
        $this->db->where($where);
        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }
        $this->db->where(['a.is_deleted' => 0]);
        $this->db->order_by('a.name', 'ASC');
        if (!empty($id)) {
            $res_data = $this->db->get(tbl_templates . ' a')->row_array();
        } else {
            $res_data = $this->db->get(tbl_templates . ' a')->result_array();
        }
        return $res_data;
    }

    /*
    * @uses: check multiple reply text
    * @author : RR 
    * @require : reply_text(as a string), user_id, reply_id
    */
    public function check_replytext($reply_text, $user_id, $reply_id = ''){
        $this->db->where_in('reply_text', $reply_text);
        $this->db->where('user_id',$user_id);
        if (!empty($reply_id)) {
            $this->db->where('reply_id !=',$reply_id);
        }
        $query = $this->db->get(tbl_reply_messages);
        echo $this->db->last_query();
        return $query->num_rows();
        
    }

}
