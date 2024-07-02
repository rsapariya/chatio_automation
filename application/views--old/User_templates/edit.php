<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Templates - <?php echo isset($template_datas['id']) ? 'Edit' : 'Add' ?> Template</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4><?php echo isset($template_datas['id']) ? 'Edit' : 'Add' ?> Templates</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area widget-template">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <form method="post" action="<?php echo base_url() . 'templates/save' ?>" class="add_template" novalidate enctype="multipart/form-data" >
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
                                                    <input type="radio" id="hRadio2" name='type' value='birthday' <?php echo isset($template_datas['type']) && $template_datas['type'] == 'birthday' ? 'checked="checked"' : 'checked="checked"' ?> class="custom-control-input radio-classic-primary template-cls" required>
                                                    <label class="custom-control-label" for="hRadio2">Birthday</label>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2 <?php echo $hide_type_class ?>">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio1" name='type' value='anniversary' <?php echo isset($template_datas['type']) && $template_datas['type'] == 'anniversary' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-cls" required>
                                                    <label class="custom-control-label" for="hRadio1" >Anniversary</label>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2">
                                                <div class="custom-control custom-radio classic-radio-info">
                                                    <input type="radio" id="hRadio3" name='type' value='automation' <?php echo isset($template_datas['type']) && $template_datas['type'] == 'automation' ? 'checked="checked"' : '' ?> class="custom-control-input radio-classic-primary template-cls" required>
                                                    <label class="custom-control-label" for="hRadio3" >Automation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <?php
                                $class = 'hide';
                                $required_class = '';
                                if (isset($template_datas['type']) && $template_datas['type'] == 'automation') {
                                    $class = '';
                                    $required_class = 'required';
                                }
                                ?>
                                <div class="div_template_name <?php echo $class ?>">
                                    <div class="form-group row mb-4">
                                        <label for="hName" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Contact Name</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <input type="text" class="form-control" id="hName" name="name" placeholder="" value="<?php echo isset($template_datas['name']) ? $template_datas['name'] : '' ?>" <?php echo $required_class ?>>
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback">
                                                Please fill the contact name
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="hDescription" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Description</label>
                                    <div class="col-xl-7 col-lg-7 col-sm-8">
                                        <?php
                                        if (isset($template_datas['temp_id']) && !empty($template_datas['temp_id'])) {
                                            $desc_json = $template_datas['description'];
                                            $desc_json_array = (!empty($desc_json)) ? json_decode($desc_json) : array();
                                            if (!empty($desc_json_array)) {
                                                $message = '';
                                                $btn_text = '';
                                                foreach ($desc_json_array as $desc) {
//                                                    pr($desc);
                                                    $desc = (array) $desc;
                                                    if (isset($desc['type']) && $desc['type'] == 'HEADER') {
                                                        if (isset($desc['format']) && $desc['format'] == 'VIDEO') {
                                                            $video_url = '';
                                                            $desc['example'] = (array) $desc['example'];
                                                            if (isset($desc['example']['header_handle'][0])) {
                                                                $video_url = $desc['example']['header_handle'][0];
                                                            }

                                                            $message .= '<video width="320" height="240" src="' . $video_url . '" controls>Your browser does not support the video tag.</video>';
                                                        }

                                                        if (isset($desc['format']) && $desc['format'] == 'DOCUMENT') {
                                                            $document_url = '';
                                                            $desc['example'] = (array) $desc['example'];
                                                            if (isset($desc['example']['header_handle'][0])) {
                                                                $document_url = $desc['example']['header_handle'][0];
                                                            }
                                                            if (!empty($document_url)) {
                                                                $message .= '<a class="text-primary" href= "' . $document_url . '">Check Document</a>';
                                                            }
                                                        }
                                                        if (isset($desc['format']) && $desc['format'] == 'IMAGE') {
                                                            $image_url = DEFAULT_ADMIN_ASSET_PATH . '/img/default-img.png';
                                                            $desc['example'] = (array) $desc['example'];
                                                            if (isset($desc['example']['header_handle'][0])) {
                                                                $image_url = $desc['example']['header_handle'][0];
                                                            }
                                                            $message .= '<img height="100px" src="' . $image_url . '"/>';
                                                        } elseif (isset($desc['format']) && $desc['format'] == 'TEXT') {
                                                            $message .= '<span class="text-dark font-weight-bold">' . $desc['text'] . '</span>';
                                                        }
                                                    } elseif (isset($desc['type']) && $desc['type'] == 'BODY') {
                                                        $br_text = ($message != '') ? '<br/><br/>' : '';
                                                        $message .= $br_text . '<span class="text-dark">' . $desc['text'] . '</span>';
                                                    } elseif (isset($desc['type']) && $desc['type'] == 'FOOTER') {
                                                        $br_text = ($message != '') ? '<br/><br/>' : '';
                                                        $message .= $br_text . '<span class="text-muted fs-6">' . $desc['text'] . '</span>';
                                                    } elseif (isset($desc['type']) && $desc['type'] == 'BUTTONS') {

                                                        if (isset($desc['buttons']) && !empty($desc['buttons'])) {
                                                            $buttons = (array) $desc['buttons'];
                                                            if (!empty($buttons)) {
                                                                foreach ($buttons as $button) {
                                                                    $button = (array) $button;
                                                                    $button_url = '';
                                                                    if (isset($button['example'][0])) {
                                                                        $button_url = $button['example'][0];
                                                                    }
                                                                    $btn_text .= '<button disabled onclick="window.location.href = ' . $button_url . '" class="btn-creative btn-3 btn-3e mb-4 mt-3"><span>' . $button['text'] . '</span></button>&nbsp';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $message = nl2br($message);
                                                }
                                                ?>
                                                <div class="temp_details">
                                                    <div id="temp_description" readonly>
                                                        <?php echo $message ?>
                                                    </div>
                                                    <div  id="temp_buttons" readonly>
                                                        <?php echo $btn_text; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <textarea class="form-control" id="description" name="description"><?php echo isset($template_datas['description']) ? $template_datas['description'] : 'Hello, ||name||' ?></textarea>
                                            <small id="passwordHelpInline" class="text-muted">
                                                Add ||name|| for replace customer name
                                            </small>
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback">
                                                Please fill the description
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>


                                <div class="div_template_automation_images <?php echo $class . ' ' . $hide_type_class ?> ">
                                    <div class="form-group row mb-4">
                                        <label for="hDescription" class="col-xl-1 col-sm-2 col-lg-1 col-form-label">Image</label>
                                        <div class="col-xl-7 col-lg-7 col-sm-8">
                                            <input type="file" class="form-control-file" id="automation_image" name="automation_image" placeholder="" accept=".jpg, .png, .pdf">
                                            <small id="passwordHelpInline" class="text-muted">
                                                Allowed file type: JPG, PNG, PDF. Max size: 500kb
                                            </small>
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