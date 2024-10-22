<div class="modal fade" id="member_info_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel"><?php echo isset($member_info) && !empty($member_info['name']) ? $member_info['name'] : '' ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body template_details">
                    <div class="user-message"></div>
                    <div class="row">
                        <div class="table-responsive pt-3">
                            <table class="table no-footer table-bordered asssigned_member_tbl">
                                <thead>
                                    <tr>
                                        <th colspan="2">Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($assign_to) && !empty($assign_to)){
                                        foreach($assign_to as $assigned){ ?>
                                        <tr id="tbl-<?php echo $assigned['id'];  ?>">
                                            <td><?php echo  $assigned['assigned_to'];   ?><?php echo  !empty($assigned['name']) ? ' ( '.$assigned['name'].' )' : '';   ?></td>
                                            <td><a href="javascript:void(0);" class="text-danger unassign_member bs-tooltip p-1"  data-bs-placement="top" data-bs-original-title="Unassign" data-id="<?php echo base64_encode($assigned['id']);  ?>"><i class="fa fa-close"></i></a></td>
                                        </tr>
                                        <?php }
                                    }else{ ?>
                                    <tr>
                                        <th colspan="2" class="text-center">Not assigned</th>
                                    </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="Temail">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" value="<?php echo isset($member_info) && !empty($member_info) && !empty($member_info['password']) ? $this->encrypt->decode($member_info['password']) : ''  ?>">
                                <button class="btn btn-outline hide_show_password"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
