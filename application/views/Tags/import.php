<div class="modal fade" id="tags_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel">Import Tags</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="import_tag_frm">
                    <div class="user-message"></div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="hTag">Tag</label>
                                <input type="file" class="form-control-file" id="hTag" name="tags" required="">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Please fill the tag</div>
                            </div> 
                        </div>
                        <div class="col-12 mt-2">
                            <input type="button" value="Save" class="mt-4 mb-4 btn btn-primary save-tags">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
