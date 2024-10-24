<form class="form-horizontal" method="post" id="save_contact_frm">
    <div class="user-message"></div>
    <input type="hidden" id="contact_id" name="contact_id" value="<?php echo isset($contact['contact_id']) && !empty($contact['contact_id']) ? $contact['contact_id'] :'' ?>">
    <input type="hidden" id="phone_number" name="phone_number" value="<?php echo isset($contact['phone_number']) && !empty($contact['phone_number']) ? $contact['phone_number'] :'' ?>">
    <div class="row">
        <div class="col-12 mt-2">
            <div class="form-group">
                <label for="Tname">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($contact['name']) && !empty($contact['name']) ? $contact['name'] :'' ?>" />
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="form-group">
                <label for="tags">Tags.</label>
                <input type="text" class="form-control" id="tags" name="tags" placeholder="Tags" value="<?php echo isset($contact['tags']) ? $contact['tags'] : '' ?>">
                <?php if (isset($user_tags) && empty($user_tags)) { ?>
                    <small id="passwordHelpInline" class="text-muted"> <i class="fa fa-info-circle"></i> Please add <a href="<?php echo base_url() ?>tag">Tags</a> first to assign</small>
                <?php } ?>
            </div> 
        </div>
        <div class="col-12 mt-2">
            <div class="form-group">
                <label for="tags">Is Subscribed?</label>
                <div class="d-flex">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="subscribed" name="is_subscribed" value="subscribed" class="custom-control-input" <?php echo isset($contact['is_subscribed']) && $contact['is_subscribed'] == 1 ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="subscribed">Subscribed</label>
                        </div>
                    <div class="custom-control custom-radio custom-control-inline ml-3">
                        <input type="radio" id="unsubscribed" name="is_subscribed" value="unsubscribed" class="custom-control-input" <?php echo isset($contact['is_subscribed']) && $contact['is_subscribed'] == 0 ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="unsubscribed">Unsubscribed</label>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($members)) { ?>
        <div class="col-12 mt-2">
            <div class="form-group">
                <?php if(!empty($members)){?>
                    <label for="Tname">Assign To</label>
                    <select class="form-control" name="member_id">
                        <option selected="selected" disabled="disabled">Select Member</option>
                        <?php foreach ($members as $m) { ?>
                            <option value="<?php echo $m['id'] ?>"><?php echo $m['name'] ?></option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <h6 class="text-center">First add Member in Team.</h6>
                <?php } ?>
            </div> 
        </div>  
        <?php } ?>
        <div class="col-12 mt-2 text-center">
            <input type="button" value="<?php echo isset($contact['contact_id']) && !empty($contact['contact_id']) ? 'Update' : 'Save' ?> Contact" class="mt-4 mb-4 btn btn-primary btn-save-contact">
        </div>
    </div>
</form>