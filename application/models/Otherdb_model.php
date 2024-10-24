<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Otherdb_model extends CI_Model {

    public function get_notification_log($wmaid){
        $otherdb = $this->load->database('otherdb', TRUE);
        $row = $otherdb->get_where('lead_notify_log', array('wamid' => $wmaid));
        $data = $row->row_array();
        $otherdb->close();

        return $data;
    }

}
?>