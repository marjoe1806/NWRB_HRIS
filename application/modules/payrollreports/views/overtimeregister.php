<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Overtime Register <small>Overtime Register Per Period Summary</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                   
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="text-info">Pay Basis</h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-info">Payroll Group</h5>
                            <div class="form-group">
                                <div class="form-line payroll_grouping_select">
                                    <select class="payroll_grouping_id form-control " name="payroll_grouping_id" id="payroll_grouping_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-info">Period</h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="f0orm-label">Location <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line location_select">
                                    <select class="location_id form-control " name="location_id" id="location_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="f0orm-label">Department <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>" data-toggle="tooltip" data-placement="top" title="Search Payroll Registers">
                                <button type="button" class="btn btn-primary btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </a>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a id="viewOvertimeRegisterSummary" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>viewOvertimeRegisterSummary">
                                <button type="button" class="btn btn-block btn-lg btn-success waves-effect">
                                <i class="material-icons">access_time</i> Overtime Summary
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- <div id="table-holder">
                    <?php echo $table; ?>
                </div> -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/payrollreports/overtimeregister.js"></script>