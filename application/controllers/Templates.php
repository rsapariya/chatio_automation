<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Template_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {

        $this->template->set('title', 'Templates');
        $this->template->load('default_home', 'User_templates/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_templates($type = '') {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Template_model->get_all_templates('count', $type, $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data =  $this->Template_model->get_all_templates(null, $type, $user_id);
        $start = $this->input->get('start') + 1;
        if(!empty($data)){
            foreach($data as $key =>$dt){
                $final['data'][$key]['sr_no'] = $start++;
                $final['data'][$key]['created_at'] = !empty($dt['created_at']) && $dt['created_at'] != '0000-00-00 00:00:00' ? date('d M Y h:i a', strtotime($dt['created_at'])) : '';
            }
        }
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        $name = '';
        $id = $this->uri->segment(3);
        $template_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($template_id)) {
            $where = 'id = ' . $this->db->escape($template_id);
            $check_templates = $this->CMS_model->get_result(tbl_templates, $where);
            if ($check_templates) {
                $this->data['template_datas'] = $check_templates[0];
            } else {
                show_404();
            }
        }
        $unique_str = '';
        $this->template->load('default_home', 'User_templates/edit', $this->data);
    }

    public function save() {
        $unique_str = '';
        if ($this->input->post()) {
            $template_id = base64_decode($this->input->post('template_id'));
            $type = $this->input->post('type');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($type == 'automation') {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
            }
            $url = base_url() . 'templates/add';
            if (is_numeric($template_id)) {
                $id = base64_encode($template_id);
                $url = base_url() . 'templates/edit/' . $id;
            }
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
                $where = array('id' => $template_id);
                $update_array = [
                    'description' => $this->input->post('description'),
                    'type' => $type,
                ];
                if ($type == 'automation') {
                    $up_data = array();
                    if (isset($_FILES['automation_image']['name']) && !empty($_FILES['automation_image']['name'])) {
                        $path = DEFAULT_IMAGE_UPLOAD_PATH;
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|png|pdf';
                        $config['max_size'] = '500';
                        $config['overwrite'] = TRUE;
                        $this->upload->initialize($config);
                        $this->load->library('upload', $config);
                        $image_name = date('Ymdhis') . '_template_' . $_FILES['automation_image']['name'];
                        $_FILES['automation_image']['name'] = $image_name;

                        if (!$this->upload->do_upload('automation_image')) {
                            $error = array('error' => $this->upload->display_errors());
                            $upload_error = isset($error['error']) ? $error['error'] : 'Invalid File';
                            $this->session->set_flashdata('error_msg', $upload_error);
                            redirect($url);
                        } else {
                            $update_array['automation_image'] = $image_name;
                        }
                    }
                    $update_array['name'] = $this->input->post('name');
                }
                if (is_numeric($template_id)) {
                    $this->CMS_model->update_record(tbl_templates, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template updated successfully !');
                } else {
                    $user_id = 0;
                    if ($this->data['user_data']['type'] == 'user') {
                        $update_array['user_id'] = $this->data['user_data']['id'];
                    }
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_templates, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template created successfully!');
                }
                redirect('templates');
            }
        }
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit_custom() {
        $name = '';
        $id = $this->uri->segment(3);
        $template_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($template_id)) {
            $where = 'id = ' . $this->db->escape($template_id);
            $check_templates = $this->CMS_model->get_result(tbl_templates, $where);
            if ($check_templates) {
                $this->data['template_datas'] = $check_templates[0];
            } else {
                show_404();
            }
        }
        $unique_str = '';
        $this->template->load('default_home', 'User_templates/edit_custom', $this->data);
    }

    public function save_custom() {
        $unique_str = '';
        //pr($this->input->post());
        if ($this->input->post()) {
            $template_id = base64_decode($this->input->post('template_id'));
            $custom_type = $this->input->post('custom_type');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            if ($custom_type == 'list') {
                $this->form_validation->set_rules('header_text', 'Title Text', 'trim|required');
                $this->form_validation->set_rules('action_title', 'Action Title', 'trim|required');
                $this->form_validation->set_rules('title[]', 'Title', 'trim|required');
                $this->form_validation->set_rules('description[]', 'Description', 'trim|required');
            } elseif ($custom_type == 'button') {
                $this->form_validation->set_rules('body_text', 'Body Text', 'trim|required');
                $this->form_validation->set_rules('btn_title[]', 'Title', 'trim|required');
            } elseif ($custom_type == 'text') {
                $this->form_validation->set_rules('text_details', 'Description', 'trim|required');
            }
            $url = base_url() . 'templates/add_custom';
            if (is_numeric($template_id)) {
                $id = base64_encode($template_id);
                $url = base_url() . 'templates/edit_custom/' . $id;
            }
//            pr($this->input->post(), 1);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
                $where = array('id' => $template_id);
                if ($custom_type == 'text') {
                    $details = array(
                        'text_details' => $this->input->post('text_details'),
                    );
                } else {
                    $title = array();
                    if ($custom_type == 'list') {
                        $title = $this->input->post('title');
                    } elseif ($custom_type == 'button') {
                        $title = $this->input->post('btn_title');
                    }
                    if (!empty($title)) {
                        ksort($title, 1);
                        $title = array_values($title);
                    }

                    $new_title = array();
                    foreach ($title as $keyT => $titleV) {
                        $new_title[++$keyT] = $titleV;
                    }
                    $title = $new_title;

                    $actions = array();
                    if (isset($title) && !empty($title)) {
                        foreach ($title as $tkey => $tvalue) {
                            if ($custom_type == 'list') {
                                $description = $this->input->post('description');
                                $actions[$tkey] = array(
                                    'title' => $tvalue,
                                    'description' => $description[$tkey]
                                );
                            } else {
                                $actions[$tkey] = array(
                                    'title' => $tvalue,
                                );
                            }
                        }
                    }
                    $details = array(
                        'header_text' => $this->input->post('header_text'),
                        'body_text' => $this->input->post('body_text'),
                        'footer_text' => $this->input->post('footer_text'),
                        'action_title' => $this->input->post('action_title'),
                        'actions' => $actions,
                    );
                }
                $update_array = [
                    'type' => 'automation',
                    'name' => $this->input->post('name'),
                    'custom_type' => $custom_type,
                    'description' => json_encode($details),
                ];
                //pr($update_array, 1);
                if (is_numeric($template_id)) {
                    $this->CMS_model->update_record(tbl_templates, $where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template updated successfully !');
                } else {
                    $user_id = 0;
                    if ($this->data['user_data']['type'] == 'user') {
                        $update_array['user_id'] = $this->data['user_data']['id'];
                    }
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_templates, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template created successfully!');
                }

                redirect('templates');
            }
        }
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * */
    public function action($action, $template_id, $type = '') {
        $template_id = base64_decode($template_id);
        $where = 'id = ' . $template_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        if (!empty($type) && $user_id > 0) {
            $where_default = ' user_id = ' . $user_id . ' and type=' . $this->db->escape($type);
            $check_default_template = $this->CMS_model->get_result(tbl_user_templates, $where_default, null, 1);
            if ($action == 'set_default') {
                $update_array = array(
                    'template_id' => $template_id
                );
                if ($check_default_template) {
                    $this->CMS_model->update_record(tbl_user_templates, $where_default, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template successfully set default for ' . ucfirst($type) . '!');
                } else {
                    $update_array['user_id'] = $user_id;
                    $update_array['type'] = $type;
                    $update_array['created_at'] = date('Y-m-d H:i:s');
                    $this->CMS_model->insert_data(tbl_user_templates, $update_array);
                    $this->session->set_flashdata('success_msg', 'Template successfully set default for ' . ucfirst($type) . '!');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
            }
        } else {
            $check_template = $this->CMS_model->get_result(tbl_templates, $where);
            if ($check_template) {
                if ($action == 'delete') {
                    $update_array = array(
                        'is_deleted' => 1
                    );
                    $this->session->set_flashdata('success_msg', 'Template successfully deleted!');
                } elseif ($action == 'block') {
                    $update_array = array(
                        'is_active' => 0
                    );
                    $this->session->set_flashdata('success_msg', 'Template successfully deactivated!');
                } elseif ($action == 'activate') {
                    $update_array = array(
                        'is_active' => 1
                    );
                    $this->session->set_flashdata('success_msg', 'Template successfully activated!');
                }
                if (!empty($update_array)) {
                    $this->CMS_model->update_record(tbl_templates, $where, $update_array);
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
            }
        }
        redirect('templates');
    }

    public function get_template_details($template_id, $seq = 0) {
        $template_id = base64_decode($template_id);
        $seq = base64_decode($seq);
        $where = 'id = ' . $template_id;
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $template_response = array();
        $response = '';
        if ($user_id > 0) {
            $where = ' user_id = ' . $user_id . ' and id=' . $template_id;
            $check_existing_template = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
            if (isset($check_existing_template) && !empty($check_existing_template)) {
                $this->data['template_datas'] = $check_existing_template;
                if ($seq > 0) {
                    $this->data['update_option'] = true;
                    $this->data['temp_add_seq'] = $seq;
                }
                $response = $this->load->view('User_templates/view', $this->data, TRUE);
                $template_response['name'] = $check_existing_template['name'];
                $template_response['response'] = $response;
            }
        }
        echo json_encode($template_response);
        die;
    }
    
    public function get_template_description($template_id){
        if(!empty($template_id)){
            $template_id = base64_decode($template_id);
            $where = ' id=' . $template_id;
            $template = $this->CMS_model->get_result(tbl_templates, $where, '', 1);
            
            if(!empty($template)){
                echo json_encode($template['description']);
            }
        }
    }

    public function get_official_templates() {
        $url = 'https://graph.facebook.com/v17.0/';
        $check_user_settings = $response = array();
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
            $where = ' user_id = ' . $user_id;
            $check_user_settings = $this->CMS_model->get_result(tbl_user_settings, $where, null, 1);
        }
        if (isset($check_user_settings) && !empty($check_user_settings)) {
            $business_account_id = $check_user_settings['business_account_id'];
            $permanent_access_token = $check_user_settings['permanent_access_token'];
            $url = $url . $business_account_id . '/message_templates';
            $response_json = $this->curl_api($url, $permanent_access_token);
            if (!empty($response_json)) {
                $response = json_decode($response_json, true);
                if (isset($response) && !empty($response)) {
                    $insert_array = $update_array = array();
                    if (isset($response['data']) && !empty($response['data'])) {
                        $deleted_templates = array();

                        $where = ' user_id = ' . $user_id . ' and temp_id IS NOT NULL';
                        $all_existing_template = $this->CMS_model->get_result(tbl_templates, $where);

                        $existing_templates = array_column($all_existing_template, 'temp_id');
                        $fetch_templates = array_column($response['data'], 'id');

                        $deleted_templates = array_diff($existing_templates, $fetch_templates);

                        foreach ($response['data'] as $key => $res) {
                            $where = ' user_id = ' . $user_id . ' and temp_id=' . $res['id'];
                            $check_existing_template = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
                            if (isset($check_existing_template) && !empty($check_existing_template)) {
                                $update_array[] = [
                                    'temp_id' => $res['id'],
                                    'name' => $res['name'],
                                    'description' => isset($res['components']) ? json_encode($res['components']) : '',
                                    'temp_language' => $res['language'],
                                    'temp_status' => $res['status'],
                                    'temp_category' => $res['category'],
                                ];
                            } else {
                                $insert_array[] = [
                                    'name' => $res['name'],
                                    'user_id' => $user_id,
                                    'description' => isset($res['components']) ? json_encode($res['components']) : '',
                                    'type' => 'automation',
                                    'temp_language' => $res['language'],
                                    'temp_status' => $res['status'],
                                    'temp_category' => $res['category'],
                                    'temp_id' => $res['id'],
                                ];
                            }
                        }

                        if (!empty($insert_array)) {
                            $this->CMS_model->insert_batch(tbl_templates, $insert_array);
                        }
                        if (!empty($update_array)) {
                            $this->CMS_model->update_multiple(tbl_templates, $update_array, 'temp_id');
                        }
                        if (!empty($deleted_templates)) {
                            foreach ($deleted_templates as $deleted_template) {
                                $this->CMS_model->delete_data(tbl_templates, array('temp_id' => $deleted_template));
                            }
                        }
                        $this->session->set_flashdata('success_msg', 'Template successfully fetched!');
                    } elseif (isset($response['error'])) {
                        $this->session->set_flashdata('error_msg', $response['error']['message']);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('templates');
    }

    public function curl_api($url, $token) {
        $authorization = "Authorization: Bearer " . $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)); // Inject the token into the header
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ch_result = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $ch_result;
    }


    function get_templates(){
        $templates = get_user_meta_templates();
        $select = '<div class="form-group mb-4"><label for="template_id">Select Templates</label><br/><select class="form-control basic template" id="template_id" name="template_id">';

        if(!empty($templates)){
            $select .= '<option >Select Template</option>';
            foreach($templates as $temp){
                $select .= '<option value="' . $temp['id'] . '">' . $temp['name'] . '</option>';
            }
        }
        $select .= '</select></div>';
        echo json_encode($select);
    }
   

}
