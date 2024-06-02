<div class="row clearfix" id="userLevelForm">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-blue">
                <h2>
                    Payroll Notification <small>Notification for payroll ready offices</small>
                </h2>
                
            </div>
            <div class="body">
                <div style="width:100%;padding-bottom:20px;" class="search_entry">
                    <?php if(Helper::role(ModuleRels::PAYROLL_NOTIFICATION_ADD_RECORDS)): ?>
                    <a id="addPayrollNotificationForm" style="text-decoration:none;" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/addPayrollNotificationForm'; ?>">
                        <button type="button" class="btn btn-info btn-lg waves-effect">
                            <i class="material-icons">save</i>
                            <span> Add Record</span>
                        </button>
                    </a>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="text-info">Pay Basis <span class="text-danger">*</span></h5>
                            <div class="form-group">
                                <div class="form-line pay_basis_select">
                                    <select class="pay_basis form-control payroll_filters" name="pay_basis" id="pay_basis" data-live-search="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                            <a id="searchEmployeePayrollNotification" href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/' ?>">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/js/transactions/payrollnotification.js"></script>