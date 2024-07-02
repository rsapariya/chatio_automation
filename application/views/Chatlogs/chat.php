<?php

if (isset($chat) && !empty($chat)) {
    $html = '';
    foreach ($chat as $key => $ch) {

        $temp = '';
        foreach ($ch as $c) {
            if ($temp != $key) {
                $diff = date_diff(date_create(date('Y-m-d', strtotime($key))), date_create(date('Y-m-d')));
                if ($diff->days == 0) {
                    $convDay = !empty($c['created']) ? date('h:i a', strtotime($c['created'])) : '';
                } else if ($diff->days == 1) {
                    $convDay = 'Yesterday';
                } else if ($diff->days > 1 && $diff->days < 7) {
                    $convDay = date('l', strtotime($c['created']));
                } else {
                    $convDay = date('Y/m/d', strtotime($c['created']));
                }
                $html .= '<div class="conversation-start"><span>' . $convDay . '</span></div>';
            }
            $temp = $key;
            $space = strlen($c['message']) < 5 ? '&nbsp;&nbsp;&nbsp;' : '';


            if (!empty($c['from_user'])) {
                $message = $c['message'];
                if ($c['message_type'] == 'text') {
                    if (!empty($c['message'])) {
                        $url = extract_url($c['message']);
                        if (!empty($url)) {
                            $title = getPageTitle($url);
                            $message = '<div class="preview"><a href="' . $url . '"><label>' . $title . '</label><br/><small>' . getBaseUrl($url) . '</small></a></div>';
                            $message .= str_replace($url, '<a href="' . $url . '" class="text-primary">' . $url . '</a>', $c['message']);
                        }
                    }
                }
                $html .='<div class="bubble you">' . $message . $space . '<span class="time mr-2">' . date('h:i a', strtotime($c['created'])) . '</span></div>';
            } else {
                $message = $c['message'];
                $message_status = '';
                if (isset($c['message_status']) && $c['message_status'] == 'delivered') {
                    $message_status = ' <i class="fa fa-check-double text-muted"></i>';
                } else if (isset($c['message_status']) && $c['message_status'] == 'read') {
                    $message_status = ' <i class="fa fa-check-double text-primary"></i>';
                } else {
                    $message_status = ' <i class="fa fa-check text-muted"></i>';
                }

                if ($c['message_type'] == 'api_text') {
                    $message_arr = !empty($c['message']) ? (array) json_decode($c['message'], 1) : '';
                    if (!empty($message_arr) && isset($message_arr['text']['preview_url'])) {
                        if ($message_arr['text']['preview_url'] == true) {
                            $url = extract_url($message_arr['text']['body']);
                            $title = getPageTitle($url);
                            $message = '<div class="preview"><a href="' . $url . '"><label>' . $title . '</label><br/><small>' . getBaseUrl($url) . '</small></a></div>';
                            $message .= str_replace($url, '<a href="' . $url . '" class="text-primary">' . $url . '</a>', $message_arr['text']['body']);
                        }else{
                            $message = $message_arr['text']['body'];
                        }
                    }
                }
                if ($c['message_type'] == 'api_image') {
                    $message_arr = !empty($c['message']) ? (array) json_decode($c['message'], 1) : '';
                    if (!empty($message_arr)) {

                        if (isset($message_arr['image']['link']) && !empty($message_arr['image']['link'])) {
                            $message = '<img src="' . $message_arr['image']['link'] . '" width="200px" /><br/>';
                            if (isset($message_arr['image']['caption']) && !empty($message_arr['image']['caption'])) {
                                $message .= $message_arr['image']['caption'];
                            }
                        }
                    }
                }



                if ($c['message_type'] == 'api_video') {
                    $message_arr = !empty($c['message']) ? (array) json_decode($c['message'], 1) : '';
                    if (!empty($message_arr)) {
                        if (isset($message_arr['video']['link']) && !empty($message_arr['video']['link'])) {
                            $file_type = substr($message_arr['video']['link'], strrpos($message_arr['video']['link'], '.') + 1);
                            $message = '<video controls width="200"><source src="' . $message_arr['video']['link'] . '" type="video/' . $file_type . '"></video>';
                            if (isset($message_arr['video']['caption']) && !empty($message_arr['video']['caption'])) {
                                $message .= $message_arr['video']['caption'];
                            }
                        }
                    }
                }


                if ($c['message_type'] == 'api_audio') {
                    $message_arr = !empty($c['message']) ? (array) json_decode($c['message'], 1) : '';
                    if (!empty($message_arr)) {
                        if (isset($message_arr['audio']['link']) && !empty($message_arr['audio']['link'])) {
                            $file_type = substr($message_arr['audio']['link'], strrpos($message_arr['audio']['link'], '.') + 1);
                            $message = '<audio controls><source src="' . $message_arr['audio']['link'] . '" type="audio/' . $file_type . '"></audio>';
                        }
                    }
                }
                if ($c['message_type'] == 'api_document') {
                    $message_arr = !empty($c['message']) ? (array) json_decode($c['message'], 1) : '';
                    if (!empty($message_arr)) {
                        if (isset($message_arr['document']['link']) && !empty($message_arr['document']['link'])) {
                            $file_type = substr($message_arr['document']['link'], strrpos($message_arr['document']['link'], '.') + 1);
                            $data = get_headers($message_arr['document']['link'], true);
                            $file_size = isset($data['Content-Length']) ? (int) $data['Content-Length'] : '';
                            $file_size_in = !empty($file_size) ? ' - ' . conver_file_size($file_size) : '';
                            if (!empty($message_arr['document']['filename'])) {
                                $file_name = $message_arr['document']['filename'];
                            } else {
                                $file_name = substr($message_arr['document']['link'], strrpos($message_arr['document']['link'], '/') + 1);
                            }
                            $message = '<a href="' . $message_arr['document']['link'] . '" class="download-file" data-link="' . $message_arr['document']['link'] . '" data-filename="' . $file_name . '" ><div class="d-flex preview"><div class="file-type"></div><small><span>' . $file_name . '</span><br/><span>' . strtoupper($file_type) . $file_size_in . '</span></small></div></a>';
                            if (isset($message_arr['document']['caption']) && !empty($message_arr['document']['caption'])) {
                                $message .= $message_arr['document']['caption'];
                            }
                        }
                    }
                }


                $html .='<div class="bubble me">' . $message . $space . '<span class="time mr-2">' . date('h:i a', strtotime($c['created'])) . $message_status . '</span></div>';
            }
        }
    }
    echo $html;
}
?>