<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    PHILHEALTH Remittance Report
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <h5 class="text-info">Service / Division / Unit <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <h5 class="text-info">Period <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewPhilHealthSummary" href="'+commons.baseurl+'payrollreports/PhilHealth/viewPhilHealthSummary">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                <i class="material-icons">print</i> Generate Report
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/remittance/philhealth.js"></script>