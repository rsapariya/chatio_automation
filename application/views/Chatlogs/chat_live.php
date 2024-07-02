<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    <div class="chat-section layout-top-spacing">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="chat-system">
                    <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                    <div class="user-list-box" id="user-list-box">
                        <div class="search">
                            <input type="text" class="form-control" id="search-contact" placeholder="Search User" />
                            <h6 class="total-contact mt-3 mr-2"><?php echo isset($customers) && !empty($customers) ? count($customers) : '0'; ?></h6>

                        </div>
                        <div class="people" id="contact-list">
                            <?php
                            if (isset($customers) && !empty($customers)) {
                                foreach ($customers as $cust) {
                                    $created = !empty($cust['created']) ? $cust['created'] : '';
                                    $diff = date_diff(date_create(date('Y-m-d', strtotime($cust['created']))), date_create(date('Y-m-d')));
                                    
                                    if ($diff->days == 0) {
                                        $time = !empty($cust['created']) ? date('h:i a', strtotime($cust['created'])) : '';
                                    } else if ($diff->days == 1) {
                                        $time = 'Yesterday';
                                    } else if ($diff->days > 1 && $diff->days < 7) {
                                        $time = date('l', strtotime($cust['created']));
                                    } else {
                                        $time = date('Y/m/d', strtotime($cust['created']));
                                    }
                                    //$time = !empty($cust['created']) ? date('h:i a', strtotime($cust['created'])): '';
                                    ?>

                                    <div class="person"  data-contact="<?php echo!empty($cust['phone_number']) ? $cust['phone_number'] : '' ?>">
                                        <div class="user-info">
                                            <div class="f-head">
                                                <!--<img src="assets/img/profile-4.jpg" alt="avatar">-->
                                            </div>
                                            <div class="f-body">
                                                <div class="meta-info">
                                                    <span class="user-name" data-name="<?php echo!empty($cust['phone_number']) ? '+' . $cust['phone_number'] : '' ?>"><?php echo!empty($cust['phone_number']) ? '+' . $cust['phone_number'] : '' ?></span>
                                                    <span class="user-meta-time"><?php echo $time ?></span>
                                                </div>
                                                <span class="preview"><?php echo!empty($cust['from_profile_name']) ? $cust['from_profile_name'] : '' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>                         
                        </div>
                    </div>
                    <div class="chat-box">
                        <div class="chat-not-selected">
                            <p> Click User To Chat </p>
                        </div>
                        <div class="chat-box-inner">
                            <div class="chat-meta-user">
                                <div class="current-chat-user-name"><span><!--<img src="assets/img/90x90.jpg" alt="dynamic-image">--><span class="name"></span></span></div>
                            </div>
                            <div class="chat-conversation-box">
                                <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                    <div class="chat">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-footer">
                            <div class="chat-input">
                                <form class="chat-form" action="javascript:void(0);">
                                    <input name="contact" id="sender_number" hidden="" value="" />
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control mail-write-box" id ="send-msg" placeholder="" aria-label="Example text with two button addons">
                                            <button type="button" class="btn btn-dark send-msg-btn"><i class="fa fa-paper-plane theme-text-color"></i></button>
                                    </div>
                                    <!--<div class="d-flex">
                                        <div class="emoji-picker"></div>
                                        
                                    </div>-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/chat_logs.js?t=<?php echo date('YmdHis'); ?>"></script>