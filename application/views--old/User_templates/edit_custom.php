<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Templates - <?php echo isset($template_datas['id']) ? 'Edit' : 'Add' ?> Custom Template</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($template_datas['id']) ? 'Edit' : 'Add' ?>Custom Templates</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area widget-template">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <form method="post" action="<?php echo base_url() . 'templates/save_custom' ?>" class="add_custom_template" novalidate enctype="multipart/form-data">
                                <input type="hidden" name='template_id' value='<?php echo isset($template_datas['id']) ? base64_encode($template_datas['id']) : '' ?>'/>
                                <?php
                                $hide_type_class = '';
                                if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
                                    $hide_type_class = 'hide';
                                }
                                ?>
                                <fieldset class="form-group mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-1 col-sm-2 col-lg-1 pt-0">Type</label>
                                        <div class="col-xl-5 col-lg-5 col-sm-4">
                                            <div class="form-check mb-2 <?php echo $hide_type_class ?>">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio2" name='custom_type' value='list' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'list' ? 'checked="checked"' : 'checked="checked"' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                                    <label class="custom-control-label" for="hRadio2">List</label>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2 <?php echo $hide_type_class ?>">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio1" name='custom_type' value='button' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'button' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                                    <label class="custom-control-label" for="hRadio1">Button</label>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2 <?php echo $hide_type_class ?>">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio3" name='custom_type' value='text' <?php echo isset($template_datas['custom_type']) && $template_datas['custom_type'] == 'text' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-type-cls">
                                                    <label class="custom-control-label" for="hRadio3">Text</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group row mb-4">
                                    <label for="hName" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Name</label>
                                    <div class="col-xl-7 col-lg-7 col-sm-8">
                                        <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($template_datas['name']) ? $template_datas['name'] : '' ?>" required>

                                        <div class="invalid-feedback">
                                            Please fill the name
                                        </div>
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
                                    <div class="form-group row mb-4">
                                        <label for="header_text" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Title Text</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <textarea class="form-control" id="header_text" required  name="header_text"><?php echo isset($description['header_text']) ? $description['header_text'] : '' ?></textarea>
<!--                                            <small id="passwordHelpInline" class="text-muted">
                                                Add ||name|| for replace customer name
                                            </small>-->
                                            <div class="invalid-feedback">
                                                Please fill the Title Text
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="body_text" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Body Text</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <textarea class="form-control" id="body_text" name="body_text"><?php echo isset($description['body_text']) ? $description['body_text'] : '' ?></textarea>

                                            <div class="invalid-feedback">
                                                Please fill the Body Text
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="footer_text" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Footer Text</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <textarea class="form-control" id="footer_text" name="footer_text"><?php echo isset($description['footer_text']) ? $description['footer_text'] : '' ?></textarea>

                                            <div class="invalid-feedback">
                                                Please fill the Footer Text
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div_template_action_title form-group row mb-4 <?php echo $class ?>">
                                        <label for="footer_text" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Action Title</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <textarea class="form-control" id="action_title" required name="action_title"><?php echo isset($description['action_title']) ? $description['action_title'] : '' ?></textarea>

                                            <div class="invalid-feedback">
                                                Please fill the Action Title
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="footer_text" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Actions</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <div class="div_template_action_main <?php echo $class ?>">
                                                <?php
                                                if (isset($description['actions']) && !empty($description['actions'])) {
                                                    foreach ($description['actions'] as $akey => $action) {
                                                        $first_key = array_key_first($description['actions']);
                                                        ?>
                                                        <div class="div_template_action mt-3" id="div_template_action_<?php echo $akey ?>" data-seq="<?php echo $akey ?>">
                                                            <div class="row"> 
                                                                <div class="col-4">
                                                                    <input type="text" class="form-control" id="title_<?php echo $akey ?>" name="title[<?php echo $akey ?>]" placeholder="title <?php echo $akey ?>" value="<?php echo isset($action['title']) ? $action['title'] : '' ?>" required>
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
                                                                        <button type="button" class="btn btn-c-gradient-1 btn-add-action"><i class="flaticon-plus-2"></i></button> 
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <button type="button" class="btn btn-c-gradient-5 btn-remove-action" id="btn_remove_action_<?php echo $akey ?>" data-id="<?php echo $akey ?>"><i class="flaticon-minus-1"></i></button> 
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
                                                                <input type="text" class="form-control" id="title_1" name="title[1]" placeholder="title 1" value="" required>
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
                                                                <button type="button" class="btn btn-c-gradient-1 btn-add-action"><i class="flaticon-plus-2"></i></button> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
                                                                    <input type="text" class="form-control" id="title_<?php echo $akey ?>" name="btn_title[<?php echo $akey ?>]" placeholder="title <?php echo $akey ?>" value="<?php echo isset($action['title']) ? $action['title'] : '' ?>" required>
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
                                                                        <button type="button" class="btn btn-c-gradient-1 btn-add-action"><i class="flaticon-plus-2"></i></button> 
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <button type="button" class="btn btn-c-gradient-5 btn-remove-btn-action" id="btn_remove_btn_action_<?php echo $akey ?>" data-id="<?php echo $akey ?>"><i class="flaticon-minus-1"></i></button> 
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
                                                                <input type="text" class="form-control" id="title_1" name="btn_title[1]" placeholder="title 1" value="" required>
                                                                <div class="invalid-feedback">
                                                                    Please fill the Action Title
                                                                </div>
                                                            </div>
                                                            <div class="col-7">
                                                            </div>
                                                            <div class="col-1">
                                                                <button type="button" class="btn btn-c-gradient-1 btn-add-btn-action"><i class="flaticon-plus-2"></i></button> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="div_template_text_name <?php echo $template_text_class ?>">
                                    <div class="form-group row mb-4">
                                        <label for="text_details" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Description</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <textarea class="form-control" required id="text_details" name="text_details" rows="5"><?php echo isset($description['text_details']) ? $description['text_details'] : '' ?></textarea>

                                            <div class="invalid-feedback">
                                                Please fill the Text
                                            </div>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/templates.js"></script>