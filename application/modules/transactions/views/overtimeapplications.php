<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Approved Overtime <small>Manage Approved Overtime</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <?php if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_ADD_RECORDS)): ?> 
                    <a id="addOvertimeApplicationsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addOvertimeApplicationsForm'; ?>">
                        <button type="button" class="btn btn-info btn-lg waves-effect">
                            <i class="material-icons">save</i>
                            <span> Add Record</span>
                        </button>
                    </a>
                    <?php endif; ?>
                    <div class="row">
                        <!-- <div class="col-md-3">
                            <label class="form-label">Location</label>
                            <div class="form-group">
                                <div class="form-line location_select">
                                    <select class="location_id form-control" name="location_id" id="location_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control" name="division_id" id="division_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info">Payroll Group <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line payroll_grouping_select">
                                    <select class="payroll_grouping_id form-control payroll_filters" name="payroll_grouping_id" id="payroll_grouping_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <label class="form-label">Employee</label>
                            <div class="form-group">
                                <div class="form-line employee_select">
                                    <select class="employee_id form-control" name="employee_id" id="employee_id" data-live-search="true">
                                        <option value="" selected></option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <h5 class="text-info">Period <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a id="searchEmployeeOvertime" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
                                <button type="button" class="btn btn-info btn-circle-lg waves-effect waves-circle waves-float" data-toggle="modal" data-target="#printOptionsModal">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/transactions/overtimeapplications.js"></script>