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
                        <div class="col-lg-6 col-12"><h4>Import Contacts</h4></div>    
                        <div class="col-lg-6 col-12 d-flex justify-content-end mt-3">
                            <button data-target="<?php echo base_url() ?>upload/excel_import/add_customer_format.xlsx" target="_blank" class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-file-download">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                <span class="btn-text-inner">Download Format</span>
                            </button>
                        </div>
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="userMessage"></div>
                            <form method="post" enctype="multipart/form-data" class="add_mutliple_client">
                                <div class="row">
                                    <div class="col-lg-4 col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hgroupNo">Tags.</label>
                                            <input type="text" class="form-control" id="hgroupNo" name="group_ids" placeholder="Tags" value="">
                                                <?php if (isset($user_tags) && empty($user_tags)) { ?>
                                                    <small id="passwordHelpInline" class="text-muted"> <i class="fa fa-info-circle"></i> Please add <a href="<?php echo base_url() ?>tag">Tags</a> first to assign</small>
                                                <?php } ?>
                                        </div> 
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-12 mt-2">
                                        <div class="form-group">
                                            <label for="hName">Upload File</label>
                                            <input type="file" class="form-control-file" id="client_file" name="client_file" placeholder="" accept=".xls, .xlsx" required>
                                                <small id="passwordHelpInline" class="text-muted">Only Excel/CSV File Import.</small>
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback">Please upload a file</div>
                                        </div> 
                                    </div>
                                    <div class=" col-12 map_columns table-responsive mt-3"></div>
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-import-data">
                                            <span class="btn-text-inner">Import</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact_modal_block"></div>
    </div>
</div>
<script>
    var userTags = '<?php echo isset($user_tags) && !empty($user_tags) ? json_encode($user_tags) : "" ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/clients.js?t=<?php echo date('YmdHis'); ?>"></script>