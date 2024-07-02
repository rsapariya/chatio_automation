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
        $name = '';
        $id = $this->uri->segment(3);
        $recurring_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($recurring_id)) {
            $where = 'id = ' . $this->db->escape($recurring_id);
            $check_recurring = $this->CMS_model->get_result(tbl_recurrings, $where);
            if ($check_recurring) {
                $this->data['recurring_datas'] = $check_recurring[0];
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->data['automation_templates'] = $this->Automation_model->get_automation_templates($user_id);
        $where = 'is_deleted = 0';
        $this->template->load('default_home', 'Recurrings/edit', $this->data);
    }

    public function save() {
        $unique_str = '';
        if ($this->input->post()) {
            $id = $this->input->post('recurring_id');
            $template_id = $this->input->post('template_id');
            $trigger_type = $this->input->post('trigger_type');
            $recurring_id = ($id != '') ? base64_decode($id) : '';
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone', 'trim|required');
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
                $this->session->set_flashdata('error_msg', validation_errors());
                $url = base_url() . 'recurrings/add';
                if (is_numeric($recurring_id)) {
                    $id = base64_encode($recurring_id);
                    $url = base_url() . 'recurrings/edit/' . $id;
                }
                redirect($url);
            } else {
                $where = array('id' => $recurring_id);
                $update_array = [
                    'name' => $this->input->post('name'),
                    'phone_number' => $this->input->post('phone_number'),
                    'phone_number_full' => ltrim($this->input->post('phone_number_full'), '+'),
                    'trigger_time' => date('H:i:s', strtotime($this->input->post('trigger_time'))),
                    'trigger_type' => $this->input->post('trigger_type'),
                    'weekly_day' => $trigger_type == 'weekly' ? $this->input->post('weekly_day') : null,
                    'monthly_date' => $trigger_type == 'monthly' ? $this->input->post('monthly_date') : null,
                    'yearly_date' => $trigger_type == 'yearly' ? date('Y-m-d', strtotime($this->input->post('yearly_date'))) : null,
                    'template_id' => $template_id,
                ];

                if ($template_id == 'other') {
                    $update_array['description'] = $this->input->post('description');
                } else {
                    $template_default_select_value = $this->input->post('default_select_value');
                    $template_default_value = $this->input->post('default_value');
                    $temp_media_array = $this->input->post('temp_media');
                    $default_temp_media_array = $this->input->post('default_temp_media');
                    if (is_numeric($recurring_id) && empty($temp_media_array[1])) {
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
                    }

                    $update_array['template_values'] = !empty($template_values) ? json_encode($template_values) : '';
                    $update_array['template_media'] = !empty($temp_media_array) ? json_encode($temp_media_array) : '';
                }
                $user_id = 0;
                if ($this->data['user_data']['type'] == 'user') {
                    $user_id = $this->data['user_data']['id'];
                }

                if (is_numeric($recurring_id)) {
                    $main_recurring_id = $recurring_id;
                    $this->CMS_model->update_record(tbl_recurrings, $where, $update_array);
                } else {
                    $update_array['user_id'] = $user_id;
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $updated_inquiry_id = $this->CMS_model->insert_data(tbl_recurrings, $update_array);
                    $main_recurring_id = $updated_inquiry_id;
                }
                if (is_numeric($recurring_id)) {
                    $this->session->set_flashdata('success_msg', 'Recurring updated successfully !');
                } else {
                    $this->session->set_flashdata('success_msg', 'Recurring created successfully!');
                }
                if ($template_id != 'other') {
                    $recurring_template_detail = create_template_message($main_recurring_id, 1, $template_id, $this->input->post('name'), 'recurring');
                    $this->CMS_model->update_record(tbl_recurrings, array('id' => $main_recurring_id), array('temp_param' => json_encode($recurring_template_detail)));
                }
                redirect('recurrings');
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

    

}
