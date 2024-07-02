<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h3>Indiamart Leads</h3>
        </div>
    </div>
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <?php $this->load->view('Partial/alert_view'); ?> 
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                            <h4>Indiamart Leads List</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-xl-3 col-md-3 col-sm-3 col-3 ">
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
                        <div class="col-xl-3 col-md-3 col-sm-3 col-3">
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
                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="input-group">
                                            <input type="text" name="query_time" id="query_time" class="form-control daterange-left" value=""> 
                                            <span class="input-group-append">
                                                <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-sm-2 col-2 mt-4">
                                    <button class="btn btn-teal-400 bg-teal-400 filter-indiamart-list">Filter <i class="icon-filter3 ml-2"></i></button>
                                    <button class="btn btn-warning clear-indiamart-list ml-2">Clear <i class="icon-eraser ml-2"></i></button>
                                </div>


                    </div>
                    <div class="row">
                        <div class="table-responsive mb-4">
                            <table id="indiamart_list_dttble" class="table style-3 table-bordered  table-hover">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column text-center">#</th>
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
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/indiamart_leads.js"></script>