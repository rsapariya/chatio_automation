
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>CRM Leads</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>CRM Leads</h4></div>
                    </div>
                </div> 
                <div class="widget-content widget-content-area">
                    <div class="simple-tab">
                        <ul class="nav nav-tabs mb-3" id="crm-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="indiamart-tab" data-bs-toggle="tab" data-bs-target="#indiamart-tab-pane" type="button" role="tab" aria-controls="indiamart-tab-pane" aria-selected="true">Indiamart</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tradeindia-tab" data-bs-toggle="tab" data-bs-target="#tradeindia-tab-pane" type="button" role="tab" aria-controls="tradeindia-tab-pane" aria-selected="false">TradeIndia</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="exportersindia-tab" data-bs-toggle="tab" data-bs-target="#exportersindia-tab-pane" type="button" role="tab" aria-controls="exportersindia-tab-pane" aria-selected="false">ExportersIndia</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="indiamart-tab-pane" role="tabpanel" aria-labelledby="indiamart-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12 ">
                                        <div class="form-group ">
                                            <label>City</label>
                                            <select data-placeholder="Select City" id="city" class="form-control form-control-select2" data-fouc>
                                                <option value="">Select City</option>;
                                                <?php
                                                if (isset($cities) && !empty($cities)) {
                                                    foreach ($cities as $city) {
                                                        echo '<option value="' . $city . '">' . $city . '</option> ';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12">
                                        <div class="form-group ">
                                            <label>Product Category</label>
                                            <select data-placeholder="Select Product Category" id="mcat_name" class="form-control form-control-select2" data-fouc>
                                                <option value="">Select Product Category</option>;
                                                <?php
                                                if (isset($categories) && !empty($categories)) {
                                                    foreach ($categories as $category) {
                                                        echo '<option value="' . $category . '">' . $category . '</option> ';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="text" name="query_time" id="query_time" class="form-control daterange-left" value=""> 
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-12 col-12 mt-4">
                                        <button class="btn btn-teal filter-indiamart-list mt-3"><i class="fa fa-filter"></i> Filter</button>
                                        <button class="btn btn-warning clear-indiamart-list mt-3 ml-2"><i class="fa fa-eraser"></i> Clear</button>
                                        <button class="btn btn-success mt-3 ml-3 generate-indiamart-excel-response-list"><i class="fa fa-file-excel"></i>Generate Excel</button>
                                    </div>
                                </div>
                                <div class="table-responsive pt-3">
                                    <table id="indiamart_list_dttble" class="table table-striped  no-footer table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column text-center"></th>
                                                <th class="text-center">#</th>
                                                <th>Sender Name</th>
                                                <th>Mobile</th>
                                                <th>Subject</th>
                                                <th>Company</th>
                                                <th>City - State</th>
                                                <th>Query Product Name</th>
                                                <th>Query Message</th>
                                                <th>Query Category</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tradeindia-tab-pane" role="tabpanel" aria-labelledby="tradeindia-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12 ">
                                        <div class="form-group ">
                                            <label>City</label>
                                            <select data-placeholder="Select City" id="ticity" name="ticity" class="form-control form-control-select2" data-fouc>
                                                <option value="">Select City</option>;
                                                <?php
                                                if (isset($ti_cities) && !empty($ti_cities)) {
                                                    foreach ($ti_cities as $ticity) {
                                                        echo '<option value="' . $ticity . '">' . $ticity . '</option> ';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="text" name="tiquery_time" id="tiquery_time" class="form-control daterange-left" value=""> 
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12 mt-4">
                                        <button class="btn btn-teal filter-tradeindia-list mt-3"><i class="fa fa-filter"></i> Filter</button>
                                        <button class="btn btn-warning clear-tradeindia-list mt-3 ml-2"><i class="fa fa-eraser"></i> Clear</button>
                                        <button class="btn btn-success mt-3 ml-3 generate-tradeindia-excel-response-list"><i class="fa fa-file-excel"></i>Generate Excel</button>
                                    </div>
                                </div>
                                <div class="table-responsive pt-3">
                                    <table id="tradeindia_list_dttble" class="table table-striped  no-footer table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column text-center"></th>
                                                <th class="text-center">#</th>
                                                <th>Sender Name</th>
                                                <th>Mobile</th>
                                                <th>Subject</th>
                                                <th>Company</th>
                                                <th>City - State</th>
                                                <th>Query Product Name</th>
                                                <th>Query Message</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="exportersindia-tab-pane" role="tabpanel" aria-labelledby="exportersindia-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12 ">
                                        <div class="form-group ">
                                            <label>City</label>
                                            <select data-placeholder="Select City" id="eicity" name="eicity" class="form-control form-control-select2" data-fouc>
                                                <option value="">Select City</option>;
                                                <?php
                                                if (isset($ei_cities) && !empty($ei_cities)) {
                                                    foreach ($ei_cities as $eicity) {
                                                        echo '<option value="' . $eicity . '">' . $eicity . '</option> ';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="text" name="eiquery_time" id="eiquery_time" class="form-control daterange-left" value=""> 
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12 col-12 mt-4">
                                        <button class="btn btn-teal mt-3" id="filter-exportersindia-list"><i class="fa fa-filter"></i> Filter</button>
                                        <button class="btn btn-warning clear-exportersindia-list mt-3 ml-2"><i class="fa fa-eraser"></i> Clear</button>
                                        <button class="btn btn-success mt-3 ml-3 generate-exportersindia-excel-response-list"><i class="fa fa-file-excel"></i>Generate Excel</button>
                                    </div>
                                </div>
                                <div class="table-responsive pt-3">
                                    <table id="exportersindia_list_dttble" class="table table-striped  no-footer table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="checkbox-column text-center"></th>
                                                <th class="text-center">#</th>
                                                <th>Sender Name</th>
                                                <th>Mobile</th>
                                                <th>Subject</th>
                                                <th>Company</th>
                                                <th>City - State</th>
                                                <th>Query Product Name</th>
                                                <th>Query Message</th>
                                                <th>Created</th>
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
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/indiamart_leads.js?t=<?php echo date('YmdHis') ?>"></script>