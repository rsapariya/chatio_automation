<div class="modal fade" id="tags_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel"><?php echo isset($tag) && !empty($tag['id']) ? 'Edit' : 'Add' ?> Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="tag_frm">
                    <div class="user-message"></div>
                    <input type="hidden" id="id" name="id" value="<?php echo isset($tag) && !empty($tag['id']) ? base64_encode($tag['id']) : '' ?>">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="hTag">Tag</label>
                                <input type="text" class="form-control" id="hTag" name="tag" value="<?php echo isset($tag) && !empty($tag['tag']) ? $tag['tag'] : '' ?>" required="">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Please fill the tag</div>
                            </div> 
                        </div>
                        <div class="col-12 mt-2">
                            <input type="button" value="Save" class="mt-4 mb-4 btn btn-primary save-tag">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
