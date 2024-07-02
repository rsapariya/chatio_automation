<?php

class Tradeindia_inquiries_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_credential($user_id = '') {
        $this->db->select('us.*,imi.id, u.phone_number');
        if (!empty($user_id)) {
            $this->db->where('us.user_id', $user_id);
        } else {
            $this->db->where('us.tradeindia_user_id IS NOT NULL', NULL);
            $this->db->where('us.tradeindia_profile_id IS NOT NULL', NULL);
            $this->db->where('us.tradeindia_key IS NOT NULL', NULL);
        }
        $this->db->join(tbl_indiamart_inquiries . ' imi', 'us.user_id = imi.user_id');
        $this->db->join(tbl_users . ' u', 'u.id = us.user_id');
        if (!empty($user_id)) {
            $res_data = $this->db->get(tbl_user_settings . ' us')->row_array();
        }else{
            $res_data = $this->db->get(tbl_user_settings . ' us')->result_array();
        }
        return $res_data;
    }

    public function get_inquiries($user_id = '') {
        $this->db->select('imi.*,us.tradeindia_user_id,us.tradeindia_profile_id,us.tradeindia_key');
        if (!empty($user_id)) {
            $this->db->where('imi.user_id', $user_id);
        } else {
            $this->db->where('(us.tradeindia_user_id IS NOT NULL, us.tradeindia_profile_id IS NOT NULL, us.tradeindia_key IS NOT NULL');
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

    public function get_indiamart_inquiries_unqiue_data($user_id, $field = 'city') {
        $this->db->select("DISTINCT $field", false);
        $this->db->where(['user_id' => $user_id]);
        $res_data = $this->db->get(tbl_indiamart_customer_leads)->result_array();
//        echo $this->db->last_query();\
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
        $start = $this->input->get('start');
        $columns = ['l.id', 'l.name', 'l.mobile', 'l.subject', 'l.company', 'l.city', 'l.product_name', 'l.message', 'l.mcat_name', 'l.query_time'];
        $this->db->select('@a:=@a+1 AS test_id,l.*,DATE_FORMAT(l.query_time,"%d %b %Y") AS query_time', false);
        $keyword = $this->input->post('search');
        $city = $this->input->post('city');
        $mcat_name = $this->input->post('mcat_name');
        $query_time = $this->input->post('query_time');
        if (!empty($keyword['value'])) {
            $this->db->having('l.company LIKE "%' . $keyword['value'] . '%" OR l.name LIKE "%' . $keyword['value'] . '%" OR l.subject LIKE "%' . $keyword['value'] . '%"  OR l.product_name LIKE "%' . $keyword['value'] . '%" OR l.mobile LIKE "%' . $keyword['value'] . '%" OR l.message LIKE "%' . $keyword['value'] . '%" OR l.mcat_name LIKE "%' . $keyword['value'] . '%" OR query_time LIKE "%' . $keyword['value'] . '%"', NULL);
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
//        $this->db->order_by($columns[$this->input->get('order')[0]['column']], $this->input->get('order')[0]['dir']);
        $order_col = $this->input->post('iSortCol_0');
        $order = $this->input->post('sSortDir_0');
        if (!empty($order) && !empty($order_col)) {
            $this->db->order_by($columns[$order_col], $order);
        } else {
            $this->db->order_by('l.id', 'desc');
        }
        if (is_null($count)):
//            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $this->db->limit($this->input->post('iDisplayLength'), $this->input->post('iDisplayStart'));
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

}
