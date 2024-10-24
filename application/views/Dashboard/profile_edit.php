<div class="middle-content container-xxl p-0">
    <!--  BEGIN BREADCRUMBS  -->
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"><h3>Profile</h3></div>
                    </div>
                </div>
            </header>
        </div>
    </div>
    <!--  END BREADCRUMBS  -->

    <div class="row layout-top-spacing">
        <div class="col-lg-12 mb-1">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <?php  if(isset($account_details) && !empty($account_details)){?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="text-bold">Business Account Details & Status</h4>
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <table class="table table-stripe">
                                    <thead>
                                        <tr>
                                            <th><b>Company Name</b></th>
                                            <th><b>Company Status</b></th>
                                            <th><b>Company Currency</b></th>
                                            <th><b>Business Verification</b></th>
                                            <th><b>Country</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo isset($account_details['name']) && !empty($account_details['name']) ? $account_details['name'] : '' ?></td>
                                            <td><?php if(isset($account_details['status']) && !empty($account_details['status'])){
                                                            if($account_details['status'] == 'ACTIVE'){?>
                                                                <b class="text-success"><?php echo strtoupper($account_details['status']); ?></b>
                                                            <?php }else{?>
                                                                <b class="text-danger"><?php echo strtoupper($account_details['status']); ?></b>
                                                            <?php }
                                                        }?>
                                            <td><?php echo isset($account_details['currency']) && !empty($account_details['currency']) ? $account_details['currency'] : '' ?></td>
                                            <td><?php if(isset($account_details['business_verification_status']) && !empty($account_details['business_verification_status'])){
                                                            if($account_details['business_verification_status']== 'verified'){?>
                                                                <b class="text-success"><?php echo strtoupper($account_details['business_verification_status']); ?></b>
                                                            <?php }else{?>
                                                                <b class="text-danger"><?php echo strtoupper($account_details['business_verification_status']); ?></b>
                                                            <?php }
                                                        }?>
                                            </td>
                                            <td><?php echo isset($account_details['country']) && !empty($account_details['country']) ? $account_details['country'] : '' ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php  if(isset($phone_details) && !empty($phone_details)){?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="text-bold">WhatsApp Phone Details</h4>
                        <div class="row">
                            <?php foreach($phone_details as $phone_details){ ?>
                            <div class="col-lg-12 col-12">
                                <table class="table table-stripe">
                                    <thead>
                                        <tr>
                                            <th><b>Verified Name</b></th>
                                            <th><b>Code Verification Status</b></th>
                                            <th><b>Display Number</b></th>
                                            <th><b>Quality Rating</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo isset($phone_details['verified_name']) && !empty($phone_details['verified_name']) ? $phone_details['verified_name'] : '' ?></td>
                                            <td><?php if(isset($phone_details['code_verification_status']) && !empty($phone_details['code_verification_status'])){
                                                            if($phone_details['code_verification_status'] == 'EXPIRED'){?>
                                                                <b class="text-danger"><?php echo strtoupper($phone_details['code_verification_status']); ?></b>
                                                            <?php }else{?>
                                                                <b class="text-success"><?php echo strtoupper($phone_details['code_verification_status']); ?></b>
                                                            <?php }
                                                        }?>
                                            <td><?php echo isset($phone_details['display_phone_number']) && !empty($phone_details['display_phone_number']) ? $phone_details['display_phone_number'] : '' ?></td>
                                            <td><?php if(isset($phone_details['quality_rating']) && !empty($phone_details['quality_rating'])){
                                                            if($phone_details['quality_rating']== 'GREEN'){?>
                                                                <b class="text-success"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                            <?php
                                                            }else if($phone_details['quality_rating']== 'YELLOW'){?>
                                                                <b class="text-warning"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                            <?php }else{?>
                                                                <b class="text-danger"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                            <?php }
                                                        }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12"><h4><?php echo isset($user_data['id']) ? 'Edit Profile' : '' ?></h4></div>    
                        </div>
                    </div>   
                    <div class="widget-content widget-content-area">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <form method="post" action="<?php echo base_url() . 'dashboard/save_profile' ?>" class="edit_profile" novalidate>
                                    <input type="hidden" name='user_id' value='<?php echo isset($user_data['id']) ? base64_encode($user_data['id']) : '' ?>'/>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <label for="hName">Name</label>
                                                <input type="text" class="form-control" id="hName" name="name" value="<?php echo isset($user_data['name']) ? $user_data['name'] : '' ?>">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback">Please fill the name</div>
                                            </div> 
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <label for="hEmail">Email</label>
                                                <input type="email" class="form-control" id="hEmail" name="email" value="<?php echo isset($user_data['email']) ? $user_data['email'] : '' ?>">
                                                <div class="invalid-feedback">Please fill the email OR valid email</div>
                                            </div> 
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <label for="hPhoneNo">Phone Number</label>
                                                <input type="text" class="form-control" id="hPhoneNo" name="phone_number" value="<?php echo isset($user_data['phone_number']) ? $user_data['phone_number'] : '' ?>">
                                                <div class="invalid-feedback">Please fill the phone</div>
                                            </div> 
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <label for="hPassword">Password</label>
                                                <input type="password" class="form-control" id="hPassword" name="password" value="">
                                            </div> 
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-group">
                                                <label for="cPassword">Password</label>
                                                <input type="password" class="form-control" id="cPassword" name="cpassword" value="">
                                            </div> 
                                        </div>
                                        <div class="col-12 mt-2">
                                            <input type="submit" name="Save" class="mt-4 mb-4 btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>