<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Button Responses</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Button Responses List</h4></div>
                    </div>
                </div> 
                <div class=" widget-content-area">
                <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group ">
                                <label>Trigger Text</label>
                                <select data-placeholder="Select Trigger Text" id="trigger_text" class="form-control form-control-sm basic js-states" data-fouc>
                                    <option value="">Select Trigger Text</option>;
                                    <?php
                                    if (isset($responses) && !empty($responses)) {
                                        foreach ($responses as $response) {
                                            echo '<option value="' . $response['response'] . '">' . $response['response'] . '</option> ';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                    <input type="text" name="query_time" id="query_time" class="form-control daterange-left" value="" style="height:45px"> 
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4 mt-4">
                            <button class="btn btn-teal mt-3 filter-response-list"><i class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-warning mt-3 clear-response-list"><i class="fa fa-eraser"></i> Clear</button>
                            <button class="btn btn-success mt-3 ml-3 generate-excel-response-list"><i class="fa fa-file-excel"></i> Generate Excel</button>
                        </div>
                    </div>
                    <div class="table-responsive pt-3">
                        <table id="reply_responses_dttble" class="table  table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="select-checkbox"></th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Trigger Text</th>
                                    <th>Created</th>
                                    <!--<th class="text-center">Action</th>-->
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
<script>
    var ATTACHMENT_IMAGE_UPLOAD_PATH = '<?php echo ATTACHMENT_IMAGE_UPLOAD_PATH ?>';
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/reply_response.js?<?php echo date('YmdHis') ?>"></script>