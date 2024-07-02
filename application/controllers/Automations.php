<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Automations extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Automation_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {

        $this->template->set('title', 'Automations');
        $this->template->load('default_home', 'Automations/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_automations() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Automation_model->get_all_automations('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->Automation_model->get_all_automations(null, $user_id);
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        $name = '';
        $id = $this->uri->segment(3);
        $automation_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($automation_id)) {
            $where = 'id = ' . $this->db->escape($automation_id);
            $check_automations = $this->CMS_model->get_result(tbl_automations, $where);
            
            if ($check_automations) {
                $this->data['automation_datas'] = $check_automations[0];
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->data['automation_templates'] = $this->Automation_model->get_automation_templates($user_id);
        
        $this->template->load('default_home', 'Automations/edit', $this->data);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function view() {
        $name = '';
        $template_id = base64_decode($this->uri->segment(3));
        $automation_datas = array();
        if (is_numeric($template_id)) {
            $where = 'id = ' . $this->db->escape($template_id);
            $check_automations = $this->CMS_model->get_result(tbl_automations, $where);
            if ($check_automations) {
                $automation_datas = $check_automations[0];
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $details = $templates = array();
        if (isset($automation_datas['details']) && !empty($automation_datas['details'])) {
            $details = json_decode($automation_datas['details'], true);
        }

        if (!empty($details)) {
            foreach ($details as $key => $detail) {
                if (is_numeric($detail)) {
                    $temp_details = $this->Automation_model->get_automation_templates($user_id, $detail);
                    $templates[] = $temp_details;
                } else {
                    $templates[] = $detail;
                }
            }
        }
        $this->data['automation_datas'] = $automation_datas;
        $this->data['automation_details'] = $templates;
        $this->template->load('default_home', 'Automations/view', $this->data);
    }

    public function automation_name_check() {
        $name = $this->input->post('name');
        $automation_id = $this->input->post('automation_id');
        if (!empty($name)) {
            $user_id = 0;
            if ($this->data['user_data']['type'] == 'user') {
                $user_id = $this->data['user_data']['id'];
            }
            if ($user_id > 0) {
                $where = 'name = ' . $this->db->escape($name) . ' and user_id = ' . $this->db->escape($user_id) . ' and is_deleted = "0"';
                if (!empty($automation_id)) {
                    $automation_id = base64_decode($automation_id);
                    $where .= ' AND id != ' . $this->db->escape($automation_id);
                }
                $check_automations = $this->CMS_model->get_result(tbl_automations, $where, null, null, true);
                if ($check_automations > 0) {
                    $this->form_validation->set_message('automation_name_check', 'The Name is already exists!');
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function save() {
        $unique_str = '';
        if ($this->input->post()) {
            $automation_id = base64_decode($this->input->post('automation_id'));
            $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_automation_name_check');
            $this->form_validation->set_rules('trigger_time', 'Trigger Time', 'trim|required');
            if ($this->input->post('templates')) {
                $this->form_validation->set_rules('templates[]', 'Message', 'trim|required');
            }
            $url = base_url() . 'automations/add';
            if (is_numeric($automation_id)) {
                $id = base64_encode($automation_id);
                $url = base_url() . 'automations/edit/' . $id;
            }
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
                if (is_numeric($automation_id)) {
                    $where = 'id = ' . $this->db->escape($automation_id);
                    $check_automations = $this->CMS_model->get_result(tbl_automations, $where);
                    if ($check_automations) {
                        $automation_datas = $check_automations[0];
                    } else {
                        show_404();
                    }
                }

                $where = array('id' => $automation_id);
                $update_array = [
                    'name' => $this->input->post('name'),
                    'trigger_time' => date('H:i:s', strtotime($this->input->post('trigger_time'))),
                ];
                $templates = $this->input->post('templates');
                $template_default_select_value = $this->input->post('default_select_value');
                $template_default_value = $this->input->post('default_value');
                $delay_count = $this->input->post('delay_count');
                $delay_duration = $this->input->post('delay_duration');
                $temp_media = $this->input->post('temp_media');

                $default_temp_media = $this->input->post('default_temp_media');
                if (is_numeric($automation_id) && !empty($default_temp_media)) {
                    foreach ($temp_media as $tmkey => $temp_media_vlaue) {
                        if (!empty($temp_media_vlaue)) {
                            $temp_media[$tmkey] = $temp_media_vlaue;
                        } else {
                            $temp_media[$tmkey] = $default_temp_media[$tmkey];
                        }
                    }
                }

                $temp_btn_url = $this->input->post('temp_btn_url');
                $default_temp_btn_url = $this->input->post('default_temp_btn_url');
                if (is_numeric($automation_id) && !empty($default_temp_btn_url)) {
                    foreach ($temp_btn_url as $tbukey => $temp_btn_url_value) {
                        if (!empty($temp_btn_url_value)) {
                            $temp_btn_url[$tbukey] = $temp_btn_url_value;
                        } else {
                            $temp_btn_url[$tbukey] = $default_temp_btn_url[$tbukey];
                        }
                    }
                }

                $details = array();
                $temp_media_array = array();
                $temp_media_name_array = array();
                $temp_btn_url_array = array();

                if (!empty($templates)) {
                    foreach ($templates as $key => $template) {
                        $details[$key] = $template;
                        $temp_media_array[$key] = '';
                        $temp_media_name_array[$key] = '';
                        $temp_btn_url_array[$key] = '';
                        if (!isset($template_default_value[$key])) {
                            $template_default_value[$key] = array();
                        }
                    }
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

                if (!empty($delay_count)) {
                    foreach ($delay_count as $keyC => $count) {
                        $details[$keyC] = $count . ' ' . $delay_duration[$keyC];
                        $template_values[$keyC] = array();
                        $temp_media_array[$keyC] = '';
                        $temp_media_name_array[$keyC] = '';
                        $temp_btn_url_array[$keyC] = '';
                    }
                }
                if (isset($temp_media) && !empty($temp_media)) {
                    foreach ($temp_media as $keyTM => $temp_media_value) {
                        $temp_media_array[$keyTM] = (!empty($temp_media_value)) ? $temp_media_value : '';
                    }
                }

                if (isset($temp_btn_url) && !empty($temp_btn_url)) {
                    foreach ($temp_btn_url as $keyTBU => $temp_btn_url_val) {
                        $temp_btn_url_array[$keyTBU] = (!empty($temp_btn_url_val)) ? $temp_btn_url_val : '';
                    }
                }
//                if (isset($_FILES['temp_media']['name']) && !empty($_FILES['temp_media']['name'])) {
//                    $files = $_FILES;
//                    $path = DEFAULT_IMAGE_UPLOAD_PATH;
//
//                    if (!empty($automation_datas)) {
//                        $existing_template_media = array();
//                        $existing_template_media_names = array();
//                        if (isset($automation_datas['template_media'])) {
//                            $existing_template_media = (array) json_decode($automation_datas['template_media'], true);
//                            if (isset($existing_template_media) && !empty($existing_template_media)) {
//                                foreach ($existing_template_media as $etm_key => $existing_media) {
//                                    $temp_media_array[$etm_key] = $existing_media;
//                                }
//                            }
//                        }
//                        if (isset($automation_datas['template_media_names'])) {
//                            $existing_template_media_names = (array) json_decode($automation_datas['template_media_names'], true);
//                            if (isset($existing_template_media_names) && !empty($existing_template_media_names)) {
//                                foreach ($existing_template_media_names as $etmn_key => $existing_media_names) {
//                                    $temp_media_name_array[$etmn_key] = $existing_media_names;
//                                }
//                            }
//                        }
//                    }
//
//                    $upload_error = array();
//                    foreach ($files['temp_media']['name'] as $seq => $media) {
//                        if (!empty($media)) {
//                            $config['upload_path'] = $path;
//                            $config['allowed_types'] = 'jpg|jpeg|png|pdf|mp4';
//                            $config['overwrite'] = TRUE;
//                            if ($files['temp_media']['type'][$seq] == 'video/mp4' || $files['temp_media']['type'][$seq] == 'application/pdf') {
//                                $config['max_size'] = (VIDEO_MAX_UPLOAD_SIZE * MB);
//                            } else {
//                                $config['max_size'] = (IMAGE_MAX_UPLOAD_SIZE * MB);
//                            }
//                            $this->load->library('upload', $config);
//                            $this->upload->initialize($config);
//
//                            $file_type = substr($media, strrpos($media, '.') + 1);
//                            $filename_without_ext = substr($media, 0, strrpos($media, "."));
//                            $media = str_replace(array('\'', '"', ',', ';', '<', '>', ' ', '-', '.', '(', ')'), '_', $filename_without_ext);
//                            $image_name = date('Ymdhis') . '_template_' . $media . '.' . $file_type;
//                            $files['temp_media']['name'][$seq] = $image_name;
//
//                            $_FILES['temp_media']['name'] = $files['temp_media']['name'][$seq];
//                            $_FILES['temp_media']['full_path'] = $files['temp_media']['full_path'][$seq];
//                            $_FILES['temp_media']['type'] = $files['temp_media']['type'][$seq];
//                            $_FILES['temp_media']['tmp_name'] = $files['temp_media']['tmp_name'][$seq];
//                            $_FILES['temp_media']['error'] = $files['temp_media']['error'][$seq];
//                            $_FILES['temp_media']['size'] = $files['temp_media']['size'][$seq];
//
//                            if (!$this->upload->do_upload('temp_media')) {
//                                $error = array('error' => $this->upload->display_errors());
//                                $upload_error[$seq] = isset($error['error']) ? $error['error'] : 'Invalid File';
//                            } else {
//                                $temp_media_array[$seq] = $image_name;
//                                $temp_media_name_array[$seq] = $files['temp_media']['full_path'][$seq];
//                            }
//                        }
//                    }
//                    if (!empty($upload_error)) {
//                        $error_text = '';
//                        foreach ($upload_error as $key => $error) {
//                            $error_text .= $error;
//                        }
//                        $this->session->set_flashdata('error_msg', $error_text);
//                        redirect($url);
//                    }
//                }

                if (!empty($details)) {
                    ksort($details, 1);
                    $details = array_values($details);

                    if (!empty($template_values)) {
                        ksort($template_values, 1);
                        $template_values = array_values($template_values);
                    }

                    if (!empty($temp_media_array)) {
                        ksort($temp_media_array, 1);
                        $temp_media_array = array_values($temp_media_array);
                    }

                    if (!empty($temp_btn_url_array)) {
                        ksort($temp_btn_url_array, 1);
                        $temp_btn_url_array = array_values($temp_btn_url_array);
                    }

                    if (!empty($temp_media_name_array)) {
                        ksort($temp_media_name_array, 1);
                        $temp_media_name_array = array_values($temp_media_name_array);
                    }

                    $new_details = array();
                    foreach ($details as $key => $detail) {
                        $new_details[++$key] = $detail;
                    }
                    $details = $new_details;

                    $new_template_values = array();
                    foreach ($template_values as $key => $template_value) {
                        $new_template_values[++$key] = $template_value;
                    }
                    $template_values = $new_template_values;

                    $new_temp_media_array = array();
                    foreach ($temp_media_array as $key => $temp_media) {
                        $new_temp_media_array[++$key] = $temp_media;
                    }
                    $temp_media_array = $new_temp_media_array;

                    $new_temp_btn_url_array = array();
                    foreach ($temp_btn_url_array as $key => $temp_btn_url_val) {
                        $new_temp_btn_url_array[++$key] = $temp_btn_url_val;
                    }
                    $temp_btn_url_array = $new_temp_btn_url_array;

                    $new_temp_media_name_array = array();
                    foreach ($temp_media_name_array as $key => $temp_media_name) {
                        $new_temp_media_name_array[++$key] = $temp_media_name;
                    }
                    $temp_media_name_array = $new_temp_media_name_array;
                }

                $update_array['details'] = !empty($details) ? json_encode($details) : '';
                $update_array['template_values'] = !empty($template_values) ? json_encode($template_values) : '';
                $update_array['template_media'] = !empty($temp_media_array) ? json_encode($temp_media_array) : '';
                $update_array['template_media_names'] = !empty($temp_media_name_array) ? json_encode($temp_media_name_array) : '';
                $update_array['template_button_url'] = !empty($temp_btn_url_array) ? json_encode($temp_btn_url_array) : '';
                if (is_numeric($automation_id)) {
                    $this->CMS_model->update_record(tbl_automations, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Automation updated successfully!');
                } else {
                    $user_id = 0;
                    if ($this->data['user_data']['type'] == 'user') {
                        $update_array['user_id'] = $this->data['user_data']['id'];
                    }
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_automations, $update_array);
                    $this->session->set_flashdata('success_msg', 'Automation created successfully!');
                }
                redirect('automations');
            }
        }
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $automation_id) {
        $automation_id = base64_decode($automation_id);
        $where = 'id = ' . $automation_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $check_automation = $this->CMS_model->get_result(tbl_automations, $where);
        if ($check_automation) {
            if ($action == 'delete') {
                $update_array = array(
                    'is_deleted' => 1
                );
                $this->session->set_flashdata('success_msg', 'Automation successfully deleted!');
            } elseif ($action == 'block') {
                $update_array = array(
                    'is_active' => 0
                );
                $this->session->set_flashdata('success_msg', 'Automation successfully deactivated!');
            } elseif ($action == 'activate') {
                $update_array = array(
                    'is_active' => 1
                );
                $this->session->set_flashdata('success_msg', 'Automation successfully activated!');
            }
            $this->CMS_model->update_record(tbl_automations, $where, $update_array);
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('automations');
    }

}
