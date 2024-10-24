<?php

if (isset($chat) && !empty($chat)) {
    $html = '';
    $ch_rev = array_reverse($chat);
    foreach ($ch_rev as $c) {
        $bg_danger = '';
        $delete_btn = '';
        if (isset($c['message_status']) && $c['message_status'] == 'failed') {
            $bg_danger = 'bg-danger';
            $error_msg = !empty($c['error_message']) && $c['error_message'] != "0000-00-00 00:00:00" ? ':' . $c['error_message'] : '';
            $delete_btn = '<a href="javascript:void(0);" class="delete_chat bs-tooltip" id="delete_chat_msg" data-id="' . $c['id'] . '" data-bs-placement="top" data-bs-original-title="Delete Message"><i class="fa fa-trash"></i> <small>FAILED<span>' . $error_msg . '</span></small></a>';
        }
        $space = !empty($c['message']) && strlen($c['message']) < 5 ? '&nbsp;&nbsp;&nbsp;' : '';

        if (isset($c['is_campaign']) && !empty($c['is_campaign'])) {
            $bg_danger = 'bg-light-secondary';
        }
        
        $message = $c['message'];
        if ($c['message_type'] == 'text') {
            if (!empty($c['message'])) {
               if(!empty($c['reply_message_id'])){
                   $message_exists = $this->CMS_model->get_result(tbl_chat_logs, 'message_id = "'.$c['reply_message_id'].'"', null, 1);
                   if(!empty($message_exists)){
                       $replied = $message_exists['message'];
                       if($message_exists['message_type'] == 'template'){
                           $replied = '<i class="fa fa-newspaper fa-2xl"></i>';
                       }else if($message_exists['message_type'] == 'image'){
                           $replied = '<i class="fa fa-file-image fa-2xl"></i>';
                       }else if($message_exists['message_type'] == 'video'){
                           $replied = '<i class="fa fa-file-video fa-2xl"></i>';
                       }else if($message_exists['message_type'] == 'document'){
                           $replied = '<i class="fa fa-file fa-2xl"></i>';
                       }
                       
                       $message = '<div class="preview replied-chat"><a href="#chat-' . $message_exists['id'] . '"><label>' . $replied . '</label></a></div>';
                       $message .= $c['message'];
                   }else{
                       $url = extract_url($c['message']);
                        if (!empty($url)) {
                            $title = getPageTitle($url);
                            $message = '<div class="preview"><a href="' . $url . '"><label>' . $title . '</label><br/><small>' . getBaseUrl($url) . '</small></a></div>';
                            $message .= str_replace($url, '<a href="' . $url . '" class="text-primary">' . $url . '</a>', $c['message']);
                        }
                   }
               }else{
                    $url = extract_url($c['message']);
                    if (!empty($url)) {
                        $title = getPageTitle($url);
                        $message = '<div class="preview"><a href="' . $url . '"><label>' . $title . '</label><br/><small>' . getBaseUrl($url) . '</small></a></div>';
                        $message .= str_replace($url, '<a href="' . $url . '" class="text-primary">' . $url . '</a>', $c['message']);
                    }
               }
            }
        }

        if ($c['message_type'] == 'image' || $c['message_type'] == 'sticker') {
            if (!empty($c['media'])) {
                $caption = isset($c['message']) && !empty($c['message']) ? $c['message'] : "";
                $message = '<a href="javascript:void(0);" class="view-image-popup mb-1" data-url="'.$c['media'].'" data-caption="'.$caption.'">';
                $message .= '<img src="'.$c['media'].'" alt="image" width="200px" class="img-fluid"></a><br/>';
                if (!empty($caption)) {
                    $message .= $caption;
                }
            }else{
               $message .= '<h6 class="no-preview-yet text-info p-1">Image not available yet.</h6>';
            }
        }

        if ($c['message_type'] == 'video') {
            if (!empty($c['media'])) {
                $file_type = substr($c['media'], strrpos($c['media'], '.') + 1);
                $message = '<video controls width="200" class="mb-1"><source src="' . $c['media'] . '" type="video/' . $file_type . '"></video>';
                if (isset($c['message']) && !empty($c['message'])) {
                    $message .= $c['message'];
                }
            }else{
               $message .= '<h6 class="no-preview-yet text-info p-1">Video not available yet.</h6>';
            }
        }

        if ($c['message_type'] == 'document') {
            if (!empty($c['media'])) {
                $file_type = substr($c['media'], strrpos($c['media'], '.') + 1);
                $link_arr = explode('/', $c['media']);
                $data = get_headers($c['media'], true);
                $file_size = isset($data['Content-Length']) ? (int) $data['Content-Length'] : '';
                $file_size_in = !empty($file_size) ? ' - ' . conver_file_size($file_size) : '';
                $file_name = !empty($c['file_name']) ? $c['file_name'] : substr($c['media'], strrpos($c['media'], '/') + 1);
                $message = '<a href="' . $c['media'] . '" target="_blank" class="download-file mb-1" data-link="' . $c['media'] . '" data-filename="' . $file_name . '" ><div class="d-flex preview"><div class="file-type"></div><small><span>' . $file_name . '</span><br/><span>' . strtoupper($file_type) . $file_size_in . '</span></small></div></a>';
            }else{
               $message .= '<h6 class="no-preview-yet text-info p-1">Document not available yet.</h6>';
            }
        }
        if ($c['message_type'] == 'audio') {
            if (!empty($c['media'])) {
                $file_type = substr($c['media'], strrpos($c['media'], '.') + 1);
                $message = '<audio controls class="mb-1"><source src="' . $c['media'] . '" type="audio/' . $file_type . '"></audio>';
            }else{
               $message .= '<h6 class="no-preview-yet text-info p-1">Audio not available yet.</h6>';
            }
        }

        if ($c['message_type'] == 'template') {
            if (!empty($c['message'])) {
                $tmp_msg = create_template_message_for_chat($c['id']);
                $message = $tmp_msg;
            }
        }

        if ($c['message_type'] == 'list' || $c['message_type'] == 'button') {
            if (!empty($c['message'])) {
                $json_decode = json_decode($c['message'], 1);
                $list_msg = '<div><p class="m-0"><b>' . $json_decode['header_text'] . '</b></p>';
                $list_msg .= '<p class="m-0">' . $json_decode['body_text'] . '</p>';
                $list_msg .= '<p class="m-0"><small class="text-muted">' . $json_decode['body_text'] . '</small></p>';
                if (!empty($json_decode['actions'])) {

                    if ($c['message_type'] == 'list') {
                        $list_msg .= '<p class="text-info m-0 pt-2"><i class="fa fa-list"></i> ' . $json_decode['action_title'] . '</p><ul>';
                        foreach ($json_decode['actions'] as $action) {
                            $list_msg .= '<li>' . $action['title'] . '</li>';
                        }
                        $list_msg .= '</ul>';
                    }
                    if ($c['message_type'] == 'button') {
                        foreach ($json_decode['actions'] as $action) {
                            $list_msg .= '<p class="btn-chat-custom">' . $action['title'] . '</p>';
                        }
                    }
                }
                $list_msg .= '</div>';
                $message = $list_msg;
            }
        }

        $message_status = '';
        
        if (isset($c['message_status']) && $c['message_status'] == 'delivered') {
            $message_status = ' <i class="fa fa-check-double text-muted"></i>';
            $zone_time = getTimeBaseOnTimeZone($c['deliver_time']);
            $time = date('Y/m/d h:i a', strtotime($zone_time));
        } else if (isset($c['message_status']) && $c['message_status'] == 'read') {
            $message_status = ' <i class="fa fa-check-double text-primary"></i>';
            //$time = date('Y/m/d h:i a', strtotime($c['read_time']));
            if (!empty($c['from_user'])) {
                $zone_time = getTimeBaseOnTimeZone($c['created']);
            }else{
                $zone_time = getTimeBaseOnTimeZone($c['read_time']);
            }
            $time = date('Y/m/d h:i a', strtotime($zone_time)); 
        } else {
            $message_status = ' <i class="fa fa-check text-muted"></i>';
            //$time = date('Y/m/d h:i a', strtotime($c['created']));
            $zone_time = getTimeBaseOnTimeZone($c['created']);
            $time = date('Y/m/d h:i a', strtotime($zone_time));
        }


        if (!empty($c['from_user'])) {
            $html .= '<div class="bubble you" id="chat-' . $c['id'] . '">' . $message . $space . '<span class="time mr-2">' . $time . '</span></div>';
        } else {
            $time = !empty($delete_btn) ?  $delete_btn : '<span class="time mr-2">' . $time . $message_status . '</span>';
            $html .= '<div class="bubble me ' . $bg_danger . '" id="chat-' . $c['id'] . '">' . $message . $space . $time . '</div>';
        }
    }
    echo $html;
}
