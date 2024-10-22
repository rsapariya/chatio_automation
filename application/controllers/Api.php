<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->base_url = 'https://graph.facebook.com/v17.0';
        $this->access_token = 'EAAMims8bwbcBALGcrQlQSNvbuy4SLeoozW3ZBAtv7idI3imA0gJM42dnL75Y57UfjjVcvKO8zylL6kQaUYGE72ZAbMBQau2AGOJYHCwhN3oW1SY7mq4Pr1OhpqVfMgqy95VT7L0VsiXVFLEsHpcZCFhHPn1R5ZC2W1VwsfkXUZAjzMbZChzyze';
        $this->register_number = '919016088306';
        $this->business_account_id = '115335524923524';
        $this->app_id = '882473225142711';
        $this->phone_no_id = '108268772303530';
        $this->load->model('CMS_model');
    }

    public function get_all_templates() {
        $url = $this->base_url . '/' . $this->business_account_id . '/message_templates';
        $res = $this->call_curl($url, '', 'GET');
        pr($res);
    }

    public function send_message() {
        $url = $this->base_url . '/' . $this->phone_no_id . '/messages';
        $data = '{
                    "messaging_product": "whatsapp",
                    "to": "919537800320",
                    "type": "template",
                    "template": {
                        "name": "hello_world",
                        "language": {
                            "code": "en_US"
                        }
                    }
                }';
        $res = $this->call_curl($url, $data);
        pr($res);
    }

    function call_curl($url, $data, $method = 'POST') {
        $access_token = $this->access_token;
        $curl_array = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'Prefer: return=representation',
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $curl_array,
        ));
        if ($data != '') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);
        if (!empty($response)) {
            $json = json_decode($response);
            return $json;
        }
        curl_close($curl);
        return $response;
    }

    /* #####################################
     * @author: Rashmikant
     * 
     * ####################################
     */

    function callCurl($url, $access_token, $data, $method = 'POST') {
        //$access_token = $this->access_token;
        $curl_array = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'Prefer: return=representation',
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $curl_array,
        ));
        if ($data != '') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);
        if (!empty($response)) {
            $json = json_decode($response);
            return $json;
        }
        curl_close($curl);
        return $response;
    }

    public function message_templates() {
        $auth = $this->input->get_request_header('Authorization');
        if (!empty($auth)) {
            $token = str_replace('Bearer ', '', $auth);
            $authentication = $this->authenticate(trim($token));
            if (!empty($authentication)) {
                $url = $this->base_url . '/' . $authentication['business_account_id'] . '/message_templates';
                $templates = $this->callCurl($url, $authentication['permanent_access_token'], '', 'GET');
                if (isset($templates->paging)) {
                    unset($templates->paging);
                }
                echo json_encode($templates);
            } else {
                $return['error'] = array(
                    "message" => "Invalid OAuth access token - Cannot parse access token.",
                    "code" => 401,
                );
                echo json_encode($return);
                exit();
            }
        } else {
            $return['error'] = array(
                "message" => "Please provide API token.",
                "code" => 401,
            );
            echo json_encode($return);
        }
    }

    function display_required_parameter_error($param) {
        $return['error'] = array(
            "message" => "The parameter " . $param . " is required.",
            "code" => 401,
        );
        echo json_encode($return);
        exit();
    }

    function template_info($user_id, $name) {
        $temp_where = 'user_id = ' . $user_id . ' AND name = "' . $name . '"';
        $template_info = $this->CMS_model->get_result(tbl_templates, $temp_where, '', 1);
        if (empty($template_info)) {
            $return['error'] = array(
                "message" => "Template name (" . $name . ") does not exist.",
                "code" => 401,
            );
            echo json_encode($return);
            exit();
        }
        return $template_info;
    }

    function authenticate($token) {
        $where = 'api_token = ' . $this->db->escape($token);
        $data = $this->CMS_model->get_result(tbl_user_settings, $where, '', 1);
        if (!empty($data)) {
            $return = array(
                'user_id' => $data['user_id'],
                'permanent_access_token' => $data['permanent_access_token'],
                'phone_number_id' => $data['phone_number_id'],
                'business_account_id' => $data['business_account_id']
            );
            return $return;
        } else {
            return false;
        }
    }

    public function messages() {
        $auth = $this->input->get_request_header('Authorization');
        if (!empty($auth)) {
            $token = str_replace('Bearer ', '', $auth);
            $authentication = $this->authenticate(trim($token));

            if (!empty($authentication)) {
                $user_id = $authentication['user_id'];
                $json_post = file_get_contents('php://input');
                
                if (!empty($json_post)) {
                    $url = $this->base_url . '/' . $authentication['phone_number_id'] . '/messages';
                    $post= $temp_data = json_decode($json_post, 1);
                    $data = array(
                        "messaging_product" => "whatsapp",
                        "recipient_type" => "individual"
                    );
                    $meta_data = json_encode(array_merge($data, $post));

                    !empty($post['to']) ? '' : $this->display_required_parameter_error('to');
                    $type = isset($post['type']) && !empty($post['type']) ? $post['type'] : $this->display_required_parameter_error('type');

                    /* if ($type == 'template') {
                      $template = $post['template'];
                      $template_name = isset($template['name']) && !empty($template['name']) ? $template['name'] : '';

                      $template_info = $this->template_info($user_id, $template_name);
                      if (empty($template_info['temp_id'] && !empty($template_info['custom_type']))) {

                      }
                      }
                     */

                    $response = (array) $this->callCurl($url, $authentication['permanent_access_token'], $meta_data);
                    if (isset($response['error']) && !empty($response['error'])) {
                        if ($response['error']->code == 190) {
                            $response['error']->code = 401;
                            $response['error']->message = 'Something went wrong.';
                            unset($response['error']->type);
                            unset($response['error']->fbtrace_id);
                        }
                        echo json_encode($response);
                        exit();
                    } else {
                        unset($temp_data['to']);
                        unset($temp_data['type']);
                        $message = $media = $filename = '';
                        if ($type == 'text') {
                            $message = $temp_data['text']['body'];
                        } else if ($type == 'image') {
                            $message = $temp_data['image']['caption'];
                            $media = $temp_data['image']['link'];
                        } else if ($type == 'video') {
                            $message = $temp_data['video']['caption'];
                            $media = $temp_data['video']['link'];
                        } else if ($type == 'audio') {
                            $media = $temp_data['audio']['link'];
                        } else if ($type == 'document') {
                            $message = $temp_data['document']['caption'];
                            $media = $temp_data['document']['link'];
                            $filename = $temp_data['document']['filename'];
                        } else {
                            $message = json_encode($temp_data);
                        }


                        $chat_log = array(
                            'user_id' => $user_id,
                            'from_user' => 0,
                            'phone_number' => str_replace('+', '', $post['to']),
                            'message_type' => $type,
                            'message_id' => $response['messages'][0]->id,
                            'message' => $message,
                            'media' => $media,
                            'file_name' => $filename,
                            'created' => date('Y-m-d H:i:s'),
                            'api_response' => json_encode($response),
                            'api_data' => json_encode($post)
                        );


                        $this->CMS_model->insert_data(tbl_chat_logs, $chat_log);
                        $return['success'] = array(
                            "message" => "Message accepted.",
                            "code" => 200,
                        );
                        echo json_encode($return);
                        exit();
                    }
                } else {
                    $return['error'] = array(
                        "message" => "Something went wrong.",
                        "code" => 401,
                    );
                    echo json_encode($return);
                    exit();
                }
            } else {
                $return['error'] = array(
                    "message" => "Invalid OAuth access token - Cannot parse access token.",
                    "code" => 401,
                );
                echo json_encode($return);
                exit();
            }
        } else {
            $return['error'] = array(
                "message" => "Please provide API token.",
                "code" => 401,
            );
            echo json_encode($return);
        }
    }

    public function docs() {
        $this->load->view('docs');
    }

    public function gd(){
        $this->load->view('gd');
    }
    
    public function modify_img(){
        $uploadedFile = $_FILES['imageInput']['tmp_name'];
        $img = imagecreatefromjpeg($uploadedFile);
        
        if ($img === false) {
            echo json_encode(array('status' => false,'error' => 'image upload failed'));
            exit;
        }

        $color = imagecolorallocate($img, 255, 0, 0);

        $name = $this->input->post('text');
        //$course = "Technologies";
        $slno = 'REG-' . rand(100, 99999);
        $issue_date = date('d-m-Y');
        $font = "assets/fonts/Arial.ttf";

        // Check if font file exists
        if (!file_exists($font)) {
            imagedestroy($img);
            echo json_encode(array('status' => false,'error' => 'font not found'));
            exit;
        }

        imagettftext($img, 24, 0, 50, 100, $color, $font, $name);
        //imagettftext($img, 24, 0, 50, 200, $color, $font, $course);
        imagettftext($img, 24, 0, 50, 300, $color, $font, $slno);
        imagettftext($img, 24, 0, 50, 400, $color, $font, $issue_date);

        $directory = 'upload/gd/';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $new_name = time() . ".jpeg";
        $filePath = $directory . $new_name;

        // Save the modified image
        if (imagejpeg($img, $filePath)) {
            echo json_encode(array('status' => true,'url' => base_url().$filePath));
            exit;
        } else {
            echo json_encode(array('status' => false,'error' => 'failed to save image'));
            exit;
        }

        // Clean up
        imagedestroy($img);
    }
}
