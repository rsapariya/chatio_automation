<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/Whatsapp_cloud.php";

class Whatsapp_cloud extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

}
