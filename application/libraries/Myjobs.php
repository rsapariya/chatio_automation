<?php

/**
 * myjobs sample code using database for single worker
 * 
 * This sample library uses database table records as a queue, we delete `exist()` and `popJob()` 
 * methods and add a `getJob()` to implement the database characteristics for single worker usage.
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 */
class Myjobs {

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model(array("Inquiries_model", "CMS_model"));
    }

    /**
     * Get a undo job from database table
     *
     * @return array
     */
    public function getJob() {
        $Inquiries = $this->CI->Inquiries_model->getInquiryLogs(true);
        return $Inquiries;
    }

    /**
     * Get a undo job from database table
     *
     * @return array
     */
    public function exists() {
        date_default_timezone_set("Asia/Calcutta");
        $Inquiries = $this->CI->Inquiries_model->getInquiryLogs();
        if (count($Inquiries) > 0) {
            return true;
        }
        return false;
    }

}
