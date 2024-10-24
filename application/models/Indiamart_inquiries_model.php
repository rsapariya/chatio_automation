<?php

class Indiamart_inquiries_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_running_inquiries($user_id = '') {
        $this->db->select('imi.*,us.crm_key');
        if (!empty($user_id)) {
            $this->db->where('imi.user_id', $user_id);
        } else {
            $this->db->where('( imi.last_run_at <= (NOW() - INTERVAL 6 MINUTE) OR imi.last_run_at IS NULL) AND (imi.status = "running" OR imi.status = "pending") AND us.crm_key IS NOT NULL');
        }
        $this->db->where(['imi.last_cron_day >' => 0]);
        $this->db->join(tbl_user_settings . ' us', 'us.user_id = imi.user_id');
        $this->db->order_by('imi.status', 'desc');
        if (!empty($user_id)) {
            $res_data = $this->db->get(tbl_indiamart_inquiries . ' imi')->row_array();
        }else{
            $res_data = $this->db->get(tbl_indiamart_inquiries . ' imi')->result_array();
        }
//        echo $this->db->last_query();
        return $res_data;
    }

    public function get_daily_inquiries() {
        $this->db->select('imi.*,us.crm_key');
        $this->db->where('imi.last_run_at <= (NOW() - INTERVAL 1 Day) AND (imi.status = "success") AND us.crm_key IS NOT NULL');
        $this->db->where(['imi.last_cron_day <' => 0]);
        $this->db->join(tbl_user_settings . ' us', 'us.user_id = imi.user_id');
        $this->db->order_by('imi.status', 'desc');
        $res_data = $this->db->get(tbl_indiamart_inquiries . ' imi')->result_array();
//        echo $this->db->last_query();
        return $res_data;
    }

    public function get_inquiries($user_id = '') {
        $this->db->select('imi.*,us.crm_key');
        $this->db->where('(imi.status = "success") AND us.crm_key IS NOT NULL');
        if (!empty($user_id)) {
            $this->db->where('imi.user_id', $user_id);
        }else{
            $this->db->where(['imi.last_cron_day <' => 0]);
        }
        $this->db->join(tbl_user_settings . ' us', 'us.user_id = imi.user_id');
        $this->db->order_by('imi.status', 'desc');
        if (!empty($user_id)) {
            $res_data = $this->db->get(tbl_indiamart_inquiries . ' imi')->row_array();
        }else{
            $res_data = $this->db->get(tbl_indiamart_inquiries . ' imi')->result_array();
        }
        return $res_data;
    }


    public function get_indiamart_inquiries_logs($user_id) {
        $this->db->where(['user_id' => $user_id, 'status' => 'success']);
        $this->db->order_by('id', 'desc');
        $res_data = $this->db->get(tbl_indiamart_inquiries)->row_array();

        $log_data = array();
        if (!empty($res_data)) {
            $this->db->where(['inquiry_id' => $res_data['id']]);
            $log_data = $this->db->get(tbl_indiamart_inquiry_logs)->result_array();
        }
        return $log_data;
    }

    public function get_indiamart_inquiries_unqiue_data($user_id, $field = 'city', $lead_souorce = 'indiamart') {
        $this->db->select("DISTINCT $field", false);
        $this->db->where(['user_id' => $user_id]);
        if(!empty($lead_souorce)){
            $this->db->where(['leads_source' => $lead_souorce]);
        }
        $res_data = $this->db->get(tbl_indiamart_customer_leads)->result_array();

        $data = array();
        if (!empty($res_data)) {
            foreach ($res_data as $res) {
                if (!empty($res[$field]))
                    $data[] = $res[$field];
            }
        }
        sort($data);
        return $data;
    }

    /**
     * @uses : this function is used to get result based on datatable in brand list page
     * @author : HPA
     */
    public function get_all_leads($count = null, $user_id = 0) {
        //pr($this->input->post(),1);
        $start = $this->input->get('start');
        $columns = ['l.id', 'l.name', 'l.mobile','l.leads_source' ,'l.subject', 'l.company', 'l.city', 'l.product_name', 'l.message', 'l.mcat_name', 'l.query_time'];    
       
        $this->db->select('@a:=@a+1 AS test_id,l.*,l.query_time', false);
        $keyword = $this->input->post('search');
        $city = $this->input->post('city');
        $mcat_name = $this->input->post('mcat_name');
        $query_time = $this->input->post('query_time');
        $this->db->where('l.leads_source', 'indiamart');

        if (!empty($keyword['value'])) {
            $this->db->having('l.company LIKE "%' . $keyword['value'] . '%" OR l.name LIKE "%' . $keyword['value'] .'%" OR l.leads_source LIKE "%' . $keyword['value'] . '%" OR l.subject LIKE "%' . $keyword['value'] . '%"  OR l.product_name LIKE "%' . $keyword['value'] . '%" OR l.mobile LIKE "%' . $keyword['value'] . '%" OR l.message LIKE "%' . $keyword['value'] . '%" OR l.mcat_name LIKE "%' . $keyword['value'] . '%" OR l.query_time LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if (!empty($city)) {
            $this->db->where('l.city', $city);
        }
        if (!empty($mcat_name)) {
            $this->db->where('l.mcat_name', $mcat_name);
        }
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(l.query_time) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(l.query_time) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
            $this->db->where('l.user_id', $user_id);
        endif;
        $this->db->group_by('l.query_id');

        $order = $this->input->post('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }
        
        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->result_array();
        else:
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->num_rows();
        endif;
        return $res_data;
    }

    public function get_tradeindia_leads($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['l.id', 'l.name', 'l.mobile','l.leads_source' ,'l.subject', 'l.company', 'l.city', 'l.product_name', 'l.message', 'l.query_time'];    
       
        $this->db->select('@a:=@a+1 AS test_id,l.*,l.query_time', false);
        $keyword = $this->input->post('search');
        $city = $this->input->post('city');
        $query_time = $this->input->post('query_time');
        $this->db->where('l.leads_source', 'tradeindia');
        if (!empty($keyword['value'])) {
            $this->db->having('l.company LIKE "%' . $keyword['value'] . '%" OR l.name LIKE "%' . $keyword['value'] .'%" OR l.leads_source LIKE "%' . $keyword['value'] . '%" OR l.subject LIKE "%' . $keyword['value'] . '%"  OR l.product_name LIKE "%' . $keyword['value'] . '%" OR l.mobile LIKE "%' . $keyword['value'] . '%" OR l.message LIKE "%' . $keyword['value'] . '%" OR l.mcat_name LIKE "%' . $keyword['value'] . '%" OR l.query_time LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if (!empty($city)) {
            $this->db->where('l.city', $city);
        }
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(l.query_time) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(l.query_time) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
            $this->db->where('l.user_id', $user_id);
        endif;
        $this->db->group_by('l.query_id');

        $order = $this->input->post('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }
        
        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->result_array();
        else:
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->num_rows();
        endif;
        return $res_data;
    }

    public function get_exportersindia_leads($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['l.id', 'l.name', 'l.mobile','l.leads_source' ,'l.subject', 'l.company', 'l.city', 'l.product_name', 'l.message', 'l.query_time'];    
       
        $this->db->select('@a:=@a+1 AS test_id,l.*,l.query_time', false);
        $keyword = $this->input->post('search');
        $city = $this->input->post('city');
        $query_time = $this->input->post('query_time');
        $this->db->where('l.leads_source', 'exportersindia');
        if (!empty($keyword['value'])) {
            $this->db->having('l.company LIKE "%' . $keyword['value'] . '%" OR l.name LIKE "%' . $keyword['value'] .'%" OR l.leads_source LIKE "%' . $keyword['value'] . '%" OR l.subject LIKE "%' . $keyword['value'] . '%"  OR l.product_name LIKE "%' . $keyword['value'] . '%" OR l.mobile LIKE "%' . $keyword['value'] . '%" OR l.message LIKE "%' . $keyword['value'] . '%" OR l.mcat_name LIKE "%' . $keyword['value'] . '%" OR l.query_time LIKE "%' . $keyword['value'] . '%"', NULL);
        }
        if (!empty($city)) {
            $this->db->where('l.city', $city);
        }
        if (!empty($query_time)) {
            $times = explode('-', $query_time);
            $this->db->where('DATE(l.query_time) >=', date('Y-m-d', strtotime($times[0])));
            $this->db->where('DATE(l.query_time) <=', date('Y-m-d', strtotime($times[1])));
        }
        if ($user_id > 0):
            $this->db->where('l.user_id', $user_id);
        endif;
        $this->db->group_by('l.query_id');
        
        $order = $this->input->post('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        }

        if (is_null($count)):
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->result_array();
        else:
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->num_rows();
        endif;
        return $res_data;
    }

    public function get_lead_messages() {
        $this->db->select('ilm.*,us.instance_id,us.access_token,us.permanent_access_token,us.phone_number_id,us.business_account_id');
        $this->db->where(['ilm.status' => 'pending']);
        $this->db->join(tbl_user_settings . ' us', 'us.user_id = ilm.user_id');
        $this->db->order_by('ilm.status', 'desc');
        $res_data = $this->db->get(tbl_indiamart_leads_message . ' ilm')->result_array();
        return $res_data;
    }

    public function get_leads($ids = array()) {
        $this->db->select('id,mobile,name');
        $this->db->where_in('id', json_decode($ids));
        $this->db->order_by('query_time', 'desc');
        $res_data = $this->db->get(tbl_indiamart_customer_leads)->result_array();
        return $res_data;
    }

    public function get_lead_rem_inquiries() {
        $this->db->where(['inquiry_id' => 4, 'id > ' => 164]);
        $log_data = $this->db->get(tbl_indiamart_inquiry_logs)->result_array();
        return $log_data;
    }

    public function get_all_logs($count = null, $user_id = 0) {
        $start = $this->input->get('start');
        $columns = ['l.id', 'l.customer_name', 'l.customer_mobile','cl.leads_source','l.template_name' ,'l.message_status', 'l.created', 'l.deliver_time',  'l.read_time'];    
       
        $this->db->select('@a:=@a+1 AS test_id,l.*,cl.leads_source,l.created,l.deliver_time,l.read_time', false);
        $this->db->join(tbl_indiamart_customer_leads . ' cl', 'cl.id = l.lead_id');
        $keyword = $this->input->get('search');
        
        if (!empty($keyword['value'])) {
            $this->db->having('l.customer_name LIKE "%' . $keyword['value'] . '%"  OR cl.leads_source LIKE "%' . $keyword['value'] .'%"  OR l.customer_mobile LIKE "%' . $keyword['value'] .'%"  OR l.message_status LIKE "%' . $keyword['value'] .'%" OR l.template_name LIKE "%' . $keyword['value'] . '%" OR l.created LIKE "%' . $keyword['value'] . '%"', NULL);
        }
       
        if ($user_id > 0):
            $this->db->where('l.user_id', $user_id);
        endif;

        $order = $this->input->get('order');
        if(!empty($order)){
            $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        }
        
        if (is_null($count)):
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $res_data = $this->db->get(tbl_lead_notify_log . ' l')->result_array();
        else:
            $res_data = $this->db->get(tbl_lead_notify_log . ' l')->num_rows();
        endif;
        return $res_data;
    }

    //get all users with crm_token and which have access to crm
    public function get_allowed_crm_users_credential($crm = crm_indiamart){
        if($crm == crm_indiamart){
            $this->db->select('u.id as user_id,u.crm_lead_access,u.waba_access,u.phone_number,us.id, us.permanent_access_token,us.phone_number_id,us.crm_key,us.message_on_inquiry,us.forward_inquiry');
        }
        if($crm == crm_tradeindia){
            $this->db->select('u.id as user_id,u.crm_lead_access,u.waba_access,u.phone_number,us.id, us.permanent_access_token,us.phone_number_id,us.tradeindia_user_id,us.tradeindia_profile_id,us.tradeindia_key,us.message_on_inquiry,us.forward_inquiry');
        }
        if($crm == crm_exportersindia){
            $this->db->select('u.id as user_id,u.crm_lead_access,u.waba_access,u.phone_number,us.id, us.permanent_access_token,us.phone_number_id,us.exportersindia_key,us.exportersindia_email,us.message_on_inquiry,us.forward_inquiry');
        }

        $this->db->where('(u.crm_lead_access = 1) AND us.crm_key IS NOT NULL');
        $this->db->where('u.is_deleted = 0');
        $this->db->join(tbl_user_settings . ' us', 'us.user_id = u.id');
        $this->db->order_by('u.id', 'asc');
        $res_data = $this->db->get(tbl_users . ' u')->result_array();
        return $res_data;
    }

    public function get_filtered_leads($data_arr = []) {
        if(!empty($data_arr)){
            $select = 'l.name,l.mobile,l.leads_source,l.subject,l.company,l.city,l.state,l.product_name,l.message,l.query_time';    
            if($data_arr['leads_source'] == 'indiamart'){
                $select .= ',l.mcat_name';    
            }

            $this->db->select($select, false);
            $this->db->where('l.leads_source', $data_arr['leads_source']);

            if (!empty($data_arr['query_time'])) {
                $times = explode('-', $data_arr['query_time']);
                $this->db->where('DATE(l.query_time) >=', date('Y-m-d', strtotime($times[0])));
                $this->db->where('DATE(l.query_time) <=', date('Y-m-d', strtotime($times[1])));
            }
            if(!empty($data_arr['user_id'])){
                $this->db->where('l.user_id =', $data_arr['user_id']);
            }
            
            $this->db->order_by('l.query_time', 'desc');
            $res_data = $this->db->get(tbl_indiamart_customer_leads . ' l')->result_array();
            return $res_data;
            
        }
        
    }



}
