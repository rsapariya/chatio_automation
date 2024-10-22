<?php

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
use yidas\queue\worker\Controller as WorkerController;

class Runcampaignjob extends WorkerController{
    // Setting for that a listener could fork up to 10 workers
    public $workerMaxNum = 10;
    // Enable text log writen into specified file for listener and worker
    
    public $logPath = BASEPATH . '../tmp/my-worker.log';
    
    function __construct() {
        parent::__construct();
        $this->load->model(['Campaigns_model', 'CMS_model']);
    }
    
    // Initializer
    protected function init() {
        $this->load->library('mycampaignjob');
    }
    
    // Worker
    protected function handleWork() {
        // Your own method to get a job from your queue in the application
            //$job = $this->mycampaignjob->getJob();
            $job = $this->Campaigns_model->campaign_logs();
            // return `false` for job not found, which would close the worker itself.
            if (empty($job)){
                return false;
            }else{
                $this->processJob($job);
                return false;
            }

            // Your own job process here
            
        
    }
    
    public function processJob($job) {

        if (isset($job) && !empty($job)) {
            file_put_contents('Z_campaign_log.txt', date('H:i:s d-M-Y').' Total :'.count($job) . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('Z_campaign_log.txt', json_encode($job) . PHP_EOL, FILE_APPEND | LOCK_EX);
            
            foreach ($job as $jb){
                file_put_contents('Z_campaign_sending.txt', date('H i s').'=> '.$jb['id'].' '.$jb['contact_number'] . PHP_EOL, FILE_APPEND | LOCK_EX);
      
                /*if(!empty($jb)){
                    $this->db->where('id',$jb['id']);
                    $this->db->update(tbl_campaign_queue, array('is_sent' => 1, 'is_deleted' => 1));
                }*/
                
                $where_settings = ' user_id = ' . $jb['user_id'];
                $user_settings = $this->CMS_model->get_result(tbl_user_settings, $where_settings, null, 1);
                if (isset($user_settings) && !empty($user_settings)) {
                    $post_data = array(
                        'from_phone_number_id' => $user_settings['phone_number_id'],
                        'access_token' => $user_settings['permanent_access_token'],
                        'to' => $jb['contact_number'],
                    );
                    $components = $jb['temp_params'];
                    if(!empty($components)){
                        $post_data['components'] = (!empty($components)) ? json_decode($components, TRUE) : array();
                        $response_json = curlSendTemplate($post_data);
                        
                        if(!empty($response_json)){
                            $update_job = array(
                                'is_sent' => 1,
                                'is_deleted' => 1,
                                'sent_time' => date('Y-m-d H:i:s'),
                                'response' => $response_json,
                            );
                            $response_arr = json_decode($response_json, 1);
                            
                            if(isset($response_arr['messages'][0]['id']) && !empty($response_arr['messages'][0]['id'])){
                                $update_job['message_id'] = $response_arr['messages'][0]['id'];
                                $update_job['message_status'] = $response_arr['messages'][0]['message_status'];

                                $this->CMS_model->update_record(tbl_campaigns, array('id' => $jb['campaign_id']), array('status' => 'in_progress'));

                                $chat_log = array(
                                    'user_id' => $jb['user_id'],
                                    'from_user' => 0,
                                    'phone_number' => $jb['contact_number'],
                                    'message_type' => 'template',
                                    'message_id' => $update_job['message_id'],
                                    'message_status' => $update_job['message_status'],
                                    'message' => $jb['temp_params'],
                                    'created' => $jb['notification_date'],
                                    'api_response' => $response_json,
                                    'is_campaign' => 1
                                );

                                $this->CMS_model->insert_data(tbl_chat_logs, $chat_log);
                            }else{
                                $update_job['message_status'] = 'failed';
                            }
                            $this->CMS_model->update_record(tbl_campaign_queue, array('id' => $jb['id']), $update_job);
                        }
                    }
                }
            }
        }
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
        return $this->mycampaignjob->exists();
    }
    
    /*public function Curl_sendTemplate(){
        
    }*/
    
}