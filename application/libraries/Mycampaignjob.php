<?php

/**
 * myjobs sample code using database for single worker
 * 
 * This sample library uses database table records as a queue, we delete `exist()` and `popJob()` 
 * methods and add a `getJob()` to implement the database characteristics for single worker usage.
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 */
class Mycampaignjob {

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model(array("Campaigns_model", "CMS_model"));
    }

    /**
     * Get a undo job from database table
     *
     * @return array
     */
    public function getJob() {
        $Inquiries = $this->CI->Campaigns_model->campaign_logs();
        return $Inquiries;
    }

    /**
     * Get a undo job from database table
     *
     * @return array
     */
    public function exists() {
        $logs = $this->CI->Campaigns_model->campaign_logs();
        if (count($logs) > 0) {
            return true;
        }
        return false;
    }

}
