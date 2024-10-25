<?php

class Campaigns_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get($count = null) {
        $start = $this->input->get('start');
        $columns = ['c.id', 'c.campaign_name', 't.name', 'c.created', 'c.status'];
        /*$contacts = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id';
        $sent_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status !="failed"';
        $failed_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="failed"';
        $deliver_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="delivered"';
        $read_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="read"';
        $accepted_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="accepted"';

        $select = 'c.id,c.campaign_name,t.name,c.created,c.status, (' . $contacts . ') as contacts, (' . $accepted_messages . ') as accepted_messages, (' . $failed_messages . ') as failed_messages, (' . $deliver_messages . ') as delivered_messages, (' . $read_messages . ') as read_messages';
        $this->db->select($select, false);*/
        
        $this->db->select('c.id, c.campaign_name, t.name, c.created, c.status, 
                   COUNT(cq.campaign_id) AS contacts,
                   SUM(CASE WHEN cq.message_status = "accepted" THEN 1 ELSE 0 END) AS accepted_messages,
                   SUM(CASE WHEN cq.message_status = "failed" THEN 1 ELSE 0 END) AS failed_messages,
                   SUM(CASE WHEN cq.message_status = "delivered" THEN 1 ELSE 0 END) AS delivered_messages,
                   SUM(CASE WHEN cq.message_status = "read" THEN 1 ELSE 0 END) AS read_messages');
        $this->db->join(tbl_templates . ' t', 't.id=c.template_id');
        $this->db->join('campaign_queue cq', 'cq.campaign_id = c.id', 'left');
        $keyword = $this->input->get('search');
        if (!empty($keyword['value'])) {
            $this->db->having('c.campaign_name LIKE "%' . $keyword['value'] . '%" OR c.status LIKE "%' . $keyword['value'] . '%" OR t.name LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if ($this->session->userdata('type') == 'user') {
            $user_id = $this->session->userdata('id');
        }
        if ($this->session->userdata('type') == 'member') {
            $user_id = $this->session->userdata('added_by');
        }
        $this->db->where('c.user_id', $user_id);
        $this->db->group_by('c.id');
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }

        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_campaigns . ' c')->result_array();
        else:
            $res_data = $this->db->get(tbl_campaigns . ' c')->num_rows();
        endif;
        return $res_data;
    }

    public function campaign_logs() {
        $date = date('Y-m-d H:i:s');
        $this->db->select('cq.*,c.user_id, t.name as template_name, t.temp_language', false);
        $this->db->join(tbl_campaigns . ' c', 'c.id = cq.campaign_id');
        $this->db->join(tbl_templates . ' t', 't.id = c.template_id');
        $this->db->where('cq.is_sent', '0');
        $this->db->where('cq.is_deleted', '0');
        $where = ' cq.notification_date <= "' . $date . '"';
        $this->db->where($where);
        $this->db->limit(170);
        $this->db->order_by('cq.id', 'asc');
        $res_data = $this->db->get(tbl_campaign_queue . ' cq')->result_array();
        return $res_data;
    }

    public function campaign_status($campaign_id) {
        $contacts = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id';
        $sent_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.is_sent= 1';
        $select = 'c.status, (' . $contacts . ') as total_messages, (' . $sent_messages . ') as sent_messages';
        $this->db->select($select, false);
        $this->db->where('c.id', $campaign_id);
        $res_data = $this->db->get(tbl_campaigns . ' c')->row_array();
        return $res_data;
    }

    public function campaign_details($campaign_id) {
        /*$start = $this->input->get('start');
        $columns = ['c.id', 'c.campaign_name', 't.name', 'c.created', 'c.status'];
        $contacts = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id';
        $sent_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status !="failed"';
        $failed_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="failed"';
        $delivered_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="delivered"';
        $accepted_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="accepted"';
        $read_messages = 'select count(*) from ' . tbl_campaign_queue . ' as cq where cq.campaign_id = c.id AND cq.message_status="read"';
        $select = 'c.id,c.campaign_name,t.name,c.created,c.status, (' . $contacts . ') as contacts, (' . $sent_messages . ') as sent_messages, (' . $failed_messages . ') as failed_messages, (' . $delivered_messages . ') as delivered_messages, (' . $read_messages . ') as read_messages,  (' . $accepted_messages . ') as accepted_messages';
        $this->db->select($select, false);
        */
        $this->db->select('c.id, c.campaign_name, t.name, c.created, c.status, 
                   COUNT(cq.id) AS contacts, 
                   COUNT(CASE WHEN cq.message_status != "failed" THEN 1 END) AS sent_messages, 
                   COUNT(CASE WHEN cq.message_status = "failed" THEN 1 END) AS failed_messages, 
                   COUNT(CASE WHEN cq.message_status = "delivered" THEN 1 END) AS delivered_messages, 
                   COUNT(CASE WHEN cq.message_status = "read" THEN 1 END) AS read_messages, 
                   COUNT(CASE WHEN cq.message_status = "accepted" THEN 1 END) AS accepted_messages');
        $this->db->join(tbl_templates . ' t', 't.id=c.template_id');
        $this->db->join('campaign_queue cq', 'cq.campaign_id = c.id', 'left');
        $this->db->where('c.id', $campaign_id);
        $this->db->group_by('c.id, c.campaign_name, t.name, c.created, c.status');
        $res_data = $this->db->get(tbl_campaigns . ' c')->row_array();
        return $res_data;
    }

    public function get_campaign_contacts($count = null) {
        //pr($this->input->post(),1);
        $start = $this->input->get('start');
        $columns = ['c.created', 'cn.name', 'cq.contact_number', 'cq.is_sent', 'cq.sent_time', 'cq.message_status', 'cq.deliver_time', 'cq.read_time'];

        $this->db->select('cq.*,c.campaign_name,c.created,c.campaign_message,cn.name', false);
        $this->db->join(tbl_campaigns . ' c', 'c.id = cq.campaign_id');
        $this->db->join(tbl_clients . ' cn', 'cn.phone_number_full = cq.contact_number', 'left');
        $keyword = $this->input->post('search');
        $campaign_id = $this->input->post('campaign_id');

        if (!empty($keyword['value'])) {
            $this->db->having('cq.contact_number LIKE "%' . $keyword['value'] . '%" OR cn.name LIKE "%' . $keyword['value'] . '%" OR cq.message_status LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if (!empty($campaign_id)) {
            $this->db->where('cq.campaign_id', base64_decode($campaign_id));
        }

        $this->db->group_by('cq.message_id');

        $order = $this->input->post('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }

        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_campaign_queue . ' cq')->result_array();
        else:
            $res_data = $this->db->get(tbl_campaign_queue . ' cq')->num_rows();
        endif;
        return $res_data;
    }

    public function get_all_campaign_contacts($campaign_id) {
        $this->db->select('cq.*,c.campaign_name,c.created,c.campaign_message,cn.name', false);
        $this->db->join(tbl_campaigns . ' c', 'c.id = cq.campaign_id');
        $this->db->join(tbl_clients . ' cn', 'cn.phone_number_full = cq.contact_number', 'left');
        $this->db->where('cq.campaign_id', $campaign_id);
        $this->db->group_by('cq.message_id');
        $res_data = $this->db->get(tbl_campaign_queue . ' cq')->result_array();
        return $res_data;
    }

    public function get_failed_campaign_contact($campaign_id) {
        $user_id = $this->session->userdata('id');
        $this->db->select('cn.*', false);
        $this->db->join(tbl_clients . ' cn', 'cn.phone_number_full = cq.contact_number');
        $this->db->where('cq.campaign_id', $campaign_id);
        $this->db->where('cn.user_id', $user_id);
        $this->db->where('cq.message_status', 'failed');
        $res_data = $this->db->get(tbl_campaign_queue . ' cq')->result_array();
        return $res_data;
    }

}
