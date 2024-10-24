<div class="modal fade" id="send_template_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel">Send Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="send_template_frm">
                    <input type="hidden" id="contact_id" name="contact_id" value="<?php echo isset($contact_id) && !empty($contact_id) ? base64_encode($contact_id) : '' ?>">
                    <div class="row">
                        <div class="col-12 col-lg-8 mt-2">
                            <div class="form-group">
                                <label for="Tname">Templates</label>
                                <select class="form-control" name="template_id" id="template_id">
                                    <option selected="selected" disabled="disabled">Select Template</option>
                                    <?php if (isset($templates) && !empty($templates)) {
                                        foreach ($templates as $t) {
                                            ?>
                                            <option value="<?php echo $t['id'] ?>"><?php echo $t['name'] ?></option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-12 mt-3" id="template_preview">
                            
                        </div>
                        <div class="user-message mt-2"></div>
                        <div class="col-12 mt-2 text-center">
                            <input type="button" value="Send Template" class="mt-4 mb-4 btn btn-primary" id="send-template">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
