<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Reply Messages</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-9 col-md-9 col-sm-9 col-9">
                            <h4>Reply Messages List</h4>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-3 col-3 text-right mt-2">
                            <button data-target="<?php echo base_url() . 'replyMessage/add' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-reply-message"><span>Add Reply Message</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area border-tab">
                    <div class="table-responsive mb-4">
                        <table id="reply_messages_dttble" class="table style-3 table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Trigger Text</th>
                                    <!--<th>Messages</th>-->
                                    <th>Attachments</th>
                                    <th>Created</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ATTACHMENT_IMAGE_UPLOAD_PATH = '<?php echo ATTACHMENT_IMAGE_UPLOAD_PATH ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/reply_messages.js?<?php echo date('YmdHis') ?>"></script>