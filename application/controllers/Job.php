<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use yidas\queue\worker\Controller as WorkerController;

class Job extends WorkerController {

// Setting for that a listener could fork up to 10 workers
    public $workerMaxNum = 10;
// Enable text log writen into specified file for listener and worker
    public $logPath = BASE_URL . 'tmp/my-worker.log';

    function __construct() {
        parent::__construct();
    }

// Initializer
    protected function init() {
        $this->load->library('myjobs');
    }

// Worker
    protected function handleWork() {
// Your own method to get a job from your queue in the application
        $exists = $this->myjobs->exists();
       
        if ($exists > 0) {
            $job = $this->myjobs->getJob();
             pr($job, 1);
// return `false` for job not found, which would close the worker itself.
            if (!$job)
                return false;

// Your own method to process a job
//        $response = $this->myjobs->processJob($job);
// Your own job process here
            $response = $this->processJob($job);
// return `true` for job existing, which would keep handling.
        }
        return true;
    }

    public function processJob($Inquiry) {
        date_default_timezone_set("Asia/Calcutta");
        $done_job = '';
        if (isset($Inquiry) && !empty($Inquiry)) {
            $where_settings = ' user_id = ' . $Inquiry['user_id'];
            $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
            if (isset($user_settings) && !empty($user_settings)) {


                $trigger_time = $Inquiry['notification_date'];
                $time = date('Y-m-d H:i:s');

                $post_data = array(
                    'from_phone_number_id' => $user_settings['phone_number_id'],
                    'access_token' => $user_settings['permanent_access_token'],
                    'to' => $Inquiry['phone_number_full'],
                    'template' => $Inquiry['template'],
                    'language' => $Inquiry['temp_language'],
                );

//                    $trigger_time = $time; // Need to comment
                if (strtotime($time) >= strtotime(date('Y-m-d H:i:s', strtotime($trigger_time)))) {

                    $components = $Inquiry['temp_param'];
                    $post_data['components'] = (!empty($components)) ? json_decode($components, TRUE) : array();
                    //$response_json = $this->send_template($post_data);
                    if (isset($response_json) && !empty($response_json)) {
                        $save_queue_running = array(
                            'user_id' => $Inquiry['user_id'],
                            'inquiry_log_id' => $Inquiry['id'],
                            'created' => date('Y-m-d H:i:s')
                        );
                        $this->CMS_model->insert_data(tbl_queue_running, $save_queue_running);

                        $response_array = array(
                            'status' => $response_json->httpStatusCode(),
                            'body' => $response_json->body()
                        );
                        $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('error_response' => json_encode($response_array), 'sent_at' => date('Y-m-d H:i:s')));
                        if ($response_json->httpStatusCode() && $response_json->httpStatusCode() == 200) {
                            $done_job = "Log Message successfully sent to Inquiry : " . $Inquiry['inquiry'] . ", Automation : " . $Inquiry['automation'] . ", Template : " . $Inquiry['template'] . " on number : " . $Inquiry['phone_number_full'];
                            $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('is_sent' => 1, 'error_response' => $done_job));
                        } else {
                            $done_job = "Error in Inquiry : " . $Inquiry['inquiry'] . ", Automation : " . $Inquiry['automation'] . ", Template : " . $Inquiry['template'] . " on number : " . $Inquiry['phone_number_full'];
                            $this->CMS_model->update_record(tbl_inquiry_logs, array('id' => $Inquiry['id']), array('is_sent' => 2, 'error_response' => $done_job));
                        }
                    }
                }
            }
        }
        return $done_job;
    }

    public function send_template($post_array = array()) {
        $response = '';
        if (!empty($post_array)) {
            $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $post_array['from_phone_number_id'],
                'access_token' => $post_array['access_token'],
            ]);
            $component_header = $component_body = $component_buttons = array();
            $components = '';
            if (isset($post_array['components']) && !empty($post_array['components'])) {
                if (isset($post_array['components']['HEADER']['parameters'])) {
                    $component_header = (array) $post_array['components']['HEADER']['parameters'];
                }
                if (isset($post_array['components']['BODY']['parameters'])) {
                    $component_body = (array) $post_array['components']['BODY']['parameters'];
                }
                if (isset($post_array['components']['BUTTONS'])) {
                    foreach ($post_array['components']['BUTTONS'] as $button) {
                        $component_buttons[] = $button;
                    }
                }
            }
            $components = new Component($component_header, $component_body, $component_buttons);
            $response = $this->whatsapp_app_cloud_api->sendTemplate($post_array['to'], $post_array['template'], $post_array['language'], $components);
            return $response;
        }
    }

// Listener
    protected function handleListen() {
// Your own method to detect job existence
// return `true` for job existing, which leads to dispatch worker(s).
// return `false` for job not found, which would keep detecting new job
        return $this->myjobs->exists();
    }

}
