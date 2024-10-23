<div class="middle-content container-xxl p-0">
    <!--  BEGIN BREADCRUMBS  -->
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"><h3>WABA Status</h3></div>
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
                <?php if (isset($account_details) && !empty($account_details)) { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="text-bold">Business Account Details & Status</h4>
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <table class="table table-stripe">
                                        <thead>
                                            <tr>
                                                <th><b>Company Name</b></th>
                                                <th><b>Company Status</b></th>
                                                <th><b>Company Currency</b></th>
                                                <th><b>Business Verification</b></th>
                                                <th><b>Country</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo isset($account_details['name']) && !empty($account_details['name']) ? $account_details['name'] : '' ?></td>
                                                <td><?php
                                                    if (isset($account_details['status']) && !empty($account_details['status'])) {
                                                        if ($account_details['status'] == 'ACTIVE') {
                                                            ?>
                                                            <b class="text-success"><?php echo strtoupper($account_details['status']); ?></b>
                                                        <?php } else { ?>
                                                            <b class="text-danger"><?php echo strtoupper($account_details['status']); ?></b>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                <td><?php echo isset($account_details['currency']) && !empty($account_details['currency']) ? $account_details['currency'] : '' ?></td>
                                                <td><?php
                                                    if (isset($account_details['business_verification_status']) && !empty($account_details['business_verification_status'])) {
                                                        if ($account_details['business_verification_status'] == 'verified') {
                                                            ?>
                                                            <b class="text-success"><?php echo strtoupper($account_details['business_verification_status']); ?></b>
                                                        <?php } else { ?>
                                                            <b class="text-danger"><?php echo strtoupper($account_details['business_verification_status']); ?></b>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo isset($account_details['country']) && !empty($account_details['country']) ? $account_details['country'] : '' ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isset($phone_details) && !empty($phone_details)) { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="text-bold">WhatsApp Phone Details</h4>
                            <div class="row">
                                <?php foreach ($phone_details as $phone_details) { ?>
                                    <div class="col-lg-12 col-12">
                                        <table class="table table-stripe">
                                            <thead>
                                                <tr>
                                                    <th><b>Verified Name</b></th>
                                                    <th><b>Code Verification Status</b></th>
                                                    <th><b>Display Number</b></th>
                                                    <th><b>Quality Rating</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo isset($phone_details['verified_name']) && !empty($phone_details['verified_name']) ? $phone_details['verified_name'] : '' ?></td>
                                                    <td><?php
                                                        if (isset($phone_details['code_verification_status']) && !empty($phone_details['code_verification_status'])) {
                                                            if ($phone_details['code_verification_status'] == 'EXPIRED') {
                                                                ?>
                                                                <b class="text-danger"><?php echo strtoupper($phone_details['code_verification_status']); ?></b>
                                                            <?php } else { ?>
                                                                <b class="text-success"><?php echo strtoupper($phone_details['code_verification_status']); ?></b>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    <td><?php echo isset($phone_details['display_phone_number']) && !empty($phone_details['display_phone_number']) ? $phone_details['display_phone_number'] : '' ?></td>
                                                    <td><?php
                                                        if (isset($phone_details['quality_rating']) && !empty($phone_details['quality_rating'])) {
                                                            if ($phone_details['quality_rating'] == 'GREEN') {
                                                                ?>
                                                                <b class="text-success"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                            <?php } else if ($phone_details['quality_rating'] == 'YELLOW') {
                                                                ?>
                                                                <b class="text-warning"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                            <?php } else { ?>
                                                                <b class="text-danger"><?php echo strtoupper($phone_details['quality_rating']); ?></b>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isset($message_details) && !empty($message_details)) { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="text-bold">WhatsApp Message Details & Status</h4>
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <table class="table table-stripe">
                                        <thead>
                                            <tr>
                                                <th><b>Start Date</b></th>
                                                <th><b>End Date</b></th>
                                                <th><b>Sent</b></th>
                                                <th><b>Delivered</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($message_details as $message_details) {
                                                $startTimestamp = $message_details['start'];
                                                $startDateTime = new DateTime("@$startTimestamp");
                                                $start = $startDateTime->format('d M Y H:i');
                                                $endTimestamp = $message_details['end'];
                                                $endDateTime = new DateTime("@$endTimestamp");
                                                $end = $endDateTime->format('d M Y H:i');
                                                ?>
                                                <tr>
                                                    <td><?php echo $start; ?></td>
                                                    <td><?php echo $end; ?></td>
                                                    <td><?php echo isset($message_details['sent']) && !empty($message_details['sent']) ? $message_details['sent'] : '' ?></td>
                                                    <td><?php echo isset($message_details['delivered']) && !empty($message_details['delivered']) ? $message_details['delivered'] : '' ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>



</div>