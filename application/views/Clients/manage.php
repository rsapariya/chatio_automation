<div class="modal fade" id="automation_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel">Add Automation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="automation_frm">
                    <div class="user-message"></div>
                    <input type="hidden" id="contact_id" name="contact_id" value="<?php echo isset($contact_id) && !empty($contact_id) ? base64_encode($contact_id) : '' ?>">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="Tname">Automation</label>
                                <select class="form-control" name="automation_id">
                                    <option selected="selected" disabled="disabled">Select Automation</option>
                                    <?php if (isset($automation) && !empty($automation)) {
                                        foreach ($automation as $a) {
                                            ?>
                                            <option value="<?php echo $a['id'] ?>"><?php echo $a['name'] ?></option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-12 mt-2">
                            <input type="button" value="Save" class="mt-4 mb-4 btn btn-primary save-automation">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
