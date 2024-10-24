<div class="modal fade" id="manage_country_code" tabindex="-1" role="dialog" data-bs-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--<div class="modal-header">
                <h5 class="modal-title template_name" id="exampleModalLabel"></h5>
            </div>-->
            <div class="modal-body template_details">
                <form class="form-horizontal" method="post" id="country_code_frm">
                    <div class="user-message"></div>
                    <div class="row mb-2 mt-3">
                        <div class="col-lg-12 col-12">
                            <h6>Do you want to skip or merge saved contacts? &nbsp;</h6>
                        </div>
                        <div class="col-lg-4 col-4 d-flex">
                            <div class="custom-control custom-radio custom-control-inline mr-3">
                                <input type="radio" id="skip_contact" name="skip_or_merge" value="skip" checked class="custom-control-input skip_or_merge">
                                <label class="custom-control-label" for="skip_contact">Skip</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="merge_contact" name="skip_or_merge" value="merge" class="custom-control-input skip_or_merge">
                                <label class="custom-control-label" for="merge_contact">Merge</label>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row mb-2 mt-3">
                        <div class="col-lg-12 col-12">
                            <h6>Did you added contry code? &nbsp;</h6>
                        </div>
                        <div class="col-lg-4 col-4 d-flex">
                            <div class="custom-control custom-radio custom-control-inline mr-3">
                                <input type="radio" id="is_contry_code_added1" name="is_contry_code_added" value="yes" checked class="custom-control-input is_contry_code_added">
                                <label class="custom-control-label" for="is_contry_code_added1">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="is_contry_code_added2" name="is_contry_code_added" value="no" class="custom-control-input is_contry_code_added">
                                <label class="custom-control-label" for="is_contry_code_added2">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="row hide" id="country_dropdown_block">
                        <div class="col-lg-12 col-12 mt-2">
                            <div class="form-group">
                                <label for="hgroupNo">Country Code</label><br/>
                                <select class="form-control" id="country_select" name="country_select"></select>
                                <!--<input type="text" class="form-control"  placeholder="Country Code" value=""><br/>-->
                                <!--<small id="country_code" class="text-muted"> <i class="fa fa-info-circle"></i> Make sure you haven't added the country code to the phone number field in the Excel sheet.</small>-->
                            </div> 
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mt-2 text-center">
                            <input type="button" value="Continue" class="mt-4 mb-4 btn btn-primary import-contacts">
                            <button type="button" class="close_country_code_modal btn btn-light-dark" aria-label="Close">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
