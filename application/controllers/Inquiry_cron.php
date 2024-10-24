<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inquiry_cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'CMS_model', 'Inquiries_model', 'Recurring_model']);
    }

    public function create_inquiry() {
        date_default_timezone_set("Asia/Calcutta");
        $response = array();
        if ($this->input->post()) {
            $post_array = $this->input->post();
            $this->form_validation->set_rules('api_token', 'Token', 'trim|required|strip_tags');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|strip_tags');
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|required|strip_tags');
            $this->form_validation->set_rules('automation_id', 'Automation', 'trim|required|strip_tags|callback_check_automation_id'); //
            if ($this->form_validation->run() == FALSE) {
//                $this->form_validation->set_error_delimiters('', '');
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                if (isset($post_array['api_token']) && !empty($post_array['api_token'])) {
                    date_default_timezone_set("Asia/Calcutta");
                    $where_a = 'api_token = ' . $this->db->escape($post_array['api_token']);
                    $check_User = $this->CMS_model->get_result(tbl_user_settings, $where_a);
                    if (isset($check_User) && !empty($check_User)) {
                        $user_id = $check_User[0]['user_id'];
                        $automation_id = $this->input->post('automation_id');
                        $name = $this->input->post('name');
                        $insert_array = [
                            'name' => $this->input->post('name'),
                            'phone_number' => $this->input->post('phone_number'),
                            'phone_number_full' => $this->input->post('country_code') . '' . $this->input->post('phone_number'),
                            'inquiry_type' => 4,
                            'automation_id' => $automation_id,
                            'user_id' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $inquiry_id = $this->CMS_model->insert_data(tbl_inquiries, $insert_array);

                        if ($automation_id != '' && $inquiry_id != '') {
                            $where_a = 'id = ' . $this->db->escape($automation_id);
                            $check_automation = $this->CMS_model->get_result(tbl_automations, $where_a);
                            $dates = array();
                            if (isset($check_automation[0]) && !empty($check_automation[0])) {
                                $details = (isset($check_automation[0]['details'])) ? json_decode($check_automation[0]['details']) : array();
                                if (!empty($details)) {
                                    $date = date('Y-m-d H:i:s');
                                    foreach ($details as $key => $detail) {
                                        if (is_numeric($detail)) {
                                            $automation_template_detail = create_template_message($automation_id, $key, $detail, $name, '', $user_id);
                                            $dates[] = array(
                                                'user_id' => $user_id,
                                                'inquiry_id' => $inquiry_id,
                                                'automation_id' => $automation_id,
                                                'automation_template_id' => $detail,
                                                'temp_param' => json_encode($automation_template_detail),
                                                'notification_date' => $date,
                                                'created_at' => date('Y-m-d H:i:s')
                                            );
                                        } else {
                                            $date_details = explode(' ', $detail);
                                            if (isset($date_details[1]) && ($date_details[1] == 'Minutes' || $date_details[1] == 'Hours')) {
                                                $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                            } else {
//                                                $date = date('Y-m-d', strtotime($date)) . '' . date('H:i:s');
//                                                $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));

                                                $trigger_time = ($key > 1) ? $check_automation[0]['trigger_time'] : date('H:i:s');
                                                $date = date('Y-m-d', strtotime($date)) . '' . $trigger_time;
                                                $date = date('Y-m-d H:i:s', strtotime($detail, strtotime($date)));
                                            }
                                        }
                                    }
                                }
                            }
                            if (!empty($dates)) {
                                $this->CMS_model->insert_batch(tbl_inquiry_logs, $dates);
                            }
                        }
                        $response = array(
                            'status' => 'success',
                            'message' => $post_array
                        );
                    } else {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Token not Found'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Token not Found'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'No Post Found'
            );
        }
        echo json_encode($response);
        exit;
    }

    function check_automation_id($input) {
        $where = 'id = ' . $this->db->escape($input);
        $check_automation = $this->CMS_model->get_result(tbl_automations, $where);
        if (!empty($check_automation)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_automation_id', 'Automation ID %s not found.');
            return FALSE;
        }
        return FALSE;
    }

}

?>