<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Clients - <?php echo isset($user_datas['id']) ? 'Edit' : 'Add' ?> Client</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($user_datas['id']) ? 'Edit' : 'Add' ?> Client</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form method="post" action="<?php echo base_url() . 'clients/save' ?>" class="add_client" novalidate>
                                <input type="hidden" name='user_id' value='<?php echo isset($user_datas['id']) ? base64_encode($user_datas['id']) : '' ?>'/>
                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Name</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($user_datas['name']) ? $user_datas['name'] : '' ?>" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the name
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hEmail" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Email</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="email" class="form-control" id="hEmail" name="email" placeholder="" value="<?php echo isset($user_datas['email']) ? $user_datas['email'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please fill the email
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hPhoneNo" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Phone Number</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="tel" class="form-control" id="hPhoneNo" name="phone_number" placeholder="" value="<?php echo isset($user_datas['phone_number']) ? $user_datas['phone_number'] : '' ?>" required>
                                        <input type="hidden" id="country_code" name='country_code' value=''/>
                                        <div class="invalid-feedback">
                                            Please fill the phone
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label for="hPhoneNo" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Group No.</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <textarea class="form-control" id="hPhoneNo" name="group_ids" placeholder=""><?php echo isset($user_datas['group_ids']) ? $user_datas['group_ids'] : '' ?></textarea>
                                        <small id="passwordHelpInline" class="text-muted">
                                            Comma (,) separated group ID
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <?php $birth_date = isset($user_datas['birth_date']) ? $user_datas['birth_date'] : date('Y-m-d'); ?>
                                    <label for="" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Birth Date</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <div class="input-control text mb-1" data-role="datepicker" data-preset="<?php echo $birth_date ?>" data-date="1972-12-21" data-format="mmmm d, yyyy" data-on-select="addDate(d,'birth_date')">
                                            <input type="text">
                                            <button class="button"><span class="flaticon-calendar-1"></span></button>
                                            <input type="hidden" id='birth_date' name='birth_date' value='<?php echo $birth_date ?>' >
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the birth date
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <?php $anniversary_date = isset($user_datas['anniversary_date']) ? $user_datas['anniversary_date'] : date('Y-m-d'); ?>
                                    <label for="" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Anniversary Date</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <div class="input-control text mb-1" data-role="datepicker" data-preset="<?php echo $anniversary_date ?>" data-date="1972-12-21" data-format="mmmm d, yyyy" data-on-select="addDate(d,'anniversary_date')" >
                                            <input type="text">
                                            <button class="button"><span class="flaticon-calendar-1"></span></button>
                                            <input type="hidden" id='anniversary_date' name='anniversary_date' value='<?php echo $anniversary_date ?>' >
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill the anniversary date
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row text-left">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn-creative btn-3 btn-3e flaticon-arrow-left mb-4 mt-3"><span>Save</span></button>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/clients.js"></script>