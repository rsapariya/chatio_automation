<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Contacts</h3></div>
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
                        <div class="col-lg-6 col-12"><h4><?php echo isset($user_datas['id']) ? 'Edit' : 'Add' ?> Contact</h4></div>   
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                            <form method="post" action="<?php echo base_url() . 'clients/save' ?>" class="add_client" novalidate>
                                <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>'/>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hName">Name</label>
                                            <input type="text" class="form-control" id="hName" name="name" value="<?php echo isset($user_datas['name']) ? $user_datas['name'] : '' ?>">
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the name</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hName">Email</label>
                                            <input type="email" class="form-control" id="hEmail" name="email" placeholder="" value="<?php echo isset($user_datas['email']) ? $user_datas['email'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the email</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hPhoneNo">Phone Number</label><br/>
                                            <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($user_datas['phone_number']) ? $user_datas['phone_number'] : '' ?>" required>
                                            <input type="hidden" id="country_code" name='country_code' value=''/>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the phone</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hgroupNo">Tags.</label>
                                            <input type="text" class="form-control" id="hgroupNo" name="group_ids" placeholder="Tags" value="<?php echo isset($user_datas['group_ids']) ? $user_datas['group_ids'] : '' ?>">
                                            <?php if (isset($user_tags) && empty($user_tags)) { ?>
                                                <small id="passwordHelpInline" class="text-muted"> <i class="fa fa-info-circle"></i> Please add <a href="<?php echo base_url() ?>tag">Tags</a> first to assign</small>
                                            <?php } ?>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <?php $birth_date = isset($user_datas['birth_date']) ? date('M d, Y', strtotime($user_datas['birth_date'])) : ''; ?>
                                        <div class="form-group">
                                            <label for="hgroupNo">Birth Date</label>
                                            <input id="dateFlatpickr" name='birth_date' value="<?php echo $birth_date ?>" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Birthdate">
                                            <div class="invalid-feedback">Please fill the birth date</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <?php $anniversary_date = isset($user_datas['anniversary_date']) ? date('M d, Y', strtotime($user_datas['anniversary_date'])) : ''; ?>
                                        <div class="form-group">
                                            <label for="hgroupNo">Anniversary Date</label>
                                            <input id="dateFlatpickr" name='anniversary_date' value="<?php echo $anniversary_date ?>" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Birthdate">
                                            <div class="invalid-feedback">Please fill the anniversary date</div>
                                        </div> 
                                    </div>
                                    <div class="col-12">
                                        <div class="row dynamic_column">
                                            <?php
                                            $column_array = array('column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8', 'column9', 'column10');
                                            $count_column = 0;
                                            foreach ($column_array as $carr) {
                                                if (isset($user_datas[$carr]) && !empty($user_datas[$carr])) {
                                                    $count_column++;
                                                    ?>
                                                    <div class="col-12 mt-2 new_column_<?php echo $count_column ?>">
                                                        <label for="hgroupNo">New Column</label>
                                                        <div class="row">
                                                            <div class="col-10">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="column[]" placeholder="" value="<?php echo $user_datas[$carr] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-2 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-danger remove_column" data-column="<?php echo $count_column ?>"><i class="fa fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" id="add_new_column" data-column="<?php echo $count_column ?>">Add new column</button>
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
<script>
    var userTags = '<?php echo isset($user_tags) && !empty($user_tags) ? json_encode($user_tags) : "" ?>';
</script>

<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/clients.js?t=<?php echo date('YmdHis') ?>"></script>
