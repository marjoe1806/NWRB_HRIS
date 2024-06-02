<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Payslip Summary
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info">Period <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <label class="form-label">Cutoff</label>
                            <div class="form-group">
                                <div class="form-line cutoff_select">
                                    <select class="cutoff_id form-control" name="cutoff_id" id="cutoff_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <label class="f0orm-label">Location <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line location_select">
                                    <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <?php 
                        if(!in_array(17001,$_SESSION["sessionModules"])){ 
                        ?>
                        <div class="col-md-3">
                            <h5 class="text-info">Service / Division / Unit <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php
                         } 
                         ?>
                        <div class="col-md-3">
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search Payslip">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="table-holder">
                    <?php echo $table; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/payslip.js"></script>