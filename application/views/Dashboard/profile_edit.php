<div class="middle-content container-xxl p-0">
    <!--  BEGIN BREADCRUMBS  -->
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"><h3><?php echo isset($user_data['id']) ? 'Edit Profile' : '' ?></h3></div>
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
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12"><h4></h4></div>    
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
                                                <input type="password" class="form-control" id="cPassword" name="cPassword" value="">
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