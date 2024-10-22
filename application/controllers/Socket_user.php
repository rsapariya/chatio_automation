<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Socket_user extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['CMS_model']);
        $this->data = get_admin_data();
    }

    /**
     * @author : RR
     */
    function index($user_id = null) {
        $this->load->view('Chatlogs/socket_chat', array('user_id' => $user_id));
    }

}
