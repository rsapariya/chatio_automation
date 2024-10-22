
<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/timeline.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/custom-timeline.css" rel="stylesheet" type="text/css" />
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Automations</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>View Automation</h4></div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-container mx-auto">
                                <section id="cd-timeline" class="cd-container">
                                    <?php
                                    if (isset($automation_details) && !empty($automation_details)) {
                                        foreach ($automation_details as $key => $automation_detail) {
                                            $image = $title = $description = '';
                                            if (is_array($automation_detail)) {
                                                $image = base_url() . DEFAULT_IMAGE_UPLOAD_PATH . 'message.png';
                                                $title = $automation_detail['name'];
                                                //                                            $description = $automation_detail['description'];
                                                /*$description = '';
                                                if (isset($automation_datas) && !empty($automation_datas)) {
                                                    $template_media = isset($automation_datas['template_media']) ? (array) json_decode($automation_datas['template_media']) : array();
                                                    if (isset($template_media) && !empty($template_media)) {
                                                        $image_name = isset($template_media[($key + 1)]) ? $template_media[($key + 1)] : '';
                                                        if (!empty($image_name)) {
                                                            $iarray = explode('.', $image_name);
                                                            if (isset($iarray[1]) && !empty($iarray[1])) {
                                                                if ($iarray[1] == 'jpg' || $iarray[1] == 'jpeg' || $iarray[1] == 'png') {
                                                                    $image = base_url() . DEFAULT_IMAGE_UPLOAD_PATH . $image_name;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }*/
                                            } else {
                                                $image = base_url() . DEFAULT_IMAGE_UPLOAD_PATH . 'delay.png';
                                                $title = 'Wait for';
                                                $description = $automation_detail;
                                            }
                                            if ($key % 2 == 0) {
                                                ?> 
                                                <div class="cd-timeline-block">
                                                    <div class="cd-timeline-img">
                                                        <img src="<?php echo $image ?>" class="img_circle" alt="Avatar-1">
                                                    </div>

                                                    <div class="cd-timeline-content bg-danger">
                                                        <h2 class="mb-4"><?php echo $title ?></h2>
                                                        <p class="mb-4"><?php echo $description ?></p>
                                                    </div>
                                                </div>

                                                <?php
                                            } else {
                                                ?>
                                                <div class="cd-timeline-block">
                                                    <div class="cd-timeline-img">
                                                        <img src="<?php echo $image ?>" class="img_circle" alt="Avatar-2">
                                                    </div>

                                                    <div class="cd-timeline-content bg-primary">
                                                        <h2 class="mb-4"><?php echo $title ?></h2>
                                                        <p class="mb-4"><?php echo $description ?></p>
                                                        <!--<span class="cd-date">Jan 18</span>-->
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>