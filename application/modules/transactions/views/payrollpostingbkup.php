<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Payroll Posting <small>Manage Payroll Posting</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <?php if(Helper::role(ModuleRels::PAYROLL_POSTING_ADD_RECORDS)): ?>
                    <div class="row">
                        <div class="col-md-2">
                            <a id="addPayrollPostingForm" class="btn btn-info btn-lg waves-effect" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPayrollPostingForm'; ?>">
                                <i class="material-icons">save</i><span> Add Record</span>
                            </a>
                        </div>
                    </div>
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
                            <label class="form-label">Payroll Schedule</label>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Service / Division / Unit <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
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
                            <label class="form-label">Period <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control " name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a id="searchEmployeePayrollPosting" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/transactions/payrollposting.js"></script>