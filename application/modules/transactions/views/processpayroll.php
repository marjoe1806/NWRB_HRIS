<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Process Payroll <small>Payroll Processing</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <?php //if(Helper::role(ModuleRels::ADD_DIVISION)): ?> 
                    <!-- <a id="addOvertimeApplicationsForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addOvertimeApplicationsForm'; ?>">
                        <button type="button" class="btn btn-info btn-lg waves-effect">
                            <i class="material-icons">save</i>
                            <span> Add Record</span>
                        </button>
                    </a> -->
                    <?php //endif; ?>
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
							<h5 class="text-info">Service / Division / Unit <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line division_select">
                                    <select class="division_id form-control " name="division_id" id="division_id" data-live-search="true" >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="text-info">Period</h5>
                            <div class="form-group">
                                <div class="form-line payroll_period_select">
                                    <select class="payroll_period_id form-control" name="payroll_period_id" id="payroll_period_id" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btn_search" class="btn btn-info btn-circle-lg waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
                        </div>
                        <div class="col-md-3 payrollProcess" style="display:none;">
                            <label class="form-label">Payroll Process</label>
                            <div class="form-group">
                                <div class="form-line payroll_process_select">
                                    <select class="payroll_process form-control" name="payroll_process" id="payroll_process" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-md-12">
                    		<br>
                            <a id="computePayroll" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/computePayroll' ?>" data-toggle="tooltip" data-placement="top" title="Compute Payroll">
                                <button type="button" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">date_range</i> Compute Payroll
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/transactions/processpayroll.js"></script>