<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Campaigns extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Campaigns_model', 'User_model', 'CMS_model']);
        $this->data = get_admin_data();
        $this->load->library('Excel');
        /* if(!empty($this->session->userdata('time_zone'))){
          date_default_timezone_set($this->session->userdata('time_zone'));
          } */
    }

    public function index() {
        $this->template->load('default_home', 'Campaigns/index', $this->data);
    }

    public function list_campaigns() {
        $final['recordsTotal'] = $this->Campaigns_model->get('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data = $this->Campaigns_model->get();

        foreach ($data as $k => $d) {
            $final['data'][$k]['sr_no'] = $k + 1;
            $final['data'][$k]['created'] = !empty($d['created']) ? getTimeBaseOnTimeZone($d['created']) : '';
        }
        echo json_encode($final);
    }

    public function new_campaign() {
        $user_id = $this->data['user_data']['id'];
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED" AND is_deleted = 0';
        $this->data['templates'] = $templates = $this->CMS_model->get_result(tbl_templates, $where);
        $tgwhere = 'user_id = ' . $this->data['user_data']['id'];
        $this->data['user_tags'] = $this->CMS_model->get_result(tbl_tags, $tgwhere, 'id as value,tag as name, user_id');
        $this->template->load('default_home', 'Campaigns/new_campaign', $this->data);
    }

    public function load_contacts() {
        $user_id = $this->data['user_data']['id'];
        $tags_arr = $this->input->post('tags');
        $count = 0;
        $contacts = '';
        if (!empty($tags_arr)) {
            $conatcts = $this->User_model->get_contacts($user_id, $tags_arr);
            if (!empty($conatcts)) {
                $count = count($conatcts);
                //$data = $conatcts;
            }
        }
        echo json_encode(array('count' => $count));
    }

    public function check_campaign_name() {
        $campaign_name = $this->input->post('campaign_name');
        if (!empty($campaign_name)) {
            $user_id = $this->session->userdata('id');
            $where = 'user_id = ' . $this->db->escape($user_id) . ' AND campaign_name="' . $campaign_name . '"';
            $is_exist = $this->CMS_model->get_result(tbl_campaigns, $where, '', 1);
            if (!empty($is_exist)) {
                echo true;
            }
            echo false;
        }
    }

    public function save() {
        $this->form_validation->set_rules('campaign_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('group_ids', 'Tags', 'trim|required');
        $this->form_validation->set_rules('campaign_template', 'Template', 'trim|required');
        if ($this->input->post('notification_campaign') == 'schedule_campaign') {
            $this->form_validation->set_rules('notification_date', 'Notification Date', 'trim|required|callback_check_notification_date');
        }

        $post = $this->input->post();
        if (isset($post['temp_media'])) {
            if (empty($post['temp_media'])) {
                $this->form_validation->set_rules('temp_media', 'Media URL', 'trim|required', array('required' => 'Please select media file.'));
            }
        }
        if (isset($post['card_media'])) {
            $custom_media_message = '';
            foreach ($post['card_media'] as $cmK => $cmV) {
                if (empty($cmV)) {
                    $custom_media_message .= '<p>Please select media file for card ' . ($cmK + 1) . '.</p>';
                    $this->form_validation->set_rules('card_media', 'Media URL', 'trim|required', array('required' => $custom_media_message));
                }
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $return = array('error' => validation_errors());
        } else {
            $campaign_data['user_id'] = $user_id = $this->session->userdata('id');
            $json_tags = $post['group_ids'];
            if (!empty($user_id) && !empty($json_tags)) {
                $campaign_data['campaign_name'] = $campaign_name = $post['campaign_name'];
                $campaign_data['template_id'] = $input_arr['template_id'] = $post['campaign_template'];

                $input_arr['default_select_header_value'] = isset($post['default_select_header_value']) && !empty($post['default_select_header_value']) ? $post['default_select_header_value'] : '';
                $input_arr['header_value'] = isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '';
                $input_arr['default_select_value'] = isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '';
                $input_arr['default_value'] = isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '';
                $input_arr['temp_media'] = isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '';
                $input_arr['temp_btn_url'] = isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '';
                $input_arr['card_media'] = isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '';

                $tags_arr = json_decode($json_tags, 1);
                $tags = [];
                foreach ($tags_arr as $tg) {
                    array_push($tags, $tg['name']);
                }
                $campaign_data['tags'] = implode(',', $tags);
                $conatcts_arr = $this->User_model->get_contacts($user_id, $tags);
                if (!empty($conatcts_arr)) {
                    $campaign_notification_date = '';
                    $notification_campaign = $this->input->post('notification_campaign');
                    if ($notification_campaign == 'schedule_campaign') {
                        $notification_date = $this->input->post('notification_date');
                        $campaign_notification_date = date('Y-m-d H:i:s', strtotime(getServerTimeZone($notification_date, true)));
                        $campaign_data['status'] = 'schedule';
                    } else {
                        $campaign_data['status'] = 'in_progress';
                    }

                    $campaign_arr = $campaign_contact_arr = $campaign_contact = [];
                    $campaign_data['created'] = date('Y-m-d H:i:s');
                    $campaign_id = $this->CMS_model->insert_data(tbl_campaigns, $campaign_data);
                    $campaign_message = '';
                    foreach ($conatcts_arr as $cn) {
                        $phone_number = $cn['contact'];
                        if (!empty($phone_number)) {
                            $response_arr = create_template_body($input_arr, $cn);
                            $campaign_message = !empty($response_arr['message']) ? $response_arr['message'] : '';
                            if (!empty($response_arr)) {
                                $message_decode['template'] = $response_arr['template'];
                                $created_at = date('Y-m-d H:i:s');
                                $campaign_contact = array(
                                    'campaign_id' => $campaign_id,
                                    'contact_number' => $phone_number,
                                    'temp_params' => json_encode($message_decode),
                                    'created_at' => $created_at,
                                    'notification_date' => $notification_campaign == 'schedule_campaign' && !empty($campaign_notification_date) ? $campaign_notification_date : date('Y-m-d H:i:s', strtotime($created_at))
                                );
                                array_push($campaign_contact_arr, $campaign_contact);
                            }
                        }
                    }
                    if (!empty($campaign_message)) {
                        $campaign_arr['campaign_message'] = $campaign_message;
                    }
                    if (!empty($campaign_arr)) {
                        $this->CMS_model->update_record(tbl_campaigns, 'id =' . $campaign_id, $campaign_arr);
                    }
                    if (!empty($campaign_contact_arr)) {
                        $total_contacts = $this->CMS_model->insert_batch(tbl_campaign_queue, $campaign_contact_arr);
                        if (!empty($total_contacts)) {
                            $return = array('success' => 'Campaign create for ' . $total_contacts . ' contacts');
                        } else {
                            $return = array('error' => 'Something went wrong!');
                        }
                    }
                } else {
                    $return = array('error' => 'Zero contact. Select diffrent tag to create campaign!');
                }
            } else {
                $return = array('error' => 'Something went wrong!');
            }
        }
        echo json_encode($return);
        exit();
    }
/*
    public function save_old() {

        $this->form_validation->set_rules('campaign_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('group_ids', 'Tags', 'trim|required');
        $this->form_validation->set_rules('campaign_template', 'Template', 'trim|required');
        if ($this->input->post('notification_campaign') == 'schedule_campaign') {
            $notification = $this->input->post('notification_date');
            $this->form_validation->set_rules('notification_date', 'Notification Date', 'trim|required|callback_check_notification_date');
        }

        $post = $this->input->post();
        if (isset($post['temp_media'])) {
            if (empty($post['temp_media'])) {
                $this->form_validation->set_rules('temp_media', 'Media URL', 'trim|required', array('required' => 'Please select media file.'));
            }
        }
        if (isset($post['card_media'])) {
            $custom_media_message = '';
            foreach ($post['card_media'] as $cmK => $cmV) {
                if (empty($cmV)) {
                    $custom_media_message .= '<p>Please select media file for card ' . ($cmK + 1) . '.</p>';
                    $this->form_validation->set_rules('card_media', 'Media URL', 'trim|required', array('required' => $custom_media_message));
                }
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $return = array('error' => validation_errors());
        } else {
            $campaign_data['user_id'] = $user_id = $this->session->userdata('id');
            $json_tags = $post['group_ids'];
            if (!empty($user_id) && !empty($json_tags)) {
                $campaign_data['campaign_name'] = $campaign_name = $post['campaign_name'];
                $campaign_data['template_id'] = $template_id = $post['campaign_template'];

                $header_select_value = isset($post['default_select_header_value']) && !empty($post['default_select_header_value']) ? $post['default_select_header_value'] : '';
                $header_value = isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '';
                $default_select_value = isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '';
                $default_value = isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '';
                $temp_media = isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '';
                $temp_btn_url = isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '';
                $card_media = isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '';

                $where = 'id = ' . $template_id;
                $template_arr = $this->CMS_model->get_result(tbl_templates, $where, '', 1);

                if (!empty($template_arr)) {
                    $tags_arr = json_decode($json_tags, 1);
                    $tags = [];
                    foreach ($tags_arr as $tg) {
                        array_push($tags, $tg['name']);
                    }
                    $conatcts_arr = $this->User_model->get_contacts($user_id, $tags);
                    $campaign_data['tags'] = implode(',', $tags);
                    if (!empty($conatcts_arr)) {

                        $campaign_arr = $campaign_contact_arr = $campaign_contact = [];
                        $campaign_data['created'] = date('Y-m-d H:i:s');
                        $campaign_id = $this->CMS_model->insert_data(tbl_campaigns, $campaign_data);
                        


                        //$campaign_id = 1;
                        $template_name = $template_arr['name'];
                        $template_language = $template_arr['temp_language'];

                        foreach ($conatcts_arr as $cn) {
                            $new_default_value = $default_value;
                            $new_default_select_value = $default_select_value;
                            $new_temp_media = $temp_media;

                            $phone_number = $cn['contact'];
                            $message_arr = array(
                                "name" => $template_name,
                                "language" => array(
                                    "code" => $template_language,
                                ),
                            );
                            $components_arr = $component_header = $component_body = $component_buttons = array();
                            $description = !empty($template_arr['description']) ? json_decode($template_arr['description'], 1) : '';
                            //pr($description);
                            $campaign_message = '';
                            if (!empty($description) && !empty($phone_number)) {
                                foreach ($description as $kdes => $des) {
                                    if (isset($des['type']) && $des['type'] == 'HEADER') {
                                        if (isset($des['format']) && !empty($des['format'])) {
                                            if ($des['format'] == 'TEXT') {
                                                if (isset($des['example']) && !empty($des['example'])) {
                                                    if (isset($des['example']['header_text']) && !empty($des['example']['header_text'])) {
                                                        $header_text = $des['example']['header_text'][0];

                                                        $campaign_message .= str_replace('{{1}}', '@#' . !empty($header_select_value) ? $header_select_value : 'None' . '#@', $des['text']) . '<br/>';
                                                        if (!empty($header_select_value)) {
                                                            if (!empty($cn[$header_select_value])) {
                                                                $hText = $cn[$header_select_value];
                                                            } else {
                                                                if (!empty($header_value)) {
                                                                    $hText = $header_value;
                                                                } else {
                                                                    $hText = $header_text;
                                                                }
                                                            }
                                                        } else {
                                                            if (!empty($header_value)) {
                                                                $hText = $header_value;
                                                            } else {
                                                                $hText = $header_text;
                                                            }
                                                        }
                                                        $component_header[] = array(
                                                            'type' => 'text',
                                                            'text' => $hText
                                                        );
                                                    }
                                                }
                                                //pr($component_header, 1);
                                            } else if ($des['format'] == 'DOCUMENT') {
                                                $path = base_url() . 'upload/users_media/' . $user_id . '/';
                                                $filename = str_replace($path, '', $temp_media);
                                                $file_arr = explode('.', $filename);
                                                $lastKey = array_key_last($file_arr); // Get the last key
                                                unset($file_arr[$lastKey]);

                                                $file_name = implode('.', $file_arr);
                                                $component_header[] = array(
                                                    'type' => strtolower($des['format']),
                                                    strtolower($des['format']) => array(
                                                        'link' => $temp_media,
                                                        'filename' => str_replace('_', ' ', $file_name)
                                                    )
                                                );
                                            } else {
                                                $component_header[] = array(
                                                    'type' => strtolower($des['format']),
                                                    strtolower($des['format']) => array(
                                                        'link' => $temp_media,
                                                    )
                                                );
                                            }
                                        }
                                        if (!empty($component_header)) {
                                            $components_arr['components'][$kdes] = array('type' => 'header', 'parameters' => $component_header);
                                        }
                                    } else if (isset($des['type']) && $des['type'] == 'BODY') {
                                        if (isset($des['example']) && !empty($des['example'])) {
                                            if (isset($des['example']['body_text']) && !empty($des['example']['body_text'])) {
                                                $body_text = $des['example']['body_text'][0];

                                                $body_message = $des['text'];
                                                foreach ($body_text as $bTextK => $bTextV) {
                                                    $msg_column = isset($new_default_select_value[$bTextK]) && !empty($new_default_select_value[$bTextK]) ? $new_default_select_value[$bTextK] : 'None';
                                                    $body_message = str_replace('{{' . ($bTextK + 1) . '}}', '@#' . $msg_column . '#@', $body_message);
                                                    if (!empty($new_default_select_value[$bTextK])) {
                                                        if (!empty($cn[$new_default_select_value[$bTextK]])) {
                                                            $bTextV = $cn[$new_default_select_value[$bTextK]];
                                                        } else {
                                                            if (!empty($new_default_value[$bTextK])) {
                                                                $bTextV = $new_default_value[$bTextK];
                                                            } else {
                                                                $bTextV = $body_text[$bTextK];
                                                            }
                                                        }
                                                    } else {
                                                        if (!empty($new_default_value[$bTextK])) {
                                                            $bTextV = $new_default_value[$bTextK];
                                                        } else {
                                                            $bTextV = $body_text[$bTextK];
                                                        }
                                                    }
                                                    $component_body[] = array(
                                                        'type' => 'text',
                                                        'text' => $bTextV
                                                    );
                                                }
                                                $campaign_message .= $body_message;

                                                array_splice($new_default_value, 0, count($body_text));
                                                array_splice($new_default_select_value, 0, count($body_text));
                                                if (!empty($component_body)) {
                                                    $components_arr['components'][$kdes] = array('type' => 'body', 'parameters' => $component_body);
                                                }
                                            }
                                        }
                                    } else if (isset($des['type']) && $des['type'] == 'CAROUSEL') {
                                        $cards = $des['cards'];
                                        $card_arr = [];
                                        if (!empty($cards)) {
                                            $carousel = [];
                                            foreach ($cards as $cardK => $cardV) {
                                                $cardComponents = $cardV['components'];
                                                $card_component = $card_component_header = $card_component_body = $card_component_buttons = array();

                                                foreach ($cardComponents as $cardCompK => $cardCompV) {
                                                    if ($cardCompV['type'] == 'HEADER') {
                                                        $card_component_header[] = array(
                                                            'type' => strtolower($cardCompV['format']),
                                                            strtolower($cardCompV['format']) => array(
                                                                'link' => $card_media[$cardK],
                                                            )
                                                        );
                                                        if (!empty($card_component_header)) {
                                                            $card_component[] = array('type' => 'header', 'parameters' => $card_component_header);
                                                        }
                                                    } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BODY') {
                                                        if (isset($cardCompV['example']) && !empty($cardCompV['example'])) {
                                                            if (isset($cardCompV['example']['body_text']) && !empty($cardCompV['example']['body_text'])) {
                                                                $card_body_text = $cardCompV['example']['body_text'][0];
                                                                foreach ($card_body_text as $CbTextK => $CbTextV) {
                                                                    if (!empty($new_default_select_value[$CbTextK])) {
                                                                        if (!empty($cn[$new_default_select_value[$CbTextK]])) {
                                                                            $CbTextV = $cn[$new_default_select_value[$CbTextK]];
                                                                        } else {
                                                                            $CbTextV = $new_default_value[$CbTextK];
                                                                        }
                                                                    } else {
                                                                        $CbTextV = $new_default_value[$CbTextK];
                                                                    }
                                                                    $card_component_body[] = array(
                                                                        'type' => 'text',
                                                                        'text' => $CbTextV
                                                                    );
                                                                }
                                                                array_splice($new_default_value, 0, count($card_body_text));
                                                                array_splice($default_select_value, 0, count($card_body_text));
                                                                if (!empty($card_component_body)) {
                                                                    $card_component[] = array('type' => 'body', 'parameters' => $card_component_body);
                                                                }
                                                            }
                                                        }
                                                    } else if (isset($cardCompV['type']) && $cardCompV['type'] == 'BUTTONS') {
                                                        $cardbuttons = $cardCompV['buttons'];
                                                        foreach ($cardbuttons as $cbtnk => $cbtn) {
                                                            if ($cbtn['type'] == 'URL') {
                                                                $payload = isset($new_temp_btn_url[$cardK]) && !empty($new_temp_btn_url[$cardK]) ? $temp_btn_url[$cardK] : '';
                                                                if (strpos($cbtn['url'], '{{1}}') != false) {
                                                                    $url = !empty($payload) ? $payload : $cbtn['example'][0];
                                                                    $card_component_buttons = array(
                                                                        "type" => 'button',
                                                                        "sub_type" => "url",
                                                                        "index" => ($cbtnk),
                                                                        "parameters" => array(
                                                                            array(
                                                                                'type' => 'payload',
                                                                                'payload' => $url
                                                                            )
                                                                        )
                                                                    );
                                                                }
                                                            }
                                                            if (!empty($card_component_buttons)) {
                                                                $card_component[] = $card_component_buttons;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (!empty($card_component)) {
                                                    $carousel[] = array('card_index' => $cardK, 'components' => $card_component);
                                                    $components_arr['components'][$kdes] = array('type' => 'carousel', 'cards' => $carousel);
                                                }
                                            }
                                        }
                                    } else if (isset($des['type']) && $des['type'] == 'BUTTONS') {
                                        $buttons = $des['buttons'];
                                        foreach ($buttons as $btnk => $btn) {
                                            if ($btn['type'] == 'URL') {
                                                $payload = isset($temp_btn_url[$btnk]) && !empty($temp_btn_url[$btnk]) ? $temp_btn_url[$btnk] : '';
                                                if (strpos($btn['url'], '{{1}}') != false) {
                                                    $url = empty($payload) ? $payload : $btn['example'][0];

                                                    $component_buttons = array(
                                                        "type" => 'button',
                                                        "sub_type" => "url",
                                                        "index" => ($btnk),
                                                        "parameters" => array(
                                                            array(
                                                                'type' => 'payload',
                                                                'payload' => $url
                                                            )
                                                        )
                                                    );
                                                }
                                            }
                                            if (!empty($component_buttons)) {
                                                $components_arr['components'][$kdes] = $component_buttons;
                                            }
                                        }
                                    }
                                }
                                $message_decode['template'] = array_merge($message_arr, $components_arr);

                                $created_at = date('Y-m-d H:i:s');
                                $campaign_contact = array(
                                    'campaign_id' => $campaign_id,
                                    'contact_number' => $phone_number,
                                    'temp_params' => json_encode($message_decode),
                                    'created_at' => $created_at,
                                );

                                if ($this->input->post('notification_campaign') == 'schedule_campaign') {
                                    $notification_date = $this->input->post('notification_date');
                                    $campaign_contact['notification_date'] = date('Y-m-d H:i:s', strtotime(getServerTimeZone($notification_date, true)));
                                    $campaign_arr['status'] = 'schedule';
                                } else {
                                    $campaign_arr['status'] = 'in_progress';
                                    $campaign_contact['notification_date'] = date('Y-m-d H:i:s', strtotime($created_at));
                                }
                                array_push($campaign_contact_arr, $campaign_contact);
                            } else {
                                $return = array('error' => 'Something went wrong!');
                            }
                        }
                        if (!empty($campaign_message)) {
                            $campaign_arr['campaign_message'] = $campaign_message;
                            $this->CMS_model->update_record(tbl_campaigns, 'id =' . $campaign_id, $campaign_arr);
                        }
                        if (!empty($campaign_contact_arr)) {
                            // print_r($campaign_contact_arr);
                            // die();
                            $total_contacts = $this->CMS_model->insert_batch(tbl_campaign_queue, $campaign_contact_arr);
                            if (!empty($total_contacts)) {
                                $return = array('success' => 'Campaign create for ' . $total_contacts . ' contacts');
                            } else {
                                $return = array('error' => 'Something went wrong!');
                            }
                        }
                    } else {
                        $return = array('error' => 'Zero contact. Select diffrent tag to create campaign!');
                    }
                } else {
                    $return = array('error' => 'Something went wrong!');
                }
            } else {
                $return = array('error' => 'Something went wrong!');
            }
        }
        echo json_encode($return);
        exit();
    }
*/
    function check_notification_date($notification_date) {
        if (!empty($notification_date)) {
            if (strtotime(getServerTimeZone($notification_date, true)) < strtotime(date('M d Y, H:i'))) {
                $this->form_validation->set_message('check_notification_date', "Invalid date and time! Select future date and time.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function view_campaign_info($id) {
        $campaign_id = base64_decode($id);
        $this->data['campaign_info'] = $campaign_info = $this->Campaigns_model->campaign_details($campaign_id);
        if (!empty($campaign_info)) {
            $this->data['campaign_id'] = $id;
            $this->template->load('default_home', 'Campaigns/campaign-details', $this->data);
        } else {
            $this->session->set_flashdata('error_msg', 'Data not found!');
            redirect('campaigns');
        }
    }

    public function list_campaign_info() {
        $final['recordsTotal'] = $this->Campaigns_model->get_campaign_contacts('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $final['data'] = $data = $this->Campaigns_model->get_campaign_contacts();
        //echo $this->db->last_query(); die();
        foreach ($data as $k => $d) {
            $final['data'][$k]['sr_no'] = $k + 1;

            $final['data'][$k]['created'] = !empty($d['created']) ? date('d M Y, H:i', strtotime(getTimeBaseOnTimeZone($d['created']))) : '';
            $final['data'][$k]['sent_time'] = !empty($d['sent_time']) ? date('d M Y, H:i', strtotime(getTimeBaseOnTimeZone($d['sent_time']))) : '';
            $final['data'][$k]['deliver_time'] = !empty($d['deliver_time']) ? date('d M Y, H:i', strtotime(getTimeBaseOnTimeZone($d['deliver_time']))) : '';
            $final['data'][$k]['read_time'] = !empty($d['read_time']) ? date('d M Y, H:i', strtotime(getTimeBaseOnTimeZone($d['read_time']))) : '';
        }
        echo json_encode($final);
    }

    public function create_campaign_for_failed_contact($campaign_id) {
        $this->data['campaign_id'] = $campaign_id = base64_decode($campaign_id);
        $user_id = $this->session->userdata('id');
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND temp_status = "APPROVED" AND is_deleted = 0';
        $this->data['templates'] = $templates = $this->CMS_model->get_result(tbl_templates, $where);
        $contacts_arr = $this->Campaigns_model->get_failed_campaign_contact($campaign_id);
        if (!empty($contacts_arr)) {
            $this->data['contacts'] = count($contacts_arr);
            $this->template->load('default_home', 'Campaigns/create_failed_campaign', $this->data);
        } else {
            $this->session->set_flashdata('error_msg', 'Data not found!');
            redirect('campaigns');
        }
    }

    public function save_failed_campaign() {
        $this->form_validation->set_rules('campaign_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('campaign_template', 'Template', 'trim|required');
        if ($this->input->post('notification_campaign') == 'schedule_campaign') {
            $notification = $this->input->post('notification_date');
            $this->form_validation->set_rules('notification_date', 'Notification Date', 'trim|required|callback_check_notification_date');
        }
        $post = $this->input->post();

        if (isset($post['temp_media'])) {
            if (empty($post['temp_media'])) {
                $this->form_validation->set_rules('temp_media', 'Media URL', 'trim|required', array('required' => 'Please select media file.'));
            }
        }
        if (isset($post['card_media'])) {
            $custom_media_message = '';
            foreach ($post['card_media'] as $cmK => $cmV) {
                if (empty($cmV)) {
                    $custom_media_message .= '<p>Please select media file for card ' . ($cmK + 1) . '.</p>';
                    $this->form_validation->set_rules('card_media', 'Media URL', 'trim|required', array('required' => $custom_media_message));
                }
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $return = array('error' => validation_errors());
        } else {
            $campaign_id = base64_decode($this->input->post('campaign_id'));
            $campaign_data['user_id'] = $user_id = $this->session->userdata('id');
            if (!empty($user_id)) {
                $campaign_data['campaign_name'] = $campaign_name = $post['campaign_name'];
                $campaign_data['template_id'] = $input_arr['template_id'] = $post['campaign_template'];

                $input_arr['default_select_header_value'] = isset($post['default_select_header_value']) && !empty($post['default_select_header_value']) ? $post['default_select_header_value'] : '';
                $input_arr['header_value'] = isset($post['header_value']) && !empty($post['header_value']) ? $post['header_value'] : '';
                $input_arr['default_select_value'] = isset($post['default_select_value']) && !empty($post['default_select_value']) ? $post['default_select_value'] : '';
                $input_arr['default_value'] = isset($post['default_value']) && !empty($post['default_value']) ? $post['default_value'] : '';
                $input_arr['temp_media'] = isset($post['temp_media']) && !empty($post['temp_media']) ? $post['temp_media'] : '';
                $input_arr['temp_btn_url'] = isset($post['temp_btn_url']) && !empty($post['temp_btn_url']) ? $post['temp_btn_url'] : '';
                $input_arr['card_media'] = isset($post['card_media']) && !empty($post['card_media']) ? $post['card_media'] : '';

                $conatcts_arr = $this->Campaigns_model->get_failed_campaign_contact($campaign_id);

                if (!empty($conatcts_arr)) {
                    $campaign_notification_date = '';
                    $notification_campaign = $this->input->post('notification_campaign');
                    if ($notification_campaign == 'schedule_campaign') {
                        $notification_date = $this->input->post('notification_date');
                        $campaign_notification_date = date('Y-m-d H:i:s', strtotime(getServerTimeZone($notification_date, true)));
                        $campaign_data['status'] = 'schedule';
                    } else {
                        $campaign_data['status'] = 'in_progress';
                    }

                    $campaign_arr = $campaign_contact_arr = $campaign_contact = [];
                    $campaign_data['created'] = date('Y-m-d H:i:s');
                    $campaign_id = $this->CMS_model->insert_data(tbl_campaigns, $campaign_data);
                    $campaign_message = '';
                    foreach ($conatcts_arr as $cn) {
                        $phone_number = $cn['phone_number_full'];
                        if (!empty($phone_number)) {
                            $response_arr = create_template_body($input_arr, $cn);
                            $campaign_message = !empty($response_arr['message']) ? $response_arr['message'] : '';
                            if (!empty($response_arr)) {
                                $message_decode['template'] = $response_arr['template'];
                                $created_at = date('Y-m-d H:i:s');
                                $campaign_contact = array(
                                    'campaign_id' => $campaign_id,
                                    'contact_number' => $phone_number,
                                    'temp_params' => json_encode($message_decode),
                                    'created_at' => $created_at,
                                    'notification_date' => $notification_campaign == 'schedule_campaign' && !empty($campaign_notification_date) ? $campaign_notification_date : date('Y-m-d H:i:s', strtotime($created_at))
                                );
                                array_push($campaign_contact_arr, $campaign_contact);
                            }
                        }
                    }
                    if (!empty($campaign_message)) {
                        $campaign_arr['campaign_message'] = $campaign_message;
                    }
                    if (!empty($campaign_arr)) {
                        $this->CMS_model->update_record(tbl_campaigns, 'id =' . $campaign_id, $campaign_arr);
                    }
                    if (!empty($campaign_contact_arr)) {
                        $total_contacts = $this->CMS_model->insert_batch(tbl_campaign_queue, $campaign_contact_arr);
                        if (!empty($total_contacts)) {
                            $return = array('success' => 'Campaign create for ' . $total_contacts . ' contacts');
                        } else {
                            $return = array('error' => 'Something went wrong!');
                        }
                    }
                } else {
                    $return = array('error' => 'Zero contact. Select diffrent tag to create campaign!');
                }
            } else {
                $return = array('error' => 'Something went wrong!');
            }
        }
        echo json_encode($return);
        exit();
    }

    public function check_in_progress_campaign() {
        $user_id = $this->session->userdata('id');
        $where = 'user_id = ' . $this->db->escape($user_id) . ' AND (status = "in_progress" OR status = "draft")';
        $is_exist = $this->CMS_model->get_result(tbl_campaigns, $where);
        if (count($is_exist) > 0) {
            echo json_encode(array('status' => true));
            exit();
        }
        echo json_encode(array('status' => false));
        exit();
    }

    public function createExcel($id) {
        $campaign_id = base64_decode($id);

        $response = array();
        $campaign_data = $this->Campaigns_model->get_all_campaign_contacts($campaign_id);

        if (isset($campaign_data) && !empty($campaign_data)) {
            $campaign_info = $this->Campaigns_model->campaign_details($campaign_id);

            $total_number = isset($campaign_info['contacts']) && !empty($campaign_info['contacts']) ? $campaign_info['contacts'] : 0;
            $sent = isset($campaign_info['accepted_messages']) && !empty($campaign_info['accepted_messages']) ? $campaign_info['accepted_messages'] : 0;
            $failed = isset($campaign_info['failed_messages']) && !empty($campaign_info['failed_messages']) ? $campaign_info['failed_messages'] : 0;
            $delivered = isset($campaign_info['delivered_messages']) && !empty($campaign_info['delivered_messages']) ? $campaign_info['delivered_messages'] : 0;
            $read = isset($campaign_info['read_messages']) && !empty($campaign_info['read_messages']) ? $campaign_info['read_messages'] : 0;

            $total_sent = $sent + $delivered + $read;
            $total_delivered = $delivered + $read;

            $campaign = $this->CMS_model->get_result(tbl_campaigns, 'id =' . $campaign_id, '', 1);

            $campaign_name = preg_replace('/[^a-zA-Z0-9]/', '_', $campaign['campaign_name']);

            $fileName = $campaign_name . '_' . date('d-M-Y_H-i', strtotime($campaign['created'])) . '.xlsx';

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Campaign Name');
            $sheet->setCellValue('B1', 'Careted');
            $sheet->setCellValue('A2', $campaign['campaign_name']);
            $sheet->setCellValue('B2', date('d M Y h-i a', strtotime($campaign['created'])));

            $sheet->setCellValue('A4', 'Total Contacts');
            $sheet->setCellValue('B4', 'Sent');
            $sheet->setCellValue('C4', 'Failed');
            $sheet->setCellValue('D4', 'Delivered');
            $sheet->setCellValue('E4', 'Read');

            $sheet->setCellValue('A5', $total_number);
            $sheet->setCellValue('B5', $total_sent);
            $sheet->setCellValue('C5', $failed);
            $sheet->setCellValue('D5', $total_delivered);
            $sheet->setCellValue('E5', $read);

            $sheet->setCellValue('A7', 'Id');
            $sheet->setCellValue('B7', 'Date');
            $sheet->setCellValue('C7', 'Name');
            $sheet->setCellValue('D7', 'Phone Number');
            $sheet->setCellValue('E7', 'Message');
            $sheet->setCellValue('F7', 'Status');
            $sheet->setCellValue('G7', 'Deliver Time');
            $sheet->setCellValue('H7', 'WA Status');
            $sheet->setCellValue('I7', 'WA Status Date Time');

            $rows = 8;
            foreach ($campaign_data as $key => $val) {
                $sheet->setCellValue('A' . $rows, ++$key);
                $sheet->setCellValue('B' . $rows, $val['created']);
                $sheet->setCellValue('C' . $rows, $val['name']);
                $sheet->setCellValue('D' . $rows, $val['contact_number']);
                $sheet->getStyle('D' . $rows)->getNumberFormat()->setFormatCode('000000000000');
                $sheet->setCellValue('E' . $rows, $val['campaign_message']);
                if ($val['is_sent'] == 1) {
                    $sheet->setCellValue('F' . $rows, 'Sent');
                } else {
                    $sheet->setCellValue('F' . $rows, '-');
                }

                $sheet->setCellValue('G' . $rows, $val['sent_time']);
                $sheet->setCellValue('H' . $rows, ucfirst($val['message_status']));

                if ($val['message_status'] == 'delivered') {
                    $sheet->setCellValue('I' . $rows, $val['deliver_time']);
                }
                if ($val['message_status'] == 'read') {
                    $sheet->setCellValue('I' . $rows, $val['read_time']);
                }
                $rows++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save("upload/" . $fileName);
            $response = array(
                'status' => true,
                'link' => base_url() . "upload/" . $fileName
            );
        } else {
            $response = array(
                'status' => false,
                'msg' => 'Invalid request. Please try again!'
            );
        }
        echo json_encode($response);
        exit;
    }

}
