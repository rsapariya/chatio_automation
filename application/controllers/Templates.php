<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Templates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Template_model', 'CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index()
    {

        $this->template->set('title', 'Templates');
        $this->template->load('default_home', 'User_templates/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_templates($type = '')
    {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->Template_model->get_all_templates('count', $type, $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data =  $this->Template_model->get_all_templates(null, $type, $user_id);
        $start = $this->input->get('start') + 1;
        if (!empty($data)) {
            foreach ($data as $key => $dt) {
                $final['data'][$key]['sr_no'] = $start++;
                $final['data'][$key]['created_at'] = !empty($dt['created_at']) && $dt['created_at'] != '0000-00-00 00:00:00' ? date('d M Y h:i a', strtotime(getTimeBaseOnTimeZone($dt['created_at']))) : '';
            }
        }
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit()
    {
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

    public function save()
    {
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
    public function edit_custom()
    {
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

    public function save_custom()
    {
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
                $this->form_validation->set_rules('text_details', 'Description', 'trim|required');
            } elseif ($custom_type == 'contacts') {
                $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
                $this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|required');
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
                } else if ($custom_type == 'contacts') {
                    $contact_name = $this->input->post('contact_name');
                    $contact_number = $this->input->post('phone_number_full');
                    $details = array(
                        'name' => array(
                            'formatted_name' => $contact_name,
                            'first_name' => $contact_name,
                        ),
                        'phones' => array(
                            array(
                                'phone' => $contact_number,
                                'type' => 'Mobile',
                                'wa_id' => str_replace('+', '', $contact_number)
                            ),
                        )
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
    public function action($action, $template_id, $type = '')
    {
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

    public function get_template_details($template_id, $seq = 0)
    {
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

    public function get_template_description($template_id)
    {
        if (!empty($template_id)) {
            $template_id = base64_decode($template_id);
            $where = ' id=' . $template_id;
            $template = $this->CMS_model->get_result(tbl_templates, $where, '', 1);

            if (!empty($template)) {
                echo json_encode($template['description']);
            }
        }
    }

    public function get_official_templates()
    {
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
                                //$this->CMS_model->delete_data(tbl_templates, array('temp_id' => $deleted_template));
                                $this->CMS_model->update_record(tbl_templates, array('temp_id' =>$deleted_template), array('is_deleted' => 1));
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

    public function curl_api($url, $token)
    {
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


    function get_templates()
    {
        $templates = get_user_meta_templates();
        $select = '<div class="form-group mb-4"><label for="template_id">Select Templates</label><br/><select class="form-control basic template" id="template_id" name="template_id">';

        if (!empty($templates)) {
            $select .= '<option >Select Template</option>';
            foreach ($templates as $temp) {
                $select .= '<option value="' . $temp['id'] . '">' . $temp['name'] . '</option>';
            }
        }
        $select .= '</select></div>';
        echo json_encode($select);
    }


    /**
     * @uses : This Function load add carousel page.
     * @author : RR
     */
    public function create_carousel()
    {
        $checkAppID = getUserSettings('app_id');
        if(empty($checkAppID)){
            $this->session->set_flashdata('error_msg', 'Please update App ID in API Settings to create carousel!');
            redirect(base_url().'templates');
        }
        $this->template->set('title', 'Templates');
        $this->template->load('default_home', 'User_templates/create_carousel', $this->data);
    }
    
    public function check_template_exist($templateName){
        $user_id = $this->session->userdata('id');
        $where = ' user_id = ' . $user_id . ' and name= "' . $templateName.'"';
        $is_exist = $this->CMS_model->get_result(tbl_templates, $where, null, 1);
        if(!empty($is_exist)){
            echo json_encode(array('status' => true));
        }else{
            echo json_encode(array('status' => false));
        }
    }

    public function start_upload_session()
    {

        $card_id = $this->input->post('card_id');
        if (!empty($card_id)) {
            foreach ($_FILES as $fileK => $fileV) {
                $cardId = str_replace('media_', '', $fileK);
                if ($cardId == $card_id) {
                    if (!empty($fileV['name'])) {
                        $file_arr = array(
                            'file_length' => $fileV['size'],
                            'file_type' => $fileV['type'],
                            'file_name' => $fileV['name'],
                            'file_tmp_path' => $fileV['tmp_name']
                        );

                        $response = startUploadSession($file_arr);
                        if (!empty($response)) {
                            $res_arr = json_decode($response, 1);
                            if (isset($res_arr['h']) && !empty($res_arr['h'])) {
                                $formate = explode('/', $fileV['type']);
                                $return = array(
                                    'format' => $formate[0],
                                    'header_handle' => $res_arr['h']
                                );
                            } else if (isset($res_arr['error']) && !empty($res_arr['error'])) {
                                $return = array('error' => $res_arr['error']['message']);
                            }
                        } else {
                            $return = array('error' => 'file upload failed!');
                        }
                        echo json_encode($return);
                        exit();
                    }
                }
            }
            echo json_encode(array('error' => 'Something went wrong! image uploading failed.'));
            exit();
        }
        echo json_encode(array('error' => 'Image uploading failed'));
        exit();
    }

    public function start_upload()
    {
        pr($this->input->post(), 1);
    }

    public function save_carousel()
    {
        //pr($this->input->post());
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
        $this->form_validation->set_rules('template_category', 'Template Category', 'trim|required');
        $this->form_validation->set_rules('template_language', 'Template Language', 'trim|required');
        $this->form_validation->set_rules('bubble_message', 'Bubble Message', 'trim|required');
        $bubble_message = $this->input->post('bubble_message');


        //Check if Bubble Message have variable. if it has then, check example value is provided or not
        $body_text = [];
        $placeholderPattern = '/{{\d+}}/';
        if (!empty($bubble_message)) {
            preg_match_all($placeholderPattern, $bubble_message, $matches);
            $bm_example = $matches[0];
            $bmi = 1;
            while ($bmi <= count($bm_example)) {
                $this->form_validation->set_rules('bm_ex_' . $bmi, 'Bubble Message Ex.' . $bmi, 'trim|required');
                array_push($body_text, $this->input->post('bm_ex_' . $bmi));
                $bmi++;
            }
        }

        //Check at least one Carousel card exist
        $content = $this->input->post('content');
        if (empty($content)) {
            $this->form_validation->set_rules(
                'content',
                'Carousel Card',
                'required',
                array('required' => 'Please add at least one %s.')
            );
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('error' => validation_errors()));
        } else {
            $template_name = $this->input->post('template_name');
            $template_language = $this->input->post('template_language');
            $template_category = $this->input->post('template_category');
            $template_arr = array(
                'name' => $template_name,
                'language' => $template_language,
                'category' => $template_category,
            );
            $components_arr = [];

            $bubble_message_arr = array(
                'type' => 'BODY',
                'text' =>  $bubble_message,
            );
            if (!empty($body_text)) {
                $bubble_message_arr['example']['body_text'] = array($body_text);
            }

            $carousel_arr = array(
                "type" => "CAROUSEL",
            );


            foreach ($content as $c) {
                $content_arr = $this->input->post($c);

                $carousel_component = array(
                    "components" => array()
                );

                $header_component = array(
                    'type' => "HEADER"
                );
                $body_component = array(
                    'type' => "BODY"
                );
                $button_component = array(
                    "type" => "BUTTONS",
                );
                $buttons_arr = $buttons_one = $buttons_two = [];

                //Carousel Header Component
                //check image or video file selected or not
                if (!isset($content_arr['header_handle']) && empty($content_arr['header_handle'])) {
                    echo json_encode(array('error' => 'Please select image or video media file', 'card' => $c));
                    exit();
                } else {
                    if (empty($content_arr['format'])) {
                        echo json_encode(array('error' => 'Please select image or video media file', 'card' => $c));
                        exit();
                    } else {
                        $header_component['format'] = strtoupper($content_arr['format']);
                    }
                    
                    $header_component["example"]["header_handle"] = array($content_arr['header_handle']);
                    array_push($carousel_component["components"], $header_component);
                }
                //Carousel Body Component
                if (isset($content_arr['card_content'])) {
                    if (empty($content_arr['card_content'])) {
                        echo json_encode(array('error' => 'Please provide Content', 'card' => $c));
                        exit();
                    } else {
                        $body_component["text"] = $content_arr['card_content'];
                        //Check if Content have variable. if it has then, check example value is provided or not
                        preg_match_all($placeholderPattern, $content_arr['card_content'], $Contentmatches);
                        $content_example = $Contentmatches[0];
                        $coni = 1;
                        $cExError = '';
                        $body_component_text = [];
                        while ($coni <= count($content_example)) {
                            if (empty($content_arr['content_ex_' . $coni])) {
                                $cExError .= '<p>Content Ex.' . $coni . ' field is required.</p>';
                            } else {
                                array_push($body_component_text, $content_arr['content_ex_' . $coni]);
                            }
                            $coni++;
                        }
                        if (!empty($cExError)) {
                            echo json_encode(array('error' => $cExError, 'card' => $c));
                            exit();
                        }
                        if (!empty($body_component_text)) {
                            $body_component['example']['body_text'] = array($body_component_text);
                        }

                        array_push($carousel_component["components"], $body_component);
                    }
                }
                //Carousel Button Component
                $error_btn = '';
                if (isset($content_arr['btn_one_type'])) {
                    $buttons_one["type"] =  $content_arr['btn_one_type'];
                    if (empty($content_arr['btn_one_type_text'])) {
                        $error_btn .= 'Please provide ' . str_replace('_', ' ', $content_arr['btn_one_type']) . ' button text value <br/>';
                    } else {
                        $buttons_one["text"] = $content_arr['btn_one_type_text'];
                        if ($content_arr['btn_one_type'] == 'URL') {
                            if (empty($content_arr['btn_one_url'])) {
                                $error_btn .= 'Please provide URL for button one';
                            } else {
                                $buttons_one["url"] = $content_arr['btn_one_url'];
                                if ($content_arr['btn_one_url_type'] == 'dynamic') {
                                    if (empty($content_arr['btn_one_url_example'])) {
                                        $error_btn .= 'Please provide URL example for button one';
                                    } else {
                                        $buttons_one["example"] = array($content_arr['btn_one_url_example']);
                                    }
                                }else{
                                    $buttons_one["example"] = array($content_arr['btn_one_url']);
                                }
                            }
                        }
                    }
                }

                if (isset($content_arr['btn_two_type'])) {
                    $buttons_two["type"] =  $content_arr['btn_two_type'];
                    if (empty($content_arr['btn_two_type_text'])) {
                        $error_btn .= 'Please provide ' . str_replace('_', ' ', $content_arr['btn_two_type']) . ' button text value <br/>';
                    } else {
                        $buttons_two["text"] = $content_arr['btn_two_type_text'];
                        if ($content_arr['btn_two_type'] == 'URL') {
                            if (empty($content_arr['btn_two_url'])) {
                                $error_btn .= 'Please provide URL for button one';
                            } else {
                                $buttons_two["url"] = $content_arr['btn_two_url'];
                                if ($content_arr['btn_two_url_type'] == 'dynamic') {
                                    if (empty($content_arr['btn_two_url_example'])) {
                                        $error_btn .= 'Please provide URL example for button two';
                                    } else {
                                        $buttons_two["example"] = array($content_arr['btn_two_url_example']);
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($error_btn)) {
                    echo json_encode(array('error' => $error_btn, 'card' => $c));
                    exit();
                } else {
                    if(!empty($buttons_one)){
                        array_push($buttons_arr, $buttons_one);
                    }
                    if(!empty($buttons_two)){
                        array_push($buttons_arr, $buttons_two);
                    }
                    
                    if ($buttons_arr) {
                        $button_component['buttons'] = $buttons_arr;
                        array_push($carousel_component["components"], $button_component);
                    }
                }
                $carousel_arr['cards'][] = $carousel_component;
            }

            array_push($components_arr, $bubble_message_arr, $carousel_arr);

            $template_arr['components'] = $components_arr;
            $template = json_encode($template_arr, JSON_UNESCAPED_SLASHES);
            $response = create_meta_template(str_replace('\r\n', '\n', $template)); 
            if (!empty($response)) {
                $response_arr = json_decode($response, 1);
                if (isset($response_arr['error'])) {
                    echo json_encode(array('error' => isset($response_arr['error']['error_user_msg']) ? $response_arr['error']['error_user_msg'] : $response_arr['error']['message']));
                    exit();
                } else {
                    $success_msg = '';
                    if ($response_arr['status'] == 'APPROVED') {
                        $success_msg = "<b>APPROVED!</b> Your carousel template has been created. It's ready to use!";
                    }
                    if ($response_arr['status'] == 'PENDING') {
                        $success_msg = "<b>PENDING!</b> Your carousel template has been created. It's in review!";
                    }
                    
                    if ($response_arr['status'] == 'REJECTED') {
                        $success_msg = "<b>REJECTED!</b> Your request to create carousel template has been rejected!";
                        echo json_encode(array('warning' => $success_msg));
                        exit();
                    }
                    echo json_encode(array('success' => $success_msg));
                    exit();
                }
            }
        }
    }
}
