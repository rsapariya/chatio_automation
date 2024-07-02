<script type="text/javascript" src="<?php echo DEFAULT_ADMIN_JS_PATH ?>core/libraries/jquery_ui/interactions.min.js"></script>
<script type="text/javascript" src="<?php echo DEFAULT_ADMIN_JS_PATH ?>plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="<?php echo DEFAULT_ADMIN_JS_PATH ?>pages/form_select2.js"></script>
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-user"></i> <span class="text-semibold">Edit Profile</span></h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Edit Profile</li>
        </ul>
    </div>
</div>
<?php $this->load->view('Admin/alert_view'); ?> 
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal form-validate" action="" id="user_info" method="POST" enctype="multipart/form-data">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="message alert alert-danger" style="display:none"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">First Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="first_name" id="first_name" placeholder="Enter First name" class="form-control" value="<?php echo (isset($user_data['first_name'])) ? $user_data['first_name'] : set_value('first_name'); ?>">
                                    <span class="validation-error-label" ><?= form_error('first_name') ?></span>
                                </div>
                            </div>                       

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Address <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="address_text" id="address_text" placeholder="Enter Address" class="form-control" rows="5" cols="4"><?php echo (isset($user_data['address']['address_text'])) ? base64_decode($user_data['address']['address_text']) : set_value('address_text'); ?></textarea>
                                    <span class="validation-error-label" ><?= form_error('address_text') ?></span>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-lg-3 control-label">City <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="city" id="city" placeholder="Enter City" class="form-control" value="<?php echo (isset($user_data['address']['city'])) ? $user_data['address']['city'] : set_value('city'); ?>">
                                    <span class="validation-error-label" ><?= form_error('city') ?></span>
                                </div>
                            </div>  
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Last Name </label>
                                <div class="col-lg-9">
                                    <input type="text" name="last_name" id="last_name" placeholder="Enter Last name" class="form-control" value="<?php echo (isset($user_data['last_name'])) ? $user_data['last_name'] : set_value('last_name'); ?>">
                                    <span class="validation-error-label" ><?= form_error('last_name') ?></span>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Pin Code <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="pincode" id="last_name" placeholder="Enter Pin Code" class="form-control" value="<?php echo (isset($user_data['address']['pincode'])) ? $user_data['address']['pincode'] : set_value('pincode'); ?>">
                                    <span class="validation-error-label" ><?= form_error('pincode') ?></span>
                                </div>
                            </div>                       
                            <div class="form-group">
                                <label class="control-label col-lg-3">Profile Image</label>
                                <div class="col-lg-9">
                                    <div class="uploader">
                                        <input type="file" name="profile_pic" id="profile_pic" class="file-styled">
                                        <span class="filename" style="-webkit-user-select: none;"></span>
                                        <span class="action btn bg-info-400" style="-webkit-user-select: none;">Choose Images</span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (isset($user_data['profile_pic']) && !empty($user_data['profile_pic'])) {
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">&nbsp;</label>
                                    <div class="col-lg-6">
                                        <div class="thumb">
                                            <div class="thumb-inner">
                                                <img src="<?php echo base_url('Media/ProfilePic') . '/' . $user_data['profile_pic']; ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="form-group image_wrapper_div" style="display:none">
                                <label class="control-label col-lg-3">New Profile Picture</label>
                                <div class="col-lg-6">
                                    <div class="image_wrapper">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-success" type="submit">Save <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#profile_pic").on("change", function ()
    {
        $('.message').html();
        $('.image_wrapper').html('');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) {
            $('.message').html("No file selected.");
            $('.message').show();
            return; // no file selected, or no FileReader support
        }

        var i = 0;
        for (var key in files) {
            if (/^image/.test(files[key].type)) { // only image file
                console.log(files);
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function () { // set image data as background of div
                    // $('#imagePreview').addClass('imagePreview');
                    $('.image_wrapper_div').show();
                    $('.image_wrapper').show();
                    $('.message').hide();
                    $('.image_wrapper').append("<div class='imagePreview" + i + "' id='imagePreview'></div>");
                    $('.imagePreview' + i).css("background-image", "url(" + this.result + ")");
                    ++i;
                }
            } else {
//                this.files = '';
                $('.message').html("Please select proper image");
                $('.message').show();
            }
        }
    });
</script>
<style>
    .image_wrapper{
        height:auto;
        width:auto;
    }
    #imagePreview {
        width: 400px;
        height: 180px;
        background-position: center center;
        background-size: contain;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
        display: inline-block;
        float: left;
        margin: 9px;
        background-repeat: no-repeat; 
    }
    #imagePreview_msg {
        width: 100%;
        height: 180px;
        background-position: center center;
        background-size: cover;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
    }
    .image_wrapper thumb-inner{
        max-width: auto; 
    }
</style>



