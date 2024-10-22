<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recurrings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Recurring_model', 'CMS_model', 'Automation_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $this->template->set('title', 'Recurrings');
        $this->template->load('default_home', 'Recurrings/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_recurrings($type = '') {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Recurring_model->get_all_recurrings('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->Recurring_model->get_all_recurrings(null, $user_id);
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        //$name = '';
        $id = $this->uri->segment(3);
        $recurring_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($recurring_id)) {
            $where = 'id = ' . $this->db->escape($recurring_id);
            $check_recurring = $this->CMS_model->get_result(tbl_recurrings, $where, null, 1);
            
            //pr($check_recurring, 1);
            if ($check_recurring) {
                if(is_numeric($check_recurring['template_id'])){
                    $where = ' id=' . $check_recurring['template_id'];
                    $check_existing_template = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
                    $data['template_datas'] = $check_existing_template;
                    //
                    //$data['temp_add_seq'] = 1;
                    //$data['update_option'] = true;
                    $data['update_data'] = array(
                        'template_values' => $check_recurring['template_values'],
                        'template_media' => $check_recurring['template_media'],
                    );
                    $this->data['template_preview'] = $this->load->view('Clients/single_template_preview', $data, TRUE);
                }
                $this->data['recurring_datas'] = $check_recurring;
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }

        $contact_where = 'user_id =' . $user_id . ' AND is_subscribed = 1';
        $this->data['contacts_arr'] = $this->CMS_model->get_result(tbl_clients, $contact_where, 'id,name,phone_number_full', null, null, 'name');
        $this->data['automation_templates'] = $this->Automation_model->get_automation_templates($user_id);
        $this->template->load('default_home', 'Recurrings/edit', $this->data);
    }

    public function save() {
        //$unique_str = '';
        if ($this->input->post()) {
            //pr($this->input->post(), 1);
            $id = $this->input->post('recurring_id');
            $recurring_id = ($id != '') ? base64_decode($id) : '';

            $template_id = $this->input->post('template_id');
            $trigger_type = $this->input->post('trigger_type');

            $this->form_validation->set_rules('client_id', 'Contact Name', 'trim|required');
            if ($template_id == 'other') {
                $this->form_validation->set_rules('description', 'Description', 'trim|required');
            } else {
                $this->form_validation->set_rules('template_id', 'Template', 'trim|required');
            }
            $this->form_validation->set_rules('trigger_type', 'Trigger Type', 'trim|required');
            $this->form_validation->set_rules('trigger_time', 'Trigger Time', 'trim|required');
            if ($trigger_type == 'weekly') {
                $this->form_validation->set_rules('weekly_day', 'Week Day', 'trim|required');
            } else if ($trigger_type == 'monthly') {
                $this->form_validation->set_rules('monthly_date', 'Monthly Date', 'trim|required');
            } else if ($trigger_type == 'yearly') {
                $this->form_validation->set_rules('yearly_date', 'Yearly Date', 'trim|required');
            }
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('status' => false, 'error' => validation_errors()));
                exit();
            } else {
                $client_id = $this->input->post('client_id');
                $contactInfo = $this->CMS_model->get_result(tbl_clients, 'id =' . $client_id, null, 1);
                $trigger_time = $this->input->post('trigger_time');
                $where = array('id' => $recurring_id);
                $update_array = [
                    'client_id' => $client_id,
                    //'name' => $this->input->post('name'),
                    //'phone_number' => $this->input->post('phone_number'),
                    //'phone_number_full' => ltrim($this->input->post('phone_number_full'), '+'),
                    'trigger_time' => date('H:i:s', strtotime(getTimeBaseOnTimeZone($trigger_time, true))),
                    'trigger_type' => $this->input->post('trigger_type'),
                    'weekly_day' => $trigger_type == 'weekly' ? $this->input->post('weekly_day') : null,
                    'monthly_date' => $trigger_type == 'monthly' ? $this->input->post('monthly_date') : null,
                    'yearly_date' => $trigger_type == 'yearly' ? date('Y-m-d', strtotime($this->input->post('yearly_date'))) : null,
                    'template_id' => $template_id,
                ];
                
                $message = '';
                if ($template_id == 'other') {
                    $description = $this->input->post('description');

                    if (strstr($description, '||name||')) {
                        $description = str_replace('||name||', $contactInfo['name'], $description);
                    }
                    $update_array['description'] = $message = $description;
                } else {
                    $post = $this->input->post();
                    $input_arr['template_id'] = $template_id;
                    $input_arr['default_select_header_value'] = isset($post['default_select_header_value']) && !empty($post['default_select_header_value']) ? $post['default_select_header_value'] : '';
                    $input_arr['header_value'] = isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '';
                    $input_arr['default_select_value'] = isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '';
                    $input_arr['default_value'] = $template_values = isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '';
                    $input_arr['temp_media'] = $temp_media = isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '';
                    $input_arr['temp_btn_url'] = isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '';
                    $input_arr['card_media'] = $card_media = isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '';
                    $template_param = '';
                    
                    
                    $response_arr = create_template_body($input_arr, $contactInfo, true);
                    if(!empty($response_arr) && isset($response_arr['template'])){
                        $template_param = json_encode($response_arr['template']);
                    }
                    
                    $update_array['temp_param'] = $message = $template_param;
                    //$template_default_select_value = $this->input->post('default_select_value');
                    //$template_default_value = $this->input->post('default_value');
                    //$temp_media_array = $this->input->post('temp_media');
                    //$default_temp_media_array = $this->input->post('default_temp_media');
                    /* if (is_numeric($recurring_id) && empty($temp_media_array[1])) {
                      $temp_media_array = $default_temp_media_array;
                      }

                      $template_values = array();
                      if (!empty($template_default_value)) {
                      foreach ($template_default_value as $keyV => $valueV) {
                      if (!empty($valueV)) {
                      foreach ($valueV as $keyS => $valueS) {
                      if (!empty($valueS)) {
                      $template_values[$keyV][$keyS] = $valueS;
                      } else {
                      $template_values[$keyV][$keyS] = $template_default_select_value[$keyV][$keyS] . '_field';
                      }
                      }
                      } else {
                      $template_values[$keyV] = array();
                      }
                      }
                      } */
                    $temp_media_array = !empty($temp_media) ? json_encode($temp_media) : '';
                    if (empty($temp_media_array) && !empty($card_media)) {
                        $temp_media_array = json_encode($card_media);
                    }
                    $update_array['template_values'] = isset($response_arr['user_values']) && !empty($response_arr['user_values']) ? json_encode($response_arr['user_values']) : '';
                    $update_array['template_media'] = $temp_media_array;
                }



                $user_id = 0;
                if ($this->data['user_data']['type'] == 'user') {
                    $user_id = $this->data['user_data']['id'];
                }

                if (!empty($message)) {
                    if (is_numeric($recurring_id)) {
                        $main_recurring_id = $recurring_id;
                        $this->CMS_model->update_record(tbl_recurrings, $where, $update_array);
                    } else {
                        $update_array['user_id'] = $user_id;
                        $update_array['created_at'] = date('Y-m-d H:i:s');
                        $updated_inquiry_id = $this->CMS_model->insert_data(tbl_recurrings, $update_array);
                        $main_recurring_id = $updated_inquiry_id;
                        
                    }
                    echo json_encode(array('status' => true));
                    exit();
                } else {
                    echo json_encode(array('status' => false, 'error' => 'Something went wrong!'));
                    exit();
                }
                /* if (is_numeric($recurring_id)) {
                  $this->session->set_flashdata('success_msg', 'Recurring updated successfully !');
                  } else {
                  $this->session->set_flashdata('success_msg', 'Recurring created successfully!');
                  } */
                /* if ($template_id != 'other') {
                  $recurring_template_detail = create_template_message($main_recurring_id, 1, $template_id, $this->input->post('name'), 'recurring');
                  $this->CMS_model->update_record(tbl_recurrings, array('id' => $main_recurring_id), array('temp_param' => json_encode($recurring_template_detail)));
                  } */
            }
        }
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $recurring_id) {
        $recurring_id = base64_decode($recurring_id);
        $where = 'id = ' . $recurring_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }

        $check_recurring = $this->CMS_model->get_result(tbl_recurrings, $where);
        if ($check_recurring) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'Recurring successfully deleted!');
            } elseif ($action == 'block') {
                $update_array = array(
                    'is_active' => 0
                );
                $this->session->set_flashdata('success_msg', 'Recurring successfully deactivated!');
            } elseif ($action == 'activate') {
                $update_array = array(
                    'is_active' => 1
                );
                $this->session->set_flashdata('success_msg', 'Recurring successfully activated!');
            }
            $res = $this->CMS_model->update_record(tbl_recurrings, array('id' => $recurring_id), $update_array);
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('recurrings');
    }
    
    public function logs(){
        $this->template->set('title', 'Recurring Logs');
        $this->template->load('default_home', 'Recurrings/logs', $this->data);
    }
    
    public function list_recurring_logs(){
        $final['recordsTotal'] = $this->Recurring_model->get_recurring_logs('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data = $this->Recurring_model->get_recurring_logs(null);
        if(!empty($data)){
            foreach($data as $k=>$d){
                $final[$k]['sr_no'] = $k+1;
                $final[$k]['created'] = date('d M Y h:i a', strtotime(getTimeBaseOnTimeZone($d['created'])));
                $final[$k]['trigger_time'] = date('d M Y h:i a', strtotime(getTimeBaseOnTimeZone($d['trigger_time'], true)));
            }
        }
        
        echo json_encode($final);
    }

}
