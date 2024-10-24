<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
<!--<link href="<?php // echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/emojionearea/emojionearea.min.css">-->
<!--<script src="<?php // echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/emojionearea/emojionearea.min.js"></script>-->
<div class="chat-section layout-top-spacing">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="chat-system">
                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                <div class="user-list-box" id="user-list-box">
                    <div id="load-more">
                        <a href="javascript:void(0);" id="load-more-btn">Load More</a>
                    </div>
                    <div class="search">
                        <input type="text" class="form-control" id="search-contact" placeholder="Search User" />
                        <h6 class="total-contact mt-3 mr-2"><?php echo $total_contact; ?></h6>
                        <input type="hidden" id="listed_contact" value="<?php echo $listed_contact ?>"> 
                    </div>
                    <div class="people" id="contact-list">
                        <?php echo $contacts; ?>                      
                    </div>
                </div>
                <div class="chat-box">
                    <div class="chat-not-selected">
                        <p> Click User To Chat </p>
                    </div>
                    <div class="chat-box-inner">
                        <div class="chat-meta-user">
                            <div class="current-chat-user-name d-flex">
                                <span><!--<img src="assets/img/90x90.jpg" alt="dynamic-image">-->
                                    <span class="name"></span>             
                                </span>
                                <div class="ml-2">
                                    <i id='reload-chat' class="fa fa-arrows-rotate"></i>
                                </div>
                            </div>
                            
                            <?php if ($this->session->userdata('type') == 'user') { ?>
                                <div class="current-member d-flex">
                                    <!--<div class="is_subscribed_blk"></div>-->
                                    <span class="member-name text-dark mx-3"></span>
                                    <!--<a href="javascript:void(0);" id="select-member"><i class="fa fa-plus fa-xl text-success"></i></a>-->
                                    <!--<a href="javascript:void(0);" id="remove-member"><i class="fa fa-close fa-xl text-danger"></i></a>-->
                                    <a href="javascript:void(0);" id="save-contact"></a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="chat-conversation-box">
                            <p id="loading-chat" class="hide mb-0 text-center theme-text-color text-bold"><i class="fa-solid fa-spinner fa-spin-pulse"></i></p>
                            <p id="no-chat" class="hide mb-0 text-center theme-text-color text-bold">No more messages to load</p>

                            <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                <div class="chat"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-footer">
                        <div class="chat-input">
                            <form class="chat-form" id="chat-form"  action="javascript:void(0);">
                                <input name="contact" id="sender_number" hidden="" value="" />
                                <div class="input-group mb-3 free-window">
                                </div>
                            </form>
                        </div>
                        <div class="select-document">
                            <div class="modal fade" id="select_document_model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header d-flext justify-content-end">
                                            <button type="button" class="btn-close-modal">&times;</button>
                                        </div>
                                        <div class="modal-body template_details">
                                            <div class="modal-user-message"></div>
                                            <div class="row">
                                                <p class="text-center" id="file-title"></p>
                                                <img class="hide" id="preview-image" src="#" alt="Preview Image" style="max-width: 100%; max-height: 300px;">
                                                    <embed class="hide" id="preview-document" src="#" width="100%" height="500px" />
                                                    <video controls class="hide" id="preview-video"></video>
                                                    <div class="hide text-center" id="no-preview">
                                                        <h5><i class="fa fa-2xl fa-file"></i></h5>
                                                        <h6>No preview available</h6>
                                                        <span id="no-preview-info"></span>
                                                    </div>   
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" id="send-file">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="image-popup-modal" class="modal fade">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-body bg-white p-2">
                                        <img id="modal-image" src="" alt="Image" style="width: 100%; max-height: 80vh;">
                                        <p id="modal-caption"></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="livechat_modal_block"></div>
    <div class="chat_side_block hide p-3">
        <div class="header mb-2">
            <a href="javascript:void(0);" class="btn btn-muted btn-icon btn-rounded chat_side_block_close"><i class="fa fa-close fa-xl"></i></a>
        </div>
        <div class="body chat_side_block_body"></div>
    </div>
</div>
<script>
    var loggedInUserType = "<?php echo $this->session->userdata('type'); ?>"
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/chat_logs.js?t=<?php echo date('YmdHis'); ?>"></script>