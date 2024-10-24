<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Inquiries</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Add Multiple Inquiry</h4></div>    
                        <div class="col-lg-6 col-12 d-flex justify-content-end mt-3">
                            <a href="<?php echo base_url() ?>upload/excel_import_inquiries/add_inquiry_format.xlsx" target="_blank" class="btn btn-primary mb-2 me-4 _effect--ripple waves-effect waves-light btn-file-download">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                <span class="btn-text-inner">Download Format</span>
                            </a>
                        </div>
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                        <form method="post" enctype="multipart/form-data" action="<?php echo base_url() . 'inquiries/save_multiple' ?>" class="add_mutliple_inquiries" novalidate>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="automation_id">Automation</label>
                                            <select class="form-control basic" id="automation_id" name= "automation_id" required>
                                                <?php
                                                if (isset($automations) && !empty($automations)) {
                                                    foreach ($automations as $automation) {
                                                        $selected = '';
                                                        if (isset($inquiry_datas['automation_id']) && $inquiry_datas['automation_id'] == $automation['id']) {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option <?php echo $selected ?> value='<?php echo $automation['id'] ?>'><?php echo $automation['name'] ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="invalid-feedback"> Please select the automation</div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="form-group">
                                            <label for="inquiries_file">Upload File</label>
                                            <input type="file" class="form-control-file" id="inquiries_file" name="inquiries_file" placeholder="" accept=".xls, .xlsx" required>
                                            <small class="text-muted">Only Excel/CSV File Import.</small>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">Please upload a file</div>
                                        </div> 
                                    </div>
                                    <div class="col-12 mt-2">
                                        <input type="submit" name="Import" class="mt-4 mb-4 btn btn-primary">
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/inquiries.js"></script>