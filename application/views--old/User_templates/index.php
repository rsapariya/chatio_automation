<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Templates</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <h4>Templates List</h4>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-right mt-2">
                            <!--<button data-target="<?php echo base_url() . 'templates/add' ?>" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-user-plus btn-add-template"><span>Add Template</span></button>-->
                            <button data-target="<?php echo base_url() . 'templates/get_official_templates' ?>" class="btn-creative btn-3 btn-3d ml-2 btn-c-gradient-1 flaticon-user-plus btn-add-template"><span>Fetch WhatsApp Template</span></button>
                            <button data-target="<?php echo base_url() . 'templates/add_custom' ?>" class="btn-creative btn-3 btn-3d ml-2 btn-c-gradient-1 flaticon-user-plus btn-add-custom-template"><span>Add Custom Template</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area border-tab">
                    <ul class="nav nav-tabs mt-3  justify-content-center" id="border-tabs" role="tablist">
                        <!--                        <li class="nav-item">
                                                    <a class="nav-link text-center" id="border-birthday-tab" data-toggle="tab" href="#border-birthday" role="tab" aria-controls="border-birthday" aria-selected="true"><i class="flaticon-calendar-1"></i> Birthday</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-center" id="border-tab" data-toggle="tab" href="#border-anniversary" role="tab" aria-controls="border-anniversary" aria-selected="false"><i class="flaticon-calendar-12"></i> Anniversary</a>
                                                </li>-->
                        <li class="nav-item">
                            <a class="nav-link active text-center" id="border-tab" data-toggle="tab" href="#border-automation" role="tab" aria-controls="border-automation" aria-selected="false"><i class="flaticon-time"></i> Automation</a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="tabsContent">
                        <!--                        <div class="tab-pane fade" id="border-birthday" role="tabpanel" aria-labelledby="border-home-tab">   
                                                    <div class="table-responsive mb-4">
                                                        <table id="templates_dttble" class="table style-3 table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="checkbox-column text-center">#</th>
                                                                    <th>Description</th>
                                                                    <th>Created</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="border-anniversary" role="tabpanel" aria-labelledby="border-anniversary-tab">
                                                    <div class="table-responsive mb-4">
                                                        <table id="templates_anniversary_dttble" class="table style-3 table-bordered  table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="checkbox-column text-center">#</th>
                                                                    <th>Description</th>
                                                                    <th>Created</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>-->
                        <div class="tab-pane fade show active" id="border-automation" role="tabpanel" aria-labelledby="border-automation-tab">
                            <div class="table-responsive mb-4">
                                <table id="templates_automation_dttble" class="table style-3 table-bordered  table-hover">
                                    <thead>
                                        <tr>
                                            <th class="checkbox-column text-center">#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Media</th>
                                            <th>Created</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var DEFAULT_IMAGE_UPLOAD_PATH = '<?php echo DEFAULT_IMAGE_UPLOAD_PATH; ?>'
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/templates.js"></script>