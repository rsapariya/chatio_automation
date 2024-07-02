<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Template</h3></div>
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
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12"><h4><?php echo isset($template_datas['id']) ? 'Edit' : 'Add' ?> Custom Templates</h4></div>    
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form method="post" action="<?php echo base_url() . 'templates/save_custom' ?>" class="add_custom_template" id="add_custom_template" novalidate enctype="multipart/form-data">
                        <input type="hidden" name='template_id' value='<?php echo isset($template_datas['id']) ? base64_encode($template_datas['id']) : '' ?>'/>
                        <?php
                            $hide_type_class = '';
                            if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
                                $hide_type_class = 'hide';
                            }
                        ?>
                        <div class="row">
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hPhoneNo">Type</label><br/>
                                    <div class="form-check form-check-primary form-check-inline <?php echo $hide_type_class ?>">
                                    <input type="radio" id="hRadio3" name='custom_type' value='list' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'list' ? 'checked="checked"' : 'checked="checked"' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                        <label class="form-check-label" for="hRadio3">
                                            List
                                        </label>
                                    </div>
                                    <div class="form-check form-check-primary form-check-inline <?php echo $hide_type_class ?>">
                                        <input type="radio" id="hRadio2" name='custom_type' value='button' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'button' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                        <label class="form-check-label" for="hRadio2">
                                            Button
                                        </label>
                                    </div>
                                    <div class="form-check form-check-primary form-check-inline <?php echo $hide_type_class ?>">
                                        <input type="radio" id="hRadio1" name='custom_type' value='text' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'text' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                        <label class="form-check-label" for="hRadio1">
                                            Text
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <div class="form-group mb-4">
                                    <label for="hName">Name</label>
                                    <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($template_datas['name']) ? $template_datas['name'] : '' ?>" required>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the name</div>
                                </div>
                            </div>
                            <?php
                                $class = '';
                                $action_class = 'hide';
                                $template_class = '';
                                $template_text_class = 'hide';
                                $description = isset($template_datas['description']) && !empty($template_datas['description']) ? json_decode($template_datas['description'], true) : array();
                                if (isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'text') {
                                    $template_text_class = '';
                                    $template_class = 'hide';
                                } else if (isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'list') {
                                    $class = '';
                                    $action_class = 'hide';
                                } else if (isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'button') {
                                    $class = 'hide';
                                    $action_class = '';
                                }
                                ?>
                                <div class="div_template_name <?php echo $template_class ?>">
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4">
                                            <label for="header_text">Title Text</label>
                                            <input type="text" class="form-control" id="header_text" name="header_text" placeholder="" value="<?php echo isset($description['header_text']) ? $description['header_text'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the title text</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4">
                                            <label for="body_text">Body Text</label>
                                            <textarea class="form-control" id="body_text" name="body_text" required><?php echo isset($description['body_text']) ? $description['body_text'] : '' ?></textarea>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the body text</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4">
                                            <label for="footer_text">Footer Text</label>
                                            <input type="text" class="form-control" id="footer_text" name="footer_text" placeholder="" value="<?php echo isset($description['footer_text']) ? $description['footer_text'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the footer text</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4 div_template_action_title <?php echo $class ?>">
                                            <label for="action_title">Action Title</label>
                                            <input type="text" class="form-control" id="action_title" name="action_title" placeholder="" value="<?php echo isset($description['action_title']) ? $description['action_title'] : '' ?>" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please fill the action title</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-12">
                                        <div class="form-group mb-4">
                                            <label for="action_title">Action</label>
                                            <div class="row">
                                                <div class="div_template_action_main <?php echo $class ?>">
                                                    <?php
                                                    if (isset($description['actions']) && !empty($description['actions'])) {
                                                        foreach ($description['actions'] as $akey => $action) {
                                                            $first_key = array_key_first($description['actions']);
                                                            ?>
                                                            <div class="col-12 div_template_action mt-3" id="div_template_action_<?php echo $akey ?>" data-seq="<?php echo $akey ?>">
                                                                <div class="row"> 
                                                                    <div class="col-4">
                                                                        <input type="text" class="form-control action-title" id="title_<?php echo $akey ?>" name="title[<?php echo $akey ?>]" placeholder="title <?php echo $akey ?>" value="<?php echo isset($action['title']) ? $action['title'] : '' ?>" maxlength="20" required>
                                                                        <small>Maximum 20 character</small>
                                                                        <div class="invalid-feedback">
                                                                            Please fill the Action Title
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-7">
                                                                        <input type="text" class="form-control" id="description_<?php echo $akey ?>" name="description[<?php echo $akey ?>]" placeholder="description <?php echo $akey ?>" value="<?php echo isset($action['description']) ? $action['description'] : '' ?>" required>
                                                                        <div class="invalid-feedback">
                                                                            Please fill the Action Description
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <?php
                                                                        if ($akey == $first_key) {
                                                                            ?>
                                                                            <button type="button" class="btn btn-primary btn-add-action"><i class="fa fa-plus"></i></button> 
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <button type="button" class="btn btn-danger btn-remove-action" id="btn_remove_action_<?php echo $akey ?>" data-id="<?php echo $akey ?>"><i class="fa fa-minus"></i></button> 
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                            <div class="div_template_action mt-3" id="div_template_action_1" data-seq="1">
                                                                <div class="row"> 
                                                                    <div class="col-4">
                                                                        <input type="text" class="form-control" id="title_1" name="title[1]" placeholder="title 1" value="" maxlength="20" required>
                                                                        <small>Maximum 20 character</small>
                                                                        <div class="invalid-feedback">
                                                                            Please fill the Action Title
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-7">
                                                                        <input type="text" class="form-control" id="description_1" name="description[1]" placeholder="description 1" value="" required>
                                                                        <div class="invalid-feedback">
                                                                            Please fill the Action Description
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <button type="button" class="btn btn-primary btn-add-action"><i class="fa fa-plus"></i></button> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php }?>
                                                </div>
                                                <div class="div_template_btn_action_main <?php echo $action_class ?>">
                                                    <?php
                                                    if (isset($description['actions']) && !empty($description['actions'])) {
                                                        foreach ($description['actions'] as $akey => $action) {
                                                            $first_key = array_key_first($description['actions']);
                                                            ?>
                                                            <div class="div_template_btn_action mt-3" id="div_template_btn_action_<?php echo $akey ?>" data-seq="<?php echo $akey ?>">
                                                                <div class="row"> 
                                                                    <div class="col-4">
                                                                        <input type="text" class="form-control" id="title_<?php echo $akey ?>" name="btn_title[<?php echo $akey ?>]"  maxlength="20"  placeholder="title <?php echo $akey ?>" value="<?php echo isset($action['title']) ? $action['title'] : '' ?>" required>
                                                                        <small>Maximum 20 character</small>
                                                                        <div class="invalid-feedback">
                                                                            Please fill the Action Title
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-7">
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <?php
                                                                        if ($akey == $first_key) {
                                                                            ?>
                                                                            <button type="button" class="btn btn-primary btn-add-btn-action"><i class="fa fa-plus"></i></button> 
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <button type="button" class="btn btn-danger btn-remove-btn-action" id="btn_remove_btn_action_<?php echo $akey ?>" data-id="<?php echo $akey ?>"><i class="fa fa-minus"></i></button> 
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <div class="div_template_btn_action mt-3" id="div_template_btn_action_1" data-seq="1">
                                                            <div class="row"> 
                                                                <div class="col-4">
                                                                    <input type="text" class="form-control" id="title_1" name="btn_title[1]" placeholder="title 1" value="" maxlength="20" required>
                                                                    <small>Maximum 20 character</small>
                                                                    <div class="invalid-feedback">
                                                                        Please fill the Action Title
                                                                    </div>
                                                                </div>
                                                                <div class="col-7">
                                                                </div>
                                                                <div class="col-1">
                                                                    <button type="button" class="btn btn-primary btn-add-btn-action"><i class="fa fa-plus"></i></button> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>


                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-12 div_template_text_name <?php echo $template_text_class ?>">
                                    <div class="form-group mb-4">
                                        <label for="text_details">Description</label>
                                        <textarea class="form-control" required id="text_details" name="text_details" rows="5"><?php echo isset($description['text_details']) ? $description['text_details'] : '' ?></textarea>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">Please fill the description</div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-12">
                                    <input type="submit" name="Save" class="mt-4 mb-4 btn btn-primary">
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/templates.js?t=<?php echo date('YmdHis') ?>"></script>