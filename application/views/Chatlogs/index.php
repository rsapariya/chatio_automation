
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Chat Logs</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Chat Log List</h4></div>
                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="row justify-content-end">
                        <div class="col-xl-5 col-md-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                    <input type="text" name="query_time" id="query_time" class="form-control daterange-left" value=""> 
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-5 col-sm-6 col-12 mt-4">
                            <button class="btn btn-teal filter-chatlogs-list mt-3"><i class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-warning clear-chatlogs-list mt-3 ml-2"><i class="fa fa-eraser"></i> Clear</button>
                            <button class="btn btn-success mt-3 ml-3 generate-chatlogs-excel-response-list"><i class="fa fa-file-excel"></i>Generate Excel</button>
                        </div>
                    </div>
                    <div class="table-responsive pt-3">
                        <table id="chatlogs_dttble" class="table table-striped  no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Name</th>
                                    <th>Phone No</th>
                                    <th>Message</th>
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
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/chat_logs.js"></script>