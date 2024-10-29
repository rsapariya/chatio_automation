<?php
$total_number = isset($campaign_info['contacts']) && !empty($campaign_info['contacts']) ? $campaign_info['contacts'] : 0;
$sent = isset($campaign_info['accepted_messages']) && !empty($campaign_info['accepted_messages']) ? $campaign_info['accepted_messages'] : 0;
$failed = isset($campaign_info['failed_messages']) && !empty($campaign_info['failed_messages']) ? $campaign_info['failed_messages'] : 0;
$delivered = isset($campaign_info['delivered_messages']) && !empty($campaign_info['delivered_messages']) ? $campaign_info['delivered_messages'] : 0;
$read = isset($campaign_info['read_messages']) && !empty($campaign_info['read_messages']) ? $campaign_info['read_messages'] : 0;

$total_sent = $sent + $delivered + $read;
$total_delivered = $delivered + $read;
?>
<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title"><h3>Campaign</h3></div>
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
                        <div class="col-lg-6 col-12"><h4>Campaign Details</h4></div>    

                    </div>
                </div> 
                <div class=" widget-content-area">
                    <div class="row">
                        <div class=" col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                            <h6><b>Campaign Name : </b> <?php echo isset($campaign_info['campaign_name']) && !empty($campaign_info['campaign_name']) ? $campaign_info['campaign_name'] : '' ?></h6>
                            <p>Created on <?php echo isset($campaign_info['created']) && !empty($campaign_info['created']) ? date('d M Y, h:i a', strtotime(getTimeBaseOnTimeZone($campaign_info['created']))) : '' ?> </p>
                        </div>
                    </div>

                    <div class="row campaign">
                        <ul class="nav nav-tabs jus" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo $total_number ?></span>
                                    <span>All  <i class="fa fa-address-book"></i></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo!empty($total_number) && !empty($total_sent) ? round((($total_sent / $total_number) * 100), 2) . '%' : '0%'; ?> (<?php echo $total_sent; ?>)</span>
                                    <span>Sent  <i class="fa fa-check"></i></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo!empty($total_number) && !empty($total_delivered) ? round((($total_delivered / $total_number) * 100), 2) . '%' : '0%'; ?> (<?php echo $total_delivered; ?>)</span>
                                    <span>Delivered  <i class="fa fa-check-double"></i></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo!empty($total_number) && !empty($read) ? round((($read / $total_number) * 100), 2) . '%' : '0%'; ?> (<?php echo $read; ?>)</span>
                                    <span>Read  <i class="fa fa-check-double text-info"></i></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo!empty($total_number) && !empty($read) ? round((($read / $total_number) * 100), 2) . '%' : '0%'; ?> (<?php echo $read; ?>)</span>
                                    <span>Replied  <i class="fa fa-check-double text-info"></i></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"  data-bs-toggle="tab" type="button" role="tab">
                                    <span><?php echo!empty($total_number) && !empty($failed) ? round((($failed / $total_number) * 100), 2) . '%' : '0%'; ?> (<?php echo $failed; ?>)</span>
                                    <span>Failed <i class="fa fa-square-xmark"></i></span>
                                </button>
                            </li>
                        </ul>
                        <!--<div class=" col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
                            <a href="javascript:void(0);">
                                <div class="widget widget-t-sales-widget widget-m-sales">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="widget-text text-dark">Total</p>
                                            <p class="widget-numeric-value"><?php //echo $total_number ?></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class=" col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-sales">
                                <div class="media">
                                    <div class="counter bg-light-info">
                                        <?php
                                        //echo!empty($total_number) && !empty($total_sent) ? round((($total_sent / $total_number) * 100), 2) . '%' : '0%';
                                        ?>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text text-info">Total Sent</p>
                                        <p class="widget-numeric-value"><?php //echo $total_sent; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-sales">
                                <div class="media">
                                    <div class="counter bg-light-danger">
                                        <?php
                                        //echo!empty($total_number) && !empty($failed) ? round((($failed / $total_number) * 100), 2) . '%' : '0%';
                                        ?>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text text-danger">Total Failed</p>
                                        <p class="widget-numeric-value"><?php //echo $failed; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-sales">
                                <div class="media">
                                    <div class="counter bg-light-primary">
                                        <?php
                                        //echo!empty($total_number) && !empty($total_delivered) ? round((($total_delivered / $total_number) * 100), 2) . '%' : '0%';
                                        ?>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text text-primary">Total Delivered</p>
                                        <p class="widget-numeric-value"><?php //echo $total_delivered; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-xl-2 col-lg-2 col-md-6 col-sm-6 col-12 layout-spacing">
                            <div class="widget widget-t-sales-widget widget-m-sales">
                                <div class="media">
                                    <div class="counter bg-light-success">
                                        <?php
                                        //echo!empty($total_number) && !empty($read) ? round((($read / $total_number) * 100), 2) . '%' : '0%';
                                        ?>
                                    </div>
                                    <div class="media-body">
                                        <p class="widget-text text-success">Total Read</p>
                                        <p class="widget-numeric-value"><?php //echo $read; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>

                    <div class="userMessage"></div>
                    <div class="row justify-content-end">
                        <?php if ($read > 0) { ?>
                            <div class="col-xl-2 col-md-2 col-sm-12 col-12 mt-4 text-end">
                                <a href="<?php echo base_url() ?>campaigns/create_recampaign/<?php echo $campaign_id ?>" class="btn btn-info mt-3 ml-3 btn-recampaign"><i class="fa fa-arrow-rotate-left"></i> Recampaign</a>
                            </div>
                        <?php } ?>
                        <div class="col-xl-2 col-md-2 col-sm-12 col-12 mt-4 text-end">
                            <button class="btn btn-success mt-3 ml-3 generate-campaign-contact-list"><i class="fa fa-file-excel"></i> Generate Excel</button>
                        </div>
                    </div>
                    <div class="table-responsive pt-3">
                        <input type="hidden" id="campaign_id" value="<?php echo $campaign_id; ?>" />
                        <table id="campaign_details_dttble" class="table table-striped  no-footer table-bordered">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">#</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>contact</th>
                                    <th>Status</th>
                                    <th>Deliver Date</th>
                                    <th>WA Status</th>
                                    <th>WA Status Date</th>
                                    <th>Error</th>
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
    <div class="campaign_modal_block"></div>
</div>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/campaigns.js?t=<?php echo date('YmdHis'); ?>"></script>