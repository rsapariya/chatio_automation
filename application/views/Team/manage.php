<div class="modal fade" id="team_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel"><?php echo isset($team) && !empty($team['id']) ? 'Edit' : 'Add' ?> Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="team_frm">
                    <div class="user-message"></div>
                    <input type="hidden" id="id" name="id" value="<?php echo isset($team) && !empty($team['id']) ? base64_encode($team['id']) : '' ?>">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="Tname">Name</label>
                                <input type="text" class="form-control" id="Tname" name="name" value="<?php echo isset($team) && !empty($team['name']) ? $team['name'] : '' ?>" required="">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Please fill the name</div>
                            </div> 
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="Temail">Email</label>
                                <input type="text" class="form-control" id="Temail" name="email" value="<?php echo isset($team) && !empty($team['email']) ? $team['email'] : '' ?>" required="">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Please fill the email</div>
                            </div> 
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="Tphone_number">Phone Number</label>
                                <input type="text" class="form-control" id="Tphone_number" name="phone_number" value="<?php echo isset($team) && !empty($team['phone_number']) ? $team['phone_number'] : '' ?>" required="">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">Please fill the phone number</div>
                            </div> 
                        </div>
                        <?php if (!isset($team)) { ?>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="Tpass">Password</label>
                                    <input type="password" class="form-control" id="Tpass" name="password" value="" required="">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the password</div>
                                </div> 
                            </div>
                        <?php } ?>
                        <div class="col-12 mt-2">
                            <input type="button" value="Save" class="mt-4 mb-4 btn btn-primary save-team">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
