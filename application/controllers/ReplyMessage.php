<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReplyMessage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['ReplyMessage_model', 'CMS_model']);
        $this->data = get_admin_data();
        $this->reply_text = [];
    }

    /**
     * @uses : This Function load view of Category list.
     * @author : HPA
     */
    public function index() {
        $this->template->set('title', 'Reply Messages');
        $this->template->load('default_home', 'ReplyMessage/index', $this->data);
    }

    /**
     * @uses : This Function is used to get result based on datatable in Category list page
     * @author : HPA
     */
    public function list_reply_messages() {
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $final['recordsTotal'] = $this->ReplyMessage_model->get_all_reply_messages('count', $user_id);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $this->ReplyMessage_model->get_all_reply_messages(null, $user_id);
        $records = $final['data'];
        if (isset($records) && !empty($records)) {
            foreach ($records as $key => $record) {
                $attachments_details = array();
                if (isset($record['attachments']) && !empty($record['attachments'])) {
                    $attachments_details = json_decode($record['attachments'], true);
                    if (!empty($attachments_details)) {
                        foreach ($attachments_details as $akey => $detail) {
                            if (is_numeric($detail)) {
                                $list_templates = $this->ReplyMessage_model->get_list_templates($user_id, $detail);
                                $meta_templates = $this->ReplyMessage_model->get_meta_templates($user_id, $detail);

                                if (!empty($list_templates)) {
                                    $records[$key]['list_templates'][$akey] = $list_templates;
                                }
                                if (!empty($meta_templates)) {
                                    $records[$key]['list_templates'][$akey] = $meta_templates;
                                }
                            }
                        }
                    }
                }
                
                $tz_date = '';
                if(!empty($record['created_at']) && $record['created_at'] != '0000-00-00 00:00:00'){
                    $create_date = date('Y-m-d H:i:s', strtotime($record['created_at']));
                    $tz_date = getTimeBaseOnTimeZone($create_date);
                }
                $records[$key]['created_at'] =  !empty($tz_date) ? date('d M Y', strtotime($tz_date)).'<br/>'.date('h:i a', strtotime($tz_date)): '';
            }
        }
        $final['data'] = $records;
        echo json_encode($final);
    }

    /**
     * @uses : This function is add/edit Category
     * @author : HPA
     */
    public function edit() {
        $name = '';
        $id = $this->uri->segment(3);
        $reply_message_id = ($id != '') ? base64_decode($id) : '';
        if (is_numeric($reply_message_id)) {
            $where = 'id = ' . $this->db->escape($reply_message_id);
            $check_reply_messages = $this->CMS_model->get_result(tbl_reply_messages, $where);
            if ($check_reply_messages) {
                if ($check_reply_messages[0]) {
                    $where_reply_id = 'reply_id = ' . $this->db->escape($check_reply_messages[0]['reply_id']);
                    $check_reply_messages_text = $this->CMS_model->get_result(tbl_reply_messages, $where_reply_id, 'reply_text');
                    $check_reply_message_texts = array_column($check_reply_messages_text, 'reply_text');
                    if (!empty($check_reply_message_texts)) {
                        $check_reply_messages[0]['reply_text'] = implode(',', $check_reply_message_texts);
                    }
                }
                $this->data['reply_message_datas'] = $check_reply_messages[0];
            } else {
                show_404();
            }
        }
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $this->data['meta_templates'] = $this->ReplyMessage_model->get_meta_templates($user_id);
        $this->data['list_templates'] = $this->ReplyMessage_model->get_list_templates($user_id);
        $this->template->load('default_home', 'ReplyMessage/edit', $this->data);
    }

    public function replytext_check() {
        $reply_text = $this->reply_text;
        //$reply_message_id = $this->input->post('reply_message_id');
        $reply_id = $this->input->post('reply_id');
        if (!empty($reply_text)) {
            $user_id = 0;
            if ($this->data['user_data']['type'] == 'user') {
                $user_id = $this->data['user_data']['id'];
            }
            if ($user_id > 0) {
                /* $where = 'reply_text = ' . $this->db->escape($reply_text) . ' and user_id = ' . $this->db->escape($user_id) . ' and is_deleted = "0"';
                  if (!empty($reply_message_id)) {
                  $reply_message_id = base64_decode($reply_message_id);
                  $where .= ' AND id != ' . $this->db->escape($reply_message_id);
                  }
                  $check_reply_messages = $this->CMS_model->get_result(tbl_reply_messages, $where, null, null, true);
                 */

                $check_reply_messages = $this->ReplyMessage_model->check_replytext($reply_text, $user_id, $reply_id);
                if ($check_reply_messages > 0) {
                    $this->form_validation->set_message('replytext_check', 'The Trigger Text is already exists!');
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
        //pr($this->input->post(), 1);
        if ($this->input->post()) {
            $reply_message_id = base64_decode($this->input->post('reply_message_id'));
            $messages = $this->input->post('messages');
            $reply_text_json = $this->input->post('reply_text');
            $arr_reply_text = json_decode($reply_text_json, true);

            $reply_text = [];
            if (!empty($arr_reply_text)) {
                foreach ($arr_reply_text as $key => $rt) {
                    $reply_text[$key] = $rt['value'];
                }
            }
            $this->reply_text = $reply_text;

            $reply_templates = $this->input->post('reply_templates');
            $reply_meta_templates = $this->input->post('reply_meta_templates');

            if ($reply_meta_templates) {
                $this->form_validation->set_rules('reply_meta_templates[]', 'Meta Template', 'trim|required');
            }
            $this->form_validation->set_rules('reply_text', 'Trigger Text', 'trim|required|callback_replytext_check');
            //$this->form_validation->set_rules('trigger_on', 'Trigger On', 'trim|required');
            if (!empty($reply_templates)) {
                $this->form_validation->set_rules('reply_templates[]', 'Custom Template', 'trim|required');
            }
            $url = base_url() . 'replyMessage/add';
            if (is_numeric($reply_message_id)) {
                $id = base64_encode($reply_message_id);
                $check_reply_message = array();
                $where = 'id = ' . $this->db->escape($reply_message_id);
                $check_reply_messages = $this->CMS_model->get_result(tbl_reply_messages, $where);
                if ($check_reply_messages) {
                    $check_reply_message = $check_reply_messages[0];
                }
                $url = base_url() . 'replyMessage/edit/' . $id;
            }
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_msg', validation_errors());
                redirect($url);
            } else {
                $where = array('id' => $reply_message_id);

//                $message_details = $attachment_details = array();
//                if (!empty($messages)) {
//                    foreach ($messages as $key => $message) {
//                        $message_details[$key] = $message;
//                    }
//                }


                if (!empty($message_details)) {
                    ksort($message_details, 1);
                    $message_details = array_values($message_details);
                    $new_details = array();
                    foreach ($message_details as $key => $detail) {
                        $new_details[++$key] = $detail;
                    }
                    $message_details = $new_details;
                }

                $template_default_select_value = $this->input->post('default_select_value');
                $template_default_value = $this->input->post('default_value');
                $temp_media = $this->input->post('temp_media');

                $attachment_caption = $this->input->post('attachment_caption');

                $default_temp_media = $this->input->post('default_temp_media');
                if (is_numeric($reply_message_id) && !empty($default_temp_media)) {
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
                if (is_numeric($reply_message_id) && !empty($default_temp_btn_url)) {
                    foreach ($temp_btn_url as $tbukey => $temp_btn_url_value) {
                        if (!empty($temp_btn_url_value)) {
                            $temp_btn_url[$tbukey] = $temp_btn_url_value;
                        } else {
                            $temp_btn_url[$tbukey] = $default_temp_btn_url[$tbukey];
                        }
                    }
                }

                $attachment_array = array();
                $file_name = array();
                $temp_media_array = array();
                $temp_btn_url_array = array();

                if (!empty($reply_meta_templates)) {
                    foreach ($reply_meta_templates as $key => $template) {
                        $file_name[$key] = $template;
                        $attachment_array[$key] = $template;
                        $temp_media_array[$key] = '';
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

                if (!empty($reply_templates)) {
                    foreach ($reply_templates as $i => $template) {
                        $file_name[$i] = $template;
                        $attachment_array[$i] = $template;
                        $template_values[$i] = array();
                        $temp_media_array[$i] = '';
                        $temp_btn_url_array[$i] = '';
                        if (!isset($template_default_value[$i])) {
                            $template_default_value[$i] = array();
                        }
                    }
                }

                if (isset($_FILES['attachment']['name']) && !empty($_FILES['attachment']['name'])) {
                    $path = ATTACHMENT_IMAGE_UPLOAD_PATH;
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf|mp4';
                    $config['overwrite'] = TRUE;
                    $ImageCount = count($_FILES['attachment']['name']);
                    $upload_error = array();
                    foreach ($_FILES['attachment']['name'] as $i => $name) {
                        $file_name[$i] = $name;

                        $file_type = substr($name, strrpos($name, '.') + 1);
                        $filename_without_ext = substr($name, 0, strrpos($name, "."));

                        $name = str_replace(array('\'', '"', ',', ';', '<', '>', ' ', '-', '.', '(', ')'), '_', $filename_without_ext);
                        $image_name = $name . '_' . date('Ymdhis') . '.' . $file_type;

                        $_FILES['file']['name'] = $image_name;
                        $_FILES['file']['type'] = $_FILES['attachment']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['attachment']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['attachment']['size'][$i];

                        $file_type = isset($_FILES['attachment']['type'][$i]) ? explode('/', $_FILES['attachment']['type'][$i])[1] : '';
                        $file_size = $_FILES['attachment']['size'][$i];
                        if ($file_type == 'pdf') {
                            $allowed_type_size = (1024 * allowed_pdf_upload_size);
                        } elseif ($file_type == 'mp4') {
                            $allowed_type_size = (1024 * allowed_video_upload_size);
                        } else {
                            $allowed_type_size = (1024 * allowed_image_upload_size);
                        }
                        $config['max_size'] = $allowed_type_size;

                        $this->upload->initialize($config);
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('file')) {
                            $error = array('error' => $this->upload->display_errors());
                            $upload_error[] = isset($error['error']) ? $error['error'] : 'Invalid File';
                        } else {
                            $attachment_array[$i] = $image_name;
                        }
                    }
                    if (!empty($upload_error)) {
                        $error_msg = implode('<br/>', $upload_error);
                        $this->session->set_flashdata('error_msg', $error_msg);
                        redirect($url);
                    }
                }


                $existing_attachment_name = array();
                if (isset($check_reply_message) && !empty($check_reply_message)) {
                    $existing_attachment_name = isset($check_reply_message['attachments_name']) ? json_decode($check_reply_message['attachments_name'], true) : array();
                }

                $existing_attachment_array = $this->input->post('existing_attachments');
                if (isset($existing_attachment_array) && !empty($existing_attachment_array)) {
                    foreach ($existing_attachment_array as $ekey => $existing_attachment) {
                        $attachment_array[$ekey] = $existing_attachment;
                        $file_name[$ekey] = isset($existing_attachment_name[$ekey]) ? $existing_attachment_name[$ekey] : '';
                    }
                }

                if (!empty($file_name)) {
                    ksort($file_name, 1);
                    $file_name = array_values($file_name);
                    $new_file_details = array();
                    foreach ($file_name as $key => $detail) {
                        $new_file_details[++$key] = $detail;
                    }
                    $file_name = $new_file_details;
                }

                if (!empty($template_values)) {
                    ksort($template_values, 1);
                    $template_values = array_values($template_values);
                    $new_template_values = array();
                    foreach ($template_values as $key => $template_value) {
                        $new_template_values[++$key] = $template_value;
                    }
                    $template_values = $new_template_values;
                }

                if (!empty($temp_media_array)) {
                    ksort($temp_media_array, 1);
                    $temp_media_array = array_values($temp_media_array);
                    $new_temp_media_array = array();
                    foreach ($temp_media_array as $key => $temp_media) {
                        $new_temp_media_array[++$key] = $temp_media;
                    }
                    $temp_media_array = $new_temp_media_array;
                }

                if (!empty($temp_btn_url_array)) {
                    ksort($temp_btn_url_array, 1);
                    $temp_btn_url_array = array_values($temp_btn_url_array);
                    $new_temp_btn_url_array = array();
                    foreach ($temp_btn_url_array as $key => $temp_btn_url_val) {
                        $new_temp_btn_url_array[++$key] = $temp_btn_url_val;
                    }
                    $temp_btn_url_array = $new_temp_btn_url_array;
                }


                $update_array['attachments_name'] = json_encode($file_name);
                $update_array['template_values'] = !empty($template_values) ? json_encode($template_values) : '';
                $update_array['template_media'] = !empty($temp_media_array) ? json_encode($temp_media_array) : '';
                $update_array['template_button_url'] = !empty($temp_btn_url_array) ? json_encode($temp_btn_url_array) : '';

                if (isset($attachment_array)) {
                    if (!empty($attachment_array)) {
                        ksort($attachment_array, 1);
                        $attachment_array = array_values($attachment_array);
                        $new_details = array();
                        foreach ($attachment_array as $key => $detail) {
                            $new_details[++$key] = $detail;
                        }
                        $attachment_array = $new_details;
                    }
                    $update_array['attachments'] = json_encode($attachment_array);
                }

                $attachment_caption_arr = [];
                if (!empty($attachment_caption)) {
                    foreach ($attachment_caption as $ci => $caption) {
                        $attachment_caption_arr[$ci] = $caption;
                    }
                    $update_array['attachments_caption'] = json_encode($attachment_caption_arr);
                }

                
                $trigger_on = $this->input->post('trigger_on');
                if (is_numeric($reply_message_id)) {
                    //fetch all data based on reply_id
                    $reply_id = $check_reply_message['reply_id'];
                    $user_id = $check_reply_message['user_id'];

                    $rdt_where = 'reply_id = ' . $this->db->escape($reply_id);
                    $reply_id_text_data = $this->CMS_model->get_result(tbl_reply_messages, $rdt_where);


                    $text_data = [];
                    foreach ($reply_id_text_data as $reply_dt) {
                        if (!in_array($reply_dt['reply_text'], $reply_text)) {
                            $urdt_where = 'id = ' . $this->db->escape($reply_dt['id']);
                            $this->CMS_model->delete_data(tbl_reply_messages, $urdt_where);
                        } else {
                            array_push($text_data, $reply_dt['reply_text']);
                        }
                    }


                    $new_reply_text = array_diff($reply_text, $text_data);
                    $ins_new_reply_text = [];
                    if (!empty($new_reply_text)) {
                        foreach ($new_reply_text as $key => $nrt) {
                            $ins_new_reply_text[$key] = $update_array;
                            $ins_new_reply_text[$key]['user_id'] = $user_id;
                            $ins_new_reply_text[$key]['reply_id'] = $reply_id;
                            $ins_new_reply_text[$key]['reply_text'] = $nrt;
                        }
                        $this->CMS_model->insert_batch(tbl_reply_messages, $ins_new_reply_text);
                    }
                    
                    
                    $update_array['trigger_on'] = !empty($trigger_on) ? $trigger_on: '';
                    $this->CMS_model->update_record(tbl_reply_messages, $rdt_where, $update_array);
                    $this->session->set_flashdata('success_msg', 'Reply Message updated successfully !');
                } else {
                    $user_id = 0;
                    if ($this->data['user_data']['type'] == 'user') {
                        $update_array['user_id'] = $this->data['user_data']['id'];
                    }
                    $new_update_array = array();
                    $reply_id = uniqid();

                    // $update_array['reply_id'] = $reply_id;
                    //$update_array['created_at'] = date('Y-m-d H:i:s');
                    
                    
                    
                    
                    if (!empty($reply_text)) {
                        foreach ($reply_text as $text) {
                            $new_update_array[] = array(
                                'reply_id' => $reply_id,
                                'reply_text' => $text,
                                'attachments_name' => $update_array['attachments_name'],
                                'template_values' => $update_array['template_values'],
                                'template_media' => $update_array['template_media'],
                                'template_button_url' => $update_array['template_button_url'],
                                'attachments' => $update_array['attachments'],
                                'attachments_caption' => $update_array['attachments_caption'],
                                'user_id' => $update_array['user_id'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'trigger_on' => !empty($trigger_on) ? $trigger_on: ''
                            );
                        }
                    }

                    $this->CMS_model->insert_batch(tbl_reply_messages, $new_update_array);
                    //$this->CMS_model->insert_data(tbl_reply_messages, $update_array);
                    $this->session->set_flashdata('success_msg', 'Reply Message created successfully!');
                }
                redirect('replyMessage');
            }
        }
    }

    /**
     * @uses : This function is delete/block/activate details by id
     * @author : HPA
     * 
     * @uses : This function is delete(remove from database) details by reply_id
     * @modified by: RR
     * */
    public function action($action, $reply_id) {
        $reply_id = base64_decode($reply_id);
        $where = 'reply_id = "' . $reply_id . '"';
        $user_id = 0;
        if ($this->data['user_data']['type'] == 'user') {
            $user_id = $this->data['user_data']['id'];
        }
        $check_reply_message = $this->CMS_model->get_result(tbl_reply_messages, $where);

        if ($check_reply_message) {
            /* if ($action == 'delete') {
              $update_array = array(
              'is_deleted' => 1
              );
              $this->session->set_flashdata('success_msg', 'Reply Message successfully deleted!');
              }
              $this->CMS_model->update_record(tbl_reply_messages, $where, $update_array);
             */
            if ($action == 'delete') {
                $this->CMS_model->delete_data(tbl_reply_messages, $where);
                $this->session->set_flashdata('success_msg', 'Reply Message successfully deleted!');
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid request. Please try again!');
        }
        redirect('replyMessage');
    }

}
