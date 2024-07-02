<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Clients - Add Multiple Client</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">
                <?php $this->load->view('Partial/alert_view'); ?> 
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-9 col-md-9 col-sm-9 col-9">
                            <h4>Add Multiple Client</h4>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-3 col-3 text-right mt-2">
                            <button data-target="<?php echo base_url() . 'upload/excel_import/add_customer_format.xlsx' ?>" target="_blank" class="btn-creative btn-3 btn-3d btn-c-gradient-1 flaticon-download-1 btn-file-download"><span>Download Format</span></button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                            <form method="post" enctype="multipart/form-data" action="<?php echo base_url() . 'clients/save_multiple' ?>" class="add_mutliple_client" novalidate>
                                <div class="form-group row mb-4">
                                    <label for="client_file" class="col-xl-3 col-sm-4 col-sm-3 col-form-label">Upload File</label>
                                    <div class="col-xl-9 col-lg-8 col-sm-9">
                                        <input type="file" class="form-control-file" id="client_file" name="client_file" placeholder="" accept=".xls, .xlsx" required>
                                        <small id="passwordHelpInline" class="text-muted">
                                            Only Excel/CSV File Import.
                                        </small>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please upload a file
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row text-left">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn-creative btn-3 btn-3e flaticon-arrow-left mb-4 mt-3"><span>Import</span></button>
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