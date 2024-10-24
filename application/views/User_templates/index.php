<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title">
                        <h3>Templates</h3>
                    </div>
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
                        <div class="col-lg-6 col-12">
                            <h4>Template List</h4>
                        </div>
                        <div class="col-lg-6 col-12 d-flex justify-content-end mt-3">
                            <a href="https://business.facebook.com/wa/manage/message-templates/" target="_blank"
                                class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light">
                                <i class="fa fa-window-restore"></i>
                                <span class="btn-text-inner">Create Template in Meta</span>
                            </a>
                            <button data-target="<?php echo base_url() ?>templates/create_carousel"
                                class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-add-template">
                                <i class="fa fa-window-restore"></i>
                                <span class="btn-text-inner">Create Carousel</span>
                            </button>
                            <button data-target="<?php echo base_url() ?>templates/get_official_templates"
                                class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-add-template">
                                <i class="fa fa-whatsapp"></i>
                                <span class="btn-text-inner">Fetch WhatsApp Template</span>
                            </button>
                            <button data-target="<?php echo base_url() ?>templates/add_custom"
                                class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-add-custom-template">
                                <i class="fa fa-newspaper"></i>
                                <span class="btn-text-inner">Add Custom Template</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class=" widget-content-area">
                    <div class="table-responsive pt-3">
                        <table id="templates_automation_dttble" class="table table-striped no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
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

<div class="modal fade" id="view_temp_desc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <pre id="temp-description"></pre>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
var DEFAULT_IMAGE_UPLOAD_PATH = '<?php echo DEFAULT_IMAGE_UPLOAD_PATH; ?>'
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/templates.js"></script>