<link rel="stylesheet" type="text/css" href="assets/css/components/custom-page_style_datetime.css">
<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Button Responses</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-9 col-md-9 col-sm-9 col-9">
                            <h4>Button Responses List</h4>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-3 col-3 text-right mt-2">
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area border-tab">
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
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4 mt-4">
                            <button class="btn btn-c-gradient-3 mt-3 mr-3 btn-sm filter-response-list">Filter</button>
                            <button class="btn btn-c-gradient-4 mt-3 btn-sm clear-response-list">Clear</button>
                            <button class="btn btn-c-gradient-1 mt-3 ml-2 btn-sm generate-excel-response-list">Generate Excel</button>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table id="reply_responses_dttble" class="table style-3 table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th class="select-checkbox"></th>
                                    <th>#</th>
                                    <th>Trigger Text</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
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